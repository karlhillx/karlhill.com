<?php

namespace App\Console\Commands;

use DryStandard\Paths;
use DryStandard\Review;
use DryStandard\Workspace;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DryStandardPublish extends Command
{
    protected $signature = 'dry-standard:publish
        {slug? : Review slug to publish}
        {--force : Ignore the weekly schedule}
        {--commit : Create a git commit after a successful build}';

    protected $description = 'Validate a Dry Standard review, sync the catalog, and record publication.';

    public function handle(): int
    {
        $workspace = Workspace::default();
        $schedule = $workspace->schedule();
        $slug = $this->argument('slug');

        if (! $this->option('force') && ! $schedule->slotOpen()) {
            $this->warn($schedule->reasonClosed());

            return self::SUCCESS;
        }

        $review = is_string($slug) && $slug !== ''
            ? $workspace->reviews()->find($slug)
            : $workspace->reviews()->all()->first(
                fn ($item): bool => in_array($item->status, ['validated', 'scheduled'], true),
            );

        if ($review === null) {
            $this->warn('No validated review is ready to publish.');

            return self::SUCCESS;
        }

        $errors = $workspace->validator()->errors($review, $workspace->config(), forPublish: true);

        if ($errors !== []) {
            $this->error("Refusing to publish [{$review->slug}] — validation failed.");
            foreach ($errors as $error) {
                $this->line('  - '.$error);
            }

            return self::FAILURE;
        }

        if ($review->status !== 'published') {
            $this->markPublished($review);
            $workspace->reviews()->catalog()->markStatus($review->slug, 'published');
        }

        $rebuild = $workspace->builder()->build();
        if ($rebuild['errors'] !== []) {
            foreach ($rebuild['errors'] as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        try {
            $workspace->queue()->mark($review->product, 'published');
        } catch (\RuntimeException) {
            // Queue entry is optional once a review file exists.
        }

        $workspace->log()->record([
            'slug' => $review->slug,
            'product' => $review->product,
            'brand' => $review->brand,
            'published_at' => now()->toIso8601String(),
            'path' => $review->path(),
        ]);

        $this->info("Published {$review->title} at /clients/the-dry-standard/{$review->path()}");

        if ($this->option('commit')) {
            $this->commit($review->slug);
        }

        return self::SUCCESS;
    }

    private function markPublished(Review $review): void
    {
        $path = $review->sourcePath;

        if (! is_file($path)) {
            $path = Paths::default()->content('reviews'.DIRECTORY_SEPARATOR.$review->slug.'.md');
        }

        if (! is_file($path)) {
            return;
        }

        $contents = File::get($path);
        $updated = preg_replace('/^status:\s*.+$/m', 'status: published', $contents, 1) ?? $contents;
        File::put($path, $updated);
    }

    private function commit(string $slug): void
    {
        $root = base_path();
        exec('git -C '.escapeshellarg($root).' add clients/the-dry-standard', $output, $addStatus);

        if ($addStatus !== 0) {
            $this->warn('git add failed; publish succeeded without a commit.');

            return;
        }

        $message = 'Publish Dry Standard review: '.$slug;
        exec(
            'git -C '.escapeshellarg($root).' commit -m '.escapeshellarg($message),
            $commitOutput,
            $commitStatus,
        );

        if ($commitStatus !== 0) {
            $this->warn('git commit skipped or failed. The catalog was still updated.');

            return;
        }

        $this->info('Committed Dry Standard publish.');
    }
}
