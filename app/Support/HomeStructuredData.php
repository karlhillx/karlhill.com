<?php

namespace App\Support;

use Illuminate\Support\Collection;

final class HomeStructuredData
{
    /**
     * @param  Collection<int, BlogPost>  $posts
     * @return array<string, mixed>
     */
    public static function build(Collection $posts): array
    {
        $url = PageMeta::siteUrl();
        $person = config('site.person');
        $research = config('site.research');

        $personLd = [
            '@type' => 'Person',
            'name' => $person['name'],
            'jobTitle' => $person['job_title'],
            'url' => $url,
            'image' => "{$url}/img/webp/profile.webp",
            'email' => $person['email'],
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Washington',
                'addressRegion' => 'DC',
                'addressCountry' => 'US',
            ],
            'worksFor' => [
                '@type' => 'Organization',
                'name' => $person['employer'],
            ],
            'subjectOf' => [
                [
                    '@type' => 'ScholarlyArticle',
                    'name' => $research['title'],
                    'url' => $research['doi'],
                    'identifier' => $research['doi'],
                    'datePublished' => '2026-05-05',
                    'isPartOf' => [
                        '@type' => 'Periodical',
                        'name' => $research['publication'],
                    ],
                ],
            ],
            'sameAs' => config('site.same_as'),
        ];

        $blogPostsLd = $posts->map(fn (BlogPost $post) => [
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'url' => $post->canonicalUrl(),
            'datePublished' => $post->publishedAt->toIso8601String(),
            'description' => $post->excerpt,
        ])->values()->all();

        $blogLd = [
            '@type' => 'Blog',
            'name' => 'Karl Hill — Writing',
            'url' => "{$url}/blog",
            'author' => ['@type' => 'Person', 'name' => $person['name'], 'url' => $url],
            'blogPost' => $blogPostsLd,
        ];

        return [
            '@context' => 'https://schema.org',
            '@graph' => [$personLd, $blogLd],
        ];
    }
}
