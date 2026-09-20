<?php

namespace DryStandard;

/**
 * Single entry point for closed vocabularies used by validation, filters, UI, and search.
 * Source files remain under data/schema/ and data/config.yaml — this class does not duplicate enums.
 */
final class Registry
{
    public const ABV_LABELS = [
        '0.0%',
        '<0.1%',
        '<0.5%',
        '0.5%',
        'Undeclared',
    ];

    /** Accepted as Undeclared on input (legacy + canonical). */
    public const ABV_UNDECLARED_ALIASES = [
        'Undeclared',
        'Not published',
        'unpublished',
        'not published',
    ];

    public const ABV_QUALIFIERS = [
        'exact',
        'less_than',
        'unpublished',
    ];

    public const METHOD_FACETS = [
        'vacuum-distillation',
        'spinning-cone',
        'reverse-osmosis',
        'membrane-filtration',
        'osmotic-distillation',
        'arrested-fermentation',
        'other',
        'unpublished',
        'not-applicable',
    ];

    public const WORKFLOW_STATUSES = [
        'queued',
        'researching',
        'researched',
        'draft',
        'tasted',
        'reviewed',
        'validated',
        'approved',
        'scheduled',
        'published',
        'needs-review',
    ];

    /** @var array<string, string> */
    public const STATUS_ALIASES = [
        'researched' => 'researching',
        'tasted' => 'draft',
        'reviewed' => 'draft',
        'approved' => 'validated',
    ];

