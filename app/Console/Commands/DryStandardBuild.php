<?php

namespace App\Console\Commands;

use DryStandard\Workspace;
use Illuminate\Console\Command;

class DryStandardBuild extends Command
{
    protected $signature = 'dry-standard:build';

    protected $description = 'Rebuild The Dry Standard static site from review and page markdown.';

    public function handle(): int
    {
        $workspace = Workspace::default();
        $result = $workspace->builder()->build();

        if ($result['errors'] !== []) {
            foreach ($result['errors'] as $error) {
                $this->error($error);
            }

            $this->error('Build halted. Unpublished or invalid reviews were not written.');

            return self::FAILURE;
        }

        $this->info("Built {$result['pages']} Dry Standard pages.");

        return self::SUCCESS;
    }
}
