<?php

namespace App\Support;

use Illuminate\Support\Collection;

class SpeculationRules
{
    /**
     * Prefetch likely destinations; prerender the highest-probability next hops.
     *
     * @param  Collection<int, BlogPost>  $latestPosts
     * @return array<string, mixed>
     */
    public static function forHomepage(Collection $latestPosts): array
    {
        $prerenderUrls = collect(['/work', '/now'])
            ->merge($latestPosts->take(2)->map(fn (BlogPost $post) => '/blog/'.$post->slug))
            ->unique()
            ->values()
            ->all();

        $prefetchUrls = collect(['/blog', '/work', '/about', '/now'])
            ->merge($latestPosts->map(fn (BlogPost $post) => '/blog/'.$post->slug))
            ->unique()
            ->values()
            ->all();

        return [
            'prerender' => [
                [
                    'source' => 'list',
                    'urls' => $prerenderUrls,
                    'eagerness' => 'moderate',
                ],
            ],
            'prefetch' => [
                [
                    'source' => 'list',
                    'urls' => $prefetchUrls,
                    'eagerness' => 'moderate',
                ],
                [
                    'source' => 'document',
                    'where' => [
                        'href_matches' => '/blog*',
                    ],
                    'eagerness' => 'conservative',
                ],
            ],
        ];
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

        return [
            'prerender' => [
                [
                    'source' => 'list',
                    'urls' => array_slice($top, 0, 2),
                    'eagerness' => 'moderate',
                ],
            ],
            'prefetch' => [
                [
                    'source' => 'list',
                    'urls' => $top,
                    'eagerness' => 'moderate',
                ],
                [
                    'source' => 'document',
                    'where' => [
                        'href_matches' => '/blog/*',
                    ],
                    'eagerness' => 'conservative',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function forWorkIndex(): array
    {
        $caseStudies = ProjectCatalog::withCaseStudies()
            ->take(3)
            ->map(fn (array $project) => '/work/'.$project['slug']);

        $prerenderUrls = $caseStudies->take(2)->values()->all();

        $prefetchUrls = collect(['/about', '/blog'])
            ->merge($caseStudies)
            ->unique()
            ->values()
            ->all();

        return [
            'prerender' => [
                [
                    'source' => 'list',
                    'urls' => $prerenderUrls,
                    'eagerness' => 'moderate',
                ],
            ],
            'prefetch' => [
                [
                    'source' => 'list',
                    'urls' => $prefetchUrls,
                    'eagerness' => 'moderate',
                ],
                [
                    'source' => 'document',
                    'where' => [
                        'href_matches' => '/work/*',
                    ],
                    'eagerness' => 'conservative',
                ],
            ],
        ];
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

        $list = $urls->unique()->values()->all();

        return [
            'prerender' => array_filter([
                $next !== null ? [
                    'source' => 'list',
                    'urls' => ['/work/'.$next['slug']],
                    'eagerness' => 'moderate',
                ] : null,
            ]),
            'prefetch' => [
                [
                    'source' => 'list',
                    'urls' => $list,
                    'eagerness' => 'moderate',
                ],
            ],
        ];
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

        $list = $urls->unique()->values()->all();

        $prerender = [];
        if ($series !== null && $series['next'] !== null) {
            $prerender[] = '/blog/'.$series['next']->slug;
        }
        if ($next !== null) {
            $prerender[] = '/blog/'.$next->slug;
        }
        $prerender = array_values(array_unique($prerender));

        return array_filter([
            'prerender' => $prerender === [] ? null : [
                [
                    'source' => 'list',
                    'urls' => array_slice($prerender, 0, 2),
                    'eagerness' => 'moderate',
                ],
            ],
            'prefetch' => [
                [
                    'source' => 'list',
                    'urls' => $list,
                    'eagerness' => 'moderate',
                ],
            ],
        ]);
    }
}