    /**
     * @return array<string, string>
     */
    public static function categories(SiteConfig $config): array
    {
        $out = [];
        foreach ($config->categories() as $slug) {
            $out[$slug] = $config->categoryLabel($slug);
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    public static function productionTypes(): array
    {
        return Review::PRODUCTION_TYPES;
    }

    /**
     * @return array<string, string>
     */
    public static function disclosureStances(): array
    {
        return Review::DISCLOSURE_STANCES;
    }

    /**
     * @return array<string, string>
     */
    public static function methodFacets(SiteConfig $config): array
    {
        $labels = [];
        foreach ($config->methods() as $slug => $label) {
            $labels[$slug] = $label;
        }

        $labels['other'] = 'Other documented method';
        $labels['unpublished'] = 'Technique undeclared';
        $labels['not-applicable'] = 'Formulated (no removal)';

        return $labels;
    }

    /**
     * @return array<string, string>
     */
    public static function abvBuckets(): array
    {
        return Review::ABV_BUCKETS;
    }

    /**
     * @return array<string, array{id: string, label: string, path: array<int, string>}>
     */
    public static function descriptors(): array
    {
        return Sensory::descriptors();
    }

    /**
     * @return array<string, mixed>
     */
    public static function structure(): array
    {
        return Sensory::structure();
    }

    /**
     * @return array<string, mixed>
     */
    public static function assessments(): array
    {
        return Sensory::assessments();
    }

    /**
     * @return array<string, array{label: string, slug: string}>
     */
    public static function styles(): array
    {
        return Taxonomy::styles();
    }

    /**
     * @return array<int, string>
     */
    public static function provenanceConfidence(): array
    {
        return Sensory::PROVENANCE_CONFIDENCE;
    }

    public static function normalizeStatus(string $status): string
    {
        $status = strtolower(trim($status));

        return self::STATUS_ALIASES[$status] ?? $status;
    }

    public static function isAllowedStatus(string $status): bool
    {
        $status = strtolower(trim($status));

        return in_array($status, self::WORKFLOW_STATUSES, true)
            || in_array(self::normalizeStatus($status), [
                'queued', 'researching', 'draft', 'validated', 'scheduled', 'published', 'needs-review',
            ], true);
    }

    public static function isCanonicalAbvLabel(?string $abv): bool
    {
        if ($abv === null || $abv === '') {
            return true;
        }

        if (in_array($abv, self::ABV_LABELS, true)) {
            return true;
        }

        // Exact residual percentages (e.g. 0.33%) are first-class claims, not collapsed to <0.5%.
        if (preg_match('/^\d+(?:\.\d+)?%$/', $abv) === 1) {
            $num = (float) rtrim($abv, '%');

            return $num >= 0.0 && $num <= 0.5;
        }

        return false;
    }

    public static function isUndeclaredAbv(?string $abv): bool
    {
        $label = trim((string) $abv);
        if ($label === '') {
            return true;
        }

        foreach (self::ABV_UNDECLARED_ALIASES as $alias) {
            if (strcasecmp($label, $alias) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * How the display label should be read: exact value, less-than ceiling, or undeclared (qualifier key: unpublished).
     * 0.5%, <0.5%, and 0.33% are materially different claims.
     */
    public static function abvQualifier(?string $abv): string
    {
        if (self::isUndeclaredAbv($abv)) {
            return 'unpublished';
        }
        $label = trim((string) $abv);
        if (str_starts_with($label, '<') || str_starts_with($label, '≤')) {
            return 'less_than';
        }

        return 'exact';
    }

    /**
     * Map a free-form ABV string to a display label + numeric without inventing a value.
     *
     * @return array{label: string, numeric: ?float, qualifier: string}
     */
    public static function normalizeAbv(?string $abv, ?float $numeric): array
    {
        $label = trim((string) $abv);
        $num = $numeric;

        if (self::isUndeclaredAbv($label)) {
            return ['label' => 'Undeclared', 'numeric' => $num, 'qualifier' => 'unpublished'];
        }

        if (preg_match('/^0(?:\.0+)?%?$/i', $label) === 1 || $label === '0.0%' || $label === '0.00%') {
            return ['label' => '0.0%', 'numeric' => $num ?? 0.0, 'qualifier' => 'exact'];
        }

        if (preg_match('/<\s*0\.1%?/i', $label) === 1 || preg_match('/≤\s*0\.1%?/u', $label) === 1) {
            return ['label' => '<0.1%', 'numeric' => $num ?? 0.1, 'qualifier' => 'less_than'];
        }

        if (preg_match('/<\s*0\.5%?/i', $label) === 1 || preg_match('/≤\s*0\.5%?/u', $label) === 1) {
            return ['label' => '<0.5%', 'numeric' => $num, 'qualifier' => 'less_than'];
        }

        if (preg_match('/^0\.5\s*%?$/i', $label) === 1) {
            return ['label' => '0.5%', 'numeric' => $num ?? 0.5, 'qualifier' => 'exact'];
        }

        // Preserve exact residuals such as 0.33% — do not collapse into <0.5%.
        if (preg_match('/^(\d+(?:\.\d+)?)\s*%$/', $label, $m) === 1) {
            $parsed = (float) $m[1];
            if ($parsed > 1 && $parsed <= 100) {
                $parsed = $parsed / 100;
            }
            $num ??= $parsed;
            if ($parsed <= 0.0) {
                return ['label' => '0.0%', 'numeric' => $num, 'qualifier' => 'exact'];
            }
            if (abs($parsed - 0.5) < 0.00001) {
                return ['label' => '0.5%', 'numeric' => $num, 'qualifier' => 'exact'];
            }
            if ($parsed > 0.0 && $parsed < 0.5) {
                $display = rtrim(rtrim(sprintf('%.2f', $parsed), '0'), '.').'%';

                return ['label' => $display, 'numeric' => $num, 'qualifier' => 'exact'];
            }
        }

        if (preg_match('/^0?\.?(\d+(?:\.\d+)?)\s*%?$/', $label, $m) === 1) {
            $parsed = (float) $m[1];
            if ($parsed > 1 && $parsed <= 100) {
                $parsed = $parsed / 100;
            }
            $num ??= $parsed;
            if ($parsed <= 0.0) {
                return ['label' => '0.0%', 'numeric' => $num, 'qualifier' => 'exact'];
            }
            if (abs($parsed - 0.5) < 0.00001) {
                return ['label' => '0.5%', 'numeric' => $num, 'qualifier' => 'exact'];
            }
            if ($parsed > 0.0 && $parsed < 0.5) {
                $display = rtrim(rtrim(sprintf('%.2f', $parsed), '0'), '.').'%';

                return ['label' => $display, 'numeric' => $num, 'qualifier' => 'exact'];
            }

            return ['label' => '<0.5%', 'numeric' => $num, 'qualifier' => 'less_than'];
        }

        if ($num !== null) {
            if ($num <= 0.0) {
                return ['label' => '0.0%', 'numeric' => $num, 'qualifier' => 'exact'];
            }
            if (abs($num - 0.5) < 0.00001) {
                return ['label' => '0.5%', 'numeric' => $num, 'qualifier' => 'exact'];
            }
            if ($num < 0.5) {
                $display = rtrim(rtrim(sprintf('%.2f', $num), '0'), '.').'%';

                return ['label' => $display, 'numeric' => $num, 'qualifier' => 'exact'];
            }
        }

        $fallback = $label !== '' ? $label : 'Undeclared';

        return [
            'label' => $fallback,
            'numeric' => $num,
            'qualifier' => self::abvQualifier($fallback),
        ];
    }
}
