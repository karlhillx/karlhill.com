<?php

namespace DryStandard;

use Illuminate\Support\Collection;
use Spatie\YamlFrontMatter\YamlFrontMatter;

final class ReviewRepository
{
    private ?Catalog $catalog = null;

    /** @var Collection<int, Review>|null */
    private ?Collection $published = null;

    public function __construct(private readonly Paths $paths) {}

    /**
     * @return Collection<int, Review>
     */
    public function all(): Collection
    {
        return $this->catalog()->reviews()
            ->sortByDesc(fn (Review $review): string => $review->reviewDate->toDateString())
            ->values();
    }

    /**
     * @return Collection<int, Review>
     */
    public function published(): Collection
    {
        if ($this->published instanceof Collection) {
            return $this->published;
        }

        $order = $this->publishOrder();

        $this->published = $this->catalog()->published()
            ->sortByDesc(function (Review $review) use ($order): string {
                return sprintf(
                    '%010d-%s-%s',
                    $order[$review->slug] ?? 0,
                    $review->modifiedAt()->format('YmdHis'),
                    $review->slug,
                );
            })
            ->values();

        return $this->published;
    }

    /**
     * @return array<string, int>
     */
    public function publishOrder(): array
    {
        $order = [];

        foreach ((new PublishLog($this->paths))->all() as $index => $entry) {
            $slug = (string) ($entry['slug'] ?? '');
            if ($slug !== '') {
                $order[$slug] = $index + 1;
            }
        }

        return $order;
    }

    public function find(string $slug): ?Review
    {
        $review = $this->catalog()->find($slug);

        if ($review instanceof Review && $review->bodyMarkdown !== '') {
            return $review;
        }

        $file = $this->paths->content('reviews'.DIRECTORY_SEPARATOR.$slug.'.md');

        if (! is_file($file)) {
            return $review;
        }

        $document = YamlFrontMatter::parseFile($file);
        $imported = Review::fromMatter($document->matter(), $document->body(), $file);
        $this->catalog()->upsert($imported);

        return $imported;
    }

    public function exists(string $slug): bool
    {
        return $this->catalog()->exists($slug);
    }

    /**
     * Markdown on disk, including image provenance that is not stored in SQLite.
     *
     * @return Collection<int, Review>
     */
    public function fromDisk(): Collection
    {
        $directory = $this->paths->content('reviews');
        $reviews = collect();

        foreach (glob($directory.DIRECTORY_SEPARATOR.'*.md') ?: [] as $file) {
            $document = YamlFrontMatter::parseFile($file);
            $reviews->push(Review::fromMatter($document->matter(), $document->body(), $file));
        }

        return $reviews->sortBy('slug')->values();
    }

    /**
     * @return Collection<int, Review>
     */
    public function byCategory(string $category): Collection
    {
        return $this->published()
            ->filter(fn (Review $review): bool => $review->category === $category)
            ->values();
    }

    /**
     * @return Collection<int, Review>
     */
    public function byBrand(string $brandSlug): Collection
    {
        return $this->published()
            ->filter(fn (Review $review): bool => $review->brandSlug() === $brandSlug)
            ->values();
    }

    /**
     * @return Collection<int, array{slug: string, name: string, reviews: Collection<int, Review>}>
     */
    public function brands(): Collection
    {
        return $this->published()
            ->groupBy(fn (Review $review): string => $review->brandSlug())
            ->map(fn (Collection $reviews, string $slug): array => [
                'slug' => $slug,
                'name' => (string) $reviews->first()?->brand,
                'reviews' => $reviews->values(),
            ])
            ->sortBy('name')
            ->values();
    }

    /**
     * @return Collection<int, Review>
     */
    public function byMethod(string $methodSlug): Collection
    {
        return $this->published()
            ->filter(fn (Review $review): bool => $review->methodKey() === $methodSlug)
            ->values();
    }

    /**
     * Generated style indexes with at least two published bottles.
     *
     * @return Collection<int, array{slug: string, label: string, reviews: Collection<int, Review>}>
     */
    public function styles(): Collection
    {
        return $this->published()
            ->groupBy(fn (Review $review): string => $review->styleSlug())
            ->map(function (Collection $reviews, string $slug): array {
                $first = $reviews->first();

                return [
                    'slug' => $slug,
                    'label' => $first instanceof Review ? $first->styleLabel() : $slug,
                    'reviews' => $reviews->values(),
                ];
            })
            ->filter(fn (array $style): bool => $style['slug'] !== '' && $style['reviews']->count() >= 2)
            ->sortBy(fn (array $style): string => mb_strtolower($style['label']), SORT_NATURAL)
            ->values();
    }

    /**
     * @return Collection<int, Review>
     */
    public function relatedTo(Review $review, int $limit = 4): Collection
    {
        return $this->published()
            ->reject(fn (Review $other): bool => $other->slug === $review->slug)
            ->sortByDesc(function (Review $other) use ($review): float {
                $score = 0.0;

                if ($other->brandSlug() === $review->brandSlug()) {
                    $score += 8;
                }

                if ($review->styleSlug() !== '' && $other->styleSlug() === $review->styleSlug()) {
                    $score += 10;
                }

                if ($review->style && $other->style && mb_strtolower($other->style) === mb_strtolower($review->style)) {
                    $score += 6;
                }

                if ($review->subcategory && $other->subcategory && mb_strtolower($other->subcategory) === mb_strtolower($review->subcategory)) {
                    $score += 4;
                }

                if ($other->category === $review->category) {
                    $score += 3;
                }

                if ($other->methodFacetKey() === $review->methodFacetKey()
                    && ! in_array($review->methodFacetKey(), ['unknown', 'other'], true)) {
                    $score += 2;
                }

                if ($other->productionType === $review->productionType) {
                    $score += 1;
                }

                return $score + (($other->rating ?? 0) / 200);
            })
            ->take($limit)
            ->values();
    }

    public function catalog(): Catalog
    {
        if ($this->catalog instanceof Catalog) {
            return $this->catalog;
        }

        $this->catalog = Catalog::open($this->paths);

        if ($this->catalog->isEmpty()) {
            (new CatalogSync($this->paths, $this->catalog))->run();
            $this->published = null;
        }

        return $this->catalog;
    }
}
