<?php

namespace DryStandard;

use Carbon\CarbonImmutable;
use DryStandard\Rendering\StructuredData;
use Illuminate\Support\Collection;

final class Renderer
{
    public function __construct(
        private readonly SiteConfig $config,
        private readonly View $view = new View(__DIR__.DIRECTORY_SEPARATOR.'views'),
    ) {}

    private function assetVersion(string $relative): string
    {
        $absolute = Paths::default()->path($relative);

        return is_file($absolute) ? (string) filemtime($absolute) : '1';
    }

    /**
     * @param  array<string, mixed>  $options
     */
    public function document(string $title, string $description, string $path, string $body, array $options = []): string
    {
        $canonical = $this->config->canonicalUrl($path);
        $ogType = (string) ($options['og_type'] ?? 'website');
        $image = (string) ($options['image'] ?? $this->defaultShareImage());
        $ogImage = $image === ''
            ? '  <meta name="twitter:card" content="summary">'
            : <<<HTML
  <meta property="og:image" content="{$this->e($this->config->canonicalUrl($image))}">
  <meta property="og:locale" content="en_US">
  <meta name="twitter:card" content="summary_large_image">
  <meta name="twitter:image" content="{$this->e($this->config->canonicalUrl($image))}">
HTML;

        return $this->view->render('layout', [
            'title' => $title,
            'fullTitle' => $path === '' ? $title : $title.' — '.$this->config->name(),
            'description' => $description,
            'canonical' => $canonical,
            'siteName' => $this->config->name(),
            'ogType' => $ogType,
            'ogImage' => $ogImage,
            'feedUrl' => $this->config->publicUrl('feed.xml'),
            'iconUrl' => $this->config->publicUrl('mark.svg'),
            'fontDisplay' => $this->config->publicUrl('fonts/fraunces.woff2'),
            'fontSans' => $this->config->publicUrl('fonts/figtree.woff2'),
            'stylesheet' => $this->config->publicUrl('styles.css').'?v='.$this->assetVersion('styles.css'),
            'script' => $this->config->publicUrl('script.js').'?v='.$this->assetVersion('script.js'),
            'baseHref' => $this->config->basePath() === '' ? '' : $this->config->basePath().'/',
            'pageUrl' => $this->config->publicUrl($path),
            'analyticsEnabled' => $this->config->bool('analytics.enabled', true),
            'extraHead' => (string) ($options['head'] ?? ''),
            'jsonLd' => (string) ($options['json_ld'] ?? ''),
            'bodyClass' => (string) ($options['body_class'] ?? ''),
            'bodyAttrs' => (string) ($options['body_attrs'] ?? ''),
            'header' => $this->header($this->navKey((string) ($options['nav'] ?? $path))),
            'footer' => $this->footer(),
            'body' => $body,
        ]);
    }

    public function notFound(): string
    {
        $items = [];
        foreach ($this->config->categories() as $category) {
            $items[] = [
                'href' => $this->config->publicUrl('reviews/'.$category.'/'),
                'label' => $this->config->categoryLabel($category),
            ];
        }

        $body = $this->view->render('not-found', [
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
            'categoryRail' => $this->view->render('partials/category-rail', [
                'label' => 'Browse by category',
                'items' => $items,
            ]),
        ]);

        return $this->document(
            'Page not found',
            'That address is not a published review, guide, or method on The Dry Standard.',
            '404/',
            $body,
            ['nav' => 'home'],
        );
    }

