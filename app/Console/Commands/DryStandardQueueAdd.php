<?php

namespace App\Console\Commands;

use DryStandard\Workspace;
use Illuminate\Console\Command;

class DryStandardQueueAdd extends Command
{
    protected $signature = 'dry-standard:queue
        {product : Product name}
        {--brand= : Brand name}
        {--category= : wine, beer, spirits, cocktails, or cider}
        {--priority=normal : high, normal, or low}
        {--notes= : Optional research note}';

    protected $description = 'Add a discovered product to The Dry Standard review queue without publishing it.';

    public function handle(): int
    {
        $workspace = Workspace::default();

        try {
            $workspace->queue()->add([
                'product' => (string) $this->argument('product'),
                'brand' => (string) $this->option('brand'),
                'category' => (string) $this->option('category'),
                'priority' => (string) $this->option('priority'),
                'notes' => (string) $this->option('notes'),
                'status' => 'queued',
            ]);
        } catch (\RuntimeException|\InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Queued '.$this->argument('product').'. It will not publish until a validated review exists.');

        return self::SUCCESS;
    }
}
