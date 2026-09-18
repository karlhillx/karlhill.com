<?php

namespace DryStandard;

use Illuminate\Support\Collection;

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
                $this->crumbs(['Reviews' => 'reviews/']),
                filterable: true,
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
                    $this->crumbs([
                        'Reviews' => 'reviews/',
                        $label => 'reviews/'.$category.'/',
                    ]),
                    filterable: true,
                    lockedCategory: $category,
                ),
            );
        }

        foreach ($published as $review) {
            $pages += $this->write(
                $review->path().'index.html',
                $renderer->review($review, $this->crumbs([
                    'Reviews' => 'reviews/',
                    $this->config->categoryLabel($review->category) => 'reviews/'.$review->category.'/',
                    $review->title => $review->path(),
                ])),
            );
        }

        $pages += $this->write(
            'brands/index.html',
            $this->brandIndex($renderer, $brands),
        );

        foreach ($brands as $brand) {
            $pages += $this->write(
                'brands/'.$brand['slug'].'/index.html',
                $renderer->brandPage(
                    $brand['name'],
                    $brand['reviews'],
                    'brands/'.$brand['slug'].'/',
                    $this->crumbs([
                        'Brands' => 'brands/',
                        $brand['name'] => 'brands/'.$brand['slug'].'/',
                    ]),
                ),
            );
        }

        $pages += $this->write(
            'guides/index.html',
            $this->documentIndex(
                $renderer,
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
                    $this->crumbs([
                        'Guides' => 'guides/',
                        $guide->title => 'guides/'.$guide->slug.'/',
                    ]),
                    'Guide',
                    'guides',
                ),
            );
        }

        $pages += $this->write(
            'methods/index.html',
            $this->documentIndex(
                $renderer,
                'Dealcoholization methods',
                'How alcohol is removed — and which common NA techniques are not dealcoholization at all.',
                'methods/',
                'methods',
                $methods,
            ),
        );

        foreach ($methods as $method) {
            $pages += $this->write(
                'methods/'.$method->slug.'/index.html',
                $renderer->articlePage(
                    $method,
                    'methods/'.$method->slug.'/',
                    $this->crumbs([
                        'Methods' => 'methods/',
                        $method->title => 'methods/'.$method->slug.'/',
                    ]),
                    'Method',
                    'methods',
                ),
            );
        }

        $pages += $this->write(
            'about/index.html',
            $renderer->articlePage(
                $about,
                'about/',
                $this->crumbs(['About' => 'about/']),
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
                    'dealcoholized' => $published->pluck('dealcoholized')->unique()->values(),
                    'methods' => $published->map(fn (Review $review): ?string => $review->methodKey())->filter()->unique()->values(),
                ],
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR)."\n",
        );

        return ['pages' => $pages, 'errors' => []];
    }

    /**
     * @param  Collection<int, array{slug: string, name: string, reviews: Collection<int, Review>}>  $brands
     */
    private function brandIndex(Renderer $renderer, Collection $brands): string
    {
        $cards = $brands->map(function (array $brand): string {
            $count = $brand['reviews']->count();
            $label = $count === 1 ? '1 review' : $count.' reviews';

            return <<<HTML
      <article class="card">
        <p class="card-meta">{$this->e($label)}</p>
        <h3><a href="{$this->e($this->config->publicUrl('brands/'.$brand['slug'].'/'))}">{$this->e($brand['name'])}</a></h3>
        <p>Published Dry Standard coverage for {$this->e($brand['name'])}.</p>
      </article>
HTML;
        })->implode('');

        $listing = $cards === ''
            ? '<p class="empty">Brand pages appear after the first review is published.</p>'
            : '<div class="card-grid">'.$cards.'</div>';

        $body = <<<HTML
    <header class="page-header">
      <div class="shell">
        <p class="kicker">The Dry Standard</p>
        <h1>Brands</h1>
        <p class="lede">Producers with published reviews. This index is generated from review data, not a separate marketing directory.</p>
      </div>
    </header>
    <section class="section">
      <div class="shell">{$listing}</div>
    </section>
HTML;

        return $renderer->document(
            'Brands',
            'An index of producers reviewed by The Dry Standard.',
            'brands/',
            $body,
            ['nav' => 'brands'],
        );
    }

    /**
     * @param  Collection<int, PageDocument>  $documents
     */
    private function documentIndex(
        Renderer $renderer,
        string $title,
        string $description,
        string $path,
        string $nav,
        Collection $documents,
    ): string {
        $cards = $documents->map(function (PageDocument $document) use ($path): string {
            return <<<HTML
      <article class="card">
        <p class="card-meta">{$this->e(ucfirst(rtrim($path, '/')))}</p>
        <h3><a href="{$this->e($this->config->publicUrl($path.$document->slug.'/'))}">{$this->e($document->title)}</a></h3>
        <p>{$this->e($document->summary)}</p>
      </article>
HTML;
        })->implode('');

        $listing = $cards === ''
            ? '<p class="empty">Nothing published here yet.</p>'
            : '<div class="card-grid">'.$cards.'</div>';

        $body = <<<HTML
    <header class="page-header">
      <div class="shell">
        <p class="kicker">The Dry Standard</p>
        <h1>{$this->e($title)}</h1>
        <p class="lede">{$this->e($description)}</p>
      </div>
    </header>
    <section class="section">
      <div class="shell">{$listing}</div>
    </section>
HTML;

        return $renderer->document($title, $description, $path, $body, ['nav' => $nav]);
    }

    /**
     * @param  array<string, string>  $items
     * @return array<int, array{label: string, url: string}>
     */
    private function crumbs(array $items): array
    {
        $crumbs = [['label' => 'Home', 'url' => '']];

        foreach ($items as $label => $url) {
            $crumbs[] = ['label' => $label, 'url' => $url];
        }

        return $crumbs;
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

    private function e(string $value): string
    {
        return Str::e($value);
    }
}
