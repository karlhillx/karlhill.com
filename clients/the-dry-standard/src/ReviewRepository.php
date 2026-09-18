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
        $directory = $this->paths->content('reviews');

        if (! is_dir($directory)) {
            return collect();
        }

        $files = glob($directory.DIRECTORY_SEPARATOR.'*.md') ?: [];

        return collect($files)
            ->map(fn (string $file): Review => $this->hydrate($file))
            ->sortByDesc(fn (Review $review): string => $review->reviewDate->toDateString())
            ->values();
    }

    /**
     * @return Collection<int, Review>
     */
    public function published(): Collection
    {
        return $this->all()->filter(fn (Review $review): bool => $review->isPublished())->values();
    }

    public function find(string $slug): ?Review
    {
        return $this->all()->first(fn (Review $review): bool => $review->slug === $slug);
    }

    public function exists(string $slug): bool
    {
        return $this->find($slug) !== null;
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
            ->filter(fn (Review $review): bool => Str::slug($review->brand) === $brandSlug)
            ->values();
    }

    /**
     * @return Collection<int, array{slug: string, name: string, reviews: Collection<int, Review>}>
     */
    public function brands(): Collection
    {
        return $this->published()
            ->groupBy(fn (Review $review): string => Str::slug($review->brand))
            ->map(fn (Collection $reviews, string $slug): array => [
                'slug' => $slug,
                'name' => (string) $reviews->first()?->brand,
                'reviews' => $reviews->values(),
            ])
            ->sortBy('name')
            ->values();
    }

    private function hydrate(string $file): Review
    {
        $document = YamlFrontMatter::parseFile($file);

        return Review::fromMatter($document->matter(), $document->body(), $file);
    }
}
