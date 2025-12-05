<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\HttpException;

class InstallController extends Controller
{
    public function status(): JsonResponse
    {
        $extensions = [
            'pdo',
            'pdo_mysql',
            'openssl',
            'mbstring',
            'tokenizer',
            'xml',
            'ctype',
            'json',
            'bcmath',
            'curl',
        ];

        $checks = [
            'php_version' => phpversion(),
            'extensions' => collect($extensions)->mapWithKeys(function (string $extension) {
                return [$extension => extension_loaded($extension)];
            }),
            'storage_writable' => is_writable(storage_path()),
            'bootstrap_writable' => is_writable(base_path('bootstrap/cache')),
            'env_exists' => File::exists($this->envPath()),
        ];

        return response()->json($checks);
    }

    public function createEnv(Request $request): JsonResponse
    {
        $data = $request->validate([
            'app_name' => 'sometimes|string',
            'app_url' => 'required|string',
            'db_host' => 'required|string',
            'db_port' => 'required|integer',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
            'overwrite' => 'sometimes|boolean',
        ]);

        $envPath = $this->envPath();

        if (File::exists($envPath) && ! ($data['overwrite'] ?? false)) {
            throw new HttpException(409, '.env file already exists. Use overwrite to recreate.');
        }

        $content = $this->buildEnvContent($data);
        File::put($envPath, $content);

        return response()->json(['status' => 'env_created']);
    }

    public function configureDatabase(Request $request): JsonResponse
    {
        $data = $request->validate([
            'db_host' => 'required|string',
            'db_port' => 'required|integer',
            'db_database' => 'required|string',
            'db_username' => 'required|string',
            'db_password' => 'nullable|string',
        ]);

        $this->applyDatabaseConfig($data);

        try {
            DB::connection('mysql')->getPdo();
        } catch (\Throwable $exception) {
            throw new HttpException(422, 'Unable to connect to the database: '.$exception->getMessage());
        }

        return response()->json(['status' => 'database_ready']);
    }

    public function runMigrations(): JsonResponse
    {
        $this->ensureDatabaseConfigured();

        Artisan::call('migrate', ['--force' => true]);

        return response()->json(['status' => 'migrations_finished']);
    }

    public function generateKey(): JsonResponse
    {
        $envPath = $this->envPath();

        if (! File::exists($envPath)) {
            throw new HttpException(409, '.env file does not exist. Create it before generating APP_KEY.');
        }

        $key = 'base64:' . base64_encode(random_bytes(32));
        $envContent = File::get($envPath);

        if (preg_match('/^APP_KEY=.*/m', $envContent)) {
            $envContent = preg_replace('/^APP_KEY=.*/m', 'APP_KEY=' . $key, $envContent);
        } else {
            $envContent .= PHP_EOL . 'APP_KEY=' . $key . PHP_EOL;
        }

        File::put($envPath, $envContent);
        Config::set('app.key', $key);

        return response()->json([
            'status' => 'key_generated',
            'app_key' => $key,
        ]);
    }

    public function createAdmin(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
        ]);

        $role = Role::query()->firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full system access',
                'is_default' => true,
            ]
        );

        $user = User::query()->updateOrCreate(
            ['email' => $data['email']],
            [
                'role_id' => $role->id,
                'name' => $data['name'],
                'password' => Hash::make($data['password']),
                'phone' => $data['phone'] ?? null,
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );

        return response()->json([
            'status' => 'admin_created',
            'user_id' => $user->id,
        ]);
    }

    public function runSeeder(): JsonResponse
    {
        Artisan::call('db:seed', ['--force' => true]);

        return response()->json(['status' => 'seed_completed']);
    }

    private function envPath(): string
    {
        return base_path('.env');
    }

    private function buildEnvContent(array $data): string
    {
        $lines = [
            'APP_NAME=' . ($data['app_name'] ?? 'Portal'),
            'APP_ENV=production',
            'APP_KEY=',
            'APP_DEBUG=false',
            'APP_URL=' . $data['app_url'],
            '',
            'LOG_CHANNEL=stack',
            'LOG_DEPRECATIONS_CHANNEL=null',
            'LOG_LEVEL=info',
            '',
            'DB_CONNECTION=mysql',
            'DB_HOST=' . $data['db_host'],
            'DB_PORT=' . $data['db_port'],
            'DB_DATABASE=' . $data['db_database'],
            'DB_USERNAME=' . $data['db_username'],
            'DB_PASSWORD=' . ($data['db_password'] ?? ''),
        ];

        return implode(PHP_EOL, $lines) . PHP_EOL;
    }

    private function applyDatabaseConfig(array $data): void
    {
        Config::set('database.default', 'mysql');
        Config::set('database.connections.mysql', [
            'driver' => 'mysql',
            'host' => $data['db_host'],
            'port' => $data['db_port'],
            'database' => $data['db_database'],
            'username' => $data['db_username'],
            'password' => $data['db_password'] ?? '',
        ]);

        DB::purge('mysql');
        DB::reconnect('mysql');
    }

    private function ensureDatabaseConfigured(): void
    {
        if (! Config::get('database.connections.mysql')) {
            $env = [
                'db_host' => env('DB_HOST', '127.0.0.1'),
                'db_port' => env('DB_PORT', 3306),
                'db_database' => env('DB_DATABASE', 'portal'),
                'db_username' => env('DB_USERNAME', 'root'),
                'db_password' => env('DB_PASSWORD', ''),
            ];

            $this->applyDatabaseConfig($env);
        }
    }
}
