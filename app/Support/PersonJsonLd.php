<?php

namespace App\Support;

use Carbon\CarbonImmutable;

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
        $personId = "{$url}/#person";

        $availability = is_string($person['availability'] ?? null) ? $person['availability'] : null;
        $trajectory = is_string($person['trajectory'] ?? null) ? $person['trajectory'] : null;
        if ($trajectory !== null && $trajectory === $availability) {
            $trajectory = null;
        }

        $description = trim(implode(' ', array_filter([
            is_string($person['bio'] ?? null) ? $person['bio'] : null,
            $availability,
            $trajectory,
        ])));

        $disambiguating = is_string($person['disambiguating_description'] ?? null)
            ? $person['disambiguating_description']
            : null;

        $node = [
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
                    'skills' => implode(', ', self::occupationSkills()),
                ],
            ],
            'alumniOf' => self::alumniOf(),
            'hasCredential' => self::credentials(),
            'knowsAbout' => self::knowsAbout(),
            'subjectOf' => [
                self::scholarlyArticle($url, $personId),
            ],
            'sameAs' => config('site.same_as'),
        ];

        if ($disambiguating !== null && $disambiguating !== '') {
            $node['disambiguatingDescription'] = $disambiguating;
        }

        return $node;
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
                    '@type' => 'ProfilePage',
                    '@id' => $url.$path.'#profile',
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
     * @return array<string, mixed>
     */
    protected static function scholarlyArticle(string $siteUrl, string $personId): array
    {
        $research = config('site.research');
        $published = is_string($research['date_published'] ?? null)
            ? $research['date_published']
            : '2026-05-05';
        $datePublished = CarbonImmutable::parse($published, 'UTC')->toIso8601String();
        $title = $research['title'];

        $authors = collect($research['authors'] ?? [])
            ->filter(fn ($author): bool => is_array($author) && ! empty($author['name']))
            ->map(function (array $author) use ($siteUrl, $personId): array {
                $node = [
                    '@type' => 'Person',
                    'name' => $author['name'],
                    'url' => ($author['self'] ?? false) === true
                        ? $siteUrl
                        : ($author['url'] ?? $siteUrl),
                ];

                if (($author['self'] ?? false) === true) {
                    $node['@id'] = $personId;
                }

                return $node;
            })
            ->values()
            ->all();

        return [
            '@type' => 'ScholarlyArticle',
            'headline' => $title,
            'name' => $title,
            'url' => $research['doi'],
            'identifier' => $research['doi'],
            'datePublished' => $datePublished,
            'image' => [
                '@type' => 'ImageObject',
                'url' => $siteUrl.($research['image'] ?? '/img/ss-geohorizons.png'),
            ],
            'author' => $authors,
            'isPartOf' => [
                '@type' => 'Periodical',
                'name' => $research['publication'],
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

    /**
     * @return list<string>
     */
    protected static function knowsAbout(): array
    {
        $concepts = [
            'Cloud-native platforms',
            'DevSecOps',
            'Engineering leadership',
            'Engineering Manager',
            'Staff to Engineering Manager',
            'Platform engineering',
            'Kubernetes',
            'CI/CD',
            'Developer tooling',
            'Aerospace software',
            'Defense mission software',
            'High-assurance software',
            'Mission simulation',
            'NASA Earth science software',
            'Flood mapping systems',
            'Release governance',
            'Technical leadership',
        ];

        $fromConfig = collect(config('site.skills', []))
            ->merge(config('site.stack', []))
            ->pluck('skills')
            ->flatten();

        return collect($concepts)
            ->merge($fromConfig)
            ->filter(fn ($term): bool => is_string($term) && $term !== '')
            ->unique()
            ->values()
            ->all();
    }

    /**
     * @return list<string>
     */
    protected static function occupationSkills(): array
    {
        $person = config('site.person');
        $tagline = $person['tagline'] ?? 'Platform engineering, DevSecOps, mission software';
        $fromTagline = collect(explode('|', (string) $tagline))
            ->map(fn (string $part): string => trim($part))
            ->filter();

        $fromStack = collect(config('site.stack', []))
            ->pluck('skills')
            ->flatten()
            ->filter(fn ($skill): bool => is_string($skill) && $skill !== '');

        return $fromTagline->merge($fromStack)->unique()->values()->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected static function credentials(): array
    {
        return collect(config('site.certifications', []))
            ->filter(fn ($entry): bool => is_array($entry) && ! empty($entry['name']))
            ->map(function (array $entry): array {
                $credential = [
                    '@type' => 'EducationalOccupationalCredential',
                    'name' => $entry['name'],
                    'credentialCategory' => $entry['abbr'] ?? $entry['name'],
                ];

                if (! empty($entry['issuer'])) {
                    $credential['recognizedBy'] = [
                        '@type' => 'Organization',
                        'name' => $entry['issuer'],
                    ];
                }

                if (! empty($entry['url'])) {
                    $credential['url'] = $entry['url'];
                }

                return $credential;
            })
            ->values()
            ->all();
    }
}
