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
        ->assertSee('application/ld+json', escape: false)
        ->assertDontSee('TDS-0096', escape: false)
        ->assertDontSee('<dt>ID</dt>', escape: false)
        ->assertDontSee('<dt>SKU</dt>', escape: false);

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
    expect($catalog['facets']['categories'])->toContain('wine');
    expect($catalog['reviews'][0])->toHaveKeys(['brand_slug', 'method_slug', 'origin', 'search_text', 'image']);
    expect($catalog['reviews'][0])->not->toHaveKey('id');
    expect($catalog['reviews'][0])->not->toHaveKey('sku');
});

it('serves product stills on reviews and the archive', function () {
    $this->get('/clients/the-dry-standard/reviews/wine/leitz-eins-zwei-zero-riesling/')
        ->assertOk()
        ->assertSee('media/reviews/leitz-eins-zwei-zero-riesling.jpg', escape: false)
        ->assertSee('og:image', escape: false)
        ->assertSee('product-figure--hero', escape: false);

    $this->get('/clients/the-dry-standard/media/reviews/guinness-0-0.jpg')->assertOk();

    $this->get('/clients/the-dry-standard/reviews/')
        ->assertOk()
        ->assertSee('product-figure--thumb', escape: false)
        ->assertSee('media/reviews/lyres-italian-orange.jpg', escape: false);
});

it('keeps a master product table without duplicating published reviews', function () {
    $path = base_path('clients/the-dry-standard/data/master-products.csv');
    expect(is_file($path))->toBeTrue();

    $rows = array_map(fn (string $line): array => str_getcsv($line), file($path, FILE_IGNORE_NEW_LINES) ?: []);
    expect($rows[0])->toBe([
        'ID',
        'SKU',
        'Product',
        'Brand',
        'Category',
        'ABV',
        'Dealcoholized?',
        'Method',
        'Retailer(s)',
        'Times Purchased',
        'First Purchase',
        'Most Recent Purchase',
    ]);
    expect(count($rows))->toBeGreaterThan(90);

    $ids = array_column(array_slice($rows, 1), 0);
    $skus = array_column(array_slice($rows, 1), 1);
    $products = array_column(array_slice($rows, 1), 2);
    expect($ids)->toBe($skus);
    expect($ids)->each->toStartWith('TDS-');
    expect(count($ids))->toBe(count(array_unique($ids)));
    expect($products)->toContain('Be Free Rose Non-Alcoholic Wine');
    expect(count($products))->toBe(count(array_unique($products)));

    $queue = file_get_contents(base_path('clients/the-dry-standard/data/review-queue.yaml')) ?: '';
    expect($queue)->toContain("status: published\n")
        ->and($queue)->toContain('Giesen 0% Sauvignon Blanc')
        ->and(substr_count($queue, "status: queued\n"))->toBeGreaterThan(80);
});

it('builds a searchable review archive', function () {
    $this->get('/clients/the-dry-standard/')
        ->assertOk()
        ->assertSee('header-search', escape: false)
        ->assertSee('Search the cellar', escape: false)
        ->assertSee('ledger-row', escape: false);

    $this->get('/clients/the-dry-standard/reviews/')
        ->assertOk()
        ->assertSee('data-archive', escape: false)
        ->assertSee('Search brand, product, origin, or method', escape: false)
        ->assertSee('data-archive-category', escape: false)
        ->assertSee('data-search=', escape: false)
        ->assertSee('ledger-row', escape: false);

    $this->get('/clients/the-dry-standard/reviews/wine/')
        ->assertOk()
        ->assertSee('data-locked-category="wine"', escape: false)
        ->assertSee('disabled', escape: false);
});
