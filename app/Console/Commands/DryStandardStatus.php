<?php

namespace App\Console\Commands;

use DryStandard\Workspace;
use Illuminate\Console\Command;

class DryStandardStatus extends Command
{
    protected $signature = 'dry-standard:status';

    protected $description = 'Show The Dry Standard queue, review states, and whether a publish slot is open.';

    public function handle(): int
    {
        $workspace = Workspace::default();
        $schedule = $workspace->schedule();

        $this->line($schedule->slotOpen()
            ? 'Publish slot: open'
            : 'Publish slot: closed — '.$schedule->reasonClosed());
        $this->newLine();

        $this->info('Reviews');
        $rows = $workspace->reviews()->all()->map(fn ($review): array => [
            $review->slug,
            $review->status,
            $review->category,
            $review->productionType,
            (string) ($review->rating ?? '—'),
        ])->all();
        $this->table(['slug', 'status', 'category', 'production', 'score'], $rows);

        $this->info('Queue');
        $queue = collect($workspace->queue()->all())->map(fn (array $item): array => [
            (string) ($item['product'] ?? ''),
            (string) ($item['brand'] ?? ''),
            (string) ($item['category'] ?? ''),
            (string) ($item['priority'] ?? ''),
            (string) ($item['status'] ?? ''),
        ])->all();
        $this->table(['product', 'brand', 'category', 'priority', 'status'], $queue);

        return self::SUCCESS;
    }
}
