<?php

namespace DryStandard\Rendering\Concerns;

use DryStandard\ArchiveQuery;
use DryStandard\Review;
use DryStandard\Sensory;
use DryStandard\Str;
use Illuminate\Support\Collection;

trait RendersArchive
{
    public function listing(
        string $title,
        string $description,
        string $path,
        Collection $reviews,
        array $crumbs,
        string $nav = 'reviews',
        bool $filterable = false,
        ?string $lockedCategory = null,
        Collection $allReviews = new Collection,
        ?ArchiveQuery $query = null,
        array $publishOrder = [],
        bool $fragment = false,
        ?Collection $filtered = null,
    ): string {
        $query ??= new ArchiveQuery(lockedCategory: $lockedCategory);
        $lockedCategory ??= $query->lockedCategory;
        $filtered ??= $query->apply($reviews, $publishOrder);
        $total = $filtered->count();
        $pages = $query->pageCount($total);
        $page = $query->currentPage($total);
        $visible = $query->page($filtered);

        $empty = '<p class="empty" data-archive-empty>No published reviews in this section yet. Products can sit in the queue until the facts are good enough to print.</p>';
        $none = '<p class="empty">No reviews match those filters.</p>';
        $list = $reviews->isEmpty()
            ? $empty
            : ($total === 0
                ? $none
                : '<div class="card-grid" data-review-grid>'.$this->reviewCards($visible, compact: true).'</div>'
                    .$this->pagination($path, $query, $page, $pages, $total));

        $archive = $filterable && $reviews->isNotEmpty()
            ? $this->archiveLayout($reviews, $query, $lockedCategory, $list, $total, $path)
            : '<div class="shell">'.$list.'</div>';

        $railSource = $allReviews->isNotEmpty() ? $allReviews : $reviews;
        $categoryItems = [
            [
                'href' => $this->config->publicUrl('reviews/'),
                'label' => 'All',
                'count' => $railSource->count(),
                'current' => $lockedCategory === null,
            ],
        ];

        foreach ($this->config->categories() as $category) {
            $count = $railSource->filter(fn (Review $review): bool => $review->category === $category)->count();
            $categoryItems[] = [
                'href' => $this->config->publicUrl('reviews/'.$category.'/'),
                'label' => $this->config->categoryLabel($category),
                'count' => $count,
                'current' => $lockedCategory === $category,
            ];
        }

        $wineColorRail = '';
        if ($lockedCategory === 'wine') {
            $colorItems = [
                [
                    'href' => $this->config->publicUrl('reviews/wine/'),
                    'label' => 'All wine',
                    'current' => $query->wineColor === [] && $query->lockedWineColor === null,
                ],
            ];
            foreach (Sensory::wineColorLabels() as $color => $label) {
                $count = $railSource->filter(
                    fn (Review $review): bool => $review->category === 'wine' && $review->wineColor() === $color,
                )->count();
                if ($count === 0) {
                    continue;
                }
                $colorItems[] = [
                    'href' => $this->config->publicUrl('reviews/wine/').'?color='.$color,
                    'label' => $label,
                    'count' => $count,
                    'current' => in_array($color, $query->wineColor, true) || $query->lockedWineColor === $color,
                ];
            }
            $wineColorRail = $this->view->render('partials/category-rail', [
                'label' => 'Wine style',
                'variant' => 'secondary',
                'items' => $colorItems,
            ]);
        }

        $itemList = $visible->values()->map(function (Review $review, int $index): array {
            return [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => $this->config->canonicalUrl($review->path()),
                'name' => $review->title,
            ];
        })->all();

        $body = $this->view->render('listing', [
            'title' => $title,
            'description' => $description,
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'archive' => $archive,
            'categoryRail' => $this->view->render('partials/category-rail', [
                'label' => 'Review categories',
                'items' => $categoryItems,
            ]).$wineColorRail,
        ]);

        if ($fragment) {
            return $archive;
        }

        $head = $page > 1
            ? '  <link rel="prev" href="'.$this->e($this->listingHref($path, $query, $page - 1)).'">'."\n"
            : '';
        if ($page < $pages) {
            $head .= '  <link rel="next" href="'.$this->e($this->listingHref($path, $query, $page + 1)).'">'."\n";
        }

        return $this->document($title, $description, $path, $body, [
            'nav' => $nav,
            'head' => $head,
            'json_ld' => $this->jsonLd([
                $this->breadcrumbGraph($crumbs),
                [
                    '@type' => 'CollectionPage',
                    'name' => $title,
                    'description' => $description,
                    'url' => $this->config->canonicalUrl($path),
                    'mainEntity' => [
                        '@type' => 'ItemList',
                        'numberOfItems' => $total,
                        'itemListElement' => $itemList,
                    ],
                ],
            ]),
        ]);
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     * @param  Collection<int, Review>|null  $relatedReviews
     */
    private function archiveLayout(
        Collection $reviews,
        ArchiveQuery $query,
        ?string $lockedCategory,
        string $list,
        int $total,
        string $path,
    ): string {
        $locked = $lockedCategory !== null ? ' data-locked-category="'.Str::e($lockedCategory).'"' : '';
        if ($query->lockedStyle !== null && $query->lockedStyle !== '') {
            $locked .= ' data-locked-style="'.Str::e($query->lockedStyle).'"';
        }
        $compact = $reviews->count() < 12 ? ' archive-layout--compact' : '';
        $noun = $reviews->count() === 1 ? 'review' : 'reviews';
        $countLabel = $total === $reviews->count()
            ? $reviews->count().' '.$noun
            : $total.' of '.$reviews->count().' '.$noun;

        $facetCache = [];
        $facetCount = function (string $name, string $value) use ($reviews, $query, &$facetCache): int {
            if (! array_key_exists($name, $facetCache)) {
                $sqlCounts = $this->reviews?->facetCounts($query, $name) ?? [];
                $facetCache[$name] = $sqlCounts === [] ? null : $sqlCounts;
            }

            if (is_array($facetCache[$name])) {
                return $facetCache[$name][$value] ?? 0;
            }

            return $reviews->filter(function (Review $review) use ($query, $name, $value): bool {
                if (! $query->matches($review, $name)) {
                    return false;
                }

                $actual = match ($name) {
                    'brand' => $review->brandSlug(),
                    'abv' => $review->abvBucket(),
                    'category' => $review->category,
                    'production' => $review->productionType,
                    'method' => $review->methodFacetKey(),
                    'style' => $review->styleSlug(),
                    'country' => $review->countrySlug(),
                    'sweetness' => (string) ($review->structureScaleInt('sweetness') ?? ''),
                    'body' => (string) ($review->structureScaleInt('body') ?? ''),
                    'acidity' => (string) ($review->structureScaleInt('acidity') ?? ''),
                    'score' => $review->scoreBand(),
                    'color' => (string) ($review->wineColor() ?? ''),
                    'price' => $review->priceBucket(),
                    'descriptor' => '',
                    default => '',
                };

                if ($name === 'descriptor') {
                    return in_array($value, $review->descriptorIds(), true);
                }

                return $actual === $value;
            })->count();
        };

        $withMeta = function (array $options, string $name) use ($query, $facetCount): array {
            $selected = match ($name) {
                'brand' => $query->brands,
                'abv' => $query->abv,
                'category' => $query->categories,
                'production' => $query->production,
                'method' => $query->methods,
                'style' => $query->styles,
                'country' => $query->countries,
                'sweetness' => $query->sweetness,
                'body' => $query->body,
                'acidity' => $query->acidity,
                'descriptor' => $query->descriptors,
                'score' => $query->score,
                'color' => $query->wineColor,
                'price' => $query->price,
                default => [],
            };

            $rows = [];
            foreach ($options as $option) {
                $count = $facetCount($name, $option['value']);
                $checked = in_array($option['value'], $selected, true);
                if ($count === 0 && ! $checked) {
                    continue;
                }
                $rows[] = [...$option, 'count' => $count, 'checked' => $checked];
            }

            return $rows;
        };

        $brandOptions = $reviews
            ->map(fn (Review $review): array => [
                'value' => $review->brandSlug(),
                'label' => $review->brandDisplayName(),
            ])
            ->unique('value')
            ->sortBy(fn (array $option): string => mb_strtolower($option['label']), SORT_NATURAL)
            ->values()
            ->all();

        $abvOptions = [];
        foreach (Review::ABV_BUCKETS as $value => $label) {
            if ($reviews->contains(fn (Review $review): bool => $review->abvBucket() === $value)) {
                $abvOptions[] = ['value' => $value, 'label' => $label];
            }
        }

        $categoryOptions = [];
        if ($lockedCategory === null) {
            foreach ($this->config->categories() as $category) {
                if ($reviews->contains(fn (Review $review): bool => $review->category === $category)) {
                    $categoryOptions[] = [
                        'value' => $category,
                        'label' => $this->config->categoryLabel($category),
                    ];
                }
            }
        }

        $processOptions = [];
        foreach (Review::PRODUCTION_TYPES as $value => $label) {
            if ($reviews->contains(fn (Review $review): bool => $review->productionType === $value)) {
                $processOptions[] = ['value' => $value, 'label' => $label];
            }
        }

        $methodLabels = $this->config->methods() + [
            'other' => 'Other documented method',
            'unpublished' => 'Method unpublished',
            'unknown' => 'Method unpublished',
            'not-applicable' => 'Formulated (no removal)',
        ];
        $methodOptions = [];
        foreach (array_keys($methodLabels) as $method) {
            if ($method === 'unknown') {
                continue;
            }
            if ($reviews->contains(fn (Review $review): bool => $review->methodFacetKey() === $method)) {
                $methodOptions[] = ['value' => $method, 'label' => $methodLabels[$method]];
            }
        }

        $styleOptions = $reviews
            ->filter(fn (Review $review): bool => $review->hasComparableStyle() || $review->styleSlug() === 'other')
            ->map(fn (Review $review): array => [
                'value' => $review->styleSlug(),
                'label' => $review->styleSlug() === 'other' ? 'Other' : $review->styleLabel(),
            ])
            ->unique('value')
            ->sortBy(fn (array $option): string => mb_strtolower($option['label']), SORT_NATURAL)
            ->values()
            ->all();

        $countryOptions = $reviews
            ->map(fn (Review $review): array => [
                'value' => $review->countrySlug(),
                'label' => $review->countryLabel() ?? '',
            ])
            ->filter(fn (array $option): bool => $option['value'] !== '' && $option['label'] !== '')
            ->unique('value')
            ->sortBy(fn (array $option): string => mb_strtolower($option['label']), SORT_NATURAL)
            ->values()
            ->all();

        $sweetnessOptions = [];
        foreach (Sensory::structure()['sweetness']['levels'] ?? [] as $level => $meta) {
            $value = (string) $level;
            if ($reviews->contains(fn (Review $review): bool => $review->structureScaleInt('sweetness') === (int) $level)) {
                $sweetnessOptions[] = ['value' => $value, 'label' => (string) ($meta['label'] ?? $value)];
            }
        }

        $bodyOptions = [];
        foreach (Sensory::structure()['body']['levels'] ?? [] as $level => $meta) {
            $value = (string) $level;
            if ($reviews->contains(fn (Review $review): bool => $review->structureScaleInt('body') === (int) $level)) {
                $bodyOptions[] = ['value' => $value, 'label' => (string) ($meta['label'] ?? $value)];
            }
        }

        $acidityOptions = [];
        foreach (Sensory::structure()['acidity']['levels'] ?? [] as $level => $meta) {
            $value = (string) $level;
            if ($reviews->contains(fn (Review $review): bool => $review->structureScaleInt('acidity') === (int) $level)) {
                $acidityOptions[] = ['value' => $value, 'label' => (string) ($meta['label'] ?? $value)];
            }
        }

        $descriptorOptions = [];
        $descriptorCounts = [];
        foreach ($reviews as $review) {
            foreach ($review->descriptorIds() as $id) {
                $descriptorCounts[$id] = ($descriptorCounts[$id] ?? 0) + 1;
            }
        }
        arsort($descriptorCounts);
        foreach (array_slice($descriptorCounts, 0, 24, true) as $id => $count) {
            if ($count < 1) {
                continue;
            }
            $descriptorOptions[] = [
                'value' => $id,
                'label' => Sensory::descriptorLabel($id).($count > 1 ? ' ('.$count.')' : ''),
            ];
        }

        $scoreOptions = [];
        foreach (ArchiveQuery::SCORE_BANDS as $value => $label) {
            if ($reviews->contains(fn (Review $review): bool => $review->scoreBand() === $value)) {
                $scoreOptions[] = ['value' => $value, 'label' => $label];
            }
        }

        $priceOptions = [];
        foreach (Review::PRICE_BUCKETS as $value => $label) {
            if ($value === 'unpublished') {
                continue;
            }
            if ($reviews->contains(fn (Review $review): bool => $review->priceBucket() === $value)) {
                $priceOptions[] = ['value' => $value, 'label' => $label];
            }
        }

        $colorOptions = [];
        if ($lockedCategory === 'wine' || $reviews->contains(fn (Review $review): bool => $review->category === 'wine')) {
            foreach (Sensory::wineColorLabels() as $value => $label) {
                if ($reviews->contains(fn (Review $review): bool => $review->wineColor() === $value)) {
                    $colorOptions[] = ['value' => $value, 'label' => $label];
                }
            }
        }

        $brandMeta = $withMeta($brandOptions, 'brand');
        $primaryFacets = $this->facetGroup('ABV', 'abv', $withMeta($abvOptions, 'abv'))
            .($categoryOptions === [] ? '' : $this->facetGroup('Category', 'category', $withMeta($categoryOptions, 'category')))
            .$this->facetGroup('Production type', 'production', $withMeta($processOptions, 'production'))
            .$this->facetGroup('Score', 'score', $withMeta($scoreOptions, 'score'));

        $advancedFacets = $this->facetGroup(
            'Brand',
            'brand',
            $brandMeta,
            searchable: count($brandMeta) > 8,
            collapsible: count($brandMeta) > 8,
            collapsed: count($brandMeta) > 8,
        )
            .($colorOptions === [] ? '' : $this->facetGroup('Wine color', 'color', $withMeta($colorOptions, 'color')))
            .$this->facetGroup('Method', 'method', $withMeta($methodOptions, 'method'))
            .($query->lockedStyle ? '' : $this->facetGroup(
                'Style',
                'style',
                $withMeta($styleOptions, 'style'),
                searchable: count($styleOptions) > 10,
                collapsible: count($styleOptions) > 8,
                collapsed: count($styleOptions) > 8,
            ))
            .$this->facetGroup('Sweetness', 'sweetness', $withMeta($sweetnessOptions, 'sweetness'))
            .$this->facetGroup('Body', 'body', $withMeta($bodyOptions, 'body'))
            .$this->facetGroup('Acidity', 'acidity', $withMeta($acidityOptions, 'acidity'))
            .($descriptorOptions === [] ? '' : $this->facetGroup(
                'Flavor',
                'descriptor',
                $withMeta($descriptorOptions, 'descriptor'),
                searchable: count($descriptorOptions) > 10,
                collapsible: count($descriptorOptions) > 8,
                collapsed: count($descriptorOptions) > 8,
            ))
            .($priceOptions === [] ? '' : $this->facetGroup('Price', 'price', $withMeta($priceOptions, 'price')))
            .$this->facetGroup('Country', 'country', $withMeta($countryOptions, 'country'));

        $advancedOpen = $query->brands !== []
            || $query->methods !== []
            || $query->styles !== []
            || $query->countries !== []
            || $query->sweetness !== []
            || $query->body !== []
            || $query->acidity !== []
            || $query->descriptors !== []
            || $query->wineColor !== []
            || $query->price !== [];

        $facets = $primaryFacets;
        if ($advancedFacets !== '') {
            $facets .= '<details class="archive-advanced"'.($advancedOpen ? ' open' : '').'>'
                .'<summary>More filters</summary>'
                .'<div class="archive-advanced-body">'.$advancedFacets.'</div>'
                .'</details>';
        }

        $chips = $this->filterChips($query, $reviews, $path);

        return $this->view->render('partials/archive', [
            'locked' => $locked,
            'compact' => $compact,
            'facets' => $facets,
            'list' => $list,
            'countLabel' => $countLabel,
            'sort' => $query->sort,
            'q' => $query->q,
            'chips' => $chips,
            'hasChips' => $chips !== '',
            'clearHref' => $this->config->publicUrl($path),
        ]);
    }

    /**
     * @param  array<int, array{value: string, label: string, count?: int, checked?: bool}>  $options
     */
    private function facetGroup(
        string $legend,
        string $name,
        array $options,
        bool $searchable = false,
        bool $collapsible = false,
        bool $collapsed = false,
    ): string {
        if ($options === []) {
            return '';
        }

        $search = '';
        if ($searchable) {
            $search = '<label class="visually-hidden" for="archive-'.$name.'-find">Find a '.Str::e($legend).'</label>'
                .'<input id="archive-'.$name.'-find" type="search" class="facet-find" placeholder="Find a '.Str::e(strtolower($legend)).'" data-facet-find="'.Str::e($name).'">';
        }

        $items = '';
        foreach ($options as $option) {
            $id = 'archive-'.$name.'-'.Str::e($option['value']);
            $checked = ! empty($option['checked']) ? ' checked' : '';
            $count = (int) ($option['count'] ?? 0);
            $items .= '<label class="facet-option" data-facet-label="'.Str::e(mb_strtolower($option['label'])).'">'
                .'<input id="'.$id.'" type="checkbox" name="'.Str::e($name).'[]" value="'.Str::e($option['value']).'" data-archive-'.Str::e($name).$checked.'>'
                .'<span>'.Str::e($option['label']).'</span>'
                .'<span class="facet-count">'.$count.'</span>'
                .'</label>';
        }

        return $this->view->render('partials/facet-group', [
            'name' => $name,
            'legend' => $legend,
            'search' => $search,
            'items' => $items,
            'collapsible' => $collapsible,
            'collapsed' => $collapsed,
        ]);
    }

    private function filterChips(ArchiveQuery $query, Collection $reviews, string $path): string
    {
        $items = '';
        $labels = [
            'brand' => 'Brand',
            'abv' => 'ABV',
            'category' => 'Category',
            'production' => 'Production type',
            'method' => 'Method',
            'style' => 'Style',
            'country' => 'Country',
            'sweetness' => 'Sweetness',
            'body' => 'Body',
            'acidity' => 'Acidity',
            'descriptor' => 'Flavor',
            'score' => 'Score',
            'color' => 'Color',
            'price' => 'Price',
        ];

        if ($query->q !== '') {
            $href = $this->listingHref($path, $query, 1, ['q' => '']);
            $items .= '<a class="filter-chip" href="'.$this->e($href).'">Search: '.$this->e($query->q).'<span aria-hidden="true">×</span></a>';
        }

        foreach ([
            'brand' => $query->brands,
            'abv' => $query->abv,
            'category' => $query->categories,
            'production' => $query->production,
            'method' => $query->methods,
            'style' => $query->styles,
            'country' => $query->countries,
            'sweetness' => $query->sweetness,
            'body' => $query->body,
            'acidity' => $query->acidity,
            'descriptor' => $query->descriptors,
            'score' => $query->score,
            'color' => $query->wineColor,
            'price' => $query->price,
        ] as $key => $values) {
            foreach ($values as $value) {
                $remaining = array_values(array_filter($values, fn (string $item): bool => $item !== $value));
                $href = $this->listingHref($path, $query, 1, [$key => implode(',', $remaining)]);
                $label = $value;
                if ($key === 'brand') {
                    $match = $reviews->first(fn (Review $review): bool => $review->brandSlug() === $value);
                    $label = $match?->brandDisplayName() ?? $value;
                } elseif ($key === 'abv') {
                    $label = Review::ABV_BUCKETS[$value] ?? $value;
                } elseif ($key === 'category') {
                    $label = $this->config->categoryLabel($value);
                } elseif ($key === 'production') {
                    $label = Review::PRODUCTION_TYPES[$value] ?? $value;
                } elseif ($key === 'method') {
                    $label = $this->config->methodLabel($value);
                    if ($value === 'unpublished' || $value === 'unknown') {
                        $label = 'Method unpublished';
                    }
                    if ($value === 'other') {
                        $label = 'Other documented method';
                    }
                    if ($value === 'not-applicable') {
                        $label = 'Formulated (no removal)';
                    }
                } elseif ($key === 'style') {
                    $match = $reviews->first(fn (Review $review): bool => $review->styleSlug() === $value);
                    $label = $match?->styleLabel() ?? $value;
                } elseif ($key === 'country') {
                    $match = $reviews->first(fn (Review $review): bool => $review->countrySlug() === $value);
                    $label = $match?->countryLabel() ?? $value;
                } elseif ($key === 'sweetness') {
                    $label = Sensory::structure()['sweetness']['levels'][(int) $value]['label'] ?? $value;
                } elseif ($key === 'body') {
                    $label = Sensory::structure()['body']['levels'][(int) $value]['label'] ?? $value;
                } elseif ($key === 'acidity') {
                    $label = Sensory::structure()['acidity']['levels'][(int) $value]['label'] ?? $value;
                } elseif ($key === 'descriptor') {
                    $label = Sensory::descriptorLabel($value);
                } elseif ($key === 'score') {
                    $label = ArchiveQuery::SCORE_BANDS[$value] ?? $value;
                } elseif ($key === 'color') {
                    $label = Sensory::wineColorLabels()[$value] ?? $value;
                } elseif ($key === 'price') {
                    $label = Review::PRICE_BUCKETS[$value] ?? $value;
                }
                $items .= '<a class="filter-chip" href="'.$this->e($href).'">'.$this->e($labels[$key].': '.$label).'<span aria-hidden="true">×</span></a>';
            }
        }

        return $items;
    }

    private function pagination(string $path, ArchiveQuery $query, int $page, int $pages, int $total): string
    {
        if ($total <= ArchiveQuery::PER_PAGE) {
            return '';
        }

        $prev = $page > 1
            ? '<a href="'.$this->e($this->listingHref($path, $query, $page - 1)).'" rel="prev">Previous</a>'
            : '<span>Previous</span>';
        $next = $page < $pages
            ? '<a href="'.$this->e($this->listingHref($path, $query, $page + 1)).'" rel="next">Next</a>'
            : '<span>Next</span>';

        return '<nav class="pagination" aria-label="Reviews pages">'.$prev
            .'<p>Page '.$page.' of '.$pages.'</p>'.$next.'</nav>';
    }

    /**
     * @param  array<string, string|int>  $overrides
     */
    private function listingHref(string $path, ArchiveQuery $query, int $page, array $overrides = []): string
    {
        $qs = $query->queryString(['page' => $page, ...$overrides]);

        return $this->config->publicUrl($path).($qs === '' ? '' : '?'.$qs);
    }
}
