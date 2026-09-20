<?php

namespace DryStandard\Rendering\Concerns;

use DryStandard\PageDocument;
use DryStandard\Review;
use DryStandard\Taxonomy;
use Illuminate\Support\Collection;

trait RendersDirectory
{
    public function collectionsIndex(array $collections): string
    {
        $listing = '<div class="directory-list">';
        foreach ($collections as $item) {
            $listing .= $this->view->render('partials/directory-row', [
                'href' => $item['href'],
                'title' => $item['title'],
                'meta' => $item['count'].($item['count'] === 1 ? ' bottle' : ' bottles'),
                'summary' => $item['lede'],
                'search' => mb_strtolower($item['title'].' '.$item['lede']),
            ]);
        }
        $listing .= '</div>';

        return $this->directoryPage(
            'Collections',
            'Facet-backed sets from the cellar — dealcoholized wines, true 0.0%, spinning cone, price bands, and more. Each page is a real filter with a short editorial lede.',
            'collections/',
            'collections',
            $this->crumbs(['Collections' => 'collections/']),
            'The cellar',
            $listing,
            searchable: true,
            searchPlaceholder: 'Find a collection',
        );
    }

    /**
     * @param  Collection<int, Review>  $reviews
     * @param  array<int, array{label: string, url: string}>  $crumbs
     * @param  Collection<int, Review>  $allReviews
     * @param  array<string, int>  $publishOrder
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
     * @param  Collection<int, PageDocument>  $guides
     * @param  Collection<int, PageDocument>  $methods
     * @param  array<string, int>  $methodCounts
     */
    public function learnHub(Collection $guides, Collection $methods, array $methodCounts = []): string
    {
        $guideCards = $guides->map(function (PageDocument $document): string {
            return $this->view->render('partials/directory-row', [
                'href' => $this->config->publicUrl('learn/'.$document->slug.'/'),
                'title' => $document->title,
                'summary' => $document->summary,
                'meta' => 'Guide',
                'search' => mb_strtolower($document->title.' '.$document->summary),
            ]);
        })->implode('');

        $methodCards = $methods->map(function (PageDocument $document) use ($methodCounts): string {
            $count = $methodCounts[$document->slug] ?? null;
            $meta = 'Method';
            if (is_int($count)) {
                $meta .= $count === 1 ? ' · 1 review' : ' · '.$count.' reviews';
            }

            return $this->view->render('partials/directory-row', [
                'href' => $this->config->publicUrl('methods/'.$document->slug.'/'),
                'title' => $document->title,
                'summary' => $document->summary,
                'meta' => $meta,
                'search' => mb_strtolower($document->title.' '.$document->summary),
            ]);
        })->implode('');

        $body = $this->view->render('learn', [
            'title' => 'Learn',
            'description' => 'Buying guides and the production methods that separate dealcoholized bottles from formulated alternatives.',
            'breadcrumbs' => $this->breadcrumbs($this->crumbs(['Learn' => 'learn/'])),
            'guidesUrl' => $this->config->publicUrl('learn/'),
            'methodsUrl' => $this->config->publicUrl('methods/'),
            'guideListing' => $guideCards === ''
                ? '<p class="empty">No guides yet.</p>'
                : '<div class="directory-list">'.$guideCards.'</div>',
            'methodListing' => $methodCards === ''
                ? '<p class="empty">No methods yet.</p>'
                : '<div class="directory-list">'.$methodCards.'</div>',
        ]);

        return $this->document(
            'Learn',
            'Buying guides and dealcoholization methods for ≤0.5% ABV drinks.',
            'learn/',
            $body,
            [
                'nav' => 'guides',
                'json_ld' => $this->jsonLd([
                    $this->breadcrumbGraph($this->crumbs(['Learn' => 'learn/'])),
                    [
                        '@type' => 'CollectionPage',
                        'name' => 'Learn',
                        'url' => $this->config->canonicalUrl('learn/'),
                    ],
                ]),
            ],
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
}
