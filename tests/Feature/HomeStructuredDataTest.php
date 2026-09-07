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
        ->and($person['sameAs'])->toContain('https://www.linkedin.com/in/khill')
        ->and($person['sameAs'])->toContain('https://www.discogs.com/artist/1286669-Karl-Hill')
        ->and($person['sameAs'])->toContain('https://en.wikipedia.org/wiki/Karl_Hill_(musician)')
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

it('homepage html includes brand-disambiguating title and json-ld', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('<title>Karl M. Hill — Staff Aerospace Software Engineer · NASA · Jacobs</title>', escape: false);
    $response->assertSee('"@type": "WebSite"', escape: false);
    $response->assertSee('"@type": "Person"', escape: false);
    $response->assertSee('"@type": "ProfilePage"', escape: false);
    $response->assertSee('NASA Goddard Space Flight Center', escape: false);
});
