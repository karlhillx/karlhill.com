<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

final class ProjectCatalog
{
    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function all(): Collection
    {
        return collect(config('site.projects', []));
    }

    /**
     * @return array<string, mixed>|null
     */
    public static function find(string $slug): ?array
    {
        return self::all()->firstWhere('slug', $slug);
    }

    /**
     * @return array<string, mixed>
     */
    public static function findOrFail(string $slug): array
    {
        $project = self::find($slug);
        if ($project === null || ! self::hasCaseStudy($project)) {
            abort(404);
        }

        return $project;
    }

    /**
     * @param  array<string, mixed>  $project
     */
    public static function hasCaseStudy(array $project): bool
    {
        return is_array($project['case_study'] ?? null) && $project['case_study'] !== [];
    }

    /**
     * @param  array<string, mixed>  $project
     */
    public static function cardUrl(array $project): ?string
    {
        if (self::hasCaseStudy($project)) {
            return route('work.show', ['slug' => $project['slug']]);
        }

        $url = $project['url'] ?? null;

        return is_string($url) && $url !== '' ? $url : null;
    }

    /**
     * @param  array<string, mixed>  $project
     */
    public static function isExternalUrl(array $project): bool
    {
        if (self::hasCaseStudy($project)) {
            return false;
        }

        $url = $project['url'] ?? null;

        return is_string($url) && str_starts_with($url, 'http');
    }

    public static function tagSlug(string $tag): string
    {
        return Str::slug($tag);
    }

    public static function tagFromSlug(string $slug): ?string
    {
        return self::allTags()->first(fn (string $tag) => self::tagSlug($tag) === $slug);
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function filteredByTagSlug(?string $slug): Collection
    {
        if (! is_string($slug) || $slug === '') {
            return self::all();
        }

        $tag = self::tagFromSlug($slug);

        return $tag !== null ? self::filteredByTag($tag) : collect();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function withCaseStudies(): Collection
    {
        return self::all()->filter(fn (array $project) => self::hasCaseStudy($project))->values();
    }

    /**
     * @return Collection<int, string>
     */
    public static function allTags(): Collection
    {
        return self::all()
            ->flatMap(fn (array $project) => $project['tags'] ?? [])
            ->unique()
            ->sort()
            ->values();
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function filteredByTag(?string $tag): Collection
    {
        $projects = self::all();

        if (! is_string($tag) || $tag === '') {
            return $projects;
        }

        return $projects
            ->filter(fn (array $project) => in_array($tag, $project['tags'] ?? [], true))
            ->values();
    }

    /**
     * @return array{previous: ?array<string, mixed>, next: ?array<string, mixed>}
     */
    public static function adjacent(string $slug): array
    {
        $projects = self::withCaseStudies();
        $index = $projects->search(fn (array $project) => $project['slug'] === $slug);

        if ($index === false) {
            return ['previous' => null, 'next' => null];
        }

        return [
            'previous' => $index > 0 ? $projects[$index - 1] : null,
            'next' => $index < $projects->count() - 1 ? $projects[$index + 1] : null,
        ];
    }

    /**
     * @return Collection<int, array<string, mixed>>
     */
    public static function related(array $project, int $limit = 3): Collection
    {
        $tags = $project['tags'] ?? [];

        return self::withCaseStudies()
            ->reject(fn (array $candidate) => $candidate['slug'] === $project['slug'])
            ->sortByDesc(fn (array $candidate) => count(array_intersect($tags, $candidate['tags'] ?? [])))
            ->filter(fn (array $candidate) => count(array_intersect($tags, $candidate['tags'] ?? [])) > 0)
            ->take($limit)
            ->values();
    }

    public static function ogImagePath(string $slug): string
    {
        return public_path("img/og/work/{$slug}.jpg");
    }

    public static function ogImageUrl(string $slug): ?string
    {
        if (! is_file(self::ogImagePath($slug))) {
            return null;
        }

        return rtrim(config('app.url', 'https://karlhill.com'), '/')."/img/og/work/{$slug}.jpg";
    }

    /**
     * @param  array<string, mixed>  $project
     */
    public static function heroImagePath(array $project): string
    {
        $image = $project['image'];

        if (str_starts_with($image, '/img/webp/')) {
            $png = str_replace('/img/webp/', '/img/', $image);
            $png = preg_replace('/\.webp$/i', '.png', $png);
            if (is_file(public_path(ltrim($png, '/')))) {
                return ltrim($png, '/');
            }
        }

        return ltrim($image, '/');
    }
}
