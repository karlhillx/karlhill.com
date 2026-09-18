<?php

beforeEach(function () {
    $this->artisan('dry-standard:build')->assertSuccessful();
});

it('lists The Dry Standard on the clients index', function () {
    $this->get('/clients')
        ->assertOk()
        ->assertSee('the-dry-standard', escape: false)
        ->assertSee('The Dry Standard', escape: false);
});

it('serves the Dry Standard homepage and primary sections', function () {
    $this->get('/clients/the-dry-standard/')
        ->assertOk()
        ->assertSee('The standard for what remains after the alcohol is gone.', escape: false)
        ->assertSee('0.5% ABV', escape: false)
        ->assertSee('<base href="/clients/the-dry-standard/">', escape: false);

    foreach ([
        '/clients/the-dry-standard/reviews/',
        '/clients/the-dry-standard/reviews/wine/',
        '/clients/the-dry-standard/reviews/beer/',
        '/clients/the-dry-standard/reviews/spirits/',
        '/clients/the-dry-standard/reviews/cocktails/',
        '/clients/the-dry-standard/reviews/cider/',
        '/clients/the-dry-standard/guides/',
        '/clients/the-dry-standard/brands/',
        '/clients/the-dry-standard/methods/',
        '/clients/the-dry-standard/about/',
    ] as $url) {
        $this->get($url)->assertOk();
    }
});

it('serves sourced sample reviews with dealcoholized badges', function () {
    $this->get('/clients/the-dry-standard/reviews/wine/leitz-eins-zwei-zero-riesling/')
        ->assertOk()
        ->assertSee('Dealcoholized: Yes', escape: false)
        ->assertSee('Vacuum distillation', escape: false)
        ->assertSee('Weingut Leitz', escape: false)
        ->assertSee('application/ld+json', escape: false);

    $this->get('/clients/the-dry-standard/reviews/cocktails/lyres-italian-orange/')
        ->assertOk()
        ->assertSee('formulated as a zero-proof alternative', escape: false)
        ->assertSee('lyres.com/pages/faqs', escape: false);
});

it('exposes a feed, sitemap, and catalog for the client site', function () {
    $feed = $this->get('/clients/the-dry-standard/feed.xml')->assertOk();
    expect($feed->streamedContent())->toContain('Leitz Eins-Zwei-Zero Riesling');

    $sitemap = $this->get('/clients/the-dry-standard/sitemap.xml')->assertOk();
    expect($sitemap->streamedContent())->toContain('reviews/wine/leitz-eins-zwei-zero-riesling');

    $catalogResponse = $this->get('/clients/the-dry-standard/catalog.json')->assertOk();
    $catalog = json_decode($catalogResponse->streamedContent(), true, flags: JSON_THROW_ON_ERROR);
    expect($catalog['reviews'])->toBeArray()->not->toBeEmpty();
});
