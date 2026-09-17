<?php

namespace App\Support;

use Carbon\CarbonImmutable;

/**
 * Canonical ScholarlyArticle node for the GeoHorizons GWFMS paper.
 */
final class ScholarlyArticleJsonLd
{
    /**
     * @return array<string, mixed>
     */
    public static function config(): array
    {
        $research = config('site.research', []);

        return is_array($research) ? $research : [];
    }

    public static function path(): string
    {
        $path = self::config()['path'] ?? '/research/global-flood-mapping';

        return is_string($path) && $path !== '' ? $path : '/research/global-flood-mapping';
    }

    public static function url(): string
    {
        return PageMeta::siteUrl().self::path();
    }

    public static function articleId(): string
    {
        return self::url().'#article';
    }

    /**
     * @return array<string, mixed>
     */
    public static function node(?string $personId = null): array
    {
        $research = self::config();
        $siteUrl = PageMeta::siteUrl();
        $personId ??= $siteUrl.'/#person';
        $title = (string) ($research['title'] ?? '');
        $published = is_string($research['date_published'] ?? null)
            ? $research['date_published']
            : '2026-07-07';
        $datePublished = CarbonImmutable::parse($published, 'UTC')->toDateString();
        $doiId = (string) ($research['doi_id'] ?? '10.1144/gh2025-7');
        $doiUrl = (string) ($research['doi'] ?? 'https://doi.org/'.$doiId);

        $credit = is_string($research['credit'] ?? null) ? $research['credit'] : '';

        $authors = collect($research['authors'] ?? [])
            ->filter(fn ($author): bool => is_array($author) && ! empty($author['name']))
            ->map(function (array $author) use ($siteUrl, $personId, $credit): array {
                $self = ($author['self'] ?? false) === true;
                $orcidUrl = self::orcidUrl($author);
                $orcidId = self::orcidId($author);

                $node = [
                    '@type' => 'Person',
                    'name' => $author['name'],
                    'url' => $self ? $siteUrl : ($orcidUrl ?? $siteUrl),
                ];

                if ($self) {
                    $node['@id'] = $personId;
                }

                if ($orcidUrl !== null) {
                    $node['sameAs'] = $orcidUrl;
                }

                if ($orcidId !== null) {
                    $node['identifier'] = [
                        '@type' => 'PropertyValue',
                        'propertyID' => 'ORCID',
                        'value' => $orcidId,
                        'url' => $orcidUrl ?? 'https://orcid.org/'.$orcidId,
                    ];
                }

                if ($self && $credit !== '') {
                    $node['description'] = $credit;
                }

                if (! empty($author['affiliation'])) {
                    $node['affiliation'] = [
                        '@type' => 'Organization',
                        'name' => $author['affiliation'],
                    ];
                }

                return $node;
            })
            ->values()
            ->all();

        $sameAs = array_values(array_filter([
            $doiUrl,
            $research['publisher_html'] ?? null,
            $research['ads'] ?? null,
        ], fn ($url): bool => is_string($url) && $url !== ''));

        $abstract = collect($research['abstract'] ?? [])
            ->filter(fn ($paragraph): bool => is_string($paragraph) && $paragraph !== '')
            ->implode(' ');

        $publisher = [
            '@type' => 'Organization',
            'name' => (string) ($research['publisher'] ?? 'Geological Society of London'),
            'url' => (string) ($research['publisher_url'] ?? 'https://www.geolsoc.org.uk'),
        ];

        $issn = array_values(array_filter([
            $research['issn'] ?? null,
            $research['eissn'] ?? null,
        ], fn ($value): bool => is_string($value) && $value !== ''));

        $periodical = [
            '@type' => 'Periodical',
            'name' => (string) ($research['publication'] ?? 'GeoHorizons'),
            'publisher' => $publisher,
        ];

        if ($issn !== []) {
            $periodical['issn'] = $issn;
        }

        $imagePath = (string) ($research['image'] ?? '/img/ss-geohorizons.png');

        $node = [
            '@type' => 'ScholarlyArticle',
            '@id' => self::articleId(),
            'headline' => $title,
            'name' => $title,
            'url' => self::url(),
            'doi' => $doiId,
            'identifier' => [
                [
                    '@type' => 'PropertyValue',
                    'propertyID' => 'DOI',
                    'value' => $doiId,
                    'url' => $doiUrl,
                ],
            ],
            'sameAs' => $sameAs,
            'datePublished' => $datePublished,
            'inLanguage' => (string) ($research['language'] ?? 'en'),
            'author' => $authors,
            'publisher' => $publisher,
            'license' => (string) ($research['license'] ?? 'https://creativecommons.org/licenses/by/4.0/'),
            'citation' => (string) ($research['citation_full'] ?? $research['citation'] ?? ''),
            'isPartOf' => [
                '@type' => 'PublicationIssue',
                'issueNumber' => (string) ($research['issue'] ?? '1'),
                'isPartOf' => [
                    '@type' => 'PublicationVolume',
                    'volumeNumber' => (string) ($research['volume'] ?? '1'),
                    'isPartOf' => $periodical,
                ],
            ],
            'image' => [
                '@type' => 'ImageObject',
                'url' => $siteUrl.$imagePath,
            ],
        ];

        if ($abstract !== '') {
            $node['abstract'] = $abstract;
        }

        $keywords = collect($research['keywords'] ?? [])
            ->filter(fn ($term): bool => is_string($term) && $term !== '')
            ->values();

        if ($keywords->isNotEmpty()) {
            $node['keywords'] = $keywords->implode(', ');
            $node['about'] = $keywords
                ->map(fn (string $term): array => [
                    '@type' => 'Thing',
                    'name' => $term,
                ])
                ->all();
        }

        if (! empty($research['publisher_pdf'])) {
            $node['encoding'] = [
                '@type' => 'MediaObject',
                'contentUrl' => $research['publisher_pdf'],
                'encodingFormat' => 'application/pdf',
            ];
        }

        if (! empty($research['zenodo'])) {
            $node['hasPart'] = [
                '@id' => self::datasetId(),
            ];
        }

        return array_filter($node, fn ($value): bool => $value !== [] && $value !== null && $value !== '');
    }

