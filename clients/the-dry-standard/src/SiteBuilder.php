<?php

namespace DryStandard;

final class SiteBuilder
{
    public function __construct(
        private readonly Paths $paths,
        private readonly SiteConfig $config,
        private readonly ReviewRepository $reviews,
        private readonly ReviewValidator $validator,
    ) {}

    /**
     * @return array{pages: int, errors: array<int, string>}
     */
    public function build(): array
    {
        $published = $this->reviews->published();
        $errors = [];

        foreach ($published as $review) {
            $reviewErrors = $this->validator->errors($review, $this->config, forPublish: true);
            foreach ($reviewErrors as $error) {
                $errors[] = $review->slug.': '.$error;
            }
        }

        if ($errors !== []) {
            return ['pages' => 0, 'errors' => $errors];
        }

        $guides = PageDocument::loadDirectory($this->paths->content('guides'));
        $methods = PageDocument::loadDirectory($this->paths->content('methods'));
        $about = PageDocument::load($this->paths->content('pages/about.md'));
        $brands = $this->reviews->brands();
        $renderer = new Renderer($this->config);
        $pages = 0;

        $pages += $this->write('index.html', $renderer->home($published, $guides, $methods));
        $pages += $this->write(
            'reviews/index.html',
            $renderer->listing(
                'All reviews',
                'Independent tasting notes and production facts for dealcoholized and non-alcoholic drinks at 0.5% ABV or less.',
                'reviews/',
                $published,
                $renderer->crumbs(['Reviews' => 'reviews/']),
                filterable: true,
                allReviews: $published,
            ),
        );

        foreach ($this->config->categories() as $category) {
            $label = $this->config->categoryLabel($category);
            $pages += $this->write(
                'reviews/'.$category.'/index.html',
                $renderer->listing(
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
                ),
            );
        }

        foreach ($published as $review) {
            $pages += $this->write(
                $review->path().'index.html',
                $renderer->review(
                    $review,
                    $renderer->crumbs([
                        'Reviews' => 'reviews/',
                        $this->config->categoryLabel($review->category) => 'reviews/'.$review->category.'/',
                        $review->title => $review->path(),
                    ]),
                    $this->reviews->relatedTo($review),
                ),
            );
        }

        $pages += $this->write(
            'brands/index.html',
            $renderer->brandIndex($brands),
        );

        foreach ($brands as $brand) {
            $pages += $this->write(
                'brands/'.$brand['slug'].'/index.html',
                $renderer->brandPage(
                    $brand['name'],
                    $brand['reviews'],
                    'brands/'.$brand['slug'].'/',
                    $renderer->crumbs([
                        'Brands' => 'brands/',
                        $brand['name'] => 'brands/'.$brand['slug'].'/',
                    ]),
                ),
            );
        }

        $pages += $this->write(
            'guides/index.html',
            $renderer->documentIndex(
                'Guides',
                'Buying guides and the editorial distinctions that keep this site from becoming another generic NA roundup.',
                'guides/',
                'guides',
                $guides,
            ),
        );

        foreach ($guides as $guide) {
            $pages += $this->write(
                'guides/'.$guide->slug.'/index.html',
                $renderer->articlePage(
                    $guide,
                    'guides/'.$guide->slug.'/',
                    $renderer->crumbs([
                        'Guides' => 'guides/',
                        $guide->title => 'guides/'.$guide->slug.'/',
                    ]),
                    'Guide',
                    'guides',
                ),
            );
        }

        $methodCounts = [];
        foreach ($methods as $method) {
            $methodCounts[$method->slug] = $this->reviews->byMethod($method->slug)->count();
        }

        $pages += $this->write(
            'methods/index.html',
            $renderer->documentIndex(
                'Dealcoholization methods',
                'How alcohol is removed — and which common NA techniques are not dealcoholization at all.',
                'methods/',
                'methods',
                $methods,
                $methodCounts,
            ),
        );

        foreach ($methods as $method) {
            $pages += $this->write(
                'methods/'.$method->slug.'/index.html',
                $renderer->articlePage(
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
                ),
            );
        }

        $pages += $this->write(
            'about/index.html',
            $renderer->articlePage(
                $about,
                'about/',
                $renderer->crumbs(['About' => 'about/']),
                'About',
                'about',
            ),
        );

        $this->write('sitemap.xml', $renderer->sitemap($published, $guides, $methods, $brands));
        $this->write('feed.xml', $renderer->feed($published));
        $this->write(
            'catalog.json',
            json_encode([
                'site' => $this->config->name(),
                'generated_at' => now()->toIso8601String(),
                'reviews' => $published->map(fn (Review $review): array => $review->catalogRecord())->values(),
                'facets' => [
                    'categories' => $published->pluck('category')->unique()->values(),
                    'brands' => $published->pluck('brand')->unique()->sort()->values(),
                    'abv' => $published->map(fn (Review $review): string => $review->abvBucket())->unique()->values(),
                    'dealcoholized' => $published->pluck('dealcoholized')->unique()->values(),
                    'methods' => $published->map(fn (Review $review): string => $review->methodFacetKey())->unique()->values(),
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n",
        );

        return ['pages' => $pages, 'errors' => []];
    }

    private function write(string $relative, string $contents): int
    {
        $file = $this->paths->path($relative);
        $directory = dirname($file);

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        file_put_contents($file, $contents);

        return 1;
    }
}
