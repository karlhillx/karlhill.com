<?php

namespace DryStandard;

use Illuminate\Support\Collection;
use Spatie\YamlFrontMatter\YamlFrontMatter;

final class ReviewRepository
{
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
        return $this->catalog()->published();
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

                if ($other->category === $review->category) {
                    $score += 3;
                }

                if ($other->methodFacetKey() === $review->methodFacetKey()
                    && $review->methodFacetKey() !== 'unpublished') {
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
        $catalog = Catalog::open($this->paths);

        if ($catalog->isEmpty()) {
            (new CatalogSync($this->paths, $catalog))->run();
        }

        return $catalog;
    }
}
