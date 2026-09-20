<?php

namespace DryStandard;

use Symfony\Component\Yaml\Yaml;

/**
 * Canonical sensory descriptors, structural scales, and editorial assessments.
 * Free-text tastes/profile chips remain legacy display fallbacks.
 */
final class Sensory
{
    public const LOCATIONS = ['nose', 'palate', 'finish'];

    public const INTENSITIES = ['low', 'moderate', 'high'];

    public const PROVENANCE_CONFIDENCE = [
        'verified',
        'manufacturer_verified',
        'label_verified',
        'secondary',
        'inferred',
        'unverified',
    ];

    /** @var array<string, array{id: string, label: string, path: array<int, string>}>|null */
    private static ?array $descriptors = null;

    /** @var array<string, string>|null */
    private static ?array $descriptorAliases = null;

    /** @var array<string, mixed>|null */
    private static ?array $structure = null;

    /** @var array<string, mixed>|null */
    private static ?array $assessments = null;

    public static function reset(): void
    {
        self::$descriptors = null;
        self::$descriptorAliases = null;
        self::$structure = null;
        self::$assessments = null;
    }

    /**
     * @return array<string, array{id: string, label: string, path: array<int, string>}>
     */
    public static function descriptors(): array
    {
        if (self::$descriptors !== null) {
            return self::$descriptors;
        }

        $tree = self::yaml('schema/descriptors.yaml');
        $flat = [];
        self::walkDescriptors($tree, [], $flat);
        self::$descriptors = $flat;

        return self::$descriptors;
    }

    public static function descriptorLabel(string $id): string
    {
        if (isset(self::descriptors()[$id]['label'])) {
            return self::descriptors()[$id]['label'];
        }

        return ucwords(str_replace('_', ' ', $id));
    }

    public static function hasDescriptor(string $id): bool
    {
        return isset(self::descriptors()[$id]);
    }

    /**
     * Resolve free-text taste chip → canonical descriptor id, or null.
     */
    public static function resolveDescriptor(string $raw): ?string
    {
        $key = self::normalizeKey($raw);
        if ($key === '') {
            return null;
        }

        $aliases = self::descriptorAliases();
        if (isset($aliases[$key])) {
            return $aliases[$key];
        }

        $slug = str_replace([' ', '-'], '_', $key);
        if (self::hasDescriptor($slug)) {
            return $slug;
        }

        return null;
    }

