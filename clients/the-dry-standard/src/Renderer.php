<?php

namespace DryStandard;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

final class Renderer
{
    private const CSS_VERSION = '16';

    private const JS_VERSION = '8';

    public function __construct(
        private readonly SiteConfig $config,
        private readonly View $view = new View(__DIR__.DIRECTORY_SEPARATOR.'views'),
    ) {}

    /**
     * @param  array<string, mixed>  $options
     */
    public function document(string $title, string $description, string $path, string $body, array $options = []): string
    {
        $canonical = $this->config->canonicalUrl($path);
        $ogType = (string) ($options['og_type'] ?? 'website');
        $image = (string) ($options['image'] ?? '');
        $ogImage = $image === ''
            ? '  <meta name="twitter:card" content="summary">'
            : <<<HTML
  <meta property="og:image" content="{$this->e($this->config->canonicalUrl($image))}">
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
            'stylesheet' => $this->config->publicUrl('styles.css').'?v='.self::CSS_VERSION,
            'script' => $this->config->publicUrl('script.js').'?v='.self::JS_VERSION,
            'extraHead' => (string) ($options['head'] ?? ''),
            'jsonLd' => (string) ($options['json_ld'] ?? ''),
            'bodyClass' => (string) ($options['body_class'] ?? ''),
            'bodyAttrs' => (string) ($options['body_attrs'] ?? ''),
            'header' => $this->header($this->navKey((string) ($options['nav'] ?? $path))),
            'footer' => $this->footer(),
            'body' => $body,
        ]);
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
        $latest = $reviews
            ->reject(fn (Review $review): bool => $review->slug === $featuredSlug)
            ->take(6);
        $highlyRated = $reviews
            ->filter(fn (Review $review): bool => ($review->rating ?? 0) >= 85)
            ->sortByDesc(fn (Review $review): int => $review->rating ?? 0)
            ->reject(fn (Review $review): bool => $review->slug === $featuredSlug)
            ->take(3);
        $dealcoholized = $reviews->filter(fn (Review $review): bool => $review->productionType === 'dealcoholized')->count();

        $categoryItems = [];
        foreach ($this->config->categories() as $category) {
            $count = $reviews->filter(fn (Review $review): bool => $review->category === $category)->count();
            $categoryItems[] = [
                'href' => $this->config->publicUrl('reviews/'.$category.'/'),
                'label' => $this->config->categoryLabel($category),
                'count' => $count,
            ];
        }

        $processItems = [];
        foreach (Review::PRODUCTION_TYPES as $value => $label) {
            $processItems[] = [
                'href' => $this->config->publicUrl('reviews/').'?production='.$value,
                'label' => $label,
                'count' => $reviews->filter(fn (Review $review): bool => $review->productionType === $value)->count(),
            ];
        }

        $methodCards = $methods->take(4)->map(function (PageDocument $method) use ($reviews): string {
            $count = $reviews->filter(fn (Review $review): bool => $review->methodKey() === $method->slug)->count();

            return $this->view->render('partials/text-card', [
                'kicker' => 'Method',
                'href' => $this->config->publicUrl('methods/'.$method->slug.'/'),
                'title' => $method->title,
                'summary' => $method->summary,
                'meta' => $count === 0 ? null : ($count === 1 ? '1 review' : $count.' reviews'),
            ]);
        })->implode('');

        $guideCards = $guides->take(3)->map(function (PageDocument $guide): string {
            return $this->view->render('partials/text-card', [
                'kicker' => 'Guide',
                'href' => $this->config->publicUrl('guides/'.$guide->slug.'/'),
                'title' => $guide->title,
                'summary' => $guide->summary,
                'meta' => null,
            ]);
        })->implode('');

        $body = $this->view->render('home', [
            'tagline' => $this->config->tagline(),
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
            'aboutUrl' => $this->config->publicUrl('about/'),
            'methodsUrl' => $this->config->publicUrl('methods/'),
            'guidesUrl' => $this->config->publicUrl('guides/'),
            'stats' => [
                ['value' => (string) $reviews->count(), 'label' => $reviews->count() === 1 ? 'Review' : 'Reviews', 'href' => $this->config->publicUrl('reviews/')],
                ['value' => (string) $reviews->pluck('brand')->unique()->count(), 'label' => 'Brands', 'href' => $this->config->publicUrl('brands/')],
                ['value' => (string) $dealcoholized, 'label' => 'Dealcoholized', 'href' => $this->config->publicUrl('reviews/').'?production=dealcoholized'],
                ['value' => '0.5%', 'label' => 'ABV ceiling', 'href' => null],
            ],
            'featured' => $featuredReview instanceof Review ? $this->featuredReview($featuredReview) : '',
            'categoryRail' => $this->view->render('partials/category-rail', [
                'label' => 'Browse by category',
                'variant' => 'tiles',
                'items' => $categoryItems,
            ]),
            'processRail' => $this->view->render('partials/category-rail', [
                'label' => 'Browse by production type',
                'items' => $processItems,
            ]),
            'latestCards' => $this->reviewCards($latest, compact: true),
            'ratedCards' => $this->reviewCards($highlyRated, compact: true),
            'methodCards' => $methodCards,
            'guideCards' => $guideCards,
        ]);

        return $this->document(
            $this->config->name(),
            $this->config->string('site.description'),
            '',
            $body,
            [
                'nav' => 'home',
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
    ): string {
        $empty = '<p class="empty" data-archive-empty>No published reviews in this section yet. Products can sit in the queue until the facts are good enough to print.</p>';
        $list = $reviews->isEmpty()
            ? $empty
            : '<div class="card-grid" data-review-grid>'.$this->reviewCards($reviews, compact: true).'</div>'
                .'<p class="empty" data-archive-empty hidden>No reviews match those filters.</p>';

        $archive = $filterable && $reviews->isNotEmpty()
            ? $this->archiveLayout($reviews, $lockedCategory, $list)
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

        $body = $this->view->render('listing', [
            'title' => $title,
            'description' => $description,
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'archive' => $archive,
            'categoryRail' => $this->view->render('partials/category-rail', [
                'label' => 'Review categories',
                'items' => $categoryItems,
            ]),
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
            'related' => $related,
            'relatedHeading' => $relatedHeading,
        ]);

        return $this->document($page->title, $description, $path, $body, [
            'nav' => $nav,
            'og_type' => 'article',
            'json_ld' => $this->jsonLd([
                $this->breadcrumbGraph($crumbs),
                [
                    '@type' => 'Article',
                    'headline' => $page->title,
                    'description' => $description,
                    'url' => $this->config->canonicalUrl($path),
                    'author' => [
                        '@type' => 'Organization',
                        'name' => $this->config->name(),
                    ],
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

        $body = $this->view->render('brand', [
            'name' => $name,
            'description' => $description,
            'meta' => $meta,
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
    public function brandIndex(Collection $brands): string
    {
        $groups = $brands
            ->groupBy(fn (array $brand): string => mb_strtoupper(mb_substr($brand['name'], 0, 1)))
            ->sortKeys();

        $letters = $groups->keys()->all();
        $letterNav = $letters === []
            ? ''
            : '<nav class="letter-nav" aria-label="Brands by letter">'.implode('', array_map(
                fn (string $letter): string => '<a href="#letter-'.$this->e($letter).'">'.$this->e($letter).'</a>',
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

                return $this->view->render('partials/directory-row', [
                    'href' => $this->config->publicUrl('brands/'.$brand['slug'].'/'),
                    'title' => $brand['name'],
                    'summary' => '',
                    'meta' => ($count === 1 ? '1 review' : $count.' reviews').($categories !== '' ? ' · '.$categories : ''),
                    'search' => mb_strtolower($brand['name'].' '.$categories),
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
        );
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     * @param  Collection<int, Review>|null  $relatedReviews
     */
    public function review(Review $review, array $crumbs, ?Collection $relatedReviews = null): string
    {
        $badgeClass = 'badge badge--'.Str::e($review->productionType);
        $bodyHtml = Markdown::toHtml($review->bodyMarkdown);
        $related = $relatedReviews?->isNotEmpty()
            ? $this->reviewCards($relatedReviews, compact: true)
            : '';

        $body = $this->view->render('review', [
            'title' => $review->title,
            'summary' => $review->summary,
            'categoryLabel' => $this->config->categoryLabel($review->category),
            'breadcrumbs' => $this->breadcrumbs($crumbs),
            'figure' => $this->productFigure($review, 'product-figure product-figure--hero', hero: true),
            'metaLine' => $this->reviewMetaLine($review),
            'badgeClass' => $badgeClass,
            'badgeLabel' => $review->productionTypeShortLabel(),
            'score' => $review->rating !== null
                ? '<p class="score" aria-label="Score '.$review->rating.' out of 100"><span>'.$review->rating.'</span><small>/100</small></p>'
                : '',
            'statusLabel' => $review->productionTypeLabel(),
            'verifiedLabel' => $review->verifiedLabel(),
            'methodBlock' => $this->methodBlock($review),
            'baseBlock' => $this->optionalBlock($review->baseBeverage, 'Base beverage: '),
            'discrepancies' => $this->discrepancies($review),
            'overview' => $bodyHtml !== '' ? '<section class="prose"><h2>Product overview</h2>'.$bodyHtml.'</section>' : '',
            'tasting' => $this->tasting($review),
            'serveBlock' => $this->optionalBlock($review->serve),
            'bestForBlock' => $this->optionalBlock($review->bestFor, 'Best for: '),
            'verdict' => $review->verdict,
            'sources' => $this->sources($review),
            'facts' => $this->facts($review),
            'links' => $this->purchaseLinks($review),
            'related' => $related,
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
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
                    $this->breadcrumbGraph($crumbs),
                    $this->articleGraph($review),
                    $this->productGraph($review),
                ]),
            ],
        );
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
    public function sitemap(Collection $reviews, Collection $guides, Collection $methods, Collection $brands): string
    {
        $urls = [
            $this->sitemapUrl('', 'weekly', '1.0'),
            $this->sitemapUrl('reviews/', 'weekly', '0.8'),
            $this->sitemapUrl('guides/', 'monthly', '0.6'),
            $this->sitemapUrl('brands/', 'weekly', '0.6'),
            $this->sitemapUrl('methods/', 'monthly', '0.6'),
            $this->sitemapUrl('about/', 'monthly', '0.5'),
        ];

        foreach ($this->config->categories() as $category) {
            $urls[] = $this->sitemapUrl('reviews/'.$category.'/', 'weekly', '0.7');
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
    <author><name>{$this->config->name()}</name></author>
    <category term="{$review->category}"/>
    <summary>{$summary}</summary>
  </entry>
XML;
        })->implode("\n");

        $home = $this->config->canonicalUrl();
        $feed = $this->config->canonicalUrl('feed.xml');
        $name = Str::xml($this->config->name());

        return <<<XML
<?xml version="1.0" encoding="utf-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title>{$name}</title>
  <subtitle>{$this->xml($this->config->string('site.description'))}</subtitle>
  <link rel="alternate" type="text/html" href="{$home}"/>
  <link rel="self" type="application/atom+xml" href="{$feed}"/>
  <id>{$feed}</id>
  <updated>{$updated}</updated>
  <author><name>{$name}</name></author>
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
            'guides' => ['Guides', 'guides/'],
            'brands' => ['Brands', 'brands/'],
            'methods' => ['Methods', 'methods/'],
            'about' => ['About', 'about/'],
        ];

        $items = '';
        foreach ($links as $key => [$label, $path]) {
            $currentAttr = $current === $key ? ' aria-current="page"' : '';
            $items .= '<a href="'.$this->url($path).'"'.$currentAttr.'>'.Str::e($label).'</a>';
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
            'feedUrl' => $this->config->publicUrl('feed.xml'),
            'categories' => $categories,
        ]);
    }

    /**
     * @param  Collection<int, Review>  $reviews
     */
    private function archiveLayout(Collection $reviews, ?string $lockedCategory, string $list): string
    {
        $locked = $lockedCategory !== null ? ' data-locked-category="'.Str::e($lockedCategory).'"' : '';

        $brandOptions = $reviews
            ->map(fn (Review $review): array => [
                'value' => $review->brandSlug(),
                'label' => $review->brand,
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
                    $categoryOptions[] = ['value' => $category, 'label' => $this->config->categoryLabel($category)];
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
            'unknown' => 'Unknown',
        ];
        $methodOptions = [];
        foreach (array_keys($methodLabels) as $method) {
            if ($reviews->contains(fn (Review $review): bool => $review->methodFacetKey() === $method)) {
                $methodOptions[] = ['value' => $method, 'label' => $methodLabels[$method]];
            }
        }

        $facets = $this->facetGroup('ABV', 'abv', $abvOptions)
            .$this->facetGroup('Brand', 'brand', $brandOptions, searchable: true, collapsible: true, collapsed: count($brandOptions) > 8)
            .($categoryOptions === [] ? '' : $this->facetGroup('Category', 'category', $categoryOptions))
            .$this->facetGroup('Production type', 'production', $processOptions)
            .$this->facetGroup('Method', 'method', $methodOptions);

        return $this->view->render('partials/archive', [
            'locked' => $locked,
            'facets' => $facets,
            'list' => $list,
        ]);
    }

    /**
     * @param  array<int, array{value: string, label: string}>  $options
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
            $items .= '<label class="facet-option" data-facet-label="'.Str::e(mb_strtolower($option['label'])).'">'
                .'<input id="'.$id.'" type="checkbox" name="'.Str::e($name).'[]" value="'.Str::e($option['value']).'" data-archive-'.Str::e($name).'>'
                .'<span>'.Str::e($option['label']).'</span>'
                .'<span class="facet-count" data-facet-count></span>'
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
        $badge = '<span class="badge badge--'.Str::e($review->productionType).'">'.Str::e($review->productionTypeShortLabel()).'</span>';

        return $this->view->render('partials/featured-review', [
            'href' => $this->config->publicUrl($review->path()),
            'figure' => $this->productFigure($review, 'product-figure product-figure--feature', hero: true),
            'brand' => '<a href="'.$this->url('brands/'.$review->brandSlug().'/').'">'.$this->e($review->brand).'</a>',
            'title' => $review->title,
            'summary' => $review->summary,
            'score' => $review->rating !== null ? '<span class="card-score">'.$review->rating.'</span>' : '',
            'badge' => $badge,
        ]);
    }

    private function reviewCards(Collection $reviews, bool $compact = false): string
    {
        return $reviews->map(function (Review $review) use ($compact): string {
            $score = $review->rating !== null ? '<span class="card-score">'.$review->rating.'</span>' : '';
            $origin = $review->originLabel();
            $meta = trim($this->config->categoryLabel($review->category).($origin ? ' · '.$origin : ''));
            $method = $review->dealcoholizationMethod ?? 'Method unknown';
            $badge = '<span class="badge badge--'.Str::e($review->productionType).'">'.Str::e($review->productionTypeShortLabel()).'</span>';
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
            $brand = '<a href="'.$this->url('brands/'.$review->brandSlug().'/').'">'.$this->e($review->brand).'</a>';

            return $this->view->render('partials/review-card', [
                'compact' => $compact,
                'attrs' => $attrs,
                'thumb' => $thumb,
                'brand' => $brand,
                'href' => $this->config->publicUrl($review->path()),
                'title' => $review->title,
                'summary' => $review->summary,
                'meta' => $meta.($compact ? ' · '.$method : ''),
                'badge' => $badge,
                'score' => $score,
            ]);
        })->implode('');
    }

    private function reviewMetaLine(Review $review): string
    {
        $parts = [
            '<a href="'.$this->url('brands/'.$review->brandSlug().'/').'">'.$this->e($review->brand).'</a>',
            '<a href="'.$this->url('reviews/'.$review->category.'/').'">'.$this->e($this->config->categoryLabel($review->category)).'</a>',
        ];

        $methodKey = $review->methodKey();
        if ($methodKey !== null && in_array($methodKey, Review::METHOD_FACETS, true)) {
            $label = $review->dealcoholizationMethod ?? $this->config->methodLabel($methodKey);
            $parts[] = '<a href="'.$this->url('methods/'.$methodKey.'/').'">'.$this->e($label).'</a>';
        } elseif ($review->dealcoholizationMethod) {
            $parts[] = $this->e($review->dealcoholizationMethod);
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
            'Production type' => $review->productionTypeShortLabel(),
            'Verified' => $review->verifiedLabel(),
            'Origin' => $review->originLabel(),
            'Category' => $this->config->categoryLabel($review->category).($review->subcategory ? ' / '.$review->subcategory : ''),
            'Style' => $review->style,
            'Producer' => $review->producer,
            'Production method' => $review->dealcoholizationMethod,
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

        if ($notes === []) {
            return '';
        }

        return $this->view->render('partials/tasting', ['notes' => $notes]);
    }

    private function sources(Review $review): string
    {
        if ($review->sources === []) {
            return '';
        }

        $items = '';
        foreach ($review->sources as $source) {
            $claims = $source['claims'] === [] ? '' : '<span class="source-claims">'.Str::e(implode(', ', $source['claims'])).'</span>';
            $items .= '<li><a href="'.Str::e($source['url']).'" rel="nofollow noopener">'.Str::e($source['title']).'</a>'.$claims.'</li>';
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
            $items .= '<li><a href="'.Str::e($link['url']).'" rel="nofollow noopener">'.Str::e($link['label']).'</a></li>';
        }

        return $this->view->render('partials/purchase-links', [
            'availability' => $review->availability ? '<p>'.Str::e($review->availability).'</p>' : '',
            'items' => $items,
        ]);
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
        $payload = [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];

        return '<script type="application/ld+json">'.json_encode(
            $payload,
            JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR,
        ).'</script>';
    }

    /**
     * @return array<string, mixed>
     */
    private function websiteGraph(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => $this->config->canonicalUrl().'#website',
            'name' => $this->config->name(),
            'url' => $this->config->canonicalUrl(),
            'description' => $this->config->string('site.description'),
        ];
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     * @return array<string, mixed>
     */
    private function breadcrumbGraph(array $crumbs): array
    {
        $items = [];

        foreach (array_values($crumbs) as $index => $crumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $crumb['label'],
                'item' => $this->config->canonicalUrl(ltrim($crumb['url'] ?? '', '/')),
            ];
        }

        return [
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function articleGraph(Review $review): array
    {
        return array_filter([
            '@type' => 'Review',
            'headline' => $review->title,
            'name' => $review->title,
            'description' => $review->summary,
            'url' => $this->config->canonicalUrl($review->path()),
            'datePublished' => $review->reviewDate->toDateString(),
            'dateModified' => $review->modifiedAt()->toDateString(),
            'author' => [
                '@type' => 'Organization',
                'name' => $this->config->name(),
            ],
            'reviewRating' => $review->rating === null ? null : [
                '@type' => 'Rating',
                'ratingValue' => $review->rating,
                'bestRating' => 100,
                'worstRating' => 0,
            ],
            'itemReviewed' => array_filter([
                '@type' => 'Product',
                'name' => $review->product,
                'brand' => [
                    '@type' => 'Brand',
                    'name' => $review->brand,
                ],
                'image' => $review->imageSrc() ? $this->config->canonicalUrl($review->imageSrc()) : null,
            ]),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function productGraph(Review $review): array
    {
        return array_filter([
            '@type' => 'Product',
            'name' => $review->title,
            'brand' => [
                '@type' => 'Brand',
                'name' => $review->brand,
            ],
            'category' => $this->config->categoryLabel($review->category),
            'description' => $review->summary,
            'image' => $review->imageSrc() ? $this->config->canonicalUrl($review->imageSrc()) : null,
            'alcoholWarning' => $review->abv,
        ], fn (mixed $value): bool => $value !== null && $value !== '');
    }

    private function productFigure(Review $review, string $class, bool $hero = false): string
    {
        $src = $review->imageSrc();

        return $this->view->render('partials/product-figure', [
            'class' => $class,
            'src' => $src === null ? '' : $this->config->publicUrl($src),
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

    private function navKey(string $path): string
    {
        $path = trim($path, '/');

        if ($path === '' || $path === 'home') {
            return 'home';
        }

        return explode('/', $path)[0];
    }
}
