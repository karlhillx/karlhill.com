<?php

namespace DryStandard;

final class Site
{
    public function __construct(
        private readonly Paths $paths,
        private readonly SiteConfig $config,
        private readonly ReviewRepository $reviews,
    ) {}

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, mixed>  $form
     */
    public function html(string $path, array $query = [], array $form = []): ?string
    {
        $path = trim($path, '/');
        $published = $this->reviews->listing();
        $guides = PageDocument::loadDirectory($this->paths->content('guides'));
        $methods = PageDocument::loadDirectory($this->paths->content('methods'));
        $renderer = $this->renderer();
        $publishOrder = $this->reviews->publishOrder();

        if ($path === '') {
            return $renderer->home($published, $guides, $methods);
        }

        if ($path === 'reviews') {
            return $renderer->listing(
                'All reviews',
                'Independent tasting notes and production facts for dealcoholized and non-alcoholic drinks at 0.5% ABV or less.',
                'reviews/',
                $published,
                $renderer->crumbs(['Reviews' => 'reviews/']),
                filterable: true,
                allReviews: $published,
                query: ArchiveQuery::from($query),
                publishOrder: $publishOrder,
                fragment: (string) ($query['fragment'] ?? '') === 'archive',
                filtered: $this->reviews->archive(ArchiveQuery::from($query)),
            );
        }

        if (preg_match('#^reviews/(wine|beer|spirits|cocktails|cider)$#', $path, $matches) === 1) {
            $category = $matches[1];
            $label = $this->config->categoryLabel($category);

            return $renderer->listing(
                $label.' reviews',
                'Dealcoholized and non-alcoholic '.$label.' reviewed for what they are, not what the label implies.',
                'reviews/'.$category.'/',
                $published->filter(fn (Review $review): bool => $review->category === $category)->values(),
                $renderer->crumbs([
                    'Reviews' => 'reviews/',
                    $label => 'reviews/'.$category.'/',
                ]),
                filterable: true,
                lockedCategory: $category,
                allReviews: $published,
                query: ArchiveQuery::from($query, $category),
                publishOrder: $publishOrder,
                fragment: (string) ($query['fragment'] ?? '') === 'archive',
                filtered: $this->reviews->archive(ArchiveQuery::from($query, $category)),
            );
        }

        if (preg_match('#^reviews/(wine|beer|spirits|cocktails|cider)/([^/]+)$#', $path, $matches) === 1) {
            $review = $this->reviews->find($matches[2]);

            if ($review === null || ! $review->isPublic() || $review->category !== $matches[1]) {
                return null;
            }

            return $renderer->review(
                $review,
                $renderer->crumbs([
                    'Reviews' => 'reviews/',
                    $this->config->categoryLabel($review->category) => 'reviews/'.$review->category.'/',
                    $review->title => $review->path(),
                ]),
                $this->reviews->relatedTo($review),
            );
        }

        if ($path === 'brands') {
            return $renderer->brandIndex($this->reviews->brands(), (string) ($query['q'] ?? ''));
        }

        if (preg_match('#^brands/([^/]+)$#', $path, $matches) === 1) {
            $brand = $this->reviews->brands()->first(fn (array $item): bool => $item['slug'] === $matches[1]);

            if ($brand === null) {
                return null;
            }

            return $renderer->brandPage(
                $brand['name'],
                $brand['reviews'],
                'brands/'.$brand['slug'].'/',
                $renderer->crumbs([
                    'Brands' => 'brands/',
                    $brand['name'] => 'brands/'.$brand['slug'].'/',
                ]),
            );
        }

        if ($path === 'guides' || $path === 'learn') {
            $methodCounts = [];
            foreach ($methods as $method) {
                $methodCounts[$method->slug] = $this->reviews->byMethod($method->slug)->count();
            }

            return $renderer->learnHub($guides, $methods, $methodCounts);
        }

        if (preg_match('#^(?:guides|learn)/([^/]+)$#', $path, $matches) === 1) {
            $guide = $guides->first(fn (PageDocument $document): bool => $document->slug === $matches[1]);

            if ($guide === null) {
                return null;
            }

            return $renderer->articlePage(
                $guide,
                'learn/'.$guide->slug.'/',
                $renderer->crumbs([
                    'Learn' => 'learn/',
                    $guide->title => 'learn/'.$guide->slug.'/',
                ]),
                'Guide',
                'guides',
            );
        }

        if ($path === 'methods') {
            $methodCounts = [];
            foreach ($methods as $method) {
                $methodCounts[$method->slug] = $this->reviews->byMethod($method->slug)->count();
            }

            return $renderer->documentIndex(
                'Dealcoholization methods',
                'How alcohol is removed — and which common NA techniques are not dealcoholization at all.',
                'methods/',
                'methods',
                $methods,
                $methodCounts,
                query: (string) ($query['q'] ?? ''),
            );
        }

        if (preg_match('#^methods/([^/]+)$#', $path, $matches) === 1) {
            $method = $methods->first(fn (PageDocument $document): bool => $document->slug === $matches[1]);

            if ($method === null) {
                return null;
            }

            $methodReviews = $this->reviews->byMethod($method->slug);
            $methodCount = $methodReviews->count();
            $methodMeta = $methodCount === 0
                ? ''
                : '<p class="page-meta">'.($methodCount === 1 ? '1 bottle' : $methodCount.' bottles').' reviewed with this method. Other methods are listed below.</p>';

            return $renderer->articlePage(
                $method,
                'methods/'.$method->slug.'/',
                $renderer->crumbs([
                    'Methods' => 'methods/',
                    $method->title => 'methods/'.$method->slug.'/',
                ]),
                'Method',
                'methods',
                $methodReviews,
                'Reviewed with this method',
                afterProse: $methodMeta,
                siblings: $renderer->methodSiblings($methods, $method->slug),
            );
        }

        if ($path === 'about') {
            return $renderer->articlePage(
                PageDocument::load($this->paths->content('pages/about.md')),
                'about/',
                $renderer->crumbs(['About' => 'about/']),
                'About',
                'about',
                afterProse: $renderer->scoreHistogram($published).$renderer->industryNote(),
            );
        }

        if ($path === 'methodology') {
            return $renderer->articlePage(
                PageDocument::load($this->paths->content('pages/methodology.md')),
                'methodology/',
                $renderer->crumbs([
                    'About' => 'about/',
                    'Methodology' => 'methodology/',
                ]),
                'Methodology',
                'methodology',
                afterProse: $renderer->scoreHistogram($published),
            );
        }

        if ($path === 'collections') {
            return $renderer->collectionsIndex(Collections::available($this->reviews, $this->config));
        }

        if (preg_match('#^collections/([a-z0-9-]+)$#', $path, $matches) === 1) {
            $available = Collections::available($this->reviews, $this->config);
            $item = collect($available)->first(fn (array $row): bool => $row['slug'] === $matches[1]);
            if ($item === null) {
                return null;
            }

            return $renderer->listing(
                $item['title'],
                $item['lede'],
                'collections/'.$item['slug'].'/',
                $item['reviews'],
                $renderer->crumbs([
                    'Collections' => 'collections/',
                    $item['title'] => 'collections/'.$item['slug'].'/',
                ]),
                nav: 'collections',
                filterable: empty($item['min_score']),
                allReviews: $published,
                query: $item['query'],
                publishOrder: $publishOrder,
                fragment: (string) ($query['fragment'] ?? '') === 'archive',
                filtered: $item['reviews'],
            );
        }

        if ($path === 'privacy') {
            return $renderer->articlePage(
                PageDocument::load($this->paths->content('pages/privacy.md')),
                'privacy/',
                $renderer->crumbs(['Privacy' => 'privacy/']),
                'Legal',
                'privacy',
            );
        }

        if ($path === 'industry') {
            return $renderer->industryHome();
        }

        if ($path === 'industry/samples') {
            return $renderer->industrySamples();
        }

        if ($path === 'industry/submit') {
            return $renderer->industrySubmit($this->formState($query, $form));
        }

        if ($path === 'industry/partnerships') {
            return $renderer->industryPartnerships($this->formState($query, $form));
        }

        if ($path === 'styles') {
            return $renderer->styleIndex($this->reviews->styles(), (string) ($query['q'] ?? ''));
        }

        if (preg_match('#^styles/([a-z0-9-]+)$#', $path, $matches) === 1) {
            $style = $this->reviews->styles()->first(
                fn (array $item): bool => $item['slug'] === $matches[1],
            );

            if ($style === null) {
                return null;
            }

            $archiveQuery = ArchiveQuery::from($query, lockedStyle: $style['slug']);

            return $renderer->listing(
                $style['label'],
                'Published reviews in the '.$style['label'].' style.',
                'styles/'.$style['slug'].'/',
                $style['reviews'],
                $renderer->crumbs([
                    'Styles' => 'styles/',
                    $style['label'] => 'styles/'.$style['slug'].'/',
                ]),
                nav: 'styles',
                filterable: true,
                allReviews: $published,
                query: $archiveQuery,
                publishOrder: $publishOrder,
                fragment: (string) ($query['fragment'] ?? '') === 'archive',
                filtered: $this->reviews->archive($archiveQuery),
            );
        }

        if ($path === 'best') {
            return $renderer->bestIndex($published);
        }

        if ($path === 'compare') {
            return $renderer->compare($published, $query);
        }

        if (preg_match('#^best/(wine|beer|spirits|cocktails|cider)$#', $path, $matches) === 1) {
            $category = $matches[1];
            $label = $this->config->categoryLabel($category);
            $query = ArchiveQuery::from(array_merge(['sort' => 'rating'], $query), $category);

            return $renderer->listing(
                'Best '.$label,
                'Highest-rated '.$label.' we have tasted, sorted by score.',
                'best/'.$category.'/',
                $this->reviews->byCategory($category)->filter(fn (Review $review): bool => ($review->rating ?? 0) >= 85)->values(),
                $renderer->crumbs([
                    'Best of' => 'best/',
                    $label => 'best/'.$category.'/',
                ]),
                filterable: true,
                lockedCategory: $category,
                allReviews: $published,
                query: $query,
                publishOrder: $publishOrder,
                fragment: (string) ($query['fragment'] ?? '') === 'archive',
                filtered: $this->reviews->archive($query)->filter(
                    fn (Review $review): bool => ($review->rating ?? 0) >= 80,
                )->values(),
            );
        }

        return null;
    }

