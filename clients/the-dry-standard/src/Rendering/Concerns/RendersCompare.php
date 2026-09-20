<?php

namespace DryStandard\Rendering\Concerns;

use DryStandard\ComparableSnapshot;
use DryStandard\Review;
use DryStandard\Sensory;
use DryStandard\Str;
use Illuminate\Support\Collection;

trait RendersCompare
{
    public function bestIndex(Collection $reviews): string
    {
        $top = $reviews
            ->filter(fn (Review $review): bool => ($review->rating ?? 0) >= 85)
            ->sortByDesc(fn (Review $review): int => $review->rating ?? 0)
            ->take(12)
            ->values();

        $sections = '';
        foreach ($this->config->categories() as $category) {
            $subset = $reviews
                ->filter(fn (Review $review): bool => $review->category === $category && ($review->rating ?? 0) >= 80)
                ->sortByDesc(fn (Review $review): int => $review->rating ?? 0)
                ->take(6)
                ->values();

            if ($subset->isEmpty()) {
                continue;
            }

            $sections .= $this->view->render('partials/section-head', [
                'kicker' => $this->config->categoryLabel($category),
                'title' => 'Highest rated '.$this->config->categoryLabel($category),
                'href' => $this->config->publicUrl('best/'.$category.'/'),
                'linkLabel' => 'See all',
            ]).'<div class="card-grid card-grid--compact">'.$this->reviewCards($subset, compact: true).'</div>';
        }

        $body = $this->view->render('best', [
            'breadcrumbs' => $this->breadcrumbs($this->crumbs(['Best of' => 'best/'])),
            'topCards' => $this->reviewCards($top, compact: true),
            'sections' => $sections,
        ]);

        return $this->document(
            'Best of the cellar',
            'Highest-rated dealcoholized and non-alcoholic drinks we have actually tasted, grouped by category.',
            'best/',
            $body,
            [
                'nav' => 'reviews',
                'json_ld' => $this->jsonLd([
                    $this->breadcrumbGraph($this->crumbs(['Best of' => 'best/'])),
                    [
                        '@type' => 'CollectionPage',
                        'name' => 'Best of the cellar',
                        'url' => $this->config->canonicalUrl('best/'),
                    ],
                ]),
            ],
        );
    }

    /**
     * Side-by-side bottle compare for 2–4 published reviews.
     *
     * @param  Collection<int, Review>  $published
     * @param  array<string, mixed>  $query
     */
    public function compare(Collection $published, array $query = []): string
    {
        $slugs = $this->parseCompareSlugs($query);
        $bySlug = $published->keyBy(fn (Review $review): string => $review->slug);
        $selected = collect($slugs)
            ->map(fn (string $slug): ?Review => $bySlug->get($slug))
            ->filter(fn (mixed $review): bool => $review instanceof Review)
            ->unique(fn (Review $review): string => $review->slug)
            ->take(4)
            ->values();

        $columns = $selected->map(function (Review $review) use ($selected): array {
            $snap = ComparableSnapshot::fromReview($review);
            $production = Review::PRODUCTION_TYPES[$snap->productionType] ?? $snap->productionType;

            return [
                'slug' => $snap->slug,
                'title' => $snap->title,
                'brand' => $snap->brand,
                'href' => $this->config->publicUrl($snap->path),
                'figure' => $this->productFigure($review, 'product-figure product-figure--compare'),
                'score' => $snap->score,
                'band' => $review->scoreGuidanceBand(),
                'abv' => $snap->abv ?? '—',
                'category' => $this->config->categoryLabel($snap->category),
                'style' => $snap->style ?? '—',
                'production' => $production,
                'method' => $snap->methodLabel ?? '—',
                'price' => $snap->price ?? '—',
                'descriptors' => $snap->descriptors,
                'structure' => Sensory::structureLabels($snap->structure),
                'removeHref' => $this->compareUrl(
                    $selected->map(fn (Review $other): string => $other->slug)
                        ->reject(fn (string $slug): bool => $slug === $snap->slug)
                        ->values()
                        ->all(),
                ),
            ];
        })->all();

        $clusters = $this->compareClusters($published);

        $body = $this->view->render('compare', [
            'breadcrumbs' => $this->breadcrumbs($this->crumbs(['Compare' => 'compare/'])),
            'columns' => $columns,
            'count' => count($columns),
            'clusters' => $clusters,
            'compareUrl' => $this->config->publicUrl('compare/'),
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
            'clearHref' => $this->config->publicUrl('compare/'),
            'canAddMore' => count($columns) < 4,
            'picker' => $this->comparePicker($published, $selected->map(fn (Review $review): string => $review->slug)->all()),
        ]);

        $description = $selected->isEmpty()
            ? 'Compare dealcoholized and non-alcoholic bottles side by side — score, ABV, method, structure, and flavor.'
            : 'Comparing '.$selected->map(fn (Review $review): string => $review->cardTitle())->implode(', ').'.';

        return $this->document(
            'Compare bottles',
            $description,
            'compare/',
            $body,
            [
                'nav' => 'compare',
                'json_ld' => $this->jsonLd([
                    $this->breadcrumbGraph($this->crumbs(['Compare' => 'compare/'])),
                    [
                        '@type' => 'WebPage',
                        'name' => 'Compare bottles',
                        'description' => $description,
                        'url' => $this->config->canonicalUrl('compare/'),
                    ],
                ]),
            ],
        );
    }

