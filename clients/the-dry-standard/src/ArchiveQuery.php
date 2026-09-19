<?php

namespace DryStandard;

use Illuminate\Support\Collection;

final class ArchiveQuery
{
    public const PER_PAGE = 24;

    /**
     * @param  array<int, string>  $brands
     * @param  array<int, string>  $abv
     * @param  array<int, string>  $categories
     * @param  array<int, string>  $production
     * @param  array<int, string>  $methods
     */
    public function __construct(
        public readonly string $q = '',
        public readonly array $brands = [],
        public readonly array $abv = [],
        public readonly array $categories = [],
        public readonly array $production = [],
        public readonly array $methods = [],
        public readonly string $sort = 'newest',
        public readonly int $page = 1,
        public readonly ?string $lockedCategory = null,
    ) {}

    /**
     * @param  array<string, mixed>  $query
     */
    public static function from(array $query, ?string $lockedCategory = null): self
    {
        $sort = (string) ($query['sort'] ?? 'newest');
        if (! in_array($sort, ['newest', 'rating', 'title'], true)) {
            $sort = 'newest';
        }

        $page = max(1, (int) ($query['page'] ?? 1));

        return new self(
            q: trim((string) ($query['q'] ?? '')),
            brands: self::list($query, 'brand'),
            abv: array_map(
                fn (string $value): string => $value === 'trace' ? 'half' : $value,
                self::list($query, 'abv'),
            ),
            categories: $lockedCategory !== null ? [] : self::list($query, 'category'),
            production: self::productionList($query),
            methods: self::methodList($query),
            sort: $sort,
            page: $page,
            lockedCategory: $lockedCategory,
        );
    }

    public function matches(Review $review, string $skip = ''): bool
    {
        $terms = $this->q === '' ? [] : (preg_split('/\s+/', mb_strtolower($this->q)) ?: []);
        $search = $review->searchText();

        foreach ($terms as $term) {
            if (! str_contains($search, $term)) {
                return false;
            }
        }

        $category = $this->lockedCategory ?? ($skip === 'category' ? [] : $this->categories);
        if (is_string($category)) {
            if ($category !== '' && $review->category !== $category) {
                return false;
            }
        } elseif ($category !== [] && ! in_array($review->category, $category, true)) {
            return false;
        }

        return $this->matchesFacet($skip, 'brand', $this->brands, $review->brandSlug())
            && $this->matchesFacet($skip, 'abv', $this->abv, $review->abvBucket())
            && $this->matchesFacet($skip, 'production', $this->production, $review->productionType)
            && $this->matchesFacet($skip, 'method', $this->methods, $review->methodFacetKey());
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @param  array<string, int>  $publishOrder
     * @return Collection<int, Review>
     */
    public function apply(Collection $reviews, array $publishOrder = []): Collection
    {
        $matched = $reviews->filter(fn (Review $review): bool => $this->matches($review))->values();

        return $matched->sortBy(function (Review $review) use ($publishOrder): string {
            return match ($this->sort) {
                'rating' => sprintf('%03d-%s', 999 - (int) ($review->rating ?? 0), mb_strtolower($review->title)),
                'title' => mb_strtolower($review->title),
                default => sprintf(
                    '%010d-%s-%s',
                    1_000_000_000 - ($publishOrder[$review->slug] ?? 0),
                    $review->modifiedAt()->format('YmdHis'),
                    $review->slug,
                ),
            };
        }, SORT_NATURAL)->values();
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @return Collection<int, Review>
     */
    public function page(Collection $reviews): Collection
    {
        $total = $reviews->count();
        $pages = max(1, (int) ceil($total / self::PER_PAGE));
        $page = min($this->page, $pages);

        return $reviews->slice(($page - 1) * self::PER_PAGE, self::PER_PAGE)->values();
    }

    public function pageCount(int $total): int
    {
        return max(1, (int) ceil($total / self::PER_PAGE));
    }

    public function currentPage(int $total): int
    {
        return min($this->page, $this->pageCount($total));
    }

    public function hasFilters(): bool
    {
        return $this->q !== ''
            || $this->brands !== []
            || $this->abv !== []
            || $this->categories !== []
            || $this->production !== []
            || $this->methods !== []
            || $this->sort !== 'newest';
    }

    /**
     * @param  array<string, string|int>  $overrides
     */
    public function queryString(array $overrides = []): string
    {
        $page = $overrides['page'] ?? $this->page;
        unset($overrides['page']);

        $parts = [];
        $q = array_key_exists('q', $overrides) ? (string) $overrides['q'] : $this->q;
        if ($q !== '') {
            $parts['q'] = $q;
        }

        foreach (['brand' => $this->brands, 'abv' => $this->abv, 'category' => $this->categories, 'production' => $this->production, 'method' => $this->methods] as $key => $values) {
            if (array_key_exists($key, $overrides)) {
                $value = (string) $overrides[$key];
                if ($value !== '') {
                    $parts[$key] = $value;
                }

                continue;
            }

            if ($values !== []) {
                $parts[$key] = implode(',', $values);
            }
        }

        $sort = array_key_exists('sort', $overrides) ? (string) $overrides['sort'] : $this->sort;
        if ($sort !== 'newest') {
            $parts['sort'] = $sort;
        }

        if ((int) $page > 1) {
            $parts['page'] = (string) (int) $page;
        }

        return http_build_query($parts);
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<int, string>
     */
    private static function list(array $query, string $key): array
    {
        $value = $query[$key] ?? $query[$key.'[]'] ?? null;

        if (is_array($value)) {
            return array_values(array_filter(array_map(
                fn (mixed $item): string => trim((string) $item),
                $value,
            )));
        }

        if (! is_string($value) || trim($value) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $value))));
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<int, string>
     */
    private static function productionList(array $query): array
    {
        $legacy = [
            'yes' => 'dealcoholized',
            'no' => 'alternative',
            'not-verified' => 'not-verified',
        ];
        $values = self::list($query, 'production');
        if ($values === []) {
            $values = self::list($query, 'dealcoholized');
        }

        return array_values(array_map(
            fn (string $value): string => $legacy[$value] ?? $value,
            $values,
        ));
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<int, string>
     */
    private static function methodList(array $query): array
    {
        $legacy = [
            'unpublished' => 'unknown',
            'reverse-distillation' => 'other',
        ];

        return array_values(array_map(
            fn (string $value): string => $legacy[$value] ?? $value,
            self::list($query, 'method'),
        ));
    }

    /**
     * @param  array<int, string>  $selected
     */
    private function matchesFacet(string $skip, string $name, array $selected, string $value): bool
    {
        if ($skip === $name || $selected === []) {
            return true;
        }

        return in_array($value, $selected, true);
    }
}
