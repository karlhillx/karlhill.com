<?php

namespace DryStandard;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;

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

    public const PRODUCTION_TYPES = [
        'dealcoholized' => 'Dealcoholized',
        'alternative' => 'Alternative',
        'naturally-low-alcohol' => 'Naturally low alcohol',
        'hybrid' => 'Hybrid',
        'not-verified' => 'Not verified',
    ];

    public const ACQUISITIONS = [
        'purchased' => 'Purchased independently',
        'manufacturer-sample' => 'Sample provided by the manufacturer',
        'distributor-sample' => 'Sample provided by a distributor',
        'unknown' => 'Acquisition not recorded',
    ];

    public const IDENTIFIER_TYPES = ['gtin', 'ean', 'upc', 'mfr', 'asin', 'tds'];

    public const PROVENANCE_KINDS = [
        'manufacturer',
        'label',
        'retailer',
        'distributor',
        'government',
        'research',
        'press',
        'inference',
        'unknown',
    ];

    public const OFFER_RELATIONSHIPS = ['citation', 'affiliate', 'paid', 'unknown'];

    public const COMMERCIAL_RELATIONSHIPS = ['none', 'brand', 'retailer', 'distributor', 'other'];

    /**
     * @param  array<int, array{label: string, url: string, region?: string, retailer?: string, price?: string, currency?: string, availability?: string, last_verified?: string, relationship?: string, referral_type?: string, affiliate_url?: string}>  $purchaseLinks
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
        public readonly string $productionType,
        public readonly string $verified,
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
        public readonly ?string $image = null,
        public readonly ?string $imageAlt = null,
        public readonly ?string $imageCredit = null,
        public readonly ?string $id = null,
        public readonly ?string $ean = null,
        public readonly ?string $imageSource = null,
        public readonly ?string $imageSourceUrl = null,
        public readonly ?string $imageSkuConfirmed = null,
        public readonly ?string $styleSlugOverride = null,
        public readonly ?string $productId = null,
        public readonly array $identifiers = [],
        public readonly ?string $producerSlug = null,
        public readonly ?string $acquisition = null,
        public readonly string $sponsored = 'no',
        public readonly string $affiliateRelationship = 'none',
        public readonly string $advertisingRelationship = 'none',
        public readonly string $commercialRelationship = 'none',
        public readonly ?string $disclosureNote = null,
        public readonly array $provenance = [],
        public readonly ?string $structure = null,
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
            country: self::normalizeCountry(self::nullableString($matter['country'] ?? null)),
            region: self::nullableString($matter['region'] ?? null),
            style: self::nullableString($matter['style'] ?? null),
            abv: self::nullableString($matter['abv'] ?? null),
            abvNumeric: isset($matter['abv_numeric']) && is_numeric($matter['abv_numeric'])
                ? (float) $matter['abv_numeric']
                : null,
            dealcoholized: ($production = self::resolveProduction($matter))['dealcoholized'],
            dealcoholizedNote: self::nullableString($matter['production_note'] ?? $matter['dealcoholized_note'] ?? null),
            productionType: $production['type'],
            verified: $production['verified'],
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
            image: self::nullableString($matter['image'] ?? null),
            imageAlt: self::nullableString($matter['image_alt'] ?? null),
            imageCredit: self::nullableString($matter['image_credit'] ?? null),
            id: self::nullableString($matter['id'] ?? null),
            ean: self::nullableString($matter['ean'] ?? null),
            imageSource: self::nullableLower($matter['image_source'] ?? null),
            imageSourceUrl: self::nullableString($matter['image_source_url'] ?? null),
            imageSkuConfirmed: self::normalizeVerified($matter['image_sku_confirmed'] ?? null),
            styleSlugOverride: self::nullableString($matter['style_slug'] ?? null),
            productId: self::nullableString($matter['product_id'] ?? $matter['id'] ?? null),
            identifiers: self::identifiers($matter['identifiers'] ?? []),
            producerSlug: self::nullableString($matter['producer_slug'] ?? null),
            acquisition: self::normalizeAcquisition($matter['acquisition'] ?? $matter['sample_source'] ?? null),
            sponsored: self::normalizeFlag($matter['sponsored'] ?? null, 'no'),
            affiliateRelationship: self::normalizePresence($matter['affiliate_relationship'] ?? null),
            advertisingRelationship: self::normalizePresence($matter['advertising_relationship'] ?? null),
            commercialRelationship: self::normalizeCommercial($matter['commercial_relationship'] ?? null),
            disclosureNote: self::nullableString($matter['disclosure_note'] ?? null),
            provenance: self::provenance($matter['provenance'] ?? []),
            structure: self::nullableString($matter['structure'] ?? $matter['structural_authenticity'] ?? null),
        );
    }

    /**
     * @param  array<string, mixed>  $row
     */
    public static function fromRecord(array $row): self
    {
        $matter = $row;
        $matter['purchase_links'] = self::decodeJsonList($row['purchase_links'] ?? '[]');
        $matter['sources'] = self::decodeJsonList($row['sources'] ?? '[]');
        $matter['discrepancies'] = self::decodeJsonList($row['discrepancies'] ?? '[]');
        $matter['identifiers'] = self::decodeJsonList($row['identifiers'] ?? '[]');
        $matter['provenance'] = self::decodeJsonMap($row['provenance'] ?? '{}');

        return self::fromMatter(
            $matter,
            (string) ($row['body_markdown'] ?? ''),
            'catalog:'.((string) ($row['slug'] ?? 'unknown')),
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toRecord(): array
    {
        return [
            'id' => $this->id,
            'ean' => $this->ean,
            'slug' => $this->slug,
            'title' => $this->title,
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
            'dealcoholized_note' => $this->dealcoholizedNote,
            'production_type' => $this->productionType,
            'verified' => $this->verified,
            'dealcoholization_method' => $this->dealcoholizationMethod,
            'base_beverage' => $this->baseBeverage,
            'producer' => $this->producer,
            'price' => $this->price,
            'volume' => $this->volume,
            'ingredients' => $this->ingredients,
            'calories' => $this->calories,
            'sugar' => $this->sugar,
            'availability' => $this->availability,
            'purchase_links' => json_encode($this->purchaseLinks, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'review_date' => $this->reviewDate->toDateString(),
            'updated_date' => $this->updatedDate?->toDateString(),
            'rating' => $this->rating,
            'verdict' => $this->verdict,
            'summary' => $this->summary,
            'nose' => $this->nose,
            'palate' => $this->palate,
            'finish' => $this->finish,
            'structure' => $this->structure,
            'best_for' => $this->bestFor,
            'serve' => $this->serve,
            'sources' => json_encode($this->sources, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'discrepancies' => json_encode($this->discrepancies, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'body_markdown' => $this->bodyMarkdown,
            'image' => $this->image,
            'image_alt' => $this->imageAlt,
            'image_credit' => $this->imageCredit,
            'image_source' => $this->imageSource,
            'image_source_url' => $this->imageSourceUrl,
            'image_sku_confirmed' => $this->imageSkuConfirmed,
            'status' => $this->status,
            'brand_slug' => $this->brandSlug(),
            'style_slug' => $this->styleSlug(),
            'method_facet' => $this->methodFacetKey(),
            'abv_bucket' => $this->abvBucket(),
            'country_slug' => $this->countrySlug(),
            'search_text' => $this->searchText(),
            'product_id' => $this->productIdValue(),
            'identifiers' => json_encode($this->identifiersRecord(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'producer_slug' => $this->resolvedProducerSlug(),
            'acquisition' => $this->acquisition,
            'sponsored' => $this->sponsored,
            'affiliate_relationship' => $this->affiliateRelationship,
            'advertising_relationship' => $this->advertisingRelationship,
            'commercial_relationship' => $this->commercialRelationship,
            'disclosure_note' => $this->disclosureNote,
            'provenance' => json_encode($this->provenance, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ];
    }

    /**
     * @return array<int, mixed>
     */
    private static function decodeJsonList(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (! is_string($value) || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @return array<string, mixed>
     */
    private static function decodeJsonMap(mixed $value): array
    {
        if (is_array($value)) {
            return $value;
        }

        if (! is_string($value) || $value === '') {
            return [];
        }

        $decoded = json_decode($value, true);

        return is_array($decoded) ? $decoded : [];
    }

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isPublic(): bool
    {
        return $this->isPublished()
            && trim($this->bodyMarkdown) !== ''
            && $this->rating !== null
            && ! str_contains(mb_strtolower($this->title.' '.$this->product), 'bundle');
    }

    public function isListed(): bool
    {
        return $this->isPublished()
            && $this->rating !== null
            && ! str_contains(mb_strtolower($this->title.' '.$this->product), 'bundle');
    }

    public function path(): string
    {
        return 'reviews/'.$this->category.'/'.$this->slug.'/';
    }

    public function originLabel(): ?string
    {
        $country = $this->countryLabel();
        $parts = array_values(array_filter([$this->region, $country]));

        return $parts === [] ? null : implode(', ', $parts);
    }

    public function countryLabel(): ?string
    {
        return self::normalizeCountry($this->country);
    }

    public function cardMetaLine(string $categoryLabel): string
    {
        $parts = [$categoryLabel];
        $country = $this->countryLabel();
        if ($country !== null && $country !== '') {
            $parts[] = $country;
        }

        if (! in_array($this->methodFacetKey(), ['unknown', 'not-applicable'], true)) {
            $parts[] = $this->methodCardLabel();
        }

        return implode(' · ', $parts);
    }

    public function cardTitle(): string
    {
        $product = trim($this->product);
        $brand = trim($this->brand);

        if ($product === '' || $brand === '') {
            return $this->title;
        }

        if (strcasecmp(trim($this->title), $brand.' '.$product) === 0) {
            return $product;
        }

        return $this->title;
    }

    public function styleSlug(): string
    {
        return Taxonomy::styleSlug($this->style, $this->subcategory, $this->product, $this->styleSlugOverride);
    }

    public function styleLabel(): string
    {
        return Taxonomy::styleLabel($this->styleSlug(), $this->style);
    }

    public function hasComparableStyle(): bool
    {
        return Taxonomy::hasStyle($this->styleSlug());
    }

    public function productionTypeLabel(): string
    {
        $label = self::PRODUCTION_TYPES[$this->productionType] ?? 'Not verified';

        if ($this->dealcoholizedNote) {
            return 'Production type: '.$label.' — '.$this->dealcoholizedNote;
        }

        return 'Production type: '.$label;
    }

    public function productionTypeShortLabel(): string
    {
        return self::PRODUCTION_TYPES[$this->productionType] ?? 'Not verified';
    }

    public function essayHeading(): string
    {
        return match ($this->category) {
            'wine' => 'The wine',
            'beer' => 'The beer',
            'spirits' => 'The spirit',
            'cocktails' => 'The drink',
            'cider' => 'The cider',
            default => 'The bottle',
        };
    }

    public function verifiedLabel(): string
    {
        if ($this->productionType === 'not-verified') {
            return '';
        }

        return $this->verified === 'yes' ? 'Sourced production type' : 'Production type not fully sourced';
    }

    public function brandDisplayName(): string
    {
        return Taxonomy::brandName($this->brand);
    }

    public function brandSlug(): string
    {
        return Taxonomy::brandSlug($this->brand);
    }

    public function countrySlug(): string
    {
        return Taxonomy::countrySlug($this->countryLabel());
    }

    public function productIdValue(): ?string
    {
        $id = $this->productId ?? $this->id;

        return $id === null || $id === '' ? null : $id;
    }

    public function resolvedProducerSlug(): ?string
    {
        if ($this->producerSlug !== null && $this->producerSlug !== '') {
            return $this->producerSlug;
        }

        if ($this->producer === null || $this->producer === '') {
            return null;
        }

        $slug = Str::slug($this->producer);

        return $slug === '' ? null : $slug;
    }

    /**
     * @return array<int, array{type: string, value: string, source?: string}>
     */
    public function identifiersRecord(): array
    {
        $items = [];
        $seen = [];

        foreach ($this->identifiers as $identifier) {
            $type = strtolower((string) ($identifier['type'] ?? ''));
            $value = trim((string) ($identifier['value'] ?? ''));
            if ($type === '' || $value === '' || isset($seen[$type.':'.$value])) {
                continue;
            }
            $seen[$type.':'.$value] = true;
            $item = ['type' => $type, 'value' => $value];
            if (! empty($identifier['source'])) {
                $item['source'] = (string) $identifier['source'];
            }
            $items[] = $item;
        }

        $ean = preg_replace('/\D+/', '', (string) $this->ean) ?: '';
        if ($ean !== '' && ! isset($seen['ean:'.$ean]) && ! isset($seen['gtin:'.$ean])) {
            $items[] = ['type' => 'ean', 'value' => $ean];
        }

        $tds = $this->productIdValue();
        if ($tds !== null && ! isset($seen['tds:'.$tds])) {
            $items[] = ['type' => 'tds', 'value' => $tds];
        }

        return $items;
    }

    public function hasPublicDisclosure(): bool
    {
        return ($this->acquisition !== null && $this->acquisition !== 'purchased' && $this->acquisition !== 'unknown')
            || $this->sponsored === 'yes'
            || $this->affiliateRelationship === 'present'
            || $this->advertisingRelationship === 'present'
            || ($this->commercialRelationship !== 'none')
            || ($this->disclosureNote !== null && $this->disclosureNote !== '');
    }

    /**
     * @return array<int, string>
     */
    public function disclosureLines(): array
    {
        $lines = [];

        if ($this->acquisition !== null && isset(self::ACQUISITIONS[$this->acquisition]) && $this->acquisition !== 'unknown') {
            $lines[] = self::ACQUISITIONS[$this->acquisition];
        }

        if ($this->sponsored === 'yes') {
            $lines[] = 'This page includes sponsored material, labeled separately from the editorial review.';
        }

        if ($this->affiliateRelationship === 'present') {
            $lines[] = 'Some outbound retailer links may be affiliate links.';
        }

        if ($this->advertisingRelationship === 'present') {
            $lines[] = 'The Dry Standard has an advertising relationship connected to this product.';
        }

        if ($this->commercialRelationship !== 'none') {
            $lines[] = 'A commercial relationship exists with a '.$this->commercialRelationship.'.';
        }

        if ($this->disclosureNote !== null && $this->disclosureNote !== '') {
            $lines[] = $this->disclosureNote;
        }

        return $lines;
    }

    public function dealcoholizedLabel(): string
    {
        return $this->productionTypeLabel();
    }

    public function dealcoholizedShortLabel(): string
    {
        return $this->productionTypeShortLabel();
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
            'abv_bucket' => $this->abvBucket(),
            'brand_slug' => $this->brandSlug(),
            'origin' => $this->originLabel(),
            'dealcoholized' => $this->dealcoholized,
            'production_type' => $this->productionType,
            'verified' => $this->verified,
            'dealcoholization_method' => $this->dealcoholizationMethod,
            'method_slug' => $this->methodKey(),
            'producer' => $this->producer,
            'price' => $this->price,
            'volume' => $this->volume,
            'rating' => $this->rating,
            'summary' => $this->summary,
            'status' => $this->status,
            'path' => $this->path(),
            'review_date' => $this->reviewDate->toDateString(),
            'updated_date' => $this->modifiedAt()->toDateString(),
            'search_text' => $this->searchText(),
            'image' => $this->imageSrc(),
            'style_slug' => $this->styleSlug(),
            'country_slug' => $this->countrySlug(),
            'method_facet' => $this->methodFacetKey(),
        ];
    }

    public function imageSrc(): ?string
    {
        $root = Paths::default()->path();
        $candidates = array_values(array_filter([
            $this->image,
            'media/reviews/'.$this->slug.'.jpg',
            'media/reviews/'.$this->slug.'.webp',
            'media/reviews/'.$this->slug.'.png',
        ]));

        foreach ($candidates as $relative) {
            $relative = ltrim(str_replace('\\', '/', (string) $relative), '/');
            $absolute = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);

            if (is_file($absolute)) {
                return $relative;
            }
        }

        return null;
    }

    public function imageAltText(): string
    {
        return $this->imageAlt ?? $this->title;
    }

    public const ABV_BUCKETS = [
        'zero' => '0.0%',
        'half' => '<0.5%',
        'unpublished' => 'Not published',
    ];

    public const METHOD_FACETS = [
        'vacuum-distillation',
        'spinning-cone',
        'reverse-osmosis',
        'membrane-filtration',
        'osmotic-distillation',
        'arrested-fermentation',
    ];

    private const METHOD_NEEDLES = [
        'reverse osmosis' => 'reverse-osmosis',
        'spinning cone' => 'spinning-cone',
        'spun cone' => 'spinning-cone',
        'osmotic distillation' => 'osmotic-distillation',
        'cold filtration' => 'membrane-filtration',
        'membrane filtration' => 'membrane-filtration',
        'vacuum distill' => 'vacuum-distillation',
        'vacuum evaporat' => 'vacuum-distillation',
        'vacuum dealcohol' => 'vacuum-distillation',
        'cold vacuum' => 'vacuum-distillation',
        'arrested fermentation' => 'arrested-fermentation',
        'arresting fermentation' => 'arrested-fermentation',
    ];

    private const NAMED_OTHER_NEEDLES = [
        'reverse distillation',
        'reverse-distillation',
        'mechanical separator',
        'boiled off',
        'thermal shock',
        'pervaporat',
        'diafiltrat',
        'centrifug',
    ];

    public function abvBucket(): string
    {
        if ($this->abvNumeric === null) {
            return 'unpublished';
        }

        if ($this->abvNumeric <= 0.0) {
            return 'zero';
        }

        return 'half';
    }

    public function methodKey(): ?string
    {
        $text = strtolower($this->dealcoholizationMethod ?? '');

        if ($text === '') {
            return null;
        }

        $best = null;
        $position = PHP_INT_MAX;

        foreach (self::METHOD_NEEDLES as $needle => $key) {
            $found = strpos($text, $needle);
            if ($found !== false && $found < $position) {
                $position = $found;
                $best = $key;
            }
        }

        return $best;
    }

    public function methodFacetKey(): string
    {
        if ($this->productionType === 'alternative') {
            return 'not-applicable';
        }

        $key = $this->methodKey();

        if ($key !== null && in_array($key, self::METHOD_FACETS, true)) {
            return $key;
        }

        $text = strtolower($this->dealcoholizationMethod ?? '');

        if ($text === '') {
            return 'unknown';
        }

        foreach (self::NAMED_OTHER_NEEDLES as $needle) {
            if (str_contains($text, $needle)) {
                return 'other';
            }
        }

        return 'unknown';
    }

    public function methodCardLabel(): string
    {
        $key = $this->methodFacetKey();

        $named = [
            'vacuum-distillation' => 'Vacuum distillation',
            'spinning-cone' => 'Spinning cone',
            'reverse-osmosis' => 'Reverse osmosis',
            'membrane-filtration' => 'Membrane / cold filtration',
            'osmotic-distillation' => 'Osmotic distillation',
            'arrested-fermentation' => 'Arrested fermentation',
            'other' => 'Other documented method',
            'not-applicable' => 'Formulated alternative',
        ];

        if (isset($named[$key])) {
            return $named[$key];
        }

        return match ($this->productionType) {
            'alternative' => 'Formulated alternative',
            'naturally-low-alcohol' => 'Brewed or fermented to ≤0.5%',
            'hybrid' => 'Hybrid process',
            default => 'Method unpublished',
        };
    }

    /**
     * @return array{src: string, webp: ?string, srcset: string, webpSrcset: string, width: int, height: int}|null
     */
    public function imageAssets(): ?array
    {
        $src = $this->imageSrc();
        if ($src === null) {
            return null;
        }

        $root = Paths::default()->path();
        $absolute = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $src);

        $size = is_file($absolute) ? @getimagesize($absolute) : false;
        $webpRelative = is_string($src) ? preg_replace('/\.(jpe?g|png)$/i', '.webp', $src) : null;
        $webpAbsolute = is_string($webpRelative)
            ? $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $webpRelative)
            : '';
        $webp = (is_string($webpRelative) && $webpAbsolute !== '' && is_file($webpAbsolute)) ? $webpRelative : null;

        $webpSrcset = [];
        foreach ([400, 800] as $width) {
            $variant = is_string($src) ? preg_replace('/\.(jpe?g|png)$/i', '-'.$width.'.webp', $src) : null;
            if (! is_string($variant)) {
                continue;
            }
            $variantAbsolute = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $variant);
            if (is_file($variantAbsolute)) {
                $webpSrcset[] = $variant.' '.$width.'w';
            }
        }
        if ($webp !== null) {
            $webpSrcset[] = $webp.' 900w';
        }

        $nativeWidth = is_array($size) ? (int) $size[0] : 720;

        return [
            'src' => $src,
            'webp' => $webp,
            'srcset' => $src.' '.$nativeWidth.'w',
            'webpSrcset' => implode(', ', $webpSrcset),
            'width' => $nativeWidth,
            'height' => is_array($size) ? (int) $size[1] : 960,
        ];
    }

    public function searchText(): string
    {
        return strtolower(implode(' ', array_filter([
            $this->title,
            Taxonomy::brandSearchTokens($this->brand),
            $this->product,
            $this->category,
            $this->subcategory,
            $this->originLabel(),
            $this->dealcoholizationMethod,
            $this->style,
            $this->styleLabel(),
            $this->structure,
            $this->productionTypeShortLabel(),
            $this->summary,
        ])));
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
            'production_type' => $this->productionTypeShortLabel(),
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

    /**
     * @return array{type: string, verified: string, dealcoholized: string}
     */
    private static function resolveProduction(array $matter): array
    {
        $type = self::normalizeProductionType($matter['production_type'] ?? null);

        if ($type === null) {
            $legacy = self::normalizeDealcoholized($matter['dealcoholized'] ?? null);
            $type = match ($legacy) {
                'yes' => 'dealcoholized',
                'no' => 'alternative',
                default => 'not-verified',
            };
        }

        $verified = self::normalizeVerified($matter['verified'] ?? null);
        if ($verified === null) {
            $verified = $type === 'not-verified' ? 'no' : 'yes';
        }

        $dealcoholized = match ($type) {
            'dealcoholized' => 'yes',
            'not-verified' => 'not-verified',
            default => 'no',
        };

        return [
            'type' => $type,
            'verified' => $verified,
            'dealcoholized' => $dealcoholized,
        ];
    }

    private static function normalizeProductionType(mixed $value): ?string
    {
        $normalized = strtolower(trim((string) $value));
        $normalized = str_replace(['_', ' '], '-', $normalized);

        return match ($normalized) {
            'dealcoholized' => 'dealcoholized',
            'alternative' => 'alternative',
            'naturally-low-alcohol', 'naturally-low', 'low-alcohol' => 'naturally-low-alcohol',
            'hybrid', 'blended', 'blended-hybrid' => 'hybrid',
            'not-verified', 'unknown', 'unpublished' => 'not-verified',
            default => null,
        };
    }

    private static function normalizeVerified(mixed $value): ?string
    {
        $normalized = strtolower(trim((string) $value));

        return match ($normalized) {
            'yes', 'true', '1' => 'yes',
            'no', 'false', '0' => 'no',
            default => null,
        };
    }

    private static function normalizeDealcoholized(mixed $value): string
    {
        $normalized = strtolower(trim((string) $value));

        if (str_starts_with($normalized, 'not')) {
            return 'not-verified';
        }

        return match ($normalized) {
            'yes', 'true', '1' => 'yes',
            'no', 'false', '0' => 'no',
            default => 'not-verified',
        };
    }

    private static function normalizeCountry(?string $country): ?string
    {
        if ($country === null || $country === '') {
            return $country;
        }

        $normalized = strtolower(trim($country, " \t\n\r\0\x0B."));

        return match ($normalized) {
            'usa', 'u.s.a', 'u.s', 'us', 'united states', 'united states of america' => 'United States',
            'uk', 'u.k', 'great britain', 'britain' => 'United Kingdom',
            default => $country,
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

    private static function nullableLower(mixed $value): ?string
    {
        $string = self::nullableString($value);

        return $string === null ? null : strtolower($string);
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
     * @return array<int, array{label: string, url: string, region?: string, retailer?: string, price?: string, currency?: string, availability?: string, last_verified?: string, relationship?: string, referral_type?: string, affiliate_url?: string}>
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

            foreach (['region', 'retailer', 'price', 'currency', 'availability', 'last_verified', 'referral_type', 'affiliate_url'] as $optional) {
                $field = self::nullableString($link[$optional] ?? null);
                if ($field !== null) {
                    $item[$optional] = $field;
                }
            }

            $relationship = self::nullableLower($link['relationship'] ?? null);
            if ($relationship !== null && in_array($relationship, self::OFFER_RELATIONSHIPS, true)) {
                $item['relationship'] = $relationship;
            }

            $links[] = $item;
        }

        return $links;
    }

    /**
     * @return array<int, array{type: string, value: string, source?: string}>
     */
    private static function identifiers(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $items = [];

        foreach ($value as $identifier) {
            if (! is_array($identifier)) {
                continue;
            }

            $type = strtolower(self::string($identifier['type'] ?? null));
            $code = self::string($identifier['value'] ?? null);
            if (! in_array($type, self::IDENTIFIER_TYPES, true) || $code === '') {
                continue;
            }

            $item = ['type' => $type, 'value' => $code];
            $source = self::nullableString($identifier['source'] ?? null);
            if ($source !== null) {
                $item['source'] = $source;
            }
            $items[] = $item;
        }

        return $items;
    }

    /**
     * @return array<string, array{kind: string, url?: string, note?: string}>
     */
    private static function provenance(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $rows = [];

        foreach ($value as $field => $entry) {
            if (! is_string($field) || $field === '') {
                continue;
            }

            if (is_string($entry)) {
                $kind = strtolower(trim($entry));
                if (in_array($kind, self::PROVENANCE_KINDS, true)) {
                    $rows[$field] = ['kind' => $kind];
                }

                continue;
            }

            if (! is_array($entry)) {
                continue;
            }

            $kind = strtolower(self::string($entry['kind'] ?? $entry['source_kind'] ?? null));
            if (! in_array($kind, self::PROVENANCE_KINDS, true)) {
                continue;
            }

            $item = ['kind' => $kind];
            $url = self::nullableString($entry['url'] ?? null);
            if ($url !== null) {
                $item['url'] = $url;
            }
            $note = self::nullableString($entry['note'] ?? null);
            if ($note !== null) {
                $item['note'] = $note;
            }
            $rows[$field] = $item;
        }

        return $rows;
    }

    private static function normalizeAcquisition(mixed $value): ?string
    {
        $normalized = strtolower(trim((string) $value));
        $normalized = str_replace(['_', ' '], '-', $normalized);

        return match ($normalized) {
            'purchased', 'purchased-independently', 'independent' => 'purchased',
            'manufacturer-sample', 'manufacturer', 'producer-sample', 'brand-sample' => 'manufacturer-sample',
            'distributor-sample', 'distributor', 'importer-sample' => 'distributor-sample',
            'unknown', 'not-recorded' => 'unknown',
            default => null,
        };
    }

    private static function normalizeFlag(mixed $value, string $default): string
    {
        $normalized = self::normalizeVerified($value);

        return $normalized ?? $default;
    }

    private static function normalizePresence(mixed $value): string
    {
        $normalized = strtolower(trim((string) $value));

        return match ($normalized) {
            'present', 'yes', 'true', '1' => 'present',
            default => 'none',
        };
    }

    private static function normalizeCommercial(mixed $value): string
    {
        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, self::COMMERCIAL_RELATIONSHIPS, true) ? $normalized : 'none';
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