    /**
     * @param  array<string, mixed>  $query
     * @return array<int, string>
     */
    private function parseCompareSlugs(array $query): array
    {
        $raw = [];
        if (isset($query['slugs'])) {
            $value = $query['slugs'];
            if (is_array($value)) {
                foreach ($value as $item) {
                    $raw[] = (string) $item;
                }
            } else {
                $raw = preg_split('/[,\s]+/', (string) $value) ?: [];
            }
        }
        if (isset($query['compare']) && is_array($query['compare'])) {
            foreach ($query['compare'] as $item) {
                $raw[] = (string) $item;
            }
        }
        foreach (['a', 'b', 'c', 'd'] as $key) {
            if (! empty($query[$key]) && is_string($query[$key])) {
                $raw[] = $query[$key];
            }
        }

        $slugs = [];
        foreach ($raw as $slug) {
            $slug = strtolower(trim($slug));
            if ($slug === '' || ! preg_match('/^[a-z0-9-]+$/', $slug) || in_array($slug, $slugs, true)) {
                continue;
            }
            $slugs[] = $slug;
            if (count($slugs) >= 4) {
                break;
            }
        }

        return $slugs;
    }

    /**
     * @param  array<int, string>  $slugs
     */
    private function compareUrl(array $slugs): string
    {
        $base = $this->config->publicUrl('compare/');
        if ($slugs === []) {
            return $base;
        }

        return $base.'?slugs='.rawurlencode(implode(',', $slugs));
    }

    /**
     * @param  Collection<int, Review>  $published
     * @return array<int, array{label: string, href: string, count: int, meta: string}>
     */
    private function compareClusters(Collection $published): array
    {
        $preferred = ['riesling', 'ipa', 'rose', 'sparkling-rose', 'pilsner', 'stout'];
        $grouped = [];
        foreach ($published as $review) {
            if (! $review->hasComparableStyle()) {
                continue;
            }
            $slug = $review->styleSlug();
            $grouped[$slug]['label'] = $review->styleLabel();
            $grouped[$slug]['reviews'][] = $review;
        }

        $clusters = [];
        foreach ($preferred as $styleSlug) {
            if (! isset($grouped[$styleSlug]) || count($grouped[$styleSlug]['reviews']) < 2) {
                continue;
            }
            /** @var array<int, Review> $reviews */
            $reviews = $grouped[$styleSlug]['reviews'];
            usort($reviews, fn (Review $a, Review $b): int => ($b->rating ?? 0) <=> ($a->rating ?? 0));
            $pick = array_slice($reviews, 0, 4);
            $slugs = array_map(fn (Review $review): string => $review->slug, $pick);
            $clusters[] = [
                'label' => $grouped[$styleSlug]['label'],
                'href' => $this->compareUrl($slugs),
                'count' => count($pick),
                'meta' => $this->config->categoryLabel($pick[0]->category),
            ];
        }

        return $clusters;
    }

    /**
     * @param  Collection<int, Review>  $published
     * @param  array<int, string>  $selected
     */
    private function comparePicker(Collection $published, array $selected): string
    {
        if (count($selected) >= 4) {
            return '';
        }

        $options = $published
            ->sortBy(fn (Review $review): string => mb_strtolower($review->cardTitle()))
            ->map(function (Review $review) use ($selected): string {
                if (in_array($review->slug, $selected, true)) {
                    return '';
                }

                return '<option value="'.Str::e($review->slug).'">'
                    .Str::e($review->brandDisplayName().' — '.$review->cardTitle())
                    .'</option>';
            })
            ->implode('');

        if ($options === '') {
            return '';
        }

        $action = $this->config->publicUrl('compare/');
        $hidden = '';
        foreach ($selected as $slug) {
            $hidden .= '<input type="hidden" name="slugs[]" value="'.Str::e($slug).'">';
        }

        return '<form class="compare-picker" method="get" action="'.Str::e($action).'" data-compare-picker>'
            .$hidden
            .'<label class="visually-hidden" for="compare-add">Add a bottle</label>'
            .'<select id="compare-add" name="slugs[]" required>'
            .'<option value="">Add a bottle…</option>'
            .$options
            .'</select>'
            .'<button class="btn" type="submit">Add</button>'
            .'</form>';
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
    public function scoreHistogram(Collection $reviews): string
    {
        $bands = [
            '90–100' => 0,
            '80–89' => 0,
            '70–79' => 0,
            '60–69' => 0,
            'Below 60' => 0,
        ];

        foreach ($reviews as $review) {
            $score = $review->rating ?? 0;
            $key = match (true) {
                $score >= 90 => '90–100',
                $score >= 80 => '80–89',
                $score >= 70 => '70–79',
                $score >= 60 => '60–69',
                default => 'Below 60',
            };
            $bands[$key]++;
        }

        $max = max(1, ...array_values($bands));
        $rows = '';
        foreach ($bands as $label => $count) {
            $width = round(100 * $count / $max);
            $rows .= '<div class="score-bar"><span class="score-bar-label">'.$this->e($label).'</span>'
                .'<span class="score-bar-track"><span class="score-bar-fill" style="width: '.$width.'%"></span></span>'
                .'<span class="score-bar-count">'.$count.'</span></div>';
        }

        return '<figure class="score-chart"><figcaption>How published scores sit on the 100-point scale</figcaption>'
            .$rows
            .'<p class="fine-print">The scale measures drink quality, not likeness to ethanol. We do not spread scores to fill bands.</p></figure>';
    }
}