    /**
     * @param  array<int, mixed>  $items
     * @return array<int, array{descriptor: string, locations: array<int, string>, intensity?: string}>
     */
    public static function normalizeSensoryList(mixed $items): array
    {
        if (! is_array($items)) {
            return [];
        }

        $rows = [];
        $seen = [];

        foreach ($items as $item) {
            if (is_string($item)) {
                $id = self::resolveDescriptor($item);
                if ($id === null || isset($seen[$id])) {
                    continue;
                }
                $seen[$id] = true;
                $rows[] = [
                    'descriptor' => $id,
                    'locations' => ['palate'],
                ];

                continue;
            }

            if (! is_array($item)) {
                continue;
            }

            $id = trim((string) ($item['descriptor'] ?? $item['id'] ?? ''));
            if ($id === '') {
                $raw = trim((string) ($item['label'] ?? $item['taste'] ?? ''));
                $id = self::resolveDescriptor($raw) ?? '';
            } elseif (! self::hasDescriptor($id)) {
                $resolved = self::resolveDescriptor($id);
                $id = $resolved ?? '';
            }

            if ($id === '' || isset($seen[$id])) {
                continue;
            }

            $locations = [];
            $rawLocations = $item['locations'] ?? $item['location'] ?? ['palate'];
            if (is_string($rawLocations)) {
                $rawLocations = preg_split('/\s*,\s*/', $rawLocations) ?: [];
            }
            if (is_array($rawLocations)) {
                foreach ($rawLocations as $location) {
                    $location = strtolower(trim((string) $location));
                    if (in_array($location, self::LOCATIONS, true) && ! in_array($location, $locations, true)) {
                        $locations[] = $location;
                    }
                }
            }
            if ($locations === []) {
                $locations = ['palate'];
            }

            $row = [
                'descriptor' => $id,
                'locations' => $locations,
            ];

            $intensity = strtolower(trim((string) ($item['intensity'] ?? '')));
            if (in_array($intensity, self::INTENSITIES, true)) {
                $row['intensity'] = $intensity;
            }

            $seen[$id] = true;
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * @return array<string, int|string>
     */
    public static function normalizeStructureScales(mixed $value, array $legacyProfile = []): array
    {
        $scales = [];

        if (is_array($value)) {
            foreach ($value as $key => $level) {
                $key = (string) $key;
                if ($key === 'texture') {
                    $texture = strtolower(trim((string) $level));
                    $allowed = array_keys(self::structure()['texture']['values'] ?? []);
                    if (in_array($texture, $allowed, true)) {
                        $scales['texture'] = $texture;
                    }

                    continue;
                }

                if (! isset(self::structure()[$key]) || $key === 'chip_aliases') {
                    continue;
                }

                if (is_numeric($level)) {
                    $int = (int) $level;
                    if (isset(self::structure()[$key]['levels'][$int])) {
                        $scales[$key] = $int;
                    }

                    continue;
                }

                $id = strtolower(trim((string) $level));
                foreach (self::structure()[$key]['levels'] ?? [] as $num => $meta) {
                    if (($meta['id'] ?? '') === $id) {
                        $scales[$key] = (int) $num;
                        break;
                    }
                }
            }
        }

        foreach ($legacyProfile as $chip) {
            $mapped = self::mapProfileChip((string) $chip);
            foreach ($mapped as $key => $level) {
                if (! array_key_exists($key, $scales)) {
                    $scales[$key] = $level;
                }
            }
        }

        return $scales;
    }

    /**
     * @return array<string, int|string>
     */
    public static function mapProfileChip(string $chip): array
    {
        $key = self::normalizeKey($chip);
        $aliases = self::structure()['chip_aliases'] ?? [];

        if (! is_array($aliases) || ! isset($aliases[$key]) || ! is_array($aliases[$key])) {
            return [];
        }

        $out = [];
        foreach ($aliases[$key] as $scale => $level) {
            if ($scale === 'texture') {
                $out['texture'] = (string) $level;
            } else {
                $out[(string) $scale] = (int) $level;
            }
        }

        return $out;
    }

    /**
     * Display labels for the Structure glance line.
     *
     * @param  array<string, int|string>  $scales
     * @return array<int, string>
     */
    public static function structureLabels(array $scales): array
    {
        $labels = [];
        $order = [
            'sweetness', 'acidity', 'body', 'tannin', 'bitterness',
            'carbonation', 'alcohol_heat', 'finish_length', 'texture',
            'flavor_intensity', 'aromatic_intensity',
        ];

        foreach ($order as $key) {
            if (! array_key_exists($key, $scales)) {
                continue;
            }

            if ($key === 'texture') {
                $texture = (string) $scales['texture'];
                $label = self::structure()['texture']['values'][$texture] ?? null;
                if (is_string($label) && $label !== '') {
                    $labels[] = $label;
                }

                continue;
            }

            $level = (int) $scales[$key];
            $label = self::structure()[$key]['levels'][$level]['label'] ?? null;
            if (is_string($label) && $label !== '') {
                $labels[] = $label;
            }
        }

        return $labels;
    }

    /**
     * Flavor-profile chip labels from structured sensory rows (fallback to legacy tastes).
     *
     * @param  array<int, array{descriptor: string, locations?: array<int, string>}>  $sensory
     * @param  array<int, string>  $legacyTastes
     * @return array<int, string>
     */
    public static function flavorLabels(array $sensory, array $legacyTastes = []): array
    {
        $labels = [];

        foreach ($sensory as $row) {
            $id = (string) ($row['descriptor'] ?? '');
            if ($id === '') {
                continue;
            }
            $labels[] = self::descriptorLabel($id);
        }

        if ($labels !== []) {
            return array_values(array_unique($labels));
        }

        foreach ($legacyTastes as $taste) {
            $taste = trim($taste);
            if ($taste === '' || self::isNoiseTaste($taste)) {
                continue;
            }
            $id = self::resolveDescriptor($taste);
            $labels[] = $id !== null ? self::descriptorLabel($id) : $taste;
        }

        return array_values(array_unique($labels));
    }

    public static function isNoiseTaste(string $taste): bool
    {
        $lower = strtolower(trim($taste));
        if ($lower === '' || str_ends_with($lower, '.')) {
            return true;
        }
        if (str_contains($lower, 'which is') || str_contains($lower, 'profile')) {
            return true;
        }
        if (preg_match('/\b(the|and|with|then|that|compliment|compliment)\b/', $lower) && str_word_count($lower) > 3) {
            return true;
        }

        return str_word_count($lower) > 5;
    }

    /**
     * @return array<string, int>
     */
    public static function normalizeAssessments(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $out = [];
        $defs = self::assessments();

        foreach ($value as $key => $level) {
            $key = (string) $key;
            if (! isset($defs[$key])) {
                continue;
            }

            if (is_numeric($level)) {
                $int = (int) $level;
                if (isset($defs[$key]['levels'][$int])) {
                    $out[$key] = $int;
                }

                continue;
            }

            $id = strtolower(trim((string) $level));
            foreach ($defs[$key]['levels'] ?? [] as $num => $meta) {
                if (($meta['id'] ?? '') === $id) {
                    $out[$key] = (int) $num;
                    break;
                }
            }
        }

        return $out;
    }

    public static function assessmentLabel(string $dimension, int $level): ?string
    {
        return self::assessments()[$dimension]['levels'][$level]['label'] ?? null;
    }

    /**
     * Wine color facet for consumer IA (not a stored product fact when derived).
     */
    public static function wineColor(?string $styleSlug, ?string $style = null, ?string $subcategory = null, ?string $product = null): ?string
    {
        $hay = strtolower(trim(($styleSlug ?? '').' '.($style ?? '').' '.($subcategory ?? '').' '.($product ?? '')));

        if ($hay === '') {
            return null;
        }

        if (str_contains($hay, 'sparkling') && (str_contains($hay, 'rose') || str_contains($hay, 'rosé'))) {
            return 'sparkling-rose';
        }
        if (str_contains($hay, 'sparkling') || str_contains($hay, 'cava') || str_contains($hay, 'prosecco') || str_contains($hay, 'brut')) {
            return 'sparkling';
        }
        if (str_contains($hay, 'rose') || str_contains($hay, 'rosé')) {
            return 'rose';
        }
        if (preg_match('/\b(pinot noir|malbec|red|cabernet|merlot|syrah|shiraz)\b/', $hay) === 1) {
            return 'red';
        }
        if (preg_match('/\b(riesling|sauvignon|chardonnay|pinot gr|blanc|white|chenin|albari)\b/', $hay) === 1) {
            return 'white';
        }

        return match ($styleSlug) {
            'pinot-noir', 'malbec' => 'red',
            'rose', 'sparkling-rose' => $styleSlug === 'sparkling-rose' ? 'sparkling-rose' : 'rose',
            'sparkling' => 'sparkling',
            'riesling', 'sauvignon-blanc', 'chardonnay', 'pinot-grigio' => 'white',
            default => null,
        };
    }

    /**
     * @return array<string, string>
     */
    public static function wineColorLabels(): array
    {
        return [
            'red' => 'Red',
            'white' => 'White',
            'rose' => 'Rosé',
            'sparkling' => 'Sparkling',
            'sparkling-rose' => 'Sparkling rosé',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function structure(): array
    {
        if (self::$structure !== null) {
            return self::$structure;
        }

        self::$structure = self::yaml('schema/structure.yaml');

        return self::$structure;
    }

    /**
     * @return array<string, mixed>
     */
    public static function assessments(): array
    {
        if (self::$assessments !== null) {
            return self::$assessments;
        }

        self::$assessments = self::yaml('schema/assessments.yaml');

        return self::$assessments;
    }

    /**
     * @param  array<string, mixed>  $tree
     * @param  array<int, string>  $path
     * @param  array<string, array{id: string, label: string, path: array<int, string>}>  $flat
     */
    private static function walkDescriptors(array $tree, array $path, array &$flat): void
    {
        foreach ($tree as $id => $node) {
            if (! is_string($id) || ! is_array($node)) {
                continue;
            }

            $label = trim((string) ($node['label'] ?? ''));
            if ($label === '') {
                continue;
            }

            $nodePath = [...$path, $id];
            $flat[$id] = [
                'id' => $id,
                'label' => $label,
                'path' => $nodePath,
            ];

            $children = $node['children'] ?? null;
            if (is_array($children)) {
                self::walkDescriptors($children, $nodePath, $flat);
            }
        }
    }

    /**
     * @return array<string, string>
     */
    private static function descriptorAliases(): array
    {
        if (self::$descriptorAliases !== null) {
            return self::$descriptorAliases;
        }

        $aliases = [];
        foreach (self::descriptors() as $id => $meta) {
            $aliases[self::normalizeKey($id)] = $id;
            $aliases[self::normalizeKey($meta['label'])] = $id;
            $aliases[self::normalizeKey(str_replace('_', ' ', $id))] = $id;
        }

        // Common tasting phrasings → canonical ids
        $extra = [
            'coffee grounds' => 'coffee',
            'fresh coffee' => 'coffee',
            'roasted coffee' => 'coffee',
            'coffee bean' => 'coffee',
            'cocoa nibs' => 'cocoa_nib',
            'green apple skin' => 'green_apple',
            'apple skin' => 'apple',
            'snap of rhubarb' => 'rhubarb',
            'cool mineral line' => 'mineral',
            'mineral line' => 'mineral',
            'bright citrus' => 'citrus',
            'citrus fruit' => 'citrus',
            'white peach' => 'white_peach',
            'tropical fruit' => 'passionfruit',
            'red berries' => 'red_berry',
            'berry' => 'red_berry',
            'kombucha-like' => 'kombucha',
            'kombucha like' => 'kombucha',
            'toasted malt' => 'malt',
            'roasted malt' => 'malt',
            'dark chocolate' => 'dark_chocolate',
            'orange citrus' => 'orange',
            'bitter orange' => 'orange',
            'lime and mandarin' => 'lime',
            'slate salt' => 'slate',
            'salty slate' => 'slate',
            'blanc de blancs profile' => 'green_apple',
            'american lager profile' => 'malt',
        ];

        foreach ($extra as $alias => $id) {
            if (self::hasDescriptor($id)) {
                $aliases[self::normalizeKey($alias)] = $id;
            }
        }

        self::$descriptorAliases = $aliases;

        return self::$descriptorAliases;
    }

    private static function normalizeKey(string $value): string
    {
        $value = strtolower(trim($value));
        $value = str_replace(['—', '–'], '-', $value);
        $value = preg_replace('/\s+/', ' ', $value) ?? $value;

        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    private static function yaml(string $relative): array
    {
        $file = Paths::default()->data($relative);
        if (! is_file($file)) {
            return [];
        }

        $parsed = Yaml::parseFile($file);

        return is_array($parsed) ? $parsed : [];
    }
}
