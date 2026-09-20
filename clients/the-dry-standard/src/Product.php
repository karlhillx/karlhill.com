<?php

namespace DryStandard;

/**
 * Product facts extracted from a review record.
 * The product does not have a palate — reviews do.
 */
final class Product
{
    /**
     * @param  array<int, array{type: string, value: string, source?: string}>  $identifiers
     * @param  array<string, array{kind: string, url?: string, note?: string, confidence?: string, verified_date?: string}>  $provenance
     */
    public function __construct(
        public readonly string $productId,
        public readonly string $brand,
        public readonly string $product,
        public readonly string $title,
        public readonly string $category,
        public readonly ?string $subcategory,
        public readonly ?string $country,
        public readonly ?string $region,
        public readonly ?string $style,
        public readonly ?string $styleSlug,
        public readonly ?string $abv,
        public readonly ?float $abvNumeric,
        public readonly string $productionType,
        public readonly string $verified,
        public readonly ?string $dealcoholizationMethod,
        public readonly ?string $baseBeverage,
        public readonly ?string $producer,
        public readonly ?string $producerSlug,
        public readonly ?string $price,
        public readonly ?string $volume,
        public readonly ?string $ingredients,
        public readonly ?string $calories,
        public readonly ?string $sugar,
        public readonly ?string $availability,
        public readonly ?string $ean,
        public readonly array $identifiers,
        public readonly array $provenance,
        public readonly ?string $wineColor = null,
    ) {}

    public static function fromReview(Review $review): self
    {
        return new self(
            productId: $review->productIdValue() ?? $review->slug,
            brand: $review->brand,
            product: $review->product,
            title: $review->title,
            category: $review->category,
            subcategory: $review->subcategory,
            country: $review->country,
            region: $review->region,
            style: $review->style,
            styleSlug: $review->hasComparableStyle() ? $review->styleSlug() : null,
            abv: $review->abv,
            abvNumeric: $review->abvNumeric,
            productionType: $review->productionType,
            verified: $review->verified,
            dealcoholizationMethod: $review->dealcoholizationMethod,
            baseBeverage: $review->baseBeverage,
            producer: $review->producer,
            producerSlug: $review->resolvedProducerSlug(),
            price: $review->price,
            volume: $review->volume,
            ingredients: $review->ingredients,
            calories: $review->calories,
            sugar: $review->sugar,
            availability: $review->availability,
            ean: $review->ean,
            identifiers: $review->identifiersRecord(),
            provenance: $review->provenanceRecord(),
            wineColor: $review->category === 'wine'
                ? Sensory::wineColor($review->styleSlug(), $review->style, $review->subcategory, $review->product)
                : null,
        );
    }

    /**
     * Product-owned columns in the denormalized catalog row.
     *
     * @return array<int, string>
     */
    public static function factColumns(): array
    {
        return [
            'id',
            'product_id',
            'ean',
            'title',
            'brand',
            'product',
            'category',
            'subcategory',
            'country',
            'region',
            'style',
            'abv',
            'abv_numeric',
            'dealcoholized',
            'dealcoholized_note',
            'production_type',
            'verified',
            'dealcoholization_method',
            'base_beverage',
            'producer',
            'price',
            'volume',
            'ingredients',
            'calories',
            'sugar',
            'availability',
            'purchase_links',
            'identifiers',
            'producer_slug',
            'provenance',
            'brand_slug',
            'style_slug',
            'method_facet',
            'abv_bucket',
            'country_slug',
            'wine_color',
            'sweetness',
            'body_level',
            'acidity_level',
            'descriptor_ids',
        ];
    }
}
