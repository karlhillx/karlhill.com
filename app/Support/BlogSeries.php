<?php

namespace App\Support;

use Illuminate\Support\Collection;

class BlogSeries
{
    /**
     * @return Collection<string, array{id: string, title: string, description: string, slugs: array<int, string>}>
     */
    public static function all(): Collection
    {
        return collect(config('site.series', []))
            ->map(function (array $series, string $id): array {
                return [
                    'id' => $id,
                    'title' => (string) ($series['title'] ?? $id),
                    'description' => (string) ($series['description'] ?? ''),
                    'slugs' => array_values(array_map('strval', $series['slugs'] ?? [])),
                ];
            });
    }

    /**
     * @return array{id: string, title: string, description: string, slugs: array<int, string>}|null
     */
    public static function find(string $id): ?array
    {
        return self::all()->get($id);
    }

    /**
     * Resolve the series a post belongs to, plus ordered siblings and adjacent posts.
     *
     * @return array{
     *     id: string,
     *     title: string,
     *     description: string,
     *     posts: Collection<int, BlogPost>,
     *     index: int,
     *     previous: ?BlogPost,
     *     next: ?BlogPost
     * }|null
     */
    public static function forPost(BlogPost $post): ?array
    {
        $series = self::all()->first(
            fn (array $candidate) => in_array($post->slug, $candidate['slugs'], true)
        );

        if ($series === null) {
            return null;
        }

        $repository = app(BlogPostRepository::class);
        $posts = collect($series['slugs'])
            ->map(fn (string $slug) => $repository->find($slug))
            ->filter()
            ->values();

        $index = $posts->search(fn (BlogPost $candidate) => $candidate->slug === $post->slug);
        if ($index === false) {
            return null;
        }

        return [
            'id' => $series['id'],
            'title' => $series['title'],
            'description' => $series['description'],
            'posts' => $posts,
            'index' => (int) $index,
            'previous' => $index > 0 ? $posts[$index - 1] : null,
            'next' => $index < $posts->count() - 1 ? $posts[$index + 1] : null,
        ];
    }

    /**
     * Series cards for the writing index (only those with at least one published post).
     *
     * @return Collection<int, array{id: string, title: string, description: string, posts: Collection<int, BlogPost>}>
     */
    public static function published(): Collection
    {
        $repository = app(BlogPostRepository::class);

        return self::all()
            ->map(function (array $series) use ($repository): ?array {
                $posts = collect($series['slugs'])
                    ->map(fn (string $slug) => $repository->find($slug))
                    ->filter()
                    ->values();

                if ($posts->isEmpty()) {
                    return null;
                }

                return [
                    'id' => $series['id'],
                    'title' => $series['title'],
                    'description' => $series['description'],
                    'posts' => $posts,
                ];
            })
            ->filter()
            ->values();
    }
}
