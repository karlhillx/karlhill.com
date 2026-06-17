<?php

namespace App\Support;

use Illuminate\Support\Collection;

class SpeculationRules
{
    /**
     * Prefetch likely destinations from the homepage nav and latest-writing promo.
     *
     * @return array<string, mixed>
     */
    public static function forHomepage(?BlogPost $latestPost): array
    {
        $urls = collect(['/blog', '/work', '/about']);

        if ($latestPost !== null) {
            $urls->push('/blog/'.$latestPost->slug);
        }

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
}
