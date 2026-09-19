<?php

namespace DryStandard;

final class Site
{
    public function __construct(
        private readonly Paths $paths,
        private readonly SiteConfig $config,
        private readonly ReviewRepository $reviews,
    ) {}

    public function html(string $path): ?string
    {
        $path = trim($path, '/');
        $published = $this->reviews->published();
        $guides = PageDocument::loadDirectory($this->paths->content('guides'));
        $methods = PageDocument::loadDirectory($this->paths->content('methods'));
        $renderer = new Renderer($this->config);

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
            );
        }

        if (preg_match('#^reviews/(wine|beer|spirits|cocktails|cider)$#', $path, $matches) === 1) {
            $category = $matches[1];
            $label = $this->config->categoryLabel($category);

            return $renderer->listing(
                $label.' reviews',
                'Dealcoholized and non-alcoholic '.$label.' reviewed for what they are, not what the label implies.',
                'reviews/'.$category.'/',
                $this->reviews->byCategory($category),
                $renderer->crumbs([
                    'Reviews' => 'reviews/',
                    $label => 'reviews/'.$category.'/',
                ]),
                filterable: true,
                lockedCategory: $category,
                allReviews: $published,
            );
        }

        if (preg_match('#^reviews/(wine|beer|spirits|cocktails|cider)/([^/]+)$#', $path, $matches) === 1) {
            $review = $this->reviews->find($matches[2]);

            if ($review === null || ! $review->isPublished() || $review->category !== $matches[1]) {
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
            return $renderer->brandIndex($this->reviews->brands());
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

        if ($path === 'guides') {
            return $renderer->documentIndex(
                'Guides',
                'Buying guides and the editorial distinctions that keep this site from becoming another generic NA roundup.',
                'guides/',
                'guides',
                $guides,
            );
        }

        if (preg_match('#^guides/([^/]+)$#', $path, $matches) === 1) {
            $guide = $guides->first(fn (PageDocument $document): bool => $document->slug === $matches[1]);

            if ($guide === null) {
                return null;
            }

            return $renderer->articlePage(
                $guide,
                'guides/'.$guide->slug.'/',
                $renderer->crumbs([
                    'Guides' => 'guides/',
                    $guide->title => 'guides/'.$guide->slug.'/',
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
            );
        }

        if (preg_match('#^methods/([^/]+)$#', $path, $matches) === 1) {
            $method = $methods->first(fn (PageDocument $document): bool => $document->slug === $matches[1]);

            if ($method === null) {
                return null;
            }

            return $renderer->articlePage(
                $method,
                'methods/'.$method->slug.'/',
                $renderer->crumbs([
                    'Methods' => 'methods/',
                    $method->title => 'methods/'.$method->slug.'/',
                ]),
                'Method',
                'methods',
                $this->reviews->byMethod($method->slug),
                'Reviewed with this method',
            );
        }

        if ($path === 'about') {
            return $renderer->articlePage(
                PageDocument::load($this->paths->content('pages/about.md')),
                'about/',
                $renderer->crumbs(['About' => 'about/']),
                'About',
                'about',
            );
        }

        return null;
    }

    public function feed(): string
    {
        return (new Renderer($this->config))->feed($this->reviews->published());
    }

    public function sitemap(): string
    {
        $renderer = new Renderer($this->config);

        return $renderer->sitemap(
            $this->reviews->published(),
            PageDocument::loadDirectory($this->paths->content('guides')),
            PageDocument::loadDirectory($this->paths->content('methods')),
            $this->reviews->brands(),
        );
    }

    public function catalogJson(): string
    {
        $published = $this->reviews->published();

        return json_encode([
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
            ],
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n";
    }
}
