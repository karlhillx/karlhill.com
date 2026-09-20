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
        'Not published',
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
    public static function methodFacets(SiteConfig $config): array
    {
        $labels = [];
        foreach ($config->methods() as $slug => $label) {
            $labels[$slug] = $label;
        }

        $labels['other'] = 'Other documented method';
        $labels['unpublished'] = 'Method unpublished';
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

        return in_array($abv, self::ABV_LABELS, true);
    }

    /**
     * Map a free-form ABV string to a display label without inventing a numeric value.
     *
     * @return array{label: string, numeric: ?float}
     */
    public static function normalizeAbv(?string $abv, ?float $numeric): array
    {
        $label = trim((string) $abv);
        $num = $numeric;

        if ($label === '' || strcasecmp($label, 'Not published') === 0) {
            return ['label' => 'Not published', 'numeric' => $num];
        }

        if (preg_match('/^0(?:\.0+)?%?$/i', $label) === 1 || $label === '0.0%' || $label === '0.00%') {
            return ['label' => '0.0%', 'numeric' => $num ?? 0.0];
        }

        if (preg_match('/<\s*0\.1%?/i', $label) === 1 || preg_match('/≤\s*0\.1%?/u', $label) === 1) {
            return ['label' => '<0.1%', 'numeric' => $num ?? 0.1];
        }

        if (preg_match('/<\s*0\.5%?/i', $label) === 1 || preg_match('/≤\s*0\.5%?/u', $label) === 1) {
            return ['label' => '<0.5%', 'numeric' => $num];
        }

        if (preg_match('/^0\.5\s*%?$/i', $label) === 1) {
            return ['label' => '0.5%', 'numeric' => $num ?? 0.5];
        }

        if (preg_match('/^0?\.?(\d+(?:\.\d+)?)\s*%?$/', $label, $m) === 1) {
            $parsed = (float) $m[1];
            if ($parsed > 1 && $parsed <= 100) {
                $parsed = $parsed / 100;
            }
            $num ??= $parsed;
            if ($parsed <= 0.0) {
                return ['label' => '0.0%', 'numeric' => $num];
            }
            if (abs($parsed - 0.5) < 0.00001) {
                return ['label' => '0.5%', 'numeric' => $num];
            }

            return ['label' => '<0.5%', 'numeric' => $num];
        }

        if ($num !== null) {
            if ($num <= 0.0) {
                return ['label' => '0.0%', 'numeric' => $num];
            }
            if (abs($num - 0.5) < 0.00001) {
                return ['label' => '0.5%', 'numeric' => $num];
            }
            if ($num < 0.5) {
                return ['label' => '<0.5%', 'numeric' => $num];
            }
            if ($num <= 0.5) {
                return ['label' => '0.5%', 'numeric' => $num];
            }
        }

        return ['label' => $label !== '' ? $label : 'Not published', 'numeric' => $num];
    }
}
