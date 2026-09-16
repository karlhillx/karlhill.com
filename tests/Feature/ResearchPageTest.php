<?php

use App\Support\ScholarlyArticleJsonLd;

it('serves a canonical publication page with scholarly metadata', function () {
    $response = $this->get('/research/global-flood-mapping');

    $response->assertOk()
        ->assertSee('<title>A web-based high-resolution global water and flood mapping platform — Karl Hill</title>', escape: false)
        ->assertSee('name="citation_title"', escape: false)
        ->assertSee('name="citation_doi" content="10.1144/gh2025-7"', escape: false)
        ->assertSee('name="citation_author" content="Hill, Karl M."', escape: false)
        ->assertSee('name="citation_publication_date" content="2026/07/07"', escape: false)
        ->assertSee('name="citation_author_orcid" content="https://orcid.org/0009-0002-6847-3368"', escape: false)
        ->assertSee('"@type": "ScholarlyArticle"', escape: false)
        ->assertSee('"doi": "10.1144/gh2025-7"', escape: false)
        ->assertSee('"license": "https://creativecommons.org/licenses/by/4.0/"', escape: false)
        ->assertSee('https://floodmapping.gsfc.nasa.gov/', escape: false)
        ->assertSee('https://ui.adsabs.harvard.edu/abs/10.1144/gh2025-7', escape: false)
        ->assertSee('https://doi.org/10.5281/zenodo.15881676', escape: false)
        ->assertSee('https://orcid.org/0009-0002-6847-3368', escape: false)
        ->assertSee('Policelli, F.S.', escape: false)
        ->assertSee('Lead software engineering', escape: false)
        ->assertSee('In the paper’s evaluation', escape: false)
        ->assertSee('/work/flood-mapping-system', escape: false)
        ->assertSee('ss-geohorizons', escape: false)
        ->assertSee('small-flood', escape: false)
        ->assertSee('data-features="reveal"', escape: false);
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
        ->and(collect($node['author'])->firstWhere('name', 'Karl M. Hill')['identifier']['value'])->toBe('0009-0002-6847-3368');

    $graph = ScholarlyArticleJsonLd::pageGraph()['@graph'];
    expect(collect($graph)->pluck('@type')->all())->toContain('Person', 'ScholarlyArticle', 'WebPage', 'Dataset');
});
