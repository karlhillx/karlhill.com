<?php

namespace App\Support;

use Illuminate\Support\Collection;

class SpeculationRules
{
    /**
     * Prefetch likely destinations from the homepage nav and latest-writing promo.
     *
     * @param  Collection<int, BlogPost>  $latestPosts
     * @return array<string, mixed>
     */
    public static function forHomepage(Collection $latestPosts): array
    {
        $urls = collect(['/blog', '/work', '/about']);

        $urls = $urls->merge(
            $latestPosts->map(fn (BlogPost $post) => '/blog/'.$post->slug)
        );

        return [
            'prefetch' => [
                [
                    'source' => 'list',
                    'urls' => $urls->unique()->values()->all(),
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
     * Prefetch individual posts when a reader explores the writing index.
     *
     * @param  Collection<int, BlogPost>  $posts
     * @return array<string, mixed>
     */
    public static function forBlogIndex(Collection $posts): array
    {
        if ($posts->isEmpty()) {
            return [];
        }

        return [
            'prefetch' => [
                [
                    'source' => 'list',
                    'urls' => $posts->take(3)->map(fn (BlogPost $post) => '/blog/'.$post->slug)->all(),
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
     * Prefetch case studies and sibling projects from the work index.
     *
     * @return array<string, mixed>
     */
    public static function forWorkIndex(): array
    {
        $urls = collect(['/about', '/blog'])
            ->merge(
                ProjectCatalog::withCaseStudies()
                    ->take(3)
                    ->map(fn (array $project) => '/work/'.$project['slug'])
            )
            ->unique()
            ->values()
            ->all();

        return [
            'prefetch' => [
                [
                    'source' => 'list',
                    'urls' => $urls,
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

        return [
            'prefetch' => [
                [
                    'source' => 'list',
                    'urls' => $urls->unique()->values()->all(),
                    'eagerness' => 'moderate',
                ],
            ],
        ];
    }
}
