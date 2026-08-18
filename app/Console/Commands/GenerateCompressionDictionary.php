<?php

namespace App\Console\Commands;

use App\Support\CompressionDictionary;
use Illuminate\Console\Command;

class GenerateCompressionDictionary extends Command
{
    protected $signature = 'dict:generate';

    protected $description = 'Build the HTML-shell compression dictionary for Dictionary Transport';

    public function handle(): int
    {
        $path = CompressionDictionary::persist();
        $this->info('Wrote '.str_replace(base_path().'/', '', $path).' ('.strlen(CompressionDictionary::bytes()).' bytes)');

        return self::SUCCESS;
    }
}
