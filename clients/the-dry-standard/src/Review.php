<?php

namespace DryStandard;

use Carbon\CarbonImmutable;

final class Review
{
    public const FACT_FIELDS = [
        'abv',
        'dealcoholization_method',
        'origin',
        'producer',
        'ingredients',
        'calories',
        'sugar',
        'price',
        'availability',
        'volume',
        'base_beverage',
        'country',
        'region',
    ];

    /**
     * @param  array<int, array{label: string, url: string, region?: string}>  $purchaseLinks
     * @param  array<int, array{title: string, url: string, claims: array<int, string>}>  $sources
     * @param  array<int, array{field: string, note: string}>  $discrepancies
     */
    public function __construct(
        public readonly string $title,
        public readonly string $slug,
        public readonly string $brand,
        public readonly string $product,
        public readonly string $category,
        public readonly ?string $subcategory,
        public readonly ?string $country,
        public readonly ?string $region,
        public readonly ?string $style,
        public readonly ?string $abv,
        public readonly ?float $abvNumeric,
        public readonly string $dealcoholized,
        public readonly ?string $dealcoholizedNote,
        public readonly ?string $dealcoholizationMethod,
        public readonly ?string $baseBeverage,
        public readonly ?string $producer,
        public readonly ?string $price,
        public readonly ?string $volume,
        public readonly ?string $ingredients,
        public readonly ?string $calories,
        public readonly ?string $sugar,
        public readonly array $purchaseLinks,
        public readonly CarbonImmutable $reviewDate,
        public readonly ?CarbonImmutable $updatedDate,
        public readonly ?int $rating,
        public readonly string $verdict,
        public readonly string $summary,
        public readonly ?string $nose,
        public readonly ?string $palate,
        public readonly ?string $finish,
        public readonly ?string $bestFor,
        public readonly ?string $serve,
        public readonly array $sources,
        public readonly array $discrepancies,
        public readonly string $status,
        public readonly string $bodyMarkdown,
        public readonly string $sourcePath,
        public readonly ?string $availability = null,
    ) {}