    /**
     * @param  Collection<int, PageDocument>  $methods
     */
    public function methodSiblings(Collection $methods, string $currentSlug): string
    {
        $others = $methods->reject(fn (PageDocument $method): bool => $method->slug === $currentSlug);
        if ($others->isEmpty()) {
            return '';
        }

        $links = $others->map(function (PageDocument $method): string {
            return '<a href="'.$this->url('methods/'.$method->slug.'/').'">'.$this->e($method->title).'</a>';
        })->implode('');

        return '<nav class="sibling-methods" aria-label="Other methods"><p class="facet-legend">Not this method</p>'.$links.'</nav>';
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @param  Collection<int, PageDocument>  $guides
     * @param  Collection<int, PageDocument>  $methods
     */
    public function home(Collection $reviews, Collection $guides, Collection $methods): string
    {
        $featuredReview = $reviews
            ->filter(fn (Review $review): bool => ($review->rating ?? 0) >= 85)
            ->sortByDesc(fn (Review $review): int => $review->rating ?? 0)
            ->first();
        $featuredSlug = $featuredReview?->slug;
        $used = array_filter([$featuredSlug]);
        $latest = collect();
        foreach ($this->config->categories() as $category) {
            $pick = $reviews->first(
                fn (Review $review): bool => $review->category === $category && ! in_array($review->slug, $used, true),
            );
            if ($pick instanceof Review) {
                $latest->push($pick);
                $used[] = $pick->slug;
            }
        }
        foreach ($reviews as $review) {
            if ($latest->count() >= 3) {
                break;
            }
            if (in_array($review->slug, $used, true)) {
                continue;
            }
            $latest->push($review);
            $used[] = $review->slug;
        }
        $latest = $latest->take(3);
        $highlyRated = $reviews
            ->filter(fn (Review $review): bool => ($review->rating ?? 0) >= 85)
            ->sortByDesc(fn (Review $review): int => $review->rating ?? 0)
            ->reject(fn (Review $review): bool => $review->slug === $featuredSlug)
            ->take(3);

        $processItems = [];
        foreach (Review::PRODUCTION_TYPES as $value => $label) {
            $processItems[] = [
                'href' => $this->config->publicUrl('reviews/').'?production='.$value,
                'label' => $label,
                'count' => $reviews->filter(fn (Review $review): bool => $review->productionType === $value)->count(),
            ];
        }

        $methodCards = $methods->take(2)->map(function (PageDocument $method) use ($reviews): string {
            $count = $reviews->filter(fn (Review $review): bool => $review->methodKey() === $method->slug)->count();

            return $this->view->render('partials/text-card', [
                'kicker' => 'Method',
                'href' => $this->config->publicUrl('methods/'.$method->slug.'/'),
                'title' => $method->title,
                'summary' => $method->summary,
                'meta' => $count === 0 ? null : ($count === 1 ? '1 review' : $count.' reviews'),
            ]);
        });

        $guideCards = $guides->take(1)->map(function (PageDocument $guide): string {
            return $this->view->render('partials/text-card', [
                'kicker' => 'Guide',
                'href' => $this->config->publicUrl('guides/'.$guide->slug.'/'),
                'title' => $guide->title,
                'summary' => $guide->summary,
                'meta' => null,
            ]);
        });

        $readCards = $methodCards->concat($guideCards)->implode('');

        $categoryItems = [];
        foreach ($this->config->categories() as $category) {
            $categoryItems[] = [
                'href' => $this->config->publicUrl('reviews/'.$category.'/'),
                'label' => $this->config->categoryLabel($category),
                'count' => $reviews->filter(fn (Review $review): bool => $review->category === $category)->count(),
            ];
        }

        $body = $this->view->render('home', [
            'tagline' => $this->config->tagline(),
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
            'aboutUrl' => $this->config->publicUrl('about/'),
            'methodsUrl' => $this->config->publicUrl('methods/'),
            'guidesUrl' => $this->config->publicUrl('guides/'),
            'bestUrl' => $this->config->publicUrl('best/'),
            'featured' => $featuredReview instanceof Review ? $this->featuredReview($featuredReview) : '',
            'processRail' => $this->view->render('partials/category-rail', [
                'label' => 'Browse by production type',
                'variant' => 'chips',
                'items' => $processItems,
            ]),
            'categoryRail' => $this->view->render('partials/category-rail', [
                'label' => 'Browse by drink',
                'variant' => 'tiles',
                'items' => $categoryItems,
            ]),
            'latestCards' => $this->reviewCards($latest, compact: true),
            'ratedCards' => $this->reviewCards($highlyRated, compact: true),
            'readCards' => $readCards,
        ]);

        return $this->document(
            $this->config->name(),
            $this->config->string('site.description'),
            '',
            $body,
            [
                'nav' => 'home',
                'body_class' => 'page-home',
                'image' => $featuredReview?->imageSrc() ?? '',
                'json_ld' => $this->jsonLd([
                    $this->websiteGraph(),
                    [
                        '@type' => 'WebPage',
                        '@id' => $this->config->canonicalUrl().'#webpage',
                        'url' => $this->config->canonicalUrl(),
                        'name' => $this->config->name(),
                        'description' => $this->config->string('site.description'),
                        'isPartOf' => ['@id' => $this->config->canonicalUrl().'#website'],
                    ],
                ]),
            ],
        );
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @param  array<int, array{label: string, url: string}>  $crumbs
     * @param  Collection<int, Review>  $allReviews
     * @param  array<string, int>  $publishOrder
     */
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
    public function articlePage(
        PageDocument $page,
        string $path,
        array $crumbs,
        string $kicker,
        string $nav,
        ?Collection $relatedReviews = null,
        string $relatedHeading = 'From the cellar',
        string $afterProse = '',
        string $siblings = '',
    ): string {
        $description = $page->summary !== '' ? $page->summary : $this->config->string('site.description');
        $related = $relatedReviews?->isNotEmpty()
            ? $this->reviewCards($relatedReviews, compact: true)
            : '';

        $body = $this->view->render('article', [
            'title' => $page->title,
            'summary' => $page->summary,
            'kicker' => $kicker,
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'bodyHtml' => $page->bodyHtml,
            'afterProse' => $afterProse,
            'siblings' => $siblings,
            'related' => $related,
            'relatedHeading' => $relatedHeading,
        ]);

        return $this->document($page->title, $description, $path, $body, [
            'nav' => $nav,
            'og_type' => 'article',
            'json_ld' => $this->jsonLd([
                $this->breadcrumbGraph($crumbs),
                [
                    '@type' => $page->slug === 'privacy' ? 'PrivacyPolicy' : 'Article',
                    'headline' => $page->title,
                    'description' => $description,
                    'url' => $this->config->canonicalUrl($path),
                    'author' => $this->personGraph(),
                ],
            ]),
        ]);
    }

    /**
     * @param  Collection<int, Review>  $brandReviews
     * @param  array<int, array{label: string, url: string}>  $crumbs
     */
    public function brandPage(string $name, Collection $brandReviews, string $path, array $crumbs): string
    {
        $description = 'Reviews of dealcoholized and non-alcoholic products from '.$name.'.';
        $categories = $brandReviews
            ->pluck('category')
            ->unique()
            ->map(fn (string $category): string => $this->config->categoryLabel($category))
            ->values();
        $count = $brandReviews->count();
        $meta = ($count === 1 ? '1 review' : $count.' reviews')
            .($categories->isNotEmpty() ? ' · '.$categories->implode(', ') : '');
        $countries = $brandReviews
            ->map(fn (Review $review): ?string => $review->countryLabel())
            ->filter()
            ->unique()
            ->values();
        $mixItems = $brandReviews
            ->groupBy(fn (Review $review): string => $review->productionType)
            ->map(fn (Collection $group, string $type): string => $group->count().' '.(Review::PRODUCTION_TYPES[$type] ?? $type))
            ->values();
        $mix = '<p class="page-meta">'.$this->e($meta)
            .($countries->isNotEmpty() ? ' · '.$this->e($countries->implode(', ')) : '')
            .($mixItems->isNotEmpty() ? ' · '.$this->e($mixItems->implode(', ')) : '')
            .'</p>';

        $body = $this->view->render('brand', [
            'name' => $name,
            'description' => $description,
            'meta' => $meta,
            'mix' => $mix,
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'cards' => $this->reviewCards($brandReviews, compact: true),
        ]);

        return $this->document($name, $description, $path, $body, [
            'nav' => 'brands',
            'json_ld' => $this->jsonLd([
                $this->breadcrumbGraph($crumbs),
                [
                    '@type' => 'Brand',
                    'name' => $name,
                    'url' => $this->config->canonicalUrl($path),
                ],
            ]),
        ]);
    }

    /**
     * @param  Collection<int, array{slug: string, name: string, reviews: Collection<int, Review>}>  $brands
     */
    public function brandIndex(Collection $brands, string $query = ''): string
    {
        $groups = $brands
            ->groupBy(fn (array $brand): string => mb_strtoupper(mb_substr($brand['name'], 0, 1)))
            ->sortKeys();

        $letters = $groups->keys()->all();
        $letterNav = $letters === []
            ? ''
            : '<nav class="letter-nav" aria-label="Brands by letter">'.implode('', array_map(
                fn (string $letter): string => '<a href="'.$this->url('brands/').'#letter-'.$this->e($letter).'">'.$this->e($letter).'</a>',
                $letters,
            )).'</nav>';

        $listing = '';
        foreach ($groups as $letter => $items) {
            $rows = $items->map(function (array $brand): string {
                $count = $brand['reviews']->count();
                $categories = $brand['reviews']
                    ->pluck('category')
                    ->unique()
                    ->map(fn (string $category): string => $this->config->categoryLabel($category))
                    ->implode(', ');

                $aliasText = implode(' ', Taxonomy::brandAliases($brand['slug']));

                return $this->view->render('partials/directory-row', [
                    'href' => $this->config->publicUrl('brands/'.$brand['slug'].'/'),
                    'title' => $brand['name'],
                    'summary' => '',
                    'meta' => ($count === 1 ? '1 review' : $count.' reviews').($categories !== '' ? ' · '.$categories : ''),
                    'search' => mb_strtolower($brand['name'].' '.$categories.' '.$aliasText),
                ]);
            })->implode('');

            $listing .= '<section class="directory-group" id="letter-'.$this->e((string) $letter).'" data-directory-group>'
                .'<h2>'.$this->e((string) $letter).'</h2>'
                .'<div class="directory-list">'.$rows.'</div>'
                .'</section>';
        }

        return $this->directoryPage(
            'Brands',
            'Producers with published reviews. This index is generated from review data, not a separate marketing directory.',
            'brands/',
            'brands',
            $this->crumbs(['Brands' => 'brands/']),
            'The Dry Standard',
            $listing === ''
                ? '<p class="empty">Brand pages appear after the first review is published.</p>'
                : $listing,
            searchable: true,
            searchPlaceholder: 'Find a brand',
            letterNav: $letterNav,
            directoryQuery: $query,
        );
    }

    /**
     * @param  Collection<int, array{slug: string, label: string, reviews: Collection<int, Review>}>  $styles
     */
    public function styleIndex(Collection $styles, string $query = ''): string
    {
        $cards = $styles->map(function (array $style): string {
            $count = $style['reviews']->count();

            return $this->view->render('partials/directory-row', [
                'href' => $this->config->publicUrl('styles/'.$style['slug'].'/'),
                'title' => $style['label'],
                'summary' => '',
                'meta' => $count === 1 ? '1 review' : $count.' reviews',
                'search' => mb_strtolower($style['label'].' '.$style['slug']),
            ]);
        })->implode('');

        $listing = $cards === ''
            ? '<p class="empty">Style pages appear once two bottles share a glass.</p>'
            : '<div class="directory-list">'.$cards.'</div>';

        return $this->directoryPage(
            'Styles',
            'Generated indexes for styles with enough bottles to compare — Riesling, IPA, stout, and the rest of the cellar.',
            'styles/',
            'styles',
            $this->crumbs(['Styles' => 'styles/']),
            'The Dry Standard',
            $listing,
            searchable: $styles->isNotEmpty(),
            searchPlaceholder: 'Find a style',
            directoryQuery: $query,
        );
    }

    /**
     * @param  Collection<int, PageDocument>  $documents
     * @param  array<string, int>  $counts
     */
    public function documentIndex(
        string $title,
        string $description,
        string $path,
        string $nav,
        Collection $documents,
        array $counts = [],
        string $query = '',
    ): string {
        $kind = ucfirst(rtrim($path, '/'));
        $cards = $documents->map(function (PageDocument $document) use ($path, $kind, $counts): string {
            $count = $counts[$document->slug] ?? null;
            $meta = $kind;
            if (is_int($count)) {
                $meta .= $count === 1 ? ' · 1 review' : ' · '.$count.' reviews';
            }

            return $this->view->render('partials/directory-row', [
                'href' => $this->config->publicUrl($path.$document->slug.'/'),
                'title' => $document->title,
                'summary' => $document->summary,
                'meta' => $meta,
                'search' => mb_strtolower($document->title.' '.$document->summary),
            ]);
        })->implode('');

        $listing = $cards === ''
            ? '<p class="empty">Nothing published here yet.</p>'
            : '<div class="directory-list">'.$cards.'</div>';

        return $this->directoryPage(
            $title,
            $description,
            $path,
            $nav,
            $this->crumbs([$title => $path]),
            'The Dry Standard',
            $listing,
            searchable: $documents->isNotEmpty(),
            searchPlaceholder: 'Find a '.$kind,
            directoryQuery: $query,
        );
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     * @param  Collection<int, Review>|null  $relatedReviews
     */
    public function review(Review $review, array $crumbs, ?Collection $relatedReviews = null): string
    {
        $bodyHtml = Markdown::toHtml($review->bodyMarkdown);
        $hasGlance = $review->hasGlancePanel();
        $hasServe = ($review->serve !== null && $review->serve !== '')
            || (! $hasGlance && $review->bestFor !== null && $review->bestFor !== '');
        $related = $relatedReviews?->isNotEmpty()
            ? $this->reviewCards($relatedReviews, compact: true)
            : '';
        $relatedHeading = 'More from the cellar';
        $relatedHref = $this->config->publicUrl('reviews/');
        $relatedLinkLabel = 'All reviews';
        $compareSlugs = [$review->slug];
        if ($relatedReviews?->isNotEmpty()) {
            $sameStyle = $review->hasComparableStyle()
                && $relatedReviews->every(fn (Review $other): bool => $other->styleSlug() === $review->styleSlug());
            if ($sameStyle) {
                $relatedHeading = 'Other '.$review->styleLabel();
                $relatedHref = $this->config->publicUrl('styles/'.$review->styleSlug().'/');
                $relatedLinkLabel = 'All '.$review->styleLabel();
                foreach ($relatedReviews->take(3) as $other) {
                    $compareSlugs[] = $other->slug;
                }
            } elseif ($relatedReviews->contains(fn (Review $other): bool => $other->brandSlug() === $review->brandSlug())) {
                $relatedHeading = 'More from '.$review->brandDisplayName();
                $relatedHref = $this->config->publicUrl('brands/'.$review->brandSlug().'/');
                $relatedLinkLabel = $review->brandDisplayName();
            }
        }
        $compareSlugs = array_values(array_unique(array_slice($compareSlugs, 0, 4)));
        $compareHref = $this->config->publicUrl('compare/').'?slugs='.rawurlencode(implode(',', $compareSlugs));

        $badge = $this->view->render('partials/production-badge', [
            'type' => $review->productionType,
            'label' => $review->productionTypeShortLabel(),
        ]);

        $body = $this->view->render('review', [
            'title' => $review->title,
            'summary' => $review->summary,
            'categoryLabel' => $this->config->categoryLabel($review->category),
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'figure' => $this->productFigure($review, 'product-figure product-figure--hero', hero: true),
            'metaLine' => $this->reviewMetaLine($review),
            'byline' => 'Reviewed by '.$this->e($this->config->editorName()).', '.strtolower($this->config->editorRole()),
            'identity' => $this->view->render('partials/identity', [
                'factsPeek' => $this->factsPeek($review),
                'badge' => $badge,
                'verifiedLabel' => $review->verifiedLabel(),
            ]),
            'score' => $this->view->render('partials/score-badge', [
                'rating' => $review->rating,
                'band' => $review->scoreBandLabel(),
            ]),
            'statusLabel' => $review->productionTypeLabel(),
            'methodBlock' => $this->methodBlock($review),
            'discrepancies' => $this->discrepancies($review),
            'overview' => $bodyHtml !== ''
                ? '<section class="prose"><h2>'.$this->e($review->essayHeading()).'</h2>'.$bodyHtml.'</section>'
                : '',
            'tasting' => $this->tasting($review),
            'provenance' => $this->provenancePanel($review),
            'hasServe' => $hasServe,
            'serveBlock' => $this->optionalBlock($review->serve),
            'bestForBlock' => $hasGlance ? '' : $this->optionalBlock($review->bestFor, 'Best for: '),
            'verdict' => $review->verdict,
            'sources' => $this->sources($review),
            'facts' => $this->facts($review),
            'links' => $this->purchaseLinks($review),
            'related' => $related,
            'relatedHeading' => $relatedHeading,
            'relatedHref' => $relatedHref,
            'relatedLinkLabel' => $relatedLinkLabel,
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
            'pageUrl' => $this->config->publicUrl($review->path()),
            'compareHref' => $compareHref,
            'disclosure' => $this->disclosure($review),
            'industryUrl' => $this->config->publicUrl('industry/'),
        ]);

        return $this->document(
            $review->title,
            $review->summary,
            $review->path(),
            $body,
            [
                'nav' => 'reviews',
                'og_type' => 'article',
                'image' => $review->imageSrc() ?? '',
                'json_ld' => $this->jsonLd([
                    $this->websiteGraph(),
                    (new StructuredData($this->config))->organization(),
                    $this->breadcrumbGraph($crumbs),
                    $this->articleGraph($review),
                    $this->productGraph($review),
                ]),
            ],
        );
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @param  Collection<int, array{slug: string, name: string, reviews: Collection<int, Review>}>  $brands
     * @param  Collection<int, array{slug: string, label: string, reviews: Collection<int, Review>}>  $styles
     */
    public function sitemap(Collection $reviews, Collection $guides, Collection $methods, Collection $brands, Collection $styles = new Collection): string
    {
        $urls = [
            $this->sitemapUrl('', 'weekly', '1.0'),
            $this->sitemapUrl('reviews/', 'weekly', '0.8'),
            $this->sitemapUrl('guides/', 'monthly', '0.6'),
            $this->sitemapUrl('brands/', 'weekly', '0.6'),
            $this->sitemapUrl('methods/', 'monthly', '0.6'),
            $this->sitemapUrl('styles/', 'weekly', '0.6'),
            $this->sitemapUrl('about/', 'monthly', '0.5'),
            $this->sitemapUrl('privacy/', 'yearly', '0.2'),
            $this->sitemapUrl('best/', 'weekly', '0.7'),
            $this->sitemapUrl('compare/', 'weekly', '0.6'),
            $this->sitemapUrl('industry/', 'monthly', '0.4'),
            $this->sitemapUrl('industry/submit/', 'monthly', '0.4'),
            $this->sitemapUrl('industry/samples/', 'monthly', '0.3'),
            $this->sitemapUrl('industry/partnerships/', 'monthly', '0.4'),
        ];

        foreach ($this->config->categories() as $category) {
            $urls[] = $this->sitemapUrl('reviews/'.$category.'/', 'weekly', '0.7');
            $urls[] = $this->sitemapUrl('best/'.$category.'/', 'weekly', '0.6');
        }

        foreach ($reviews as $review) {
            $urls[] = $this->sitemapUrl($review->path(), 'monthly', '0.8', $review->modifiedAt()->toDateString());
        }

        foreach ($guides as $guide) {
            $urls[] = $this->sitemapUrl('guides/'.$guide->slug.'/', 'monthly', '0.6');
        }

        foreach ($methods as $method) {
            $urls[] = $this->sitemapUrl('methods/'.$method->slug.'/', 'monthly', '0.6');
        }

        foreach ($brands as $brand) {
            $urls[] = $this->sitemapUrl('brands/'.$brand['slug'].'/', 'weekly', '0.5');
        }

        foreach ($styles as $style) {
            $urls[] = $this->sitemapUrl('styles/'.$style['slug'].'/', 'weekly', '0.6');
        }

        $body = implode("\n", $urls);

        return <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
{$body}
</urlset>
XML;
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
    public function feed(Collection $reviews): string
    {
        $updated = $reviews->map(fn (Review $review): string => $review->modifiedAt()->toIso8601String())->first()
            ?? CarbonImmutable::now()->toIso8601String();
        $entries = $reviews->map(function (Review $review): string {
            $url = $this->config->canonicalUrl($review->path());
            $title = Str::xml($review->title);
            $summary = Str::xml($review->summary);
            $updated = $review->modifiedAt()->toIso8601String();
            $published = $review->reviewDate->toIso8601String();

            return <<<XML
  <entry>
    <id>{$url}</id>
    <title>{$title}</title>
    <link rel="alternate" type="text/html" href="{$url}"/>
    <updated>{$updated}</updated>
    <published>{$published}</published>
    <author><name>{$this->xml($this->config->editorName())}</name></author>
    <category term="{$review->category}"/>
    <summary>{$summary}</summary>
  </entry>
XML;
        })->implode("\n");

        $home = $this->config->canonicalUrl();
        $feed = $this->config->canonicalUrl('feed.xml');
        $name = Str::xml($this->config->name());
        $editor = $this->xml($this->config->editorName());

        return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>{$name}</title>
  <subtitle>{$this->xml($this->config->string('site.description'))}</subtitle>
  <link rel="alternate" type="text/html" href="{$home}"/>
  <link rel="self" type="application/atom+xml" href="{$feed}"/>
  <id>{$feed}</id>
  <updated>{$updated}</updated>
  <author><name>{$editor}</name></author>
{$entries}
</feed>
XML;
    }

    /**
     * @param  array<string, string>  $items
     * @return array<int, array{label: string, url: string}>
     */
    public function crumbs(array $items): array
    {
        $crumbs = [['label' => 'Home', 'url' => '']];

        foreach ($items as $label => $url) {
            $crumbs[] = ['label' => $label, 'url' => $url];
        }

        return $crumbs;
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     */
    private function directoryPage(
        string $title,
        string $description,
        string $path,
        string $nav,
        array $crumbs,
        string $kicker,
        string $listing,
        bool $searchable = false,
        string $searchPlaceholder = 'Find a name',
        string $letterNav = '',
        string $directoryQuery = '',
    ): string {
        $body = $this->view->render('directory', [
            'title' => $title,
            'description' => $description,
            'kicker' => $kicker,
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'searchable' => $searchable,
            'searchPlaceholder' => $searchPlaceholder,
            'letterNav' => $letterNav,
            'listing' => $listing,
            'directoryQuery' => $directoryQuery,
            'searchAction' => $this->config->publicUrl($path),
        ]);

        return $this->document($title, $description, $path, $body, [
            'nav' => $nav,
            'json_ld' => $this->jsonLd([
                $this->breadcrumbGraph($crumbs),
                [
                    '@type' => 'CollectionPage',
                    'name' => $title,
                    'description' => $description,
                    'url' => $this->config->canonicalUrl($path),
                ],
            ]),
        ]);
    }

    private function header(string $current): string
    {
        $links = [
            'reviews' => ['Reviews', 'reviews/'],
            'best' => ['Best', 'best/'],
            'compare' => ['Compare', 'compare/'],
            'brands' => ['Brands', 'brands/'],
            'guides' => ['Learn', 'guides/'],
        ];
        $cellar = [
            'methods' => ['How it’s made', 'methods/'],
            'styles' => ['Styles', 'styles/'],
        ];

        $items = '';
        foreach ($links as $key => [$label, $path]) {
            $currentAttr = $current === $key ? ' aria-current="page"' : '';
            $items .= '<a href="'.$this->url($path).'"'.$currentAttr.'>'.Str::e($label).'</a>';
        }
        $items .= '<span class="nav-split" aria-hidden="true"></span>';
        foreach ($cellar as $key => [$label, $path]) {
            $currentAttr = $current === $key ? ' aria-current="page"' : '';
            $items .= '<a class="nav-cellar" href="'.$this->url($path).'"'.$currentAttr.'>'.Str::e($label).'</a>';
        }

        return $this->view->render('partials/header', [
            'homeUrl' => $this->config->publicUrl(),
            'homeCurrent' => $current === 'home',
            'markUrl' => $this->config->publicUrl('mark.svg'),
            'searchUrl' => $this->config->publicUrl('reviews/'),
            'items' => $items,
        ]);
    }

    private function footer(): string
    {
        $categories = [];
        foreach ($this->config->categories() as $category) {
            $categories[] = [
                'href' => $this->config->publicUrl('reviews/'.$category.'/'),
                'label' => $this->config->categoryLabel($category),
            ];
        }

        return $this->view->render('partials/footer', [
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
            'guidesUrl' => $this->config->publicUrl('guides/'),
            'methodsUrl' => $this->config->publicUrl('methods/'),
            'brandsUrl' => $this->config->publicUrl('brands/'),
            'aboutUrl' => $this->config->publicUrl('about/'),
            'privacyUrl' => $this->config->publicUrl('privacy/'),
            'feedUrl' => $this->config->publicUrl('feed.xml'),
            'bestUrl' => $this->config->publicUrl('best/'),
            'compareUrl' => $this->config->publicUrl('compare/'),
            'stylesUrl' => $this->config->publicUrl('styles/'),
            'industryUrl' => $this->config->publicUrl('industry/'),
            'submitUrl' => $this->config->publicUrl('industry/submit/'),
            'partnershipsUrl' => $this->config->publicUrl('industry/partnerships/'),
            'year' => (string) now()->year,
            'categories' => $categories,
            'editorEmail' => $this->config->editorEmail(),
            'editorMailto' => $this->config->editorMailto(),
        ]);
    }

    /**
     * @param  Collection<int, Review>  $reviews
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

        $facetCount = function (string $name, string $value) use ($reviews, $query): int {
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
                    'score' => $review->scoreBand(),
                    'color' => (string) ($review->wineColor() ?? ''),
                    default => '',
                };

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
                'score' => $query->score,
                'color' => $query->wineColor,
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

        $colorOptions = [];
        if ($lockedCategory === 'wine' || $reviews->contains(fn (Review $review): bool => $review->category === 'wine')) {
            foreach (Sensory::wineColorLabels() as $value => $label) {
                if ($reviews->contains(fn (Review $review): bool => $review->wineColor() === $value)) {
                    $colorOptions[] = ['value' => $value, 'label' => $label];
                }
            }
        }

        $brandMeta = $withMeta($brandOptions, 'brand');
        $facets = $this->facetGroup('ABV', 'abv', $withMeta($abvOptions, 'abv'))
            .$this->facetGroup(
                'Brand',
                'brand',
                $brandMeta,
                searchable: count($brandMeta) > 8,
                collapsible: count($brandMeta) > 8,
                collapsed: count($brandMeta) > 8,
            )
            .($categoryOptions === [] ? '' : $this->facetGroup('Category', 'category', $withMeta($categoryOptions, 'category')))
            .($colorOptions === [] ? '' : $this->facetGroup('Wine color', 'color', $withMeta($colorOptions, 'color')))
            .$this->facetGroup('Production type', 'production', $withMeta($processOptions, 'production'))
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
            .$this->facetGroup('Score', 'score', $withMeta($scoreOptions, 'score'))
            .$this->facetGroup('Country', 'country', $withMeta($countryOptions, 'country'));

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

    private function featuredReview(Review $review): string
    {
        $badge = $this->view->render('partials/production-badge', [
            'type' => $review->productionType,
            'label' => $review->productionTypeShortLabel(),
        ]);

        return $this->view->render('partials/featured-review', [
            'href' => $this->config->publicUrl($review->path()),
            'figure' => $this->productFigure($review, 'product-figure product-figure--feature', hero: true),
            'brand' => '<a href="'.$this->url('brands/'.$review->brandSlug().'/').'">'.$this->e($review->brandDisplayName()).'</a>',
            'title' => $review->cardTitle(),
            'summary' => $review->summary,
            'score' => $review->rating !== null ? '<span class="card-score">'.$review->rating.'</span>' : '',
            'badge' => $badge,
        ]);
    }

    private function reviewCards(Collection $reviews, bool $compact = false): string
    {
        return $reviews->map(function (Review $review) use ($compact): string {
            $score = $review->rating !== null ? '<span class="card-score">'.$review->rating.'</span>' : '';
            $meta = $review->cardMetaLine($this->config->categoryLabel($review->category));
            $badge = $this->view->render('partials/production-badge', [
                'type' => $review->productionType,
                'label' => $review->productionTypeShortLabel(),
            ]);
            $attrs = implode(' ', [
                'data-production="'.Str::e($review->productionType).'"',
                'data-verified="'.Str::e($review->verified).'"',
                'data-category="'.Str::e($review->category).'"',
                'data-brand="'.Str::e($review->brandSlug()).'"',
                'data-abv="'.Str::e($review->abvBucket()).'"',
                'data-method="'.Str::e($review->methodFacetKey()).'"',
                'data-rating="'.Str::e((string) ($review->rating ?? 0)).'"',
                'data-date="'.Str::e($review->reviewDate->toDateString()).'"',
                'data-search="'.Str::e($review->searchText()).'"',
            ]);
            $thumb = $this->productFigure($review, 'product-figure product-figure--thumb');
            $brand = '<a href="'.$this->url('brands/'.$review->brandSlug().'/').'">'.$this->e($review->brandDisplayName()).'</a>';

            return $this->view->render('partials/review-card', [
                'compact' => $compact,
                'attrs' => $attrs,
                'thumb' => $thumb,
                'brand' => $brand,
                'href' => $this->config->publicUrl($review->path()),
                'title' => $review->cardTitle(),
                'summary' => $review->summary,
                'meta' => $meta,
                'badge' => $badge,
                'score' => $score,
                'compareSlug' => $review->slug,
            ]);
        })->implode('');
    }

    private function reviewMetaLine(Review $review): string
    {
        $parts = [
            '<a href="'.$this->url('brands/'.$review->brandSlug().'/').'">'.$this->e($review->brandDisplayName()).'</a>',
            '<a href="'.$this->url('reviews/'.$review->category.'/').'">'.$this->e($this->config->categoryLabel($review->category)).'</a>',
        ];

        if ($review->hasComparableStyle()) {
            $parts[] = '<a href="'.$this->url('styles/'.$review->styleSlug().'/').'">'.$this->e($review->styleLabel()).'</a>';
        }

        $methodKey = $review->methodKey();
        if ($methodKey !== null && in_array($methodKey, Review::METHOD_FACETS, true)) {
            $parts[] = '<a href="'.$this->url('methods/'.$methodKey.'/').'">'.$this->e($review->methodCardLabel()).'</a>';
        } elseif ($review->dealcoholizationMethod) {
            $parts[] = $this->e($review->methodCardLabel());
        }

        return '<p class="review-meta">'.implode('<span aria-hidden="true"> · </span>', $parts).'</p>';
    }

    private function methodBlock(Review $review): string
    {
        if ($review->dealcoholizationMethod === null || $review->dealcoholizationMethod === '') {
            return '';
        }

        $methodKey = $review->methodKey();
        if ($methodKey !== null && in_array($methodKey, Review::METHOD_FACETS, true)) {
            return '<p>Method: <a href="'.$this->url('methods/'.$methodKey.'/').'">'.$this->e($review->dealcoholizationMethod).'</a></p>';
        }

        return $this->optionalBlock($review->dealcoholizationMethod, 'Method: ');
    }

    private function facts(Review $review): string
    {
        $rows = [
            'ABV' => $review->abv,
            'Origin' => $review->originLabel(),
            'Category' => $this->config->categoryLabel($review->category).($review->subcategory ? ' / '.$review->subcategory : ''),
            'Style' => $review->style,
            'Producer' => $review->producer,
            'Base beverage' => $review->baseBeverage,
            'Bottle / can' => $review->volume,
            'Typical price' => $review->price,
            'Ingredients' => $review->ingredients,
            'Calories' => $review->calories,
            'Sugar' => $review->sugar,
        ];

        $html = '';
        foreach ($rows as $label => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $html .= '<div><dt>'.Str::e($label).'</dt><dd>'.Str::e($value).'</dd></div>';
        }

        return $this->view->render('partials/facts', ['rows' => $html]);
    }

    /**
     * @return array<int, array{label: string, value: string}>
     */
    private function factsPeek(Review $review): array
    {
        $peek = [];
        if ($review->abv !== null && $review->abv !== '') {
            $peek[] = ['label' => 'ABV', 'value' => $review->abv];
        }
        $country = $review->countryLabel();
        if ($country !== null && $country !== '') {
            $peek[] = [
                'label' => 'Origin',
                'value' => $country,
                'href' => $this->config->publicUrl('reviews/').'?country='.$review->countrySlug(),
            ];
        }
        if ($review->hasComparableStyle()) {
            $peek[] = [
                'label' => 'Style',
                'value' => $review->styleLabel(),
                'href' => $this->config->publicUrl('styles/'.$review->styleSlug().'/'),
            ];
        } elseif ($review->style) {
            $peek[] = ['label' => 'Style', 'value' => $review->style];
        }

        $methodKey = $review->methodKey();
        if ($review->methodFacetKey() === 'not-applicable') {
            $peek[] = ['label' => 'Method', 'value' => 'Formulated'];
        } elseif ($methodKey !== null && in_array($methodKey, Review::METHOD_FACETS, true)) {
            $peek[] = [
                'label' => 'Method',
                'value' => $this->peekMethodLabel($review),
                'href' => $this->config->publicUrl('methods/'.$methodKey.'/'),
            ];
        }

        return $peek;
    }

    private function peekMethodLabel(Review $review): string
    {
        return match ($review->methodFacetKey()) {
            'membrane-filtration' => 'Cold filtration',
            'vacuum-distillation' => 'Vacuum',
            'reverse-osmosis' => 'Reverse osmosis',
            'spinning-cone' => 'Spinning cone',
            'osmotic-distillation' => 'Osmotic',
            'arrested-fermentation' => 'Arrested',
            'not-applicable' => 'Formulated',
            'other' => 'Other method',
            default => $review->methodCardLabel(),
        };
    }

    private function tasting(Review $review): string
    {
        $parts = [
            'Nose' => $review->nose,
            'Palate' => $review->palate,
            'Finish' => $review->finish,
        ];

        $notes = [];
        foreach ($parts as $label => $value) {
            if ($value === null) {
                continue;
            }

            $notes[] = [
                'label' => $label,
                'text' => $value,
            ];
        }

        $hasGlance = $review->hasGlancePanel();
        if (! $hasGlance && $notes === []) {
            return '';
        }

        return $this->view->render('partials/tasting', [
            'heading' => $hasGlance ? 'At a glance' : 'Tasting notes',
            'showGlance' => $hasGlance,
            'tastes' => $review->flavorProfileLabels(),
            'profile' => $review->structureProfileLabels(),
            'mouthfeel' => $review->mouthfeel,
            'assessments' => $this->assessmentChips($review),
            'highlight' => $review->distinctHighlight(),
            'likeness' => $review->likenessText(),
            'likenessHeading' => $review->likenessHeading(),
            'perfectFor' => $hasGlance ? $review->bestFor : null,
            'drinkIfYouLike' => $review->drinkIfYouLike,
            'productionLine' => null,
            'notes' => $notes,
            'detailTitle' => $hasGlance && $notes !== [] ? 'Tasting notes' : null,
        ]);
    }

    /**
     * @return array<int, string>
     */
    private function assessmentChips(Review $review): array
    {
        $chips = [];
        foreach ($review->assessments as $dimension => $level) {
            $label = Sensory::assessmentLabel($dimension, (int) $level);
            $heading = Sensory::assessments()[$dimension]['label'] ?? $dimension;
            if ($label !== null) {
                $chips[] = $heading.': '.$label;
            }
        }

        return $chips;
    }

    private function provenancePanel(Review $review): string
    {
        $provenance = $review->provenanceRecord();
        if ($provenance === []) {
            return '';
        }

        $labels = [
            'abv' => 'ABV',
            'dealcoholization_method' => 'Production method',
            'production_type' => 'Production type',
            'country' => 'Origin / country',
            'region' => 'Region',
            'producer' => 'Producer',
            'ingredients' => 'Ingredients',
            'calories' => 'Calories',
            'sugar' => 'Sugar',
            'price' => 'Price',
            'availability' => 'Availability',
            'volume' => 'Volume',
            'base_beverage' => 'Base beverage',
            'ean' => 'Barcode (EAN/GTIN)',
        ];

        $kindLabels = [
            'manufacturer' => 'Producer',
            'label' => 'Bottle / can label',
            'retailer' => 'Retail listing',
            'distributor' => 'Distributor / importer',
            'government' => 'Government record',
            'research' => 'Research / registry',
            'press' => 'Press',
            'inference' => 'Editorial inference',
            'unknown' => 'Cited source',
        ];

        $confidenceLabels = [
            'verified' => 'Independently corroborated',
            'manufacturer_verified' => 'Producer verified',
            'label_verified' => 'Bottle verified',
            'secondary' => 'Secondary source',
            'inferred' => 'Inferred',
            'unverified' => 'Unverified',
            'bottle_verified' => 'Bottle verified',
            'producer_verified' => 'Producer verified',
            'distributor_verified' => 'Distributor verified',
            'retailer_verified' => 'Retailer verified',
            'independently_corroborated' => 'Independently corroborated',
        ];

        $grouped = [];
        foreach ($provenance as $field => $entry) {
            $url = (string) ($entry['url'] ?? '');
            $kind = Review::resolveProvenanceKind(
                (string) ($entry['kind'] ?? 'unknown'),
                $url,
                (string) ($entry['note'] ?? ''),
            );
            $confidence = Sensory::normalizeConfidence((string) ($entry['confidence'] ?? (
                in_array($kind, ['manufacturer', 'label'], true) ? 'manufacturer_verified' : 'secondary'
            )));
            if ($confidence === '') {
                $confidence = 'secondary';
            }
            $key = $confidence.'|'.$kind.'|'.$url;
            if (! isset($grouped[$key])) {
                $note = isset($entry['note']) && ! str_starts_with((string) $entry['note'], 'Derived from')
                    ? (string) $entry['note']
                    : null;
                $host = $url !== '' ? (parse_url($url, PHP_URL_HOST) ?: null) : null;
                if (is_string($host) && str_starts_with($host, 'www.')) {
                    $host = substr($host, 4);
                }
                $grouped[$key] = [
                    'kind' => $kindLabels[$kind] ?? $kind,
                    'confidence' => $confidenceLabels[$confidence] ?? $confidence,
                    'confidenceClass' => preg_replace('/[^a-z0-9-]+/', '-', $confidence) ?: 'secondary',
                    'href' => $url !== '' ? $url : null,
                    'source' => $host ?? ($note ?? 'Recorded claim'),
                    'note' => $url === '' ? $note : null,
                    'fields' => [],
                ];
            }
            $grouped[$key]['fields'][] = $labels[$field] ?? ucfirst(str_replace('_', ' ', $field));
        }

        $groups = array_values($grouped);
        $fieldCount = array_sum(array_map(fn (array $group): int => count($group['fields']), $groups));
        $sourceCount = count($groups);
        $summary = $fieldCount.' '.($fieldCount === 1 ? 'fact' : 'facts')
            .' · '.$sourceCount.' '.($sourceCount === 1 ? 'source' : 'sources');

        return $this->view->render('partials/provenance', [
            'groups' => $groups,
            'summary' => $summary,
        ]);
    }

    private function sources(Review $review): string
    {
        if ($review->sources === []) {
            return '';
        }

        $items = '';
        foreach ($review->sources as $source) {
            $items .= '<li><a href="'.Str::e($source['url']).'" rel="nofollow noopener">'.Str::e($source['title']).'</a></li>';
        }

        return $this->view->render('partials/sources', ['items' => $items]);
    }

    private function discrepancies(Review $review): string
    {
        if ($review->discrepancies === []) {
            return '';
        }

        $items = '';
        foreach ($review->discrepancies as $row) {
            $items .= '<li><strong>'.Str::e(ucfirst($row['field'])).':</strong> '.Str::e($row['note']).'</li>';
        }

        return $this->view->render('partials/discrepancies', ['items' => $items]);
    }

    private function purchaseLinks(Review $review): string
    {
        if ($review->purchaseLinks === []) {
            return $review->availability
                ? '<p><strong>Where to buy:</strong> '.Str::e($review->availability).'</p>'
                : '';
        }

        $items = '';
        foreach ($review->purchaseLinks as $link) {
            $relationship = $link['relationship'] ?? 'citation';
            $rel = $relationship === 'affiliate'
                ? 'sponsored nofollow noopener'
                : 'nofollow noopener';
            $label = $link['label'];
            if (! empty($link['region'])) {
                $label .= ' ('.$link['region'].')';
            }
            $items .= '<li><a href="'.Str::e($link['url']).'" rel="'.$rel.'" data-analytics-event="outbound_buy">'.Str::e($label).'</a></li>';
        }

        return $this->view->render('partials/purchase-links', [
            'availability' => $review->availability ? '<p>'.Str::e($review->availability).'</p>' : '',
            'items' => $items,
        ]);
    }

    private function disclosure(Review $review): string
    {
        if (! $review->hasPublicDisclosure()) {
            return '';
        }

        $items = '';
        foreach ($review->disclosureLines() as $line) {
            $items .= '<li>'.Str::e($line).'</li>';
        }

        return $this->view->render('partials/disclosure', ['items' => $items]);
    }

    public function industryNote(): string
    {
        $url = $this->config->publicUrl('industry/');

        return '<p class="industry-note">Brands, producers, importers, and other industry partners may <a href="'.$this->e($url).'">submit a product</a> or inquire about collaborations. Editorial coverage is independent of samples and commercial relationships.</p>';
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     */
    private function breadcrumbs(array $crumbs): string
    {
        $items = '';
        $last = array_key_last($crumbs);

        foreach ($crumbs as $index => $crumb) {
            if ($index === $last) {
                $items .= '<li><span aria-current="page">'.Str::e($crumb['label']).'</span></li>';
            } else {
                $items .= '<li><a href="'.Str::e($this->config->publicUrl(ltrim((string) ($crumb['url'] ?? ''), '/'))).'">'.Str::e($crumb['label']).'</a></li>';
            }
        }

        return $this->view->render('partials/breadcrumbs', ['items' => $items]);
    }

    /**
     * @param  array<int, array<string, mixed>>  $graph
     */
    private function jsonLd(array $graph): string
    {
        return (new StructuredData($this->config))->script($graph);
    }

    /**
     * @return array<string, mixed>
     */
    private function websiteGraph(): array
    {
        return (new StructuredData($this->config))->website();
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     * @return array<string, mixed>
     */
    private function breadcrumbGraph(array $crumbs): array
    {
        return (new StructuredData($this->config))->breadcrumbs($crumbs);
    }

    /**
     * @return array<string, mixed>
     */
    private function articleGraph(Review $review): array
    {
        return (new StructuredData($this->config))->review($review);
    }

    /**
     * @return array<string, mixed>
     */
    private function productGraph(Review $review): array
    {
        return (new StructuredData($this->config))->product($review, $this->config->categoryLabel($review->category));
    }

    /**
     * @return array<string, mixed>
     */
    private function personGraph(): array
    {
        return (new StructuredData($this->config))->person();
    }

    /**
     * @return array{editorName: string, editorEmail: string, editorMailto: string}
     */
    private function editorViewData(): array
    {
        return [
            'editorName' => $this->config->editorName(),
            'editorEmail' => $this->config->editorEmail(),
            'editorMailto' => $this->config->editorMailto(),
        ];
    }

    private function editorDesk(): string
    {
        return $this->view->render('partials/editor-desk', $this->editorViewData() + [
            'name' => $this->config->editorName(),
            'role' => $this->config->editorRole(),
            'email' => $this->config->editorEmail(),
            'mailto' => $this->config->editorMailto(),
            'location' => $this->config->editorLocation(),
        ]);
    }

    private function productFigure(Review $review, string $class, bool $hero = false): string
    {
        $assets = $review->imageAssets();

        return $this->view->render('partials/product-figure', [
            'class' => $class,
            'src' => $assets === null ? '' : $this->config->publicUrl($assets['src']),
            'webp' => ($assets['webp'] ?? null) ? $this->config->publicUrl((string) $assets['webp']) : '',
            'webpSrcset' => $this->srcsetUrls($assets['webpSrcset'] ?? ''),
            'srcset' => $this->srcsetUrls($assets['srcset'] ?? ''),
            'sizes' => match (true) {
                str_contains($class, 'product-figure--feature') => '(max-width: 640px) 78vw, (max-width: 1024px) 42vw, 420px',
                $hero => '(max-width: 640px) 42vw, 224px',
                default => '(max-width: 640px) 78vw, (max-width: 980px) 45vw, 274px',
            },
            'width' => $assets['width'] ?? 720,
            'height' => $assets['height'] ?? 960,
            'alt' => $review->imageAltText(),
            'loading' => $hero ? 'eager' : 'lazy',
            'priority' => $hero,
            'credit' => $hero && $review->imageCredit !== null
                ? '<figcaption>'.$this->e($review->imageCredit).'</figcaption>'
                : '',
        ]);
    }

    private function optionalBlock(?string $value, string $prefix = ''): string
    {
        if ($value === null || $value === '') {
            return '';
        }

        return '<p>'.Str::e($prefix.$value).'</p>';
    }

    private function sitemapUrl(string $path, string $freq, string $priority, ?string $lastmod = null): string
    {
        $loc = Str::xml($this->config->canonicalUrl($path));
        $last = $lastmod ? '<lastmod>'.Str::xml($lastmod).'</lastmod>' : '';

        return "  <url><loc>{$loc}</loc>{$last}<changefreq>{$freq}</changefreq><priority>{$priority}</priority></url>";
    }

    private function srcsetUrls(string $srcset): string
    {
        if ($srcset === '') {
            return '';
        }

        $parts = [];
        foreach (preg_split('/\s*,\s*/', $srcset) ?: [] as $candidate) {
            if (! preg_match('/^(?<path>\S+)\s+(?<width>\d+w)$/', $candidate, $matches)) {
                continue;
            }
            $parts[] = $this->config->publicUrl($matches['path']).' '.$matches['width'];
        }

        return implode(', ', $parts);
    }

    private function url(string $path = ''): string
    {
        return Str::e($this->config->publicUrl($path));
    }

    private function e(string $value): string
    {
        return Str::e($value);
    }

    private function xml(string $value): string
    {
        return Str::xml($value);
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
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
                'band' => $review->scoreBandLabel(),
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

    private function defaultShareImage(): string
    {
        foreach (['media/reviews/guinness-0-0.jpg', 'mark.svg'] as $relative) {
            $absolute = Paths::default()->path().DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);
            if (is_file($absolute)) {
                return $relative;
            }
        }

        return '';
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
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

    private function navKey(string $path): string
    {
        $path = trim($path, '/');

        if ($path === '' || $path === 'home') {
            return 'home';
        }

        return explode('/', $path)[0];
    }

    /**
     * @param  array{csrf: string, errors: array<string, string>, old: array<string, mixed>, sent: bool, failed: bool}  $form
     */
    public function industrySubmit(array $form): string
    {
        return $this->industryFormPage(
            'Submit a product',
            'Tell us about a non-alcoholic drink for editorial consideration. Submission does not guarantee publication or a favorable review.',
            'industry/submit/',
            'submit',
            $form,
        );
    }

    /**
     * @param  array{csrf: string, errors: array<string, string>, old: array<string, mixed>, sent: bool, failed: bool}  $form
     */
    public function industryPartnerships(array $form): string
    {
        return $this->industryFormPage(
            'Partnerships & business inquiries',
            'A quiet front desk for advertising, distribution, product feeds, and other collaborations — without turning the cellar into a sales floor.',
            'industry/partnerships/',
            'partnerships',
            $form,
        );
    }

    public function industryHome(): string
    {
        $crumbs = $this->crumbs(['For Brands & Industry' => 'industry/']);
        $body = $this->view->render('industry', [
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'submitUrl' => $this->config->publicUrl('industry/submit/'),
            'samplesUrl' => $this->config->publicUrl('industry/samples/'),
            'partnershipsUrl' => $this->config->publicUrl('industry/partnerships/'),
            'aboutUrl' => $this->config->publicUrl('about/'),
            'privacyUrl' => $this->config->publicUrl('privacy/'),
            'editorDesk' => $this->editorDesk(),
        ]);

        return $this->document(
            'For Brands & Industry',
            'Submit a product for editorial consideration, request sample-shipping details, or inquire about collaborations with The Dry Standard.',
            'industry/',
            $body,
            [
                'nav' => 'industry',
                'json_ld' => $this->jsonLd([
                    $this->breadcrumbGraph($crumbs),
                    [
                        '@type' => 'WebPage',
                        'name' => 'For Brands & Industry',
                        'url' => $this->config->canonicalUrl('industry/'),
                    ],
                ]),
            ],
        );
    }

    public function industrySamples(): string
    {
        $crumbs = $this->crumbs([
            'For Brands & Industry' => 'industry/',
            'Editorial samples' => 'industry/samples/',
        ]);
        $body = $this->view->render('industry-samples', [
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'submitUrl' => $this->config->publicUrl('industry/submit/'),
            'industryUrl' => $this->config->publicUrl('industry/'),
            'privacyUrl' => $this->config->publicUrl('privacy/'),
            ...$this->editorViewData(),
        ]);

        return $this->document(
            'Editorial samples',
            'How brands may send products to The Dry Standard for independent editorial consideration.',
            'industry/samples/',
            $body,
            [
                'nav' => 'industry',
                'json_ld' => $this->jsonLd([
                    $this->breadcrumbGraph($crumbs),
                    [
                        '@type' => 'WebPage',
                        'name' => 'Editorial samples',
                        'url' => $this->config->canonicalUrl('industry/samples/'),
                    ],
                ]),
            ],
        );
    }

    /**
     * @param  array{csrf: string, errors: array<string, string>, old: array<string, mixed>, sent: bool, failed: bool}  $form
     */
    private function industryFormPage(
        string $title,
        string $description,
        string $path,
        string $kind,
        array $form,
    ): string {
        $crumbs = $this->crumbs([
            'For Brands & Industry' => 'industry/',
            $title => $path,
        ]);
        $template = $kind === 'partnerships' ? 'industry-partnerships' : 'industry-submit';
        $body = $this->view->render($template, [
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'action' => $this->config->publicUrl($path),
            'csrf' => $form['csrf'],
            'errors' => $form['errors'],
            'old' => $form['old'],
            'sent' => $form['sent'],
            'failed' => $form['failed'] ?? false,
            'industryUrl' => $this->config->publicUrl('industry/'),
            'samplesUrl' => $this->config->publicUrl('industry/samples/'),
            'submitUrl' => $this->config->publicUrl('industry/submit/'),
            'privacyUrl' => $this->config->publicUrl('privacy/'),
            'categories' => array_map(
                fn (string $category): array => [
                    'value' => $category,
                    'label' => $this->config->categoryLabel($category),
                ],
                $this->config->categories(),
            ),
            ...$this->editorViewData(),
        ]);

        return $this->document($title, $description, $path, $body, [
            'nav' => 'industry',
            'json_ld' => $this->jsonLd([
                $this->breadcrumbGraph($crumbs),
                [
                    '@type' => 'WebPage',
                    'name' => $title,
                    'url' => $this->config->canonicalUrl($path),
                ],
            ]),
        ]);
    }
}
