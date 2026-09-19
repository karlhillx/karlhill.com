<?php

namespace App\Console\Commands;

use DryStandard\Workspace;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

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
            $item = [
                'product' => (string) $this->argument('product'),
                'brand' => (string) $this->option('brand'),
                'category' => (string) $this->option('category') ?: 'wine',
                'priority' => (string) $this->option('priority'),
                'notes' => (string) $this->option('notes'),
                'status' => 'queued',
            ];
            $workspace->queue()->add($item);
            $workspace->reviews()->catalog()->upsertRow([
                ...$item,
                'slug' => Str::slug((string) $this->argument('product')),
                'title' => (string) $this->argument('product'),
            ]);
        } catch (\RuntimeException|\InvalidArgumentException $exception) {
            $this->error($exception->getMessage());

            return self::FAILURE;
        }

        $this->info('Queued '.$this->argument('product').'. It will not publish until a validated review exists.');

        return self::SUCCESS;
    }
}
