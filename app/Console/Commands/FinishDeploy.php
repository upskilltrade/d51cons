<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * FTPS can upload files but cannot run commands, so the deploy workflow
 * uploads a new bootstrap/deploy-id and puts the site in maintenance mode.
 * The scheduler (cron, every minute) runs this command, which notices the new id,
 * migrates, rebuilds caches and brings the site back up.
 */
#[Signature('deploy:finish')]
#[Description('Finish an uploaded deploy: migrate, rebuild caches and leave maintenance mode')]
class FinishDeploy extends Command
{
    public function handle(): int
    {
        $uploaded = $this->readId(base_path('bootstrap/deploy-id'));
        $finishedFile = storage_path('app/deployed-id');

        if ($uploaded === null || $uploaded === $this->readId($finishedFile)) {
            return self::SUCCESS;
        }

        try {
            $this->call('optimize:clear');
            $this->call('migrate', ['--force' => true]);

            if (! file_exists(public_path('storage'))) {
                $this->call('storage:link');
            }

            $this->call('optimize');
            $this->call('up');
        } catch (Throwable $e) {
            // Stay in maintenance mode; the next scheduler run retries.
            Log::error('Deploy finish failed.', ['deploy_id' => $uploaded, 'error' => $e->getMessage()]);
            $this->components->error($e->getMessage());

            return self::FAILURE;
        }

        File::put($finishedFile, $uploaded);
        $this->components->info("Deploy {$uploaded} finished.");

        return self::SUCCESS;
    }

    private function readId(string $path): ?string
    {
        return is_file($path) ? (trim(File::get($path)) ?: null) : null;
    }
}
