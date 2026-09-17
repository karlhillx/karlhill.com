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
        $personId = "{$url}/#person";

        // Identity only. Next-role copy stays on kit, llms.txt, and the
        // hire packet — not on the Person node, About, or /now.
        $description = is_string($person['bio'] ?? null) ? $person['bio'] : '';

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
            'mainEntityOfPage' => [
                '@id' => $url.'/#profile',
            ],
            'image' => [
                '@type' => 'ImageObject',
                // JPEG portrait for Googlebot-Image. The hero uses WebP; Search
                // thumbnails and Person rich results prefer a crawlable raster.
                'url' => "{$url}/img/profile.jpg",
                'contentUrl' => "{$url}/img/profile.jpg",
                'width' => 800,
                'height' => 800,
                'caption' => $person['name'],
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
            'identifier' => self::identifiers(),
            'memberOf' => self::memberOf(),
            'subjectOf' => [
                ScholarlyArticleJsonLd::node($personId),
            ],
            'sameAs' => config('site.same_as'),
        ];

        $node = array_filter($node, fn ($value): bool => $value !== [] && $value !== null);

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
        $shareImage = [
            '@type' => 'ImageObject',
            'url' => "{$url}/img/og-home.jpg",
            'contentUrl' => "{$url}/img/og-home.jpg",
            'width' => 1200,
            'height' => 630,
            'caption' => $seo['title'] ?? $person['name'],
        ];

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
                    'image' => $shareImage,
                    'primaryImageOfPage' => $shareImage,
                    'thumbnailUrl' => "{$url}/img/og-home.jpg",
                    'isPartOf' => ['@id' => $url.'/#website'],
                    'about' => ['@id' => $person['@id']],
                    'mainEntity' => ['@id' => $person['@id']],
                ],
            ],
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected static function identifiers(): array
    {
        $identifiers = [];

        $orcid = collect(config('site.social', []))
            ->first(fn ($link): bool => is_array($link) && ($link['icon'] ?? '') === 'orcid');

        if (is_array($orcid) && ! empty($orcid['url'])) {
            $url = rtrim((string) $orcid['url'], '/');
            preg_match('/\d{4}-\d{4}-\d{4}-\d{3}[\dX]/', $url, $matches);

            $identifier = [
                '@type' => 'PropertyValue',
                'propertyID' => 'ORCID',
                'url' => $url,
            ];

            if (! empty($matches[0])) {
                $identifier['value'] = $matches[0];
            }

            $identifiers[] = $identifier;
        }

        foreach (config('site.same_as', []) as $url) {
            if (! is_string($url) || ! preg_match('#wikidata\.org/wiki/(Q\d+)#', $url, $matches)) {
                continue;
            }

            $identifiers[] = [
                '@type' => 'PropertyValue',
                'propertyID' => 'Wikidata',
                'value' => $matches[1],
                'url' => $url,
            ];
            break;
        }

        $scholar = collect(config('site.social', []))
            ->first(fn ($link): bool => is_array($link) && ($link['icon'] ?? '') === 'scholar');

        if (is_array($scholar) && ! empty($scholar['url'])) {
            $url = (string) $scholar['url'];
            preg_match('/[?&]user=([^&]+)/', $url, $matches);

            $identifier = [
                '@type' => 'PropertyValue',
                'propertyID' => 'Google Scholar',
                'url' => $url,
            ];

            if (! empty($matches[1])) {
                $identifier['value'] = $matches[1];
            }

            $identifiers[] = $identifier;
        }

        $scilit = collect(config('site.social', []))
            ->first(fn ($link): bool => is_array($link) && ($link['icon'] ?? '') === 'scilit');

        if (is_array($scilit) && ! empty($scilit['url'])) {
            $url = rtrim((string) $scilit['url'], '/');
            preg_match('#scilit\.com/scholars/([a-f0-9]+)#i', $url, $matches);

            $identifier = [
                '@type' => 'PropertyValue',
                'propertyID' => 'Scilit',
                'url' => $url,
            ];

            if (! empty($matches[1])) {
                $identifier['value'] = $matches[1];
            }

            $identifiers[] = $identifier;
        }

        $sciProfiles = collect(config('site.social', []))
            ->first(fn ($link): bool => is_array($link) && ($link['icon'] ?? '') === 'sciprofiles');

        if (is_array($sciProfiles) && ! empty($sciProfiles['url'])) {
            $url = rtrim((string) $sciProfiles['url'], '/');
            preg_match('~sciprofiles\.com/profile/author/([A-Za-z0-9+/=]+)~i', $url, $matches);

            $identifier = [
                '@type' => 'PropertyValue',
                'propertyID' => 'SciProfiles',
                'url' => $url,
            ];

            if (! empty($matches[1])) {
                $identifier['value'] = $matches[1];
            }

            $identifiers[] = $identifier;
        }

        return $identifiers;
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected static function memberOf(): array
    {
        return collect(config('site.person.bands', []))
            ->filter(fn ($band): bool => is_array($band) && ! empty($band['name']))
            ->map(function (array $band): array {
                $node = [
                    '@type' => 'MusicGroup',
                    'name' => $band['name'],
                ];

                if (! empty($band['same_as'])) {
                    $node['sameAs'] = $band['same_as'];
                }

                return $node;
            })
            ->values()
            ->all();
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
            'Platform engineering',
            'Kubernetes',
            'CI/CD',
            'Developer tooling',
            'Aerospace software',
            'Defense mission software',
            'High-assurance software',
            'Mission simulation',
            'NASA Earth science software',
            'NASA flood mapping',
            'Flood mapping systems',
            'Global Water and Flood Mapping System',
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
        $tagline = $person['tagline'] ?? 'Software engineering, technical leadership, and delivery';
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
