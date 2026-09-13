<?php

use App\Support\HomeStructuredData;

it('homepage structured data describes the person website and blog graph', function () {
    $data = HomeStructuredData::build(collect());
    $graph = $data['@graph'];

    $types = collect($graph)->pluck('@type')->all();
    expect($types)->toContain('Person', 'WebSite', 'ProfilePage', 'Blog');

    $person = collect($graph)->firstWhere('@type', 'Person');
    expect($person['name'])->toBe('Karl Hill')
        ->and($person['givenName'])->toBe('Karl')
        ->and($person['familyName'])->toBe('Hill')
        ->and($person['@id'])->toEndWith('/#person')
        ->and($person['description'])->toContain('Engineering Manager')
        ->and($person['disambiguatingDescription'])->toContain('Sorry About Your Daughter')
        ->and($person['disambiguatingDescription'])->toContain('not the Scottish novelist')
        ->and($person['sameAs'])->toContain('https://www.linkedin.com/in/khill')
        ->and($person['sameAs'])->toContain('https://www.discogs.com/artist/1286669-Karl-Hill')
        ->and($person['sameAs'])->toContain('https://orcid.org/0009-0002-6847-3368')
        ->and($person['sameAs'])->toContain('https://www.wikidata.org/wiki/Q139902938')
        ->and($person['sameAs'])->not->toContain('https://en.wikipedia.org/wiki/Karl_Hill_(musician)')
        ->and($person['identifier'][0]['propertyID'])->toBe('ORCID')
        ->and($person['identifier'][0]['value'])->toBe('0009-0002-6847-3368')
        ->and(collect($person['memberOf'])->pluck('name')->all())->toBe([
            'Sorry About Your Daughter',
            'Government Issue',
            'The Factory Incident',
        ])
        ->and(collect($person['memberOf'])->pluck('sameAs')->all())->toBe([
            'https://www.wikidata.org/wiki/Q30674084',
            'https://www.wikidata.org/wiki/Q1476234',
            'https://www.wikidata.org/wiki/Q23138529',
        ])
        ->and($person['alumniOf'])->toBeArray()
        ->and($person['knowsAbout'])->toContain('DevSecOps')
        ->and($person['knowsAbout'])->toContain('Engineering Manager')
        ->and($person['knowsAbout'])->toContain('Python')
        ->and($person['hasOccupation'][0]['@type'])->toBe('Occupation')
        ->and($person['hasCredential'])->toBeArray()->not->toBeEmpty();

    $article = $person['subjectOf'][0];
    expect($article['@type'])->toBe('ScholarlyArticle')
        ->and($article['headline'])->toBe($article['name'])
        ->and($article['datePublished'])->toMatch('/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}[+-]\d{2}:\d{2}$/')
        ->and(collect($article['author'])->pluck('url')->filter())->toHaveCount(4)
        ->and(collect($article['author'])->firstWhere('name', 'Karl M. Hill')['url'])->toContain('karlhill.com');

    $website = collect($graph)->firstWhere('@type', 'WebSite');
    expect($website['alternateName'])->toBe('karlhill.com')
        ->and($website['publisher']['@id'])->toBe($person['@id']);
});

it('homepage html includes preferred-name title and json-ld', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('<title>Karl Hill — Staff Aerospace Software Engineer · Jacobs</title>', escape: false);
    $response->assertSee('Karl Hill is a Staff Aerospace Software Engineer at Jacobs', escape: false);
    $response->assertSee('"@type": "WebSite"', escape: false);
    $response->assertSee('"@type": "Person"', escape: false);
    $response->assertSee('"@type": "ProfilePage"', escape: false);
    $response->assertSee('NASA Goddard Space Flight Center', escape: false);
    $response->assertSee('Washington, DC', escape: false);
    $response->assertSee('"propertyID": "ORCID"', escape: false);
});
