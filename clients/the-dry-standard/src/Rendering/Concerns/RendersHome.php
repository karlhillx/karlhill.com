<?php

namespace DryStandard\Rendering\Concerns;

use DryStandard\PageDocument;
use DryStandard\Review;
use Illuminate\Support\Collection;

trait RendersHome
{
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
            if ($latest->count() >= 3) {
                break;
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
            ->reject(fn (Review $review): bool => in_array($review->slug, $used, true))
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
                'href' => $this->config->publicUrl('learn/'.$guide->slug.'/'),
                'title' => $guide->title,
                'summary' => $guide->summary,
                'meta' => null,
            ]);
        });

        $readCards = $methodCards->concat($guideCards)->implode('');

        $body = $this->view->render('home', [
            'tagline' => $this->config->tagline(),
            'reviewsUrl' => $this->config->publicUrl('reviews/'),
            'aboutUrl' => $this->config->publicUrl('about/'),
            'methodologyUrl' => $this->config->publicUrl('methodology/'),
            'dealcoholizedUrl' => $this->config->publicUrl('learn/dealcoholized-vs-formulated/'),
            'learnUrl' => $this->config->publicUrl('learn/'),
            'bestUrl' => $this->config->publicUrl('best/'),
            'submitUrl' => $this->config->publicUrl('industry/submit/'),
            'featured' => $featuredReview instanceof Review ? $this->featuredReview($featuredReview) : '',
            'processRail' => $this->view->render('partials/category-rail', [
                'label' => 'Browse by production type',
                'variant' => 'chips',
                'items' => $processItems,
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
     * @param  array<int, array{slug: string, title: string, lede: string, count: int, href: string}>  $collections
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
}
