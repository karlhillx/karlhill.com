<?php

use App\Support\HomeStructuredData;

it('homepage structured data describes the person website and blog graph', function () {
    $data = HomeStructuredData::build(collect());
    $graph = $data['@graph'];

    $types = collect($graph)->pluck('@type')->all();
    expect($types)->toContain('Person', 'WebSite', 'ProfilePage', 'Blog');

    $person = collect($graph)->firstWhere('@type', 'Person');
    expect($person['name'])->toBe('Karl Hill')
        ->and($person['alternateName'])->toContain('Karl M. Hill')
        ->and($person['jobTitle'])->toBe('Staff Aerospace Software Engineer')
        ->and($person['worksFor']['name'])->toBe('Jacobs')
        ->and($person['url'])->toBe('https://karlhill.com')
        ->and($person['mainEntityOfPage']['@id'])->toBe('https://karlhill.com/#profile')
        ->and($person['address']['addressLocality'])->toBe('Washington')
        ->and($person['address']['addressRegion'])->toBe('DC')
        ->and(collect($person['alumniOf'])->pluck('name'))->toContain('NASA Goddard Space Flight Center')
        ->and($person['givenName'])->toBe('Karl')
        ->and($person['familyName'])->toBe('Hill')
        ->and($person['@id'])->toEndWith('/#person')
        ->and($person['description'])->toBe(config('site.person.bio'))
        ->and($person['description'])->toContain('Staff Aerospace Software Engineer')
        ->and($person['description'])->not->toContain('Engineering Manager')
        ->and($person['disambiguatingDescription'])->toContain('Sorry About Your Daughter')
        ->and($person['disambiguatingDescription'])->toContain('Government Issue')
        ->and($person['disambiguatingDescription'])->toContain('not the Scottish novelist')
        ->and($person['sameAs'])->toContain('https://www.linkedin.com/in/khill')
        ->and($person['sameAs'])->toContain('https://github.com/karlhillx')
        ->and($person['sameAs'])->toContain('https://www.discogs.com/artist/1286669-Karl-Hill')
        ->and($person['sameAs'])->toContain('https://orcid.org/0009-0002-6847-3368')
        ->and($person['sameAs'])->toContain('https://www.scilit.com/scholars/019f42b58ad870d181875c7fd187375e')
        ->and($person['sameAs'])->toContain('https://sciprofiles.com/profile/author/MlNoK0RnM3hZUE9BRXNSUnhhclJJZz09')
        ->and($person['sameAs'])->toContain('https://www.wikidata.org/wiki/Q139902938')
        ->and($person['sameAs'])->toContain('https://gravatar.com/karlhillx')
        ->and($person['sameAs'])->toContain('https://www.crunchbase.com/person/karl-hill-09bb')
        ->and($person['sameAs'])->toContain('https://about.me/karlhill')
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
            [
                'https://www.wikidata.org/wiki/Q1476234',
                'https://en.wikipedia.org/wiki/Government_Issue',
            ],
            [
                'https://www.wikidata.org/wiki/Q23138529',
                'https://en.wikipedia.org/wiki/The_Factory_Incident',
            ],
        ])
        ->and($person['alumniOf'])->toBeArray()
        ->and($person['knowsAbout'])->toContain('DevSecOps')
        ->and($person['knowsAbout'])->toContain('Engineering leadership')
        ->and($person['knowsAbout'])->not->toContain('Engineering Manager')
        ->and($person['knowsAbout'])->toContain('Python')
        ->and(collect($person['identifier'])->pluck('propertyID')->all())->toContain('ORCID', 'Wikidata', 'Google Scholar', 'Scilit', 'SciProfiles')
        ->and(collect($person['identifier'])->firstWhere('propertyID', 'Scilit')['value'])->toBe('019f42b58ad870d181875c7fd187375e')
        ->and(collect($person['identifier'])->firstWhere('propertyID', 'SciProfiles')['value'])->toBe('MlNoK0RnM3hZUE9BRXNSUnhhclJJZz09')
        ->and($person['hasOccupation'][0]['@type'])->toBe('Occupation')
        ->and($person['hasCredential'])->toBeArray()->not->toBeEmpty();

    $article = $person['subjectOf'][0];
    expect($article['@type'])->toBe('ScholarlyArticle')
        ->and($article['headline'])->toBe($article['name'])
        ->and($article['datePublished'])->toBe('2026-07-07')
        ->and($article['doi'])->toBe('10.1144/gh2025-7')
        ->and($article['url'])->toEndWith('/research/global-flood-mapping')
        ->and($article['license'])->toContain('creativecommons.org/licenses/by/4.0')
        ->and($article['sameAs'])->toContain('https://doi.org/10.1144/gh2025-7')
        ->and($article['citation'])->toContain('Policelli')
        ->and(collect($article['author'])->pluck('url')->filter())->toHaveCount(4)
        ->and(collect($article['author'])->firstWhere('name', 'Karl M. Hill')['url'])->toContain('karlhill.com')
        ->and(collect($article['author'])->firstWhere('name', 'Karl M. Hill')['identifier']['value'])->toBe('0009-0002-6847-3368');

    expect($person['image']['url'])->toEndWith('/img/profile.jpg')
        ->and($person['image']['width'])->toBe(800);

    $website = collect($graph)->firstWhere('@type', 'WebSite');
    expect($website['alternateName'])->toBe('karlhill.com')
        ->and($website['publisher']['@id'])->toBe($person['@id'])
        ->and($website['image']['url'])->toEndWith('/img/og-home.jpg')
        ->and($website['logo']['url'])->toEndWith('/img/android-chrome-512x512.png');

    $profile = collect($graph)->firstWhere('@type', 'ProfilePage');
    expect($profile['primaryImageOfPage']['url'])->toEndWith('/img/profile.jpg')
        ->and($profile['thumbnailUrl'])->toEndWith('/img/profile.jpg')
        ->and($profile['image']['url'])->toEndWith('/img/profile.jpg');
});

