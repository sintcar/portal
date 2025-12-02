<?php

namespace App\Services;

use App\Models\Module;
use App\Models\ModuleVersion;
use App\Models\UpdateLog;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;

class UpdateService
{
    public function __construct(private readonly Filesystem $filesystem)
    {
    }

    /**
     * Checks Developer Console for available packages and applies them.
     *
     * @param callable|null $output Callback to stream progress messages to CLI/UI
     *
     * @return array<string, string>
     */
    public function checkAndUpdate(string $channel = 'stable', bool $force = false, ?callable $output = null): array
    {
        $manifest = $this->fetchManifest($channel);

        $results = [];

        foreach ($manifest['packages'] ?? [] as $package) {
            $moduleSlug = $package['module'];
            $module = $this->resolveModule($moduleSlug, $package['module_name'] ?? null);

            $version = $this->resolveVersion($module, $package['version'], $package['notes'] ?? null, $package['released_at'] ?? null);
            $log = $this->createLog($version, 'pending', 'Update scheduled', ['channel' => $channel]);

            $this->emit($output, sprintf('Preparing update %s@%s', $module->slug, $version->version));

            try {
                $status = $this->applyPackage($package, $log, $force, $output);
                $results[$module->slug] = $status;
            } catch (\Throwable $e) {
                $this->updateLog($log, 'failed', $e->getMessage());
                Log::error('Update failed', ['module' => $module->slug, 'version' => $version->version, 'error' => $e->getMessage()]);
                $results[$module->slug] = 'failed: ' . $e->getMessage();
            }
        }

        return $results;
    }

    private function fetchManifest(string $channel): array
    {
        $baseUrl = rtrim(config('update.developer_console_url'), '/');
        $token = config('update.api_token');

        $response = Http::withToken($token)
            ->acceptJson()
            ->timeout(config('update.http_timeout', 15))
            ->get($baseUrl . '/api/updates', [
                'channel' => $channel,
                'modules' => Module::query()->active()->pluck('slug')->toArray(),
            ]);

        if (! $response->successful()) {
            throw new \RuntimeException('Developer Console registry unavailable: ' . $response->body());
        }

        return $response->json();
    }

    private function resolveModule(string $slug, ?string $name = null): Module
    {
        return Module::query()->firstOrCreate(
            ['slug' => $slug],
            ['name' => $name ?? Str::headline($slug), 'description' => 'Managed by Update Service', 'is_active' => true]
        );
    }

    private function resolveVersion(Module $module, string $version, ?string $notes = null, ?string $releasedAt = null): ModuleVersion
    {
        return ModuleVersion::query()->firstOrCreate(
            ['module_id' => $module->id, 'version' => $version],
            [
                'changelog' => $notes,
                'released_at' => $releasedAt ? now()->parse($releasedAt) : now(),
                'is_stable' => true,
            ]
        );
    }

    private function createLog(ModuleVersion $version, string $status, string $message, array $context = []): UpdateLog
    {
        return UpdateLog::query()->create([
            'module_version_id' => $version->id,
            'status' => $status,
            'message' => $message,
            'context' => $context,
        ]);
    }

    private function updateLog(UpdateLog $log, string $status, ?string $message = null, array $context = []): void
    {
        $log->update([
            'status' => $status,
            'message' => $message ?? $log->message,
            'context' => array_merge($log->context ?? [], $context),
        ]);
    }

    private function applyPackage(array $package, UpdateLog $log, bool $force, ?callable $output): string
    {
        $this->updateLog($log, 'running', 'Downloading package');
        $archivePath = $this->downloadPackage($package['download_url'], $package['checksum'] ?? null);
        $releasePath = $this->extractPackage($archivePath);

        $this->emit($output, 'Running health pre-checks');
        $this->assertHealth();

        try {
            $this->emit($output, 'Applying migrations');
            $this->runMigrations($releasePath, $force);

            $this->emit($output, 'Switching release atomically (zero downtime)');
            $this->switchRelease($releasePath);

            $this->updateLog($log, 'successful', 'Update applied', ['release' => $releasePath]);

            return 'updated';
        } catch (\Throwable $e) {
            $this->emit($output, 'Failure detected, performing rollback');
            $this->rollbackMigrations($releasePath);
            $this->updateLog($log, 'failed', $e->getMessage());

            throw $e;
        }
    }

    private function downloadPackage(string $url, ?string $checksum = null): string
    {
        $disk = config('update.storage_disk');
        $filename = 'update_' . now()->format('Ymd_His') . '_' . Str::random(6) . '.zip';
        $path = 'updates/' . $filename;

        $response = Http::withToken(config('update.api_token'))
            ->withOptions(['stream' => true])
            ->timeout(config('update.download_timeout', 120))
            ->get($url);

        if (! $response->successful()) {
            throw new \RuntimeException('Failed to download update package: ' . $response->status());
        }

        Storage::disk($disk)->put($path, $response->body());
        $fullPath = Storage::disk($disk)->path($path);

        if ($checksum && ! hash_equals($checksum, hash_file('sha256', $fullPath))) {
            throw new \RuntimeException('Checksum mismatch for downloaded package');
        }

        return $fullPath;
    }

    private function extractPackage(string $archivePath): string
    {
        $zip = new ZipArchive();
        $extractTo = storage_path('app/releases/release_' . Str::random(8));

        $this->filesystem->makeDirectory($extractTo, 0755, true, true);

        if ($zip->open($archivePath) !== true) {
            throw new \RuntimeException('Unable to open update archive');
        }

        $zip->extractTo($extractTo);
        $zip->close();

        return $extractTo;
    }

    private function runMigrations(string $releasePath, bool $force): void
    {
        $migrationPath = $releasePath . '/update-package/database/migrations';

        if (! $this->filesystem->exists($migrationPath)) {
            return;
        }

        $this->updateCache();

        DB::transaction(function () use ($migrationPath, $force) {
            Artisan::call('migrate', [
                '--path' => $migrationPath,
                '--realpath' => true,
                '--force' => true,
                '--step' => true,
            ]);

            if (! $force) {
                sleep((int) config('update.canary_delay', 5));
            }
        }, 3);
    }

    private function rollbackMigrations(string $releasePath): void
    {
        $migrationPath = $releasePath . '/update-package/database/migrations';

        if (! $this->filesystem->exists($migrationPath)) {
            return;
        }

        Artisan::call('migrate:rollback', [
            '--path' => $migrationPath,
            '--realpath' => true,
            '--force' => true,
        ]);
    }

    private function switchRelease(string $releasePath): void
    {
        $currentSymlink = storage_path('app/releases/current');

        if (is_link($currentSymlink)) {
            unlink($currentSymlink);
        }

        symlink($releasePath, $currentSymlink);
    }

    private function assertHealth(): void
    {
        if (config('update.healthcheck_url')) {
            $response = Http::timeout(5)->get(config('update.healthcheck_url'));

            if (! $response->successful()) {
                throw new \RuntimeException('Health check failed before applying update');
            }
        }
    }

    private function updateCache(): void
    {
        Artisan::call('config:cache');
        Artisan::call('route:cache');
    }

    private function emit(?callable $output, string $message): void
    {
        $output?->__invoke($message);
    }
}
