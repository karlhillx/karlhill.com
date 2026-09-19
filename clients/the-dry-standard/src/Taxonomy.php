<?php

namespace DryStandard;

use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;

final class Taxonomy
{
    private const STYLE_NEEDLES = [
        'sparkling rose' => 'sparkling-rose',
        'sparkling rosé' => 'sparkling-rose',
        'brut rose' => 'sparkling-rose',
        'brut rosé' => 'sparkling-rose',
        'cava rose' => 'sparkling-rose',
        'cava rosé' => 'sparkling-rose',
        'negroni' => 'negroni',
        'stout' => 'stout',
        'porter' => 'porter',
        'hazy' => 'ipa',
        'ipa' => 'ipa',
        'pils' => 'pils',
        'kölsch' => 'kolsch',
        'kolsch' => 'kolsch',
        'weissbier' => 'wheat',
        'wheat beer' => 'wheat',
        'hefeweizen' => 'wheat',
        'lager' => 'lager',
        'sour' => 'sour',
        'riesling' => 'riesling',
        'sauvignon' => 'sauvignon-blanc',
        'chardonnay' => 'chardonnay',
        'pinot noir' => 'pinot-noir',
        'pinot gr' => 'pinot-grigio',
        'malbec' => 'malbec',
        'cava' => 'sparkling',
        'prosecco' => 'sparkling',
        'sparkling' => 'sparkling',
        'brut' => 'sparkling',
        'rosé' => 'rose',
        'rose' => 'rose',
        'tequila' => 'tequila',
        'mezcal' => 'tequila',
        'whisky' => 'whisky',
        'whiskey' => 'whisky',
        'cider' => 'cider',
        'poire' => 'cider',
        'aperitif' => 'aperitif',
        'aperitivo' => 'aperitif',
        'spritz' => 'aperitif',
    ];

    /** @var array<string, array{name: string, slug: string, aliases: array<int, string>}>|null */
    private static ?array $brands = null;

    /** @var array<string, string>|null */
    private static ?array $brandLookup = null;

    /** @var array<string, array{label: string, slug: string}>|null */
    private static ?array $styles = null;

    public static function reset(): void
    {
        self::$brands = null;
        self::$brandLookup = null;
        self::$styles = null;
    }

    /**
     * @return array<string, array{name: string, slug: string, aliases: array<int, string>}>
     */
    public static function brands(): array
    {
        if (self::$brands !== null) {
            return self::$brands;
        }

        $parsed = self::yaml('schema/brands.yaml');
        $brands = [];

        foreach ($parsed as $slug => $row) {
            if (! is_string($slug) || ! is_array($row)) {
                continue;
            }

            $name = trim((string) ($row['name'] ?? ''));
            if ($name === '') {
                continue;
            }

            $aliases = [];
            if (isset($row['aliases']) && is_array($row['aliases'])) {
                foreach ($row['aliases'] as $alias) {
                    $alias = trim((string) $alias);
                    if ($alias !== '') {
                        $aliases[] = $alias;
                    }
                }
            }

            $brands[$slug] = [
                'slug' => $slug,
                'name' => $name,
                'aliases' => $aliases,
            ];
        }

        self::$brands = $brands;

        return self::$brands;
    }

    public static function brandSlug(string $name): string
    {
        $lookup = self::brandLookup();
        $key = self::key($name);

        if ($key !== '' && isset($lookup[$key])) {
            return $lookup[$key];
        }

        return Str::slug($name);
    }

    public static function brandName(string $name): string
    {
        $slug = self::brandSlug($name);
        $brands = self::brands();

        return $brands[$slug]['name'] ?? $name;
    }

    public static function canonicalBrandSlug(string $slug): ?string
    {
        foreach (self::brands() as $canonical => $brand) {
            if ($canonical === $slug) {
                return $canonical;
            }

            foreach ($brand['aliases'] as $alias) {
                if (Str::slug($alias) === $slug) {
                    return $canonical;
                }
            }
        }

        return null;
    }

    /**
     * @return array<int, string>
     */
    public static function brandAliases(string $slug): array
    {
        return self::brands()[$slug]['aliases'] ?? [];
    }

    public static function brandSearchTokens(string $name): string
    {
        $slug = self::brandSlug($name);
        $parts = [$name, self::brandName($name), $slug];

        foreach (self::brandAliases($slug) as $alias) {
            $parts[] = $alias;
        }

        return implode(' ', array_values(array_unique(array_filter($parts))));
    }

    /**
     * @return array<string, array{label: string, slug: string}>
     */
    public static function styles(): array
    {
        if (self::$styles !== null) {
            return self::$styles;
        }

        $parsed = self::yaml('schema/styles.yaml');
        $styles = [];

        foreach ($parsed as $slug => $row) {
            if (! is_string($slug) || ! is_array($row)) {
                continue;
            }

            $label = trim((string) ($row['label'] ?? ''));
            if ($label === '') {
                continue;
            }

            $styles[$slug] = [
                'slug' => $slug,
                'label' => $label,
            ];
        }

        self::$styles = $styles;

        return self::$styles;
    }

    public static function hasStyle(string $slug): bool
    {
        return $slug !== '' && $slug !== 'other' && isset(self::styles()[$slug]);
    }

    public static function styleSlug(?string $style, ?string $subcategory = null, ?string $product = null, ?string $explicit = null): string
    {
        $explicit = $explicit !== null ? trim($explicit) : '';
        if ($explicit !== '' && self::hasStyle($explicit)) {
            return $explicit;
        }

        $text = strtolower(trim(($style ?? '').' '.($subcategory ?? '').' '.($product ?? '')));

        foreach (self::STYLE_NEEDLES as $needle => $slug) {
            if ($text !== '' && str_contains($text, $needle) && self::hasStyle($slug)) {
                return $slug;
            }
        }

        return 'other';
    }

    public static function styleLabel(string $slug, ?string $fallback = null): string
    {
        if (isset(self::styles()[$slug])) {
            return self::styles()[$slug]['label'];
        }

        if ($fallback !== null && $fallback !== '') {
            return $fallback;
        }

        return 'Other';
    }

    public static function countrySlug(?string $country): string
    {
        if ($country === null || trim($country) === '') {
            return '';
        }

        return Str::slug($country);
    }

    /**
     * @return array<string, string>
     */
    private static function brandLookup(): array
    {
        if (self::$brandLookup !== null) {
            return self::$brandLookup;
        }

        $lookup = [];

        foreach (self::brands() as $slug => $brand) {
            $lookup[self::key($brand['name'])] = $slug;
            $lookup[self::key($slug)] = $slug;
            foreach ($brand['aliases'] as $alias) {
                $lookup[self::key($alias)] = $slug;
                $lookup[self::key(Str::slug($alias))] = $slug;
            }
        }

        self::$brandLookup = $lookup;

        return self::$brandLookup;
    }

    private static function key(string $value): string
    {
        return strtolower(trim($value));
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
