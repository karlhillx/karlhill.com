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
        $seo = config('site.seo.home');
        $personId = "{$url}/#person";
        $websiteId = "{$url}/#website";

        $personLd = [
            '@type' => 'Person',
            '@id' => $personId,
            'name' => $person['name'],
            'alternateName' => ['karlhillx'],
            'description' => $seo['description'],
            'jobTitle' => $person['job_title'],
            'url' => $url,
            'image' => [
                '@type' => 'ImageObject',
                'url' => "{$url}/img/webp/profile.webp",
                'contentUrl' => "{$url}/img/webp/profile.webp",
            ],
            'email' => 'mailto:'.$person['email'],
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => 'Washington',
                'addressRegion' => 'DC',
                'addressCountry' => 'US',
            ],
            'worksFor' => [
                '@type' => 'Organization',
                'name' => $person['employer'],
                'url' => 'https://www.jacobs.com',
            ],
            'alumniOf' => self::alumniOf(),
            'knowsAbout' => [
                'Cloud-native platforms',
                'DevSecOps',
                'Engineering leadership',
                'Platform engineering',
                'Aerospace software',
                'NASA Earth science software',
                'Flood mapping systems',
                'Release governance',
            ],
            'subjectOf' => [
                [
                    '@type' => 'ScholarlyArticle',
                    'name' => $research['title'],
                    'url' => $research['doi'],
                    'identifier' => $research['doi'],
                    'datePublished' => '2026-05-05',
                    'image' => $url.($research['image'] ?? '/img/ss-geohorizons.png'),
                    'author' => [
                        ['@type' => 'Person', 'name' => 'Frederick S. Policelli'],
                        ['@type' => 'Person', 'name' => 'Albert J. Kettner'],
                        ['@type' => 'Person', 'name' => 'Karl M. Hill'],
                        ['@type' => 'Person', 'name' => 'Devon V. Maloney'],
                    ],
                    'isPartOf' => [
                        '@type' => 'Periodical',
                        'name' => $research['publication'],
                    ],
                ],
            ],
            'sameAs' => config('site.same_as'),
        ];

        $websiteLd = [
            '@type' => 'WebSite',
            '@id' => $websiteId,
            'url' => $url.'/',
            'name' => $person['name'],
            'alternateName' => 'karlhill.com',
            'description' => $seo['description'],
            'inLanguage' => 'en-US',
            'publisher' => ['@id' => $personId],
            'about' => ['@id' => $personId],
        ];

        $profilePageLd = [
            '@type' => 'ProfilePage',
            '@id' => $url.'/#profile',
            'url' => $url.'/',
            'name' => $person['name'].' — Professional profile',
            'description' => $seo['description'],
            'inLanguage' => 'en-US',
            'mainEntity' => ['@id' => $personId],
            'isPartOf' => ['@id' => $websiteId],
        ];

        $blogPostsLd = $posts->map(fn (BlogPost $post) => [
            '@type' => 'BlogPosting',
            'headline' => $post->title,
            'url' => $post->canonicalUrl(),
            'datePublished' => $post->publishedAt->toIso8601String(),
            'description' => $post->excerpt,
            'author' => ['@id' => $personId],
        ])->values()->all();

        $blogLd = [
            '@type' => 'Blog',
            '@id' => "{$url}/blog#blog",
            'name' => 'Karl Hill — Writing',
            'url' => "{$url}/blog",
            'author' => ['@id' => $personId],
            'publisher' => ['@id' => $personId],
            'blogPost' => $blogPostsLd,
        ];

        return [
            '@context' => 'https://schema.org',
            '@graph' => [$personLd, $websiteLd, $profilePageLd, $blogLd],
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    protected static function alumniOf(): array
    {
        $orgs = [
            [
                '@type' => 'Organization',
                'name' => 'NASA Goddard Space Flight Center',
                'url' => 'https://www.nasa.gov/goddard',
            ],
            [
                '@type' => 'Organization',
                'name' => 'Science Systems and Applications, Inc.',
            ],
        ];

        foreach (config('site.education', []) as $entry) {
            if (! is_array($entry) || empty($entry['school'])) {
                continue;
            }

            $orgs[] = [
                '@type' => 'CollegeOrUniversity',
                'name' => $entry['school'],
            ];
        }

        return $orgs;
    }
}
