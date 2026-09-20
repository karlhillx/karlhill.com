<?php

namespace DryStandard\Rendering;

use DryStandard\Product;
use DryStandard\Review;
use DryStandard\Sensory;
use DryStandard\SiteConfig;

final class StructuredData
{
    public function __construct(private readonly SiteConfig $config) {}

    /**
     * @param  array<int, array<string, mixed>>  $graph
     */
    public function script(array $graph): string
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
    public function website(): array
    {
        return [
            '@type' => 'WebSite',
            '@id' => $this->config->canonicalUrl().'#website',
            'name' => $this->config->name(),
            'url' => $this->config->canonicalUrl(),
            'description' => $this->config->string('site.description'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $this->config->canonicalUrl('reviews/').'?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * @param  array<int, array{label: string, url: string}>  $crumbs
     * @return array<string, mixed>
     */
    public function breadcrumbs(array $crumbs): array
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
    public function person(): array
    {
        $email = $this->config->editorEmail();
        $location = $this->config->editorLocation();

        return array_filter([
            '@type' => 'Person',
            '@id' => $this->config->canonicalUrl('about/').'#editor',
            'name' => $this->config->editorName(),
            'jobTitle' => $this->config->editorRole(),
            'email' => $email === '' ? null : 'mailto:'.$email,
            'url' => $this->config->canonicalUrl('about/'),
            'homeLocation' => $location === '' ? null : [
                '@type' => 'Place',
                'name' => $location,
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function review(Review $review): array
    {
        $url = $this->config->canonicalUrl($review->path());

        return array_filter([
            '@type' => 'Review',
            '@id' => $url.'#review',
            'headline' => $review->title,
            'name' => $review->title,
            'description' => $review->summary,
            'url' => $url,
            'datePublished' => $review->reviewDate->toDateString(),
            'dateModified' => $review->modifiedAt()->toDateString(),
            'author' => $this->person(),
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->config->name(),
                'url' => $this->config->canonicalUrl(),
            ],
            'reviewRating' => $review->rating === null ? null : [
                '@type' => 'Rating',
                'ratingValue' => $review->rating,
                'bestRating' => 100,
                'worstRating' => 0,
            ],
            'itemReviewed' => ['@id' => $url.'#product'],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    public function organization(): array
    {
        return [
            '@type' => 'Organization',
            '@id' => $this->config->canonicalUrl().'#organization',
            'name' => $this->config->name(),
            'url' => $this->config->canonicalUrl(),
            'description' => $this->config->string('site.description'),
            'email' => $this->config->editorEmail() !== '' ? $this->config->editorEmail() : null,
        ];
    }

    /**
     * @param  array<int, Review>  $reviews
     * @return array<string, mixed>|null
     */
    public function itemList(string $name, string $url, array $reviews): ?array
    {
        if ($reviews === []) {
            return null;
        }

        $elements = [];
        foreach (array_values($reviews) as $index => $review) {
            $elements[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'url' => $this->config->canonicalUrl($review->path()),
                'name' => $review->title,
            ];
        }

        return [
            '@type' => 'ItemList',
            'name' => $name,
            'url' => $url,
            'numberOfItems' => count($elements),
            'itemListElement' => $elements,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function product(Review $review, string $categoryLabel): array
    {
        $url = $this->config->canonicalUrl($review->path());
        $product = Product::fromReview($review);
        $properties = [];

        if ($product->abv !== null && $product->abv !== '') {
            $properties[] = [
                '@type' => 'PropertyValue',
                'name' => 'ABV',
                'value' => $product->abv,
            ];
        }

        if ($product->abvNumeric !== null) {
            $properties[] = [
                '@type' => 'PropertyValue',
                'name' => 'alcoholContent',
                'value' => $product->abvNumeric,
                'unitText' => '% alcohol by volume',
            ];
        }

        if ($product->productionType !== '') {
            $properties[] = [
                '@type' => 'PropertyValue',
                'name' => 'productionType',
                'value' => $product->productionType,
            ];
        }

        if ($review->methodFacetKey() !== '') {
            $properties[] = [
                '@type' => 'PropertyValue',
                'name' => 'productionMethod',
                'value' => $review->methodCardLabel(),
            ];
        }

        foreach ($review->resolvedStructureScales() as $key => $level) {
            if ($key === 'texture') {
                $properties[] = [
                    '@type' => 'PropertyValue',
                    'name' => 'texture',
                    'value' => (string) $level,
                ];

                continue;
            }

            $label = Sensory::structure()[$key]['levels'][(int) $level]['label'] ?? null;
            if (is_string($label)) {
                $properties[] = [
                    '@type' => 'PropertyValue',
                    'name' => $key,
                    'value' => $label,
                ];
            }
        }

        $ean = preg_replace('/\D+/', '', (string) $product->ean) ?: '';
        foreach ($product->identifiers as $identifier) {
            if (($identifier['type'] ?? '') === 'gtin' || ($identifier['type'] ?? '') === 'ean') {
                $ean = preg_replace('/\D+/', '', (string) $identifier['value']) ?: $ean;
            }
        }

        $aggregate = $review->rating === null ? null : [
            '@type' => 'AggregateRating',
            'ratingValue' => $review->rating,
            'bestRating' => 100,
            'worstRating' => 0,
            'ratingCount' => 1,
        ];

        return array_filter([
            '@type' => 'Product',
            '@id' => $url.'#product',
            'name' => $product->product !== '' ? $product->product : $product->title,
            'brand' => [
                '@type' => 'Brand',
                'name' => $review->brandDisplayName(),
            ],
            'category' => $categoryLabel,
            'description' => $review->summary,
            'image' => $review->imageSrc() ? $this->config->canonicalUrl($review->imageSrc()) : null,
            'gtin' => $ean !== '' ? $ean : null,
            'additionalProperty' => $properties === [] ? null : $properties,
            'aggregateRating' => $aggregate,
            'review' => ['@id' => $url.'#review'],
        ], fn (mixed $value): bool => $value !== null && $value !== '');
    }
}