    /**
     * @param  array<string, mixed>  $matter
     */
    public static function fromMatter(array $matter, string $body, string $sourcePath): self
    {
        $slug = self::string($matter['slug'] ?? null);
        $category = self::string($matter['category'] ?? null);

        if ($slug === '' || $category === '') {
            throw new \InvalidArgumentException('Review requires slug and category in '.$sourcePath);
        }

        return new self(
            title: self::string($matter['title'] ?? null) ?: $slug,
            slug: $slug,
            brand: self::string($matter['brand'] ?? null),
            product: self::string($matter['product'] ?? null),
            category: $category,
            subcategory: self::nullableString($matter['subcategory'] ?? null),
            country: self::nullableString($matter['country'] ?? null),
            region: self::nullableString($matter['region'] ?? null),
            style: self::nullableString($matter['style'] ?? null),
            abv: self::nullableString($matter['abv'] ?? null),
            abvNumeric: isset($matter['abv_numeric']) && is_numeric($matter['abv_numeric'])
                ? (float) $matter['abv_numeric']
                : null,
            dealcoholized: self::normalizeDealcoholized($matter['dealcoholized'] ?? null),
            dealcoholizedNote: self::nullableString($matter['dealcoholized_note'] ?? null),
            dealcoholizationMethod: self::nullableString($matter['dealcoholization_method'] ?? null),
            baseBeverage: self::nullableString($matter['base_beverage'] ?? null),
            producer: self::nullableString($matter['producer'] ?? null),
            price: self::nullableString($matter['price'] ?? null),
            volume: self::nullableString($matter['volume'] ?? null),
            ingredients: self::nullableString($matter['ingredients'] ?? null),
            calories: self::nullableString($matter['calories'] ?? null),
            sugar: self::nullableString($matter['sugar'] ?? null),
            purchaseLinks: self::purchaseLinks($matter['purchase_links'] ?? []),
            reviewDate: self::date($matter['review_date'] ?? $matter['date'] ?? null) ?? CarbonImmutable::now(),
            updatedDate: self::date($matter['updated_date'] ?? $matter['updated'] ?? null),
            rating: isset($matter['rating']) && is_numeric($matter['rating']) ? (int) $matter['rating'] : null,
            verdict: self::string($matter['verdict'] ?? null),
            summary: self::string($matter['summary'] ?? null),
            nose: self::nullableString($matter['nose'] ?? null),
            palate: self::nullableString($matter['palate'] ?? null),
            finish: self::nullableString($matter['finish'] ?? null),
            bestFor: self::nullableString($matter['best_for'] ?? null),
            serve: self::nullableString($matter['serve'] ?? null),
            sources: self::sources($matter['sources'] ?? []),
            discrepancies: self::discrepancies($matter['discrepancies'] ?? []),
            status: self::string($matter['status'] ?? null) ?: 'draft',
            bodyMarkdown: trim($body),
            sourcePath: $sourcePath,
            availability: self::nullableString($matter['availability'] ?? null),
        );
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function path(): string
    {
        return 'reviews/'.$this->category.'/'.$this->slug.'/';
    }

    public function originLabel(): ?string
    {
        $parts = array_values(array_filter([$this->region, $this->country]));

        return $parts === [] ? null : implode(', ', $parts);
    }

    public function dealcoholizedLabel(): string
    {
        return match ($this->dealcoholized) {
            'yes' => 'Dealcoholized: Yes',
            'no' => $this->dealcoholizedNote
                ?: 'Dealcoholized: No — formulated as a zero-proof alternative',
            default => 'Dealcoholized: Not verified',
        };
    }

    public function modifiedAt(): CarbonImmutable
    {
        return $this->updatedDate ?? $this->reviewDate;
    }

    /**
     * @return array<string, mixed>
     */
    public function catalogRecord(): array
    {
        return [
            'title' => $this->title,
            'slug' => $this->slug,
            'brand' => $this->brand,
            'product' => $this->product,
            'category' => $this->category,
            'subcategory' => $this->subcategory,
            'country' => $this->country,
            'region' => $this->region,
            'style' => $this->style,
            'abv' => $this->abv,
            'abv_numeric' => $this->abvNumeric,
            'dealcoholized' => $this->dealcoholized,
            'dealcoholization_method' => $this->dealcoholizationMethod,
            'producer' => $this->producer,
            'price' => $this->price,
            'volume' => $this->volume,
            'rating' => $this->rating,
            'summary' => $this->summary,
            'status' => $this->status,
            'path' => $this->path(),
            'review_date' => $this->reviewDate->toDateString(),
            'updated_date' => $this->modifiedAt()->toDateString(),
        ];
    }

    public function fact(string $field): ?string
    {
        return match ($field) {
            'abv' => $this->abv,
            'dealcoholization_method' => $this->dealcoholizationMethod,
            'origin' => $this->originLabel(),
            'producer' => $this->producer,
            'ingredients' => $this->ingredients,
            'calories' => $this->calories,
            'sugar' => $this->sugar,
            'price' => $this->price,
            'availability' => $this->availability,
            'volume' => $this->volume,
            'base_beverage' => $this->baseBeverage,
            'country' => $this->country,
            'region' => $this->region,
            default => null,
        };
    }

    /**
     * @return array<int, string>
     */
    public function sourcedClaims(): array
    {
        $claims = [];

        foreach ($this->sources as $source) {
            foreach ($source['claims'] as $claim) {
                $claims[] = $claim;
            }
        }

        return array_values(array_unique($claims));
    }

    private static function normalizeDealcoholized(mixed $value): string
    {
        $normalized = strtolower(trim((string) $value));

        return match ($normalized) {
            'yes', 'true', '1' => 'yes',
            'no', 'false', '0' => 'no',
            default => 'not-verified',
        };
    }

    private static function string(mixed $value): string
    {
        return is_scalar($value) ? trim((string) $value) : '';
    }

    private static function nullableString(mixed $value): ?string
    {
        $string = self::string($value);

        return $string === '' ? null : $string;
    }

    private static function date(mixed $value): ?CarbonImmutable
    {
        if ($value instanceof CarbonImmutable) {
            return $value;
        }

        if ($value instanceof \DateTimeInterface) {
            return CarbonImmutable::createFromInterface($value);
        }

        if (is_int($value) || (is_numeric($value) && ! str_contains((string) $value, '.'))) {
            return CarbonImmutable::createFromTimestamp((int) $value);
        }

        $string = self::string($value);

        if ($string === '') {
            return null;
        }

        return CarbonImmutable::parse($string);
    }

    /**
     * @return array<int, array{label: string, url: string, region?: string}>
     */
    private static function purchaseLinks(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $links = [];

        foreach ($value as $link) {
            if (! is_array($link)) {
                continue;
            }

            $label = self::string($link['label'] ?? null);
            $url = self::string($link['url'] ?? null);

            if ($label === '' || $url === '') {
                continue;
            }

            $item = ['label' => $label, 'url' => $url];
            $region = self::nullableString($link['region'] ?? null);

            if ($region !== null) {
                $item['region'] = $region;
            }

            $links[] = $item;
        }

        return $links;
    }

    /**
     * @return array<int, array{title: string, url: string, claims: array<int, string>}>
     */
    private static function sources(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $sources = [];

        foreach ($value as $source) {
            if (! is_array($source)) {
                continue;
            }

            $title = self::string($source['title'] ?? null);
            $url = self::string($source['url'] ?? null);

            if ($title === '' || $url === '') {
                continue;
            }

            $claims = [];
            if (isset($source['claims']) && is_array($source['claims'])) {
                $claims = array_values(array_filter(array_map(
                    fn (mixed $claim): string => self::string($claim),
                    $source['claims'],
                )));
            }

            $sources[] = [
                'title' => $title,
                'url' => $url,
                'claims' => $claims,
            ];
        }

        return $sources;
    }

    /**
     * @return array<int, array{field: string, note: string}>
     */
    private static function discrepancies(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $rows = [];

        foreach ($value as $item) {
            if (! is_array($item)) {
                continue;
            }

            $field = self::string($item['field'] ?? null);
            $note = self::string($item['note'] ?? null);

            if ($field === '' || $note === '') {
                continue;
            }

            $rows[] = ['field' => $field, 'note' => $note];
        }

        return $rows;
    }
}
