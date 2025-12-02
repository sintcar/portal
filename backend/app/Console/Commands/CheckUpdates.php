<?php

namespace App\Console\Commands;

use App\Services\UpdateService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckUpdates extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'portal:check-updates {--channel=stable : Release channel to check} {--force : Apply updates even when in canary window}';

    /**
     * The console command description.
     */
    protected $description = 'Checks Developer Console for new portal updates and applies them with zero downtime strategy';

    public function __construct(private readonly UpdateService $updates)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking Developer Console for updates...');

        try {
            $result = $this->updates->checkAndUpdate(
                channel: (string) $this->option('channel'),
                force: (bool) $this->option('force'),
                output: fn (string $message) => $this->line($message)
            );

            foreach ($result as $module => $status) {
                $this->line(sprintf('Module %s: %s', $module, $status));
            }

            $this->info('Update check complete.');

            return self::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('Update check failed', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            $this->error('Update failed: ' . $e->getMessage());

            return self::FAILURE;
        }
    }
}
