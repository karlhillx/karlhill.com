<?php

namespace App\Console\Commands;

use DryStandard\StillAudit;
use DryStandard\Workspace;
use Illuminate\Console\Command;

class DryStandardValidate extends Command
{
    protected $signature = 'dry-standard:validate {slug? : Review slug to check} {--publish : Apply publish-time rules}';

    protected $description = 'Validate Dry Standard review files against the factual-source contract.';

    public function handle(): int
    {
        $workspace = Workspace::default();
        $workspace->sync();
        $reviews = $workspace->reviews()->all();
        $slug = $this->argument('slug');

        if (is_string($slug) && $slug !== '') {
            $review = $workspace->reviews()->find($slug);
            if ($review === null) {
                $this->error("No review found for [{$slug}].");

                return self::FAILURE;
            }
            $reviews = collect([$review]);
        }

        $failed = 0;
        $hashes = [];
        $fromDisk = collect();

        if ($this->option('publish')) {
            $hashes = (new StillAudit)->hashes($workspace->paths);
            $fromDisk = $workspace->reviews()->fromDisk()->keyBy('slug');
        }

        foreach ($reviews as $review) {
            $candidate = $fromDisk->get($review->slug) ?? $review;
            $errors = $this->option('publish')
                ? $workspace->validator()->errorsForPublish($candidate, $workspace->config(), $hashes)
                : $workspace->validator()->errors($review, $workspace->config());
            $warnings = $workspace->validator()->warnings($candidate);

            if ($errors === []) {
                $this->info("OK  {$review->slug}");
                foreach ($warnings as $warning) {
                    $this->warn('  ~ '.$warning);
                }

                continue;
            }

            $failed++;
            $this->error("FAIL  {$review->slug}");
            foreach ($errors as $error) {
                $this->line('  - '.$error);
            }
            foreach ($warnings as $warning) {
                $this->warn('  ~ '.$warning);
            }
        }

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }
}
