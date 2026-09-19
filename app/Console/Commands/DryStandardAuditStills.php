<?php

namespace App\Console\Commands;

use DryStandard\StillAudit;
use DryStandard\Workspace;
use Illuminate\Console\Command;

class DryStandardAuditStills extends Command
{
    protected $signature = 'dry-standard:audit-stills {slug? : Review slug to inspect}';

    protected $description = 'Check product stills for retailer scrapes, duplicates, unlabeled mockups, and un-normalized backgrounds.';

    public function handle(): int
    {
        $workspace = Workspace::default();
        $audit = new StillAudit;
        $hashes = $audit->hashes($workspace->paths);
        $reviews = $workspace->reviews()->fromDisk();
        $slug = $this->argument('slug');

        if (is_string($slug) && $slug !== '') {
            $reviews = $reviews->filter(fn ($review): bool => $review->slug === $slug)->values();
            if ($reviews->isEmpty()) {
                $this->error("No review found for [{$slug}].");

                return self::FAILURE;
            }
        }

        $rows = [];
        $failed = 0;
        $warned = 0;

        foreach ($reviews as $review) {
            $result = $audit->inspect($review, $hashes, forPublish: false);
            $issues = array_merge(
                array_map(fn (string $error): string => 'error: '.$error, $result['errors']),
                array_map(fn (string $warning): string => 'warn: '.$warning, $result['warnings']),
            );

            if ($result['errors'] !== []) {
                $failed++;
                $status = 'FAIL';
            } elseif ($result['warnings'] !== []) {
                $warned++;
                $status = 'WARN';
            } else {
                $status = 'OK';
            }

            $rows[] = [
                $review->slug,
                $status,
                $issues === [] ? '—' : implode("\n", $issues),
            ];
        }

        $ok = $reviews->count() - $failed - $warned;
        $this->table(['slug', 'status', 'issues'], $rows);
        $this->line("OK {$ok}  WARN {$warned}  FAIL {$failed}");

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }
}
