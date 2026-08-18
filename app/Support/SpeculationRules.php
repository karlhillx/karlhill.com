<?php

namespace App\Support;

use Illuminate\Support\Collection;

class SpeculationRules
{
    /**
     * @param  Collection<int, BlogPost>  $latestPosts
     * @return array<string, mixed>
     */
    public static function forHomepage(Collection $latestPosts): array
    {
        $postUrls = $latestPosts->map(fn (BlogPost $post) => '/blog/'.$post->slug);

        return self::document(
            prerender: collect(['/work', '/now'])->merge($postUrls->take(2))->all(),
            prefetch: collect(['/blog', '/work', '/about', '/now'])->merge($postUrls)->all(),
            hrefMatches: '/blog*',
        );
    }

    /**
     * @param  Collection<int, BlogPost>  $posts
     * @return array<string, mixed>
     */
    public static function forBlogIndex(Collection $posts): array
    {
        if ($posts->isEmpty()) {
            return [];
        }

        $top = $posts->take(3)->map(fn (BlogPost $post) => '/blog/'.$post->slug)->all();

        return self::document(
            prerender: array_slice($top, 0, 2),
            prefetch: $top,
            hrefMatches: '/blog/*',
        );
    }

    /**
     * @return array<string, mixed>
     */
    public static function forWorkIndex(): array
    {
        $caseStudies = ProjectCatalog::withCaseStudies()
            ->take(3)
            ->map(fn (array $project) => '/work/'.$project['slug']);

        return self::document(
            prerender: $caseStudies->take(2)->values()->all(),
            prefetch: collect(['/about', '/blog'])->merge($caseStudies)->all(),
            hrefMatches: '/work/*',
        );
    }

    /**
     * @param  array<string, mixed>  $project
     * @param  array<string, mixed>|null  $previous
     * @param  array<string, mixed>|null  $next
     * @return array<string, mixed>
     */
    public static function forCaseStudy(array $project, ?array $previous, ?array $next): array
    {
        $urls = collect(['/work', '/about']);

        if ($previous !== null) {
            $urls->push('/work/'.$previous['slug']);
        }
        if ($next !== null) {
            $urls->push('/work/'.$next['slug']);
        }

        return self::document(
            prerender: $next !== null ? ['/work/'.$next['slug']] : [],
            prefetch: $urls->all(),
        );
    }

    /**
     * @param  Collection<int, BlogPost>  $related
     * @return array<string, mixed>
     */
    public static function forBlogPost(
        BlogPost $post,
        ?BlogPost $previous,
        ?BlogPost $next,
        Collection $related,
    ): array {
        $urls = collect(['/blog']);

        if ($previous !== null) {
            $urls->push('/blog/'.$previous->slug);
        }
        if ($next !== null) {
            $urls->push('/blog/'.$next->slug);
        }

        $urls = $urls->merge(
            $related->map(fn (BlogPost $relatedPost) => '/blog/'.$relatedPost->slug)
        );

        $series = BlogSeries::forPost($post);
        if ($series !== null) {
            if ($series['previous'] !== null) {
                $urls->push('/blog/'.$series['previous']->slug);
            }
            if ($series['next'] !== null) {
                $urls->push('/blog/'.$series['next']->slug);
            }
        }

        $prerender = [];
        if ($series !== null && $series['next'] !== null) {
            $prerender[] = '/blog/'.$series['next']->slug;
        }
        if ($next !== null) {
            $prerender[] = '/blog/'.$next->slug;
        }

        return self::document(
            prerender: $prerender,
            prefetch: $urls->all(),
        );
    }

    /**
     * @param  list<string>  $prerender
     * @param  list<string>  $prefetch
     * @return array<string, mixed>
     */
    protected static function document(array $prerender, array $prefetch, ?string $hrefMatches = null): array
    {
        $prerender = array_values(array_unique($prerender));
        $prefetch = array_values(array_unique($prefetch));

        $prefetchRules = [
            [
                'source' => 'list',
                'urls' => $prefetch,
                'eagerness' => 'moderate',
            ],
        ];

        if ($hrefMatches !== null) {
            $prefetchRules[] = [
                'source' => 'document',
                'where' => [
                    'href_matches' => $hrefMatches,
                ],
                'eagerness' => 'conservative',
            ];
        }

        return array_filter([
            'prerender' => $prerender === [] ? null : [
                [
                    'source' => 'list',
                    'urls' => array_slice($prerender, 0, 2),
                    'eagerness' => 'moderate',
                ],
            ],
            'prefetch' => $prefetchRules,
        ]);
    }
}
