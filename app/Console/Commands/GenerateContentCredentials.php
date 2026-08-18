<?php

namespace App\Console\Commands;

use App\Support\ContentCredentials;
use Illuminate\Console\Command;

class GenerateContentCredentials extends Command
{
    protected $signature = 'credentials:generate';

    protected $description = 'Write C2PA-shaped content credential sidecars for resume and OG assets';

    public function handle(): int
    {
        $path = ContentCredentials::persist();
        $this->info('Wrote '.str_replace(base_path().'/', '', $path));

        if (ContentCredentials::c2patoolAvailable()) {
            $this->comment('c2patool is on PATH — embed into the PDF separately if you want JUMBF claims.');
        }

        return self::SUCCESS;
    }
}