    public function redirect(string $path): ?string
    {
        $path = trim($path, '/');

        if ($path === 'guides') {
            return 'learn/';
        }

        if (preg_match('#^guides/([^/]+)$#', $path, $matches) === 1) {
            return 'learn/'.$matches[1].'/';
        }

        if (preg_match('#^brands/([^/]+)$#', $path, $matches) !== 1) {
            return null;
        }

        $canonical = Taxonomy::canonicalBrandSlug($matches[1]);
        if ($canonical === null || $canonical === $matches[1]) {
            return null;
        }

        $exists = $this->reviews->brands()->contains(
            fn (array $item): bool => $item['slug'] === $canonical,
        );

        return $exists ? 'brands/'.$canonical.'/' : null;
    }

    public function notFound(): string
    {
        return $this->renderer()->notFound();
    }

    public function robots(): string
    {
        if (! $this->config->indexable()) {
            return "User-agent: *\nDisallow: /\n";
        }

        return "User-agent: *\nAllow: /\n\nSitemap: ".$this->config->canonicalUrl('sitemap.xml')."\n";
    }

    public function feed(): string
    {
        return $this->renderer()->feed($this->reviews->published());
    }

    public function sitemap(): string
    {
        $renderer = $this->renderer();

        return $renderer->sitemap(
            $this->reviews->published(),
            PageDocument::loadDirectory($this->paths->content('guides')),
            PageDocument::loadDirectory($this->paths->content('methods')),
            $this->reviews->brands(),
            $this->reviews->styles(),
        );
    }