    /**
     * @return array<string, mixed>
     */
    public static function pageGraph(): array
    {
        $url = self::url();
        $siteUrl = PageMeta::siteUrl();
        $person = PersonJsonLd::node();
        $article = self::node($person['@id']);
        $seo = config('site.seo.research', []);
        $research = self::config();
        $pageMeta = PageMeta::research();
        $shareImage = [
            '@type' => 'ImageObject',
            'url' => $siteUrl.($research['image'] ?? '/img/ss-geohorizons.png'),
            'contentUrl' => $siteUrl.($research['image'] ?? '/img/ss-geohorizons.png'),
            'caption' => $research['title'] ?? ($seo['title'] ?? 'Research'),
        ];

        $graph = [
            $person,
            $article,
            [
                '@type' => 'WebPage',
                '@id' => $url.'#webpage',
                'url' => $url,
                'name' => $pageMeta->title,
                'description' => $pageMeta->ogDescription ?? $pageMeta->description,
                'inLanguage' => 'en-US',
                'image' => $shareImage,
                'primaryImageOfPage' => $shareImage,
                'isPartOf' => ['@id' => $siteUrl.'/#website'],
                'about' => ['@id' => $article['@id']],
                'mainEntity' => ['@id' => $article['@id']],
                'author' => ['@id' => $person['@id']],
            ],
        ];

        $dataset = self::datasetNode();
        if ($dataset !== null) {
            $graph[] = $dataset;
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph,
        ];
    }

