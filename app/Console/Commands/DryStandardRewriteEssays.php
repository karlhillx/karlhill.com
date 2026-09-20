<?php

namespace App\Console\Commands;

use DryStandard\EssayRewriter;
use DryStandard\Workspace;
use Illuminate\Console\Command;

class DryStandardRewriteEssays extends Command
{
    protected $signature = 'dry-standard:rewrite-essays
        {slug? : Rewrite a single review}
        {--dry-run : Report only}
        {--filler : Only files that still contain known AI filler}
        {--repair : Rewrite files with duplicate headings, contaminated notes, or filler}
        {--force : Rebuild essay body from frontmatter even when prose looks intact}';

    protected $description = 'Rebuild Dry Standard review essays from frontmatter; strip AI filler without inventing tasting notes.';

    public function handle(): int
    {
        $workspace = Workspace::default();
        $rewriter = new EssayRewriter;
        $slug = $this->argument('slug');
        $dry = (bool) $this->option('dry-run');
        $fillerOnly = (bool) $this->option('filler');
        $repair = (bool) $this->option('repair');
        $force = (bool) $this->option('force');

        $reviews = $workspace->reviews()->fromDisk();
        if (is_string($slug) && $slug !== '') {
            $one = $reviews->first(fn ($r) => $r->slug === $slug);
            if ($one === null) {
                $this->error("No review [{$slug}].");

                return self::FAILURE;
            }
            $reviews = collect([$one]);
            $fillerOnly = false;
            $repair = true;
        } elseif (! $fillerOnly && ! $repair) {
            // Default: repair contaminated essays from the batch rewrite.
            $repair = true;
        }

        $changed = 0;
        $skipped = 0;

        foreach ($reviews as $review) {
            $should = $fillerOnly
                ? $rewriter->needsRewrite($review->bodyMarkdown)
                : $rewriter->needsRepair($review);

            if (! $should && $slug === null) {
                $skipped++;

                continue;
            }

            $path = $workspace->paths->content('reviews'.DIRECTORY_SEPARATOR.$review->slug.'.md');
            if ($dry) {
                $result = $rewriter->rewrite($review);
                if ($result['changed'] || $rewriter->needsRepair($review)) {
                    $this->line($review->slug.' ('.strlen($result['body']).' chars)');
                    $changed++;
                } else {
                    $skipped++;
                }

                continue;
            }

            if ($rewriter->applyToFile($path, $review, force: $force)) {
                $this->info('rewrote '.$review->slug);
                $changed++;
            } else {
                $skipped++;
            }
        }

        if (! $dry && $changed > 0) {
            $sync = $workspace->sync();
            $this->line("Catalog synced ({$sync['reviews']} reviews).");
        }

        $this->line("Changed {$changed}, skipped {$skipped}".($dry ? ' (dry-run)' : ''));

        return self::SUCCESS;
    }
}
