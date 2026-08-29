<?php

namespace App\Support;

/**
 * Canonical Person node + hire-page graphs for Google and AI crawlers.
 */
final class PersonJsonLd
{
    /**
     * @return array<string, mixed>
     */
    public static function node(): array
    {
        $url = PageMeta::siteUrl();
        $person = config('site.person');
        $research = config('site.research');
        $personId = "{$url}/#person";

        $description = trim(implode(' ', array_filter([
            is_string($person['bio'] ?? null) ? $person['bio'] : null,
            is_string($person['availability'] ?? null) ? $person['availability'] : null,
            is_string($person['trajectory'] ?? null) ? $person['trajectory'] : null,
        ])));

        return [
            '@type' => 'Person',
            '@id' => $personId,
            'name' => $person['name'],
            'givenName' => $person['given_name'] ?? 'Karl',
            'familyName' => $person['family_name'] ?? 'Hill',
            'additionalName' => $person['additional_name'] ?? 'M.',
            'alternateName' => ['Karl M. Hill', 'karlhillx'],
            'description' => $description,
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
            'hasOccupation' => [
                [
                    '@type' => 'Occupation',
                    'name' => $person['job_title'],
                    'occupationLocation' => [
                        '@type' => 'City',
                        'name' => $person['location'],
                    ],
                    'skills' => $person['tagline'] ?? 'Platform engineering, DevSecOps, mission software',
                ],
            ],
            'alumniOf' => self::alumniOf(),
            'knowsAbout' => [
                'Cloud-native platforms',
                'DevSecOps',
                'Engineering leadership',
                'Engineering Manager',
                'Staff to Engineering Manager',
                'Platform engineering',
                'Aerospace software',
                'Defense mission software',
                'High-assurance software',
                'Mission simulation',
                'NASA Earth science software',
                'Flood mapping systems',
                'Release governance',
                'Technical leadership',
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
    }

    /**
     * @return array<string, mixed>
     */
    public static function forNamedPage(string $seoKey, string $path): array
    {
        $url = PageMeta::siteUrl();
        $seo = config('site.seo.'.$seoKey, []);
        $person = self::node();

        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                $person,
                [
                    '@type' => 'WebPage',
                    '@id' => $url.$path.'#webpage',
                    'url' => $url.$path,
                    'name' => $seo['title'] ?? $person['name'],
                    'description' => $seo['description'] ?? $person['description'],
                    'inLanguage' => 'en-US',
                    'isPartOf' => ['@id' => $url.'/#website'],
                    'about' => ['@id' => $person['@id']],
                    'mainEntity' => ['@id' => $person['@id']],
                ],
            ],
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