    /**
     * Highwire / Google Scholar citation tags.
     *
     * @return list<array{name: string, content: string}>
     */
    public static function citationMetas(): array
    {
        $research = self::config();
        $published = is_string($research['date_published'] ?? null)
            ? $research['date_published']
            : '2026-07-07';
        $citationDate = str_replace('-', '/', $published);
        $abstract = collect($research['abstract'] ?? [])
            ->filter(fn ($paragraph): bool => is_string($paragraph) && $paragraph !== '')
            ->implode(' ');

        $tags = [
            ['name' => 'citation_title', 'content' => (string) ($research['title'] ?? '')],
            ['name' => 'citation_publication_date', 'content' => $citationDate],
            ['name' => 'citation_date', 'content' => $citationDate],
            ['name' => 'citation_online_date', 'content' => $citationDate],
            ['name' => 'citation_journal_title', 'content' => (string) ($research['publication'] ?? 'GeoHorizons')],
            ['name' => 'citation_publisher', 'content' => (string) ($research['publisher'] ?? 'Geological Society of London')],
            ['name' => 'citation_doi', 'content' => (string) ($research['doi_id'] ?? '')],
            ['name' => 'citation_volume', 'content' => (string) ($research['volume'] ?? '1')],
            ['name' => 'citation_issue', 'content' => (string) ($research['issue'] ?? '1')],
            ['name' => 'citation_id', 'content' => (string) ($research['article_number'] ?? 'gh2025-7')],
            ['name' => 'citation_language', 'content' => (string) ($research['language'] ?? 'en')],
            ['name' => 'citation_fulltext_html_url', 'content' => (string) ($research['publisher_html'] ?? '')],
            ['name' => 'citation_pdf_url', 'content' => (string) ($research['publisher_pdf'] ?? '')],
            ['name' => 'citation_abstract_html_url', 'content' => self::url()],
            ['name' => 'dc.identifier', 'content' => 'doi:'.($research['doi_id'] ?? '')],
        ];

        if (is_string($research['issn'] ?? null) && $research['issn'] !== '') {
            $tags[] = ['name' => 'citation_issn', 'content' => $research['issn']];
        }
        if (is_string($research['eissn'] ?? null) && $research['eissn'] !== '') {
            $tags[] = ['name' => 'citation_issn', 'content' => $research['eissn']];
        }
        if ($abstract !== '') {
            $tags[] = ['name' => 'citation_abstract', 'content' => $abstract];
        }

        $keywords = collect($research['keywords'] ?? [])
            ->filter(fn ($term): bool => is_string($term) && $term !== '')
            ->implode('; ');
        if ($keywords !== '') {
            $tags[] = ['name' => 'citation_keywords', 'content' => $keywords];
        }

        foreach ($research['authors'] ?? [] as $author) {
            if (! is_array($author) || empty($author['family']) || empty($author['given'])) {
                continue;
            }

            $tags[] = [
                'name' => 'citation_author',
                'content' => $author['family'].', '.$author['given'],
            ];

            $orcid = self::orcidId($author);
            if ($orcid !== null) {
                $tags[] = [
                    'name' => 'citation_author_orcid',
                    'content' => 'https://orcid.org/'.$orcid,
                ];
            }
        }

        return array_values(array_filter(
            $tags,
            fn (array $tag): bool => $tag['content'] !== '',
        ));
    }

    public static function datasetId(): string
    {
        $zenodo = self::config()['zenodo'] ?? 'https://doi.org/10.5281/zenodo.15881676';

        return is_string($zenodo) && $zenodo !== '' ? $zenodo : 'https://doi.org/10.5281/zenodo.15881676';
    }

    /**
     * @return array<string, mixed>|null
     */
    protected static function datasetNode(): ?array
    {
        $research = self::config();
        if (empty($research['zenodo']) || empty($research['zenodo_doi'])) {
            return null;
        }

        return [
            '@type' => 'Dataset',
            '@id' => self::datasetId(),
            'name' => 'Data used in publication of “'.($research['title'] ?? 'the paper').'”',
            'description' => (string) ($research['zenodo_description'] ?? ''),
            'url' => $research['zenodo'],
            'identifier' => $research['zenodo'],
            'license' => $research['license'] ?? 'https://creativecommons.org/licenses/by/4.0/',
            'creator' => [
                '@type' => 'Person',
                'name' => 'Frederick S. Policelli',
                'identifier' => [
                    '@type' => 'PropertyValue',
                    'propertyID' => 'ORCID',
                    'value' => '0000-0002-7446-7338',
                    'url' => 'https://orcid.org/0000-0002-7446-7338',
                ],
            ],
            // Google Dataset: isPartOf must be a Dataset or URL, not a
            // ScholarlyArticle stub. citation is the related paper.
            'citation' => (string) ($research['doi'] ?? 'https://doi.org/10.1144/gh2025-7'),
        ];
    }

    /**
     * @param  array<string, mixed>  $author
     */
    protected static function orcidUrl(array $author): ?string
    {
        $id = self::orcidId($author);

        return $id !== null ? 'https://orcid.org/'.$id : null;
    }

    /**
     * @param  array<string, mixed>  $author
     */
    protected static function orcidId(array $author): ?string
    {
        if (is_string($author['orcid'] ?? null) && preg_match('/\d{4}-\d{4}-\d{4}-\d{3}[\dX]/', $author['orcid'], $matches)) {
            return $matches[0];
        }

        $url = (string) ($author['url'] ?? '');
        if (preg_match('/orcid\.org\/(\d{4}-\d{4}-\d{4}-\d{3}[\dX])/', $url, $matches)) {
            return $matches[1];
        }

        return null;
    }
}
