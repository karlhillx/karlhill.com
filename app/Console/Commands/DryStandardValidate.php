<?php

namespace App\Console\Commands;

use DryStandard\Workspace;
use Illuminate\Console\Command;

class DryStandardValidate extends Command
{
    protected $signature = 'dry-standard:validate {slug? : Review slug to check} {--publish : Apply publish-time rules}';

    protected $description = 'Validate Dry Standard review files against the factual-source contract.';

    public function handle(): int
    {
        $workspace = Workspace::default();
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

        foreach ($reviews as $review) {
            $errors = $workspace->validator()->errors(
                $review,
                $workspace->config(),
                forPublish: (bool) $this->option('publish'),
            );

            if ($errors === []) {
                $this->info("OK  {$review->slug}");

                continue;
            }

            $failed++;
            $this->error("FAIL  {$review->slug}");
            foreach ($errors as $error) {
                $this->line('  - '.$error);
            }
        }

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }
}
