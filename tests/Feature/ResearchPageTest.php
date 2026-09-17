<?php

use App\Support\PageMeta;
use App\Support\ScholarlyArticleJsonLd;

it('serves a canonical publication page with scholarly metadata', function () {
    $response = $this->get('/research/global-flood-mapping');

    $response->assertOk()
        ->assertSee('<title>NASA Global Water and Flood Mapping Research — Karl Hill</title>', escape: false)
        ->assertSee('property="og:title" content="NASA Global Water and Flood Mapping Research — Karl Hill"', escape: false)
        ->assertSee('name="twitter:title" content="NASA Global Water and Flood Mapping Research — Karl Hill"', escape: false)
        ->assertSee('name="citation_title" content="A web-based high-resolution global water and flood mapping platform"', escape: false)
        ->assertSee('name="citation_doi" content="10.1144/gh2025-7"', escape: false)
        ->assertSee('name="citation_author" content="Hill, Karl M."', escape: false)
        ->assertSee('name="citation_publication_date" content="2026/07/07"', escape: false)
        ->assertSee('name="citation_author_orcid" content="https://orcid.org/0009-0002-6847-3368"', escape: false)
        ->assertSee('name="citation_keywords"', escape: false)
        ->assertSee('NASA flood mapping', escape: false)
        ->assertSee('"@type": "ScholarlyArticle"', escape: false)
        ->assertSee('"doi": "10.1144/gh2025-7"', escape: false)
        ->assertSee('"license": "https://creativecommons.org/licenses/by/4.0/"', escape: false)
        ->assertSee('Software (Equal)', escape: false)
        ->assertSee('Writing – review &amp; editing (Equal)', escape: false)
        ->assertSee('https://floodmapping.gsfc.nasa.gov/', escape: false)
        ->assertSee('https://ui.adsabs.harvard.edu/abs/10.1144/gh2025-7', escape: false)
        ->assertSee('https://doi.org/10.5281/zenodo.15881676', escape: false)
        ->assertSee('https://orcid.org/0009-0002-6847-3368', escape: false)
        ->assertSee('Policelli, F.S.', escape: false)
        ->assertSee('Led software engineering', escape: false)
        ->assertSee('NASA-supported experimental website', escape: false)
        ->assertSee('90.9%', escape: false)
        ->assertSee('research-results', escape: false)
        ->assertSee('--reveal-i: 0', escape: false)
        ->assertSee('--reveal-i: 5', escape: false)
        ->assertSee('87.5%', escape: false)
        ->assertSee('74.1%', escape: false)
        ->assertSee('0.80', escape: false)
        ->assertSee('0.67', escape: false)
        ->assertSee('3.5%', escape: false)
        ->assertSee('25.9%', escape: false)
        ->assertSee('collaborative contributions across the research team', escape: false)
        ->assertSee('/work/flood-mapping-system', escape: false)
        ->assertSee('>Work</span>', escape: false)
        ->assertSee('Case study', escape: false)
        ->assertSee('ss-geohorizons', escape: false)
        ->assertSee('small-flood', escape: false)
        ->assertSee('data-features="reveal"', escape: false)
        ->assertSee('Publisher abstract', escape: false);
});

it('redirects /research to the publication page', function () {
    $this->get('/research')
        ->assertRedirect('/research/global-flood-mapping');
});

it('builds complete scholarly article json-ld', function () {
    $node = ScholarlyArticleJsonLd::node();

    expect($node['@type'])->toBe('ScholarlyArticle')
        ->and($node['headline'])->toBe($node['name'])
        ->and($node['doi'])->toBe('10.1144/gh2025-7')
        ->and($node['datePublished'])->toBe('2026-07-07')
        ->and($node['publisher']['name'])->toBe('Geological Society of London')
        ->and($node['isPartOf']['@type'])->toBe('PublicationIssue')
        ->and($node['citation'])->toContain('10.1144/gh2025-7')
        ->and(collect($node['author'])->firstWhere('name', 'Karl M. Hill')['identifier']['value'])->toBe('0009-0002-6847-3368')
        ->and(collect($node['author'])->firstWhere('name', 'Karl M. Hill')['description'])->toBe('Software (Equal); Writing – review & editing (Equal)')
        ->and($node['keywords'])->toContain('NASA flood mapping');

    $graph = ScholarlyArticleJsonLd::pageGraph()['@graph'];
    $dataset = collect($graph)->firstWhere('@type', 'Dataset');

    expect(collect($graph)->pluck('@type')->all())->toContain('Person', 'ScholarlyArticle', 'WebPage', 'Dataset')
        ->and(collect($graph)->firstWhere('@type', 'WebPage')['name'])->toBe(PageMeta::research()->title)
        ->and($dataset['description'])->toBe(config('site.research.zenodo_description'))
        ->and(mb_strlen($dataset['description']))->toBeGreaterThanOrEqual(50)
        ->and($dataset)->not->toHaveKey('isPartOf')
        ->and($dataset['citation'])->toBe(config('site.research.doi'));
});
