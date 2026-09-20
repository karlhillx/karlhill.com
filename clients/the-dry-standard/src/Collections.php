<?php

namespace DryStandard;

/**
 * Facet-backed editorial collections. Each entry is a real filter set plus a short lede —
 * not a thin SEO stub. Only surfaces when the catalog has enough matching bottles.
 */
final class Collections
{
    public const MIN_COUNT = 3;

    /**
     * @return array<string, array{
     *   slug: string,
     *   title: string,
     *   lede: string,
     *   query: array<string, string|list<string>>,
     * }>
     */
    public static function definitions(): array
    {
        return [
            'true-0-0' => [
                'slug' => 'true-0-0',
                'title' => 'True 0.0%',
                'lede' => 'Bottles labeled or verified at 0.0% ABV — residual alcohol is not the point for these.',
                'query' => ['abv' => 'zero'],
            ],
            'dealcoholized-wine' => [
                'slug' => 'dealcoholized-wine',
                'title' => 'Dealcoholized wines',
                'lede' => 'Wine that started alcoholic and had the ethanol removed. Production type is sourced, not inferred from marketing.',
                'query' => ['category' => 'wine', 'production' => 'dealcoholized'],
            ],
            'spinning-cone' => [
                'slug' => 'spinning-cone',
                'title' => 'Spinning-cone wines and more',
                'lede' => 'Products made with a spinning cone column — a named removal method we can point to.',
                'query' => ['method' => 'spinning-cone'],
            ],
            'dry-wines' => [
                'slug' => 'dry-wines',
                'title' => 'Dry NA wines',
                'lede' => 'Dealcoholized and low-ABV wines scored bone dry or dry on our sweetness scale.',
                'query' => ['category' => 'wine', 'sweetness' => ['0', '1']],
            ],
            'dealcoholized-spirits' => [
                'slug' => 'dealcoholized-spirits',
                'title' => 'True dealcoholized spirits',
                'lede' => 'Spirits that began as high-proof liquid and had alcohol removed — not botanical alternatives.',
                'query' => ['category' => 'spirits', 'production' => 'dealcoholized'],
            ],
            'best-beer' => [
                'slug' => 'best-beer',
                'title' => 'Highest-rated NA beers',
                'lede' => 'Beers scoring 85 and above — quality in the glass, not likeness theater.',
                'query' => ['category' => 'beer', 'sort' => 'rating'],
                'min_score' => 85,
            ],
            'under-20' => [
                'slug' => 'under-20',
                'title' => 'Under $20',
                'lede' => 'Reviewed bottles with a sourced shelf price under twenty dollars.',
                'query' => ['price' => 'under20'],
            ],
            'under-30' => [
                'slug' => 'under-30',
                'title' => 'Under $30',
                'lede' => 'Reviewed bottles with a sourced shelf price under thirty dollars.',
                'query' => ['price' => ['under20', 'under30']],
            ],
        ];
    }

    /**
     * @return array<int, array{slug: string, title: string, lede: string, count: int, href: string, query: ArchiveQuery, reviews: \Illuminate\Support\Collection}>
     */
    public static function available(ReviewRepository $reviews, SiteConfig $config): array
    {
        $out = [];
        foreach (self::definitions() as $def) {
            $query = self::queryFor($def['query']);
            $matched = $reviews->archive($query);
            if (isset($def['min_score'])) {
                $min = (int) $def['min_score'];
                $matched = $matched->filter(fn (Review $review): bool => ($review->rating ?? 0) >= $min)->values();
            }
            $count = $matched->count();
            if ($count < self::MIN_COUNT) {
                continue;
            }
            $out[] = [
                'slug' => $def['slug'],
                'title' => $def['title'],
                'lede' => $def['lede'],
                'count' => $count,
                'href' => $config->publicUrl('collections/'.$def['slug'].'/'),
                'query' => $query,
                'reviews' => $matched,
                'min_score' => $def['min_score'] ?? null,
            ];
        }

        return $out;
    }

    /**
     * @param  array<string, string|list<string>>  $params
     */
    public static function queryFor(array $params): ArchiveQuery
    {
        $flat = [];
        foreach ($params as $key => $value) {
            if (is_array($value)) {
                $flat[$key] = implode(',', $value);
            } else {
                $flat[$key] = $value;
            }
        }

        return ArchiveQuery::from($flat);
    }

    public static function find(string $slug): ?array
    {
        return self::definitions()[$slug] ?? null;
    }
}
