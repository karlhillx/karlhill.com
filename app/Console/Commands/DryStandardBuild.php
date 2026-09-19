<?php

namespace App\Console\Commands;

use DryStandard\Workspace;
use Illuminate\Console\Command;

class DryStandardBuild extends Command
{
    protected $signature = 'dry-standard:build';

    protected $description = 'Sync The Dry Standard product catalog and validate published reviews.';

    public function handle(): int
    {
        $workspace = Workspace::default();
        $result = $workspace->builder()->build();

        if ($result['errors'] !== []) {
            foreach ($result['errors'] as $error) {
                $this->error($error);
            }

            $this->error('Build halted. Invalid published reviews were not synced.');

            return self::FAILURE;
        }

        $this->info("Synced {$result['products']} catalog products ({$result['pages']} published reviews).");

        return self::SUCCESS;
    }
}
