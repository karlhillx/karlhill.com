<?php

namespace DryStandard;

/**
 * Comparison-ready projection of a reviewed product.
 * UI may render 2–4 of these later; this DTO keeps fields stable.
 */
final class ComparableSnapshot
{
    /**
     * @param  array<int, string>  $descriptors
     * @param  array<string, int|string>  $structure
     * @param  array<string, int>  $assessments
     */
    public function __construct(
        public readonly string $productId,
        public readonly string $slug,
        public readonly string $title,
        public readonly string $brand,
        public readonly string $category,
        public readonly ?string $style,
        public readonly ?int $score,
        public readonly ?string $abv,
        public readonly ?float $abvNumeric,
        public readonly ?string $price,
        public readonly string $productionType,
        public readonly string $methodFacet,
        public readonly ?string $methodLabel,
        public readonly ?string $sugar,
        public readonly array $descriptors,
        public readonly array $structure,
        public readonly array $assessments,
        public readonly ?int $categoryLikeness,
        public readonly ?int $structuralAuthenticity,
        public readonly string $path,
    ) {}

    public static function fromReview(Review $review): self
    {
        $structure = $review->resolvedStructureScales();
        $assessments = $review->assessments;

        return new self(
            productId: $review->productIdValue() ?? $review->slug,
            slug: $review->slug,
            title: $review->title,
            brand: $review->brandDisplayName(),
            category: $review->category,
            style: $review->styleLabel(),
            score: $review->rating,
            abv: $review->abv,
            abvNumeric: $review->abvNumeric,
            price: $review->price,
            productionType: $review->productionType,
            methodFacet: $review->methodFacetKey(),
            methodLabel: $review->methodCardLabel(),
            sugar: $review->sugar,
            descriptors: $review->flavorProfileLabels(),
            structure: $structure,
            assessments: $assessments,
            categoryLikeness: isset($assessments['likeness']) ? (int) $assessments['likeness'] : null,
            structuralAuthenticity: isset($assessments['structural_authenticity'])
                ? (int) $assessments['structural_authenticity']
                : null,
            path: $review->path(),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'product_id' => $this->productId,
            'slug' => $this->slug,
            'title' => $this->title,
            'brand' => $this->brand,
            'category' => $this->category,
            'style' => $this->style,
            'score' => $this->score,
            'abv' => $this->abv,
            'abv_numeric' => $this->abvNumeric,
            'price' => $this->price,
            'production_type' => $this->productionType,
            'method_facet' => $this->methodFacet,
            'method_label' => $this->methodLabel,
            'sugar' => $this->sugar,
            'descriptors' => $this->descriptors,
            'structure' => $this->structure,
            'assessments' => $this->assessments,
            'category_likeness' => $this->categoryLikeness,
            'structural_authenticity' => $this->structuralAuthenticity,
            'path' => $this->path,
        ];
    }
}