    private function renderer(): Renderer
    {
        return new Renderer($this->config, reviews: $this->reviews);
    }

    public function catalogJson(): string
    {
        $published = $this->reviews->listing();

        return json_encode([
            'version' => 1,
            'site' => $this->config->name(),
            'generated_at' => now()->toIso8601String(),
            'reviews' => $published->map(fn (Review $review): array => $review->catalogRecord())->values(),
            'facets' => [
                'categories' => $published->pluck('category')->unique()->values(),
                'brands' => $published->pluck('brand')->unique()->sort()->values(),
                'abv' => $published->map(fn (Review $review): string => $review->abvBucket())->unique()->values(),
                'production_type' => $published->pluck('productionType')->unique()->values(),
                'verified' => $published->pluck('verified')->unique()->values(),
                'methods' => $published->map(fn (Review $review): string => $review->methodFacetKey())->unique()->values(),
                'styles' => $published->map(fn (Review $review): string => $review->styleSlug())->unique()->values(),
                'wine_color' => $published->map(fn (Review $review): ?string => $review->wineColor())->filter()->unique()->values(),
                'sweetness' => $published->map(fn (Review $review): ?int => $review->structureScaleInt('sweetness'))->filter(fn ($v) => $v !== null)->unique()->values(),
                'body' => $published->map(fn (Review $review): ?int => $review->structureScaleInt('body'))->filter(fn ($v) => $v !== null)->unique()->values(),
                'score' => $published->map(fn (Review $review): string => $review->scoreBand())->filter()->unique()->values(),
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
    }

    /**
     * @param  array<string, mixed>  $query
     * @param  array<string, mixed>  $form
     * @return array{csrf: string, errors: array<string, string>, old: array<string, mixed>, sent: bool, failed: bool}
     */
    private function formState(array $query, array $form): array
    {
        $errors = $form['errors'] ?? [];
        if (! is_array($errors)) {
            $errors = [];
        }

        $flat = [];
        foreach ($errors as $field => $messages) {
            if (is_array($messages)) {
                $flat[(string) $field] = (string) ($messages[0] ?? '');
            } else {
                $flat[(string) $field] = (string) $messages;
            }
        }

        $old = $form['old'] ?? [];
        if (! is_array($old)) {
            $old = [];
        }

        return [
            'csrf' => (string) ($form['csrf'] ?? ''),
            'errors' => $flat,
            'old' => $old,
            'sent' => (bool) ($form['sent'] ?? ((string) ($query['sent'] ?? '') === '1')),
            'failed' => (bool) ($form['failed'] ?? false),
        ];
    }
}