it('homepage html includes a disambiguating title and json-ld', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('<title>Karl Hill · Staff Aerospace Software Engineer</title>', escape: false);
    $response->assertSee('property="og:title" content="Karl Hill · Staff Aerospace Software Engineer"', escape: false);
    $response->assertSee('<span class="hero-shine">Karl Hill</span>', escape: false);
    $response->assertDontSee('<title>Karl Hill</title>', escape: false);
    $response->assertSee('Karl Hill is a Staff Aerospace Software Engineer at Jacobs', escape: false);
    $response->assertSee('NASA Goddard Earth science', escape: false);
    $response->assertSee('"@type": "WebSite"', escape: false);
    $response->assertSee('"@type": "Person"', escape: false);
    $response->assertSee('"@type": "ProfilePage"', escape: false);
    $response->assertSee('/img/og-home.jpg', escape: false);
    $response->assertSee('/img/profile.jpg', escape: false);
    $response->assertSee('property="og:image:type" content="image/jpeg"', escape: false);
    $response->assertSee('href="/img/favicon.svg', escape: false);
    $response->assertSee('type="image/svg+xml"', escape: false);
    $response->assertSee('rel="icon" href="/favicon.ico" sizes="48x48"', escape: false);
    $response->assertSee('sizes="48x48" href="/img/favicon-48x48.png"', escape: false);
    $response->assertSee('sizes="96x96" href="/img/favicon-96x96.png"', escape: false);
    $response->assertSee('sizes="192x192" href="/img/android-chrome-192x192.png"', escape: false);
    $response->assertSee('class="brand-lockup', escape: false);
    $response->assertSee('brand-lockup__mark', escape: false);
    $response->assertSee('>KARL HILL</span>', escape: false);
    $response->assertSee('NASA Goddard Space Flight Center', escape: false);
    $response->assertSee('Washington, DC', escape: false);
    $response->assertSee('"propertyID": "ORCID"', escape: false);
});
