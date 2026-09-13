<?php

namespace App\Support;

/**
 * Decide Integrity-Policy enforce vs report-only from stored browser reports
 * and whether the Vite manifest carries SRI hashes for entry scripts.
 */
final class IntegrityPolicy
{
    public const MODE_REPORT_ONLY = 'report-only';

    public const MODE_ENFORCE = 'enforce';

    public const MODE_AUTO = 'auto';

    public static function mode(): string
    {
        $mode = strtolower((string) config('site.integrity_policy', self::MODE_AUTO));

        return in_array($mode, [self::MODE_REPORT_ONLY, self::MODE_ENFORCE, self::MODE_AUTO], true)
            ? $mode
            : self::MODE_AUTO;
    }

    public static function shouldEnforce(): bool
    {
        return match (self::mode()) {
            self::MODE_ENFORCE => self::manifestHasIntegrity(),
            self::MODE_AUTO => self::manifestHasIntegrity() && self::reportsAreClean(),
            default => false,
        };
    }

    public static function headerName(): string
    {
        return self::shouldEnforce() ? 'Integrity-Policy' : 'Integrity-Policy-Report-Only';
    }

    public static function headerValue(): string
    {
        return 'blocked-destinations=(script), endpoints=(default)';
    }

    /**
     * No integrity-violation reports in the retained window.
     */
    public static function reportsAreClean(): bool
    {
        return ! ReportingStore::hasIntegrityViolations();
    }

    public static function manifestHasIntegrity(): bool
    {
        $path = public_path('build/manifest.json');
        if (! is_file($path)) {
            return false;
        }

        $manifest = json_decode((string) file_get_contents($path), true);
        if (! is_array($manifest)) {
            return false;
        }

        foreach ($manifest as $chunk) {
            if (! is_array($chunk)) {
                continue;
            }
            $file = (string) ($chunk['file'] ?? '');
            if ($file !== '' && str_ends_with($file, '.js') && filled($chunk['integrity'] ?? null)) {
                return true;
            }
        }

        return false;
    }
}
