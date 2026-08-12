<?php

use App\Support\HomeStructuredData;

it('homepage structured data describes the person website and blog graph', function () {
    $data = HomeStructuredData::build(collect());
    $graph = $data['@graph'];

    $types = collect($graph)->pluck('@type')->all();
    expect($types)->toContain('Person', 'WebSite', 'Blog');

    $person = collect($graph)->firstWhere('@type', 'Person');
    expect($person['name'])->toBe('Karl Hill')
        ->and($person['@id'])->toEndWith('/#person')
        ->and($person['sameAs'])->toContain('https://www.linkedin.com/in/khill')
        ->and($person['alumniOf'])->toBeArray()
        ->and($person['knowsAbout'])->toContain('DevSecOps');

    expect(collect($person['sameAs'])->implode(' '))->not->toContain('discogs.com');

    $website = collect($graph)->firstWhere('@type', 'WebSite');
    expect($website['alternateName'])->toBe('karlhill.com')
        ->and($website['publisher']['@id'])->toBe($person['@id']);
});

it('homepage html includes brand-disambiguating title and json-ld', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('<title>Karl Hill — Staff Aerospace Software Engineer · NASA · Jacobs</title>', escape: false);
    $response->assertSee('"@type": "WebSite"', escape: false);
    $response->assertSee('"@type": "Person"', escape: false);
    $response->assertSee('NASA Goddard Space Flight Center', escape: false);
});
