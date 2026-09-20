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
        {--all-filler : Only files that still contain known AI filler}';

    protected $description = 'Rebuild Dry Standard review essays from frontmatter; strip AI filler without inventing tasting notes.';

    public function handle(): int
    {
        $workspace = Workspace::default();
        $rewriter = new EssayRewriter;
        $slug = $this->argument('slug');
        $dry = (bool) $this->option('dry-run');
        $fillerOnly = (bool) $this->option('all-filler') || $slug === null;

        $reviews = $workspace->reviews()->fromDisk();
        if (is_string($slug) && $slug !== '') {
            $one = $reviews->first(fn ($r) => $r->slug === $slug);
            if ($one === null) {
                $this->error("No review [{$slug}].");

                return self::FAILURE;
            }
            $reviews = collect([$one]);
            $fillerOnly = false;
        }

        $changed = 0;
        $skipped = 0;

        foreach ($reviews as $review) {
            if ($fillerOnly && ! $rewriter->needsRewrite($review->bodyMarkdown)) {
                $skipped++;

                continue;
            }

            if (! $fillerOnly && ! $rewriter->needsRewrite($review->bodyMarkdown) && $slug === null) {
                $skipped++;

                continue;
            }

            $path = $workspace->paths->content('reviews'.DIRECTORY_SEPARATOR.$review->slug.'.md');
            if ($dry) {
                $result = $rewriter->rewrite($review);
                if ($result['changed']) {
                    $this->line($review->slug.' ('.strlen($result['body']).' chars)');
                    $changed++;
                } else {
                    $skipped++;
                }

                continue;
            }

            if ($rewriter->applyToFile($path, $review)) {
                $this->info('rewrote '.$review->slug);
                $changed++;
            } else {
                $skipped++;
            }
        }

        $this->line("Changed {$changed}, skipped {$skipped}".($dry ? ' (dry-run)' : ''));

        return self::SUCCESS;
    }
}
