<?php

namespace DryStandard\Rendering;

use DryStandard\Review;
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

        return array_filter([
            '@type' => 'Person',
            '@id' => $this->config->canonicalUrl('about/').'#editor',
            'name' => $this->config->editorName(),
            'jobTitle' => $this->config->editorRole(),
            'email' => $email === '' ? null : 'mailto:'.$email,
            'url' => $this->config->canonicalUrl('about/'),
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
    public function product(Review $review, string $categoryLabel): array
    {
        $url = $this->config->canonicalUrl($review->path());
        $properties = [];

        if ($review->abv !== null && $review->abv !== '') {
            $properties[] = [
                '@type' => 'PropertyValue',
                'name' => 'ABV',
                'value' => $review->abv,
            ];
        }

        if ($review->abvNumeric !== null) {
            $properties[] = [
                '@type' => 'PropertyValue',
                'name' => 'alcoholContent',
                'value' => $review->abvNumeric,
                'unitText' => '% alcohol by volume',
            ];
        }

        $ean = preg_replace('/\D+/', '', (string) $review->ean) ?: '';

        return array_filter([
            '@type' => 'Product',
            '@id' => $url.'#product',
            'name' => $review->product !== '' ? $review->product : $review->title,
            'brand' => [
                '@type' => 'Brand',
                'name' => $review->brandDisplayName(),
            ],
            'category' => $categoryLabel,
            'description' => $review->summary,
            'image' => $review->imageSrc() ? $this->config->canonicalUrl($review->imageSrc()) : null,
            'gtin' => $ean !== '' ? $ean : null,
            'additionalProperty' => $properties === [] ? null : $properties,
            'review' => ['@id' => $url.'#review'],
        ], fn (mixed $value): bool => $value !== null && $value !== '');
    }
}
