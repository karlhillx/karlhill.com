<?php

namespace App\Console\Commands;

use DryStandard\Review;
use DryStandard\Workspace;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

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

        $published = $workspace->reviews()->published();
        $total = $published->count();
        $this->newLine();
        $this->info('Completeness ('.$total.' published)');
        $this->table(
            ['field', 'present', 'missing'],
            $this->completenessRows($published, $total),
        );

        $inbox = $workspace->inbox();
        $this->newLine();
        $this->info('Industry inbox');
        $this->line('Submissions: '.$inbox->submissionCount());
        $this->line('Inquiries: '.$inbox->inquiryCount());

        return self::SUCCESS;
    }

    /**
     * @param  Collection<int, Review>  $published
     * @return array<int, array{0: string, 1: string, 2: string}>
     */
    private function completenessRows($published, int $total): array
    {
        $checks = [
            'purchase_links' => fn (Review $review): bool => $review->purchaseLinks !== [],
            'ingredients' => fn (Review $review): bool => filled($review->ingredients),
            'sugar' => fn (Review $review): bool => filled($review->sugar),
            'calories' => fn (Review $review): bool => filled($review->calories),
            'ean' => fn (Review $review): bool => filled($review->ean),
            'dealcoholization_method' => fn (Review $review): bool => $review->productionType !== 'dealcoholized'
                || filled($review->dealcoholizationMethod),
            'region' => fn (Review $review): bool => filled($review->region),
            'price' => fn (Review $review): bool => filled($review->price),
            'abv_numeric' => fn (Review $review): bool => $review->abvNumeric !== null,
            'image_source' => fn (Review $review): bool => filled($review->imageSource),
        ];

        $rows = [];
        foreach ($checks as $field => $present) {
            $count = $published->filter($present)->count();
            $rows[] = [$field, (string) $count, (string) max(0, $total - $count)];
        }

        return $rows;
    }
}
