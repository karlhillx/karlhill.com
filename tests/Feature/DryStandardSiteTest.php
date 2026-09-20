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
        '/clients/the-dry-standard/learn/',
        '/clients/the-dry-standard/brands/',
        '/clients/the-dry-standard/methods/',
        '/clients/the-dry-standard/about/',
        '/clients/the-dry-standard/privacy/',
        '/clients/the-dry-standard/styles/',
        '/clients/the-dry-standard/industry/',
        '/clients/the-dry-standard/industry/samples/',
        '/clients/the-dry-standard/industry/submit/',
        '/clients/the-dry-standard/industry/partnerships/',
    ] as $url) {
        $this->get($url)->assertOk();
    }
});

it('serves sourced sample reviews with production-type badges', function () {
    $this->get('/clients/the-dry-standard/reviews/wine/leitz-eins-zwei-zero-riesling/')
        ->assertOk()
        ->assertSee('Production type: Dealcoholized', escape: false)
        ->assertSee('class="identity"', escape: false)
        ->assertSee('Sourced production type', escape: false)
        ->assertSee('Vacuum distillation', escape: false)
        ->assertSee('Weingut Leitz', escape: false)
        ->assertSee('id="how-it-was-made"', escape: false)
        ->assertSee('<h2>The wine</h2>', escape: false)
        ->assertDontSee('Product overview', escape: false)
        ->assertSee('application/ld+json', escape: false)
        ->assertSee('"gtin":"4260196280136"', escape: false)
        ->assertSee('alcoholContent', escape: false)
        ->assertDontSee('TDS-0096', escape: false)
        ->assertDontSee('<dt>ID</dt>', escape: false)
        ->assertDontSee('<dt>EAN</dt>', escape: false);

    $this->get('/clients/the-dry-standard/reviews/cocktails/lyres-italian-orange/')
        ->assertOk()
        ->assertSee('Production type: Alternative', escape: false)
        ->assertSee('Formulated as a zero-proof alternative', escape: false)
        ->assertSee('class="identity-value">Formulated</span>', escape: false)
        ->assertSee('lyres.com/pages/faqs', escape: false)
        ->assertSee('<h2>The drink</h2>', escape: false)
        ->assertDontSee('Product overview', escape: false)
        ->assertDontSee('abv, method', escape: false)
        ->assertDontSee('class="source-claims"', escape: false);

    $this->get('/clients/the-dry-standard/reviews/wine/st-regis-non-alcoholic-rose/')
        ->assertOk()
        ->assertSee('<h2>The wine</h2>', escape: false)
        ->assertSee('At a glance', escape: false)
        ->assertSee('<dt>Flavor profile</dt>', escape: false)
        ->assertSee('<dt>Structure</dt>', escape: false)
        ->assertSee('How wine-like is it?', escape: false)
        ->assertSee('balsamic', escape: false)
        ->assertSee('id="provenance"', escape: false)
        ->assertSee('Evidence', escape: false)
        ->assertSee('provenance-summary-meta', escape: false)
        ->assertDontSee('Product overview', escape: false);
});

it('exposes a feed, sitemap, and catalog for the client site', function () {
    $feed = $this->get('/clients/the-dry-standard/feed.xml')->assertOk();
    expect($feed->getContent())->toContain('Leitz Eins-Zwei-Zero Riesling');

    $sitemap = $this->get('/clients/the-dry-standard/sitemap.xml')->assertOk();
    expect($sitemap->getContent())->toContain('reviews/wine/leitz-eins-zwei-zero-riesling')
        ->and($sitemap->getContent())->toContain('best/')
        ->and($sitemap->getContent())->toContain('styles/riesling')
        ->and($sitemap->getContent())->toContain('industry/submit')
        ->and($sitemap->getContent())->toContain('privacy/');

    $catalogResponse = $this->get('/clients/the-dry-standard/catalog.json')->assertOk();
    $catalog = json_decode($catalogResponse->getContent(), true, flags: JSON_THROW_ON_ERROR);
    expect($catalog['reviews'])->toBeArray()->not->toBeEmpty();
    expect($catalog['version'])->toBe(1);
    expect($catalog['facets']['categories'])->toContain('wine');
    expect($catalog['facets']['brands'])->not->toBeEmpty();
    expect($catalog['facets']['abv'])->not->toBeEmpty();
    expect($catalog['reviews'][0])->toHaveKeys(['brand_slug', 'method_slug', 'origin', 'search_text', 'image', 'abv_bucket']);
    expect($catalog['reviews'][0])->not->toHaveKey('id');
    expect($catalog['reviews'][0])->not->toHaveKey('ean');
});

it('serves product stills on reviews and the archive', function () {
    $this->get('/clients/the-dry-standard/reviews/wine/leitz-eins-zwei-zero-riesling/')
        ->assertOk()
        ->assertSee('media/reviews/leitz-eins-zwei-zero-riesling.jpg', escape: false)
        ->assertSee('og:image', escape: false)
        ->assertSee('product-figure--hero', escape: false);

    $this->get('/clients/the-dry-standard/media/reviews/guinness-0-0.jpg')->assertOk();
    $this->get('/clients/the-dry-standard/media/reviews/guinness-0-0.webp')->assertOk();
    $this->get('/clients/the-dry-standard/media/reviews/guinness-0-0-400.webp')->assertOk();

    $this->get('/clients/the-dry-standard/reviews/')
        ->assertOk()
        ->assertSee('product-figure--thumb', escape: false)
        ->assertSee('media/reviews/', escape: false);
});

it('keeps a master product table without duplicating published reviews', function () {
    $path = base_path('clients/the-dry-standard/data/master-products.csv');
    expect(is_file($path))->toBeTrue();

    $rows = array_map(fn (string $line): array => str_getcsv($line), file($path, FILE_IGNORE_NEW_LINES) ?: []);
    expect($rows[0])->toBe([
        'ID',
        'EAN',
        'Product',
        'Brand',
        'Category',
        'ABV',
        'Production Type',
        'Verified',
        'Method',
        'Retailer(s)',
    ]);
    expect(count($rows))->toBeGreaterThan(90);

    $ids = array_column(array_slice($rows, 1), 0);
    $products = array_column(array_slice($rows, 1), 2);
    $brands = array_column(array_slice($rows, 1), 3);
    $byId = [];
    foreach (array_slice($rows, 1) as $row) {
        $byId[$row[0]] = $row;
    }
    expect($ids)->each->toStartWith('TDS-');
    expect(count($ids))->toBe(count(array_unique($ids)));
    expect($byId['TDS-0001'][2])->toBe('Rosé');
    expect($byId['TDS-0001'][1])->toBe('4003301079788');
    expect($byId['TDS-0003'][2])->toBe('Chardonnay');
    expect($byId['TDS-0003'][1])->toBe('4049366003207');
    expect($byId['TDS-0004'][2])->toBe('White Sparkling');
    expect($byId['TDS-0002'][2])->toBe('Vanish Riesling');
    expect($byId['TDS-0006'][2])->toBe('Chardonnay');
    expect($byId['TDS-0097'][2])->toBe('Guinness 0.0');
    expect($products)->toContain('Rosé');
    $brandProduct = array_map(fn (int $i): string => mb_strtolower($brands[$i])."\0".$products[$i], array_keys($products));
    expect(count($brandProduct))->toBe(count(array_unique($brandProduct)));

    $queue = file_get_contents(base_path('clients/the-dry-standard/data/review-queue.yaml')) ?: '';
    expect($queue)->toContain("status: published\n")
        ->and($queue)->toContain('0% Sauvignon Blanc')
        ->and($queue)->not->toContain("status: queued\n");
});

it('links related reviews and exposes directory search', function () {
    $this->get('/clients/the-dry-standard/reviews/wine/leitz-eins-zwei-zero-riesling/')
        ->assertOk()
        ->assertSee('Other Riesling', escape: false)
        ->assertSee('brands/leitz/', escape: false)
        ->assertSee('methods/vacuum-distillation/', escape: false)
        ->assertSee('Dealcoholized', escape: false);

    $this->get('/clients/the-dry-standard/brands/')
        ->assertOk()
        ->assertSee('data-directory', escape: false)
        ->assertSee('data-directory-q', escape: false)
        ->assertSee('directory-row', escape: false);

    $this->get('/clients/the-dry-standard/brands/leitz/')
        ->assertOk()
        ->assertSee('<h1>Leitz</h1>', escape: false)
        ->assertDontSee('<h1>brand</h1>', escape: false);

    $this->get('/clients/the-dry-standard/methods/vacuum-distillation/')
        ->assertOk()
        ->assertSee('Reviewed with this method', escape: false)
        ->assertSee('reviewed with this method', escape: false)
        ->assertSee('leitz-eins-zwei-zero-riesling', escape: false);

    $this->get('/clients/the-dry-standard/styles/')
        ->assertOk()
        ->assertSee('Riesling', escape: false)
        ->assertSee('styles/riesling/', escape: false);

    $this->get('/clients/the-dry-standard/styles/riesling/')
        ->assertOk()
        ->assertSee('leitz-eins-zwei-zero-riesling', escape: false)
        ->assertDontSee('Guinness 0.0', escape: false);
});

it('builds a searchable review archive', function () {
    $this->get('/clients/the-dry-standard/')
        ->assertOk()
        ->assertSee('header-search', escape: false)
        ->assertSee('Search the cellar', escape: false)
        ->assertSee('review-card', escape: false);

    $this->get('/clients/the-dry-standard/reviews/')
        ->assertOk()
        ->assertSee('data-archive', escape: false)
        ->assertSee('archive-sidebar', escape: false)
        ->assertSee('data-archive-brand', escape: false)
        ->assertSee('data-archive-abv', escape: false)
        ->assertSee('data-archive-category', escape: false)
        ->assertSee('data-facet="abv"', escape: false)
        ->assertSee('data-facet="brand"', escape: false)
        ->assertSee('data-facet="production"', escape: false)
        ->assertSee('data-facet="method"', escape: false)
        ->assertSee('data-facet="country"', escape: false)
        ->assertSee('Formulated (no removal)', escape: false)
        ->assertDontSee('data-facet="partials/facet-group"', escape: false)
        ->assertSee('data-abv=', escape: false)
        ->assertSee('data-search=', escape: false)
        ->assertSee('review-card', escape: false)
        ->assertSee('pagination', escape: false)
        ->assertSee('page=2', escape: false);

    $this->get('/clients/the-dry-standard/reviews/?q=leitz')
        ->assertOk()
        ->assertSee('Leitz', escape: false)
        ->assertDontSee('Guinness 0.0', escape: false);

    $this->get('/clients/the-dry-standard/reviews/wine/')
        ->assertOk()
        ->assertSee('data-locked-category="wine"', escape: false)
        ->assertDontSee('data-archive-category', escape: false);
});

it('hides source claim tokens and ships search + share metadata', function () {
    $home = $this->get('/clients/the-dry-standard/')->assertOk();
    $home->assertSee('og:image', escape: false)
        ->assertSee('SearchAction', escape: false)
        ->assertSee('Best of the cellar', escape: false);

    $this->get('/clients/the-dry-standard/about/')
        ->assertOk()
        ->assertSee('How published scores sit on the 100-point scale', escape: false);

    $this->get('/clients/the-dry-standard/privacy/')
        ->assertOk()
        ->assertSee('Privacy policy', escape: false)
        ->assertSee('drinkdrystandard@gmail.com', escape: false)
        ->assertSee('session cookie', escape: false)
        ->assertSee('not published automatically', escape: false)
        ->assertSee('"@type":"PrivacyPolicy"', escape: false)
        ->assertDontSee('Google Analytics', escape: false);

    $this->get('/clients/the-dry-standard/best/')
        ->assertOk()
        ->assertSee('What holds up in the glass', escape: false)
        ->assertSee('Guinness 0.0', escape: false);

    $this->get('/clients/the-dry-standard/guides/buying-na-spirits/')
        ->assertOk()
        ->assertSee('How to buy a non-alcoholic spirit', escape: false);
});

it('does not duplicate catalog IDs or publish inventory bundles', function () {
    $this->get('/clients/the-dry-standard/reviews/')
        ->assertDontSee('Oddbird Non-Alcoholic Wine Bundle', escape: false);

    $pdo = new PDO('sqlite:'.base_path('clients/the-dry-standard/data/catalog.sqlite'));
    $ids = $pdo->query('SELECT id FROM products WHERE id IS NOT NULL AND id != ""')->fetchAll(PDO::FETCH_COLUMN);
    expect($ids)->toHaveCount(count(array_unique($ids)));
    expect($ids)->toContain('TDS-0074')->not->toContain('R4');
});

it('serves Dry Standard HTML without a session cookie', function () {
    $response = $this->get('/clients/the-dry-standard/');

    $response->assertOk()
        ->assertHeader('X-Robots-Tag', 'noindex, nofollow');

    expect($response->headers->get('Cache-Control'))->toContain('public');
    expect($response->headers->get('Set-Cookie'))->toBeNull();
});

it('normalizes United States on review pages', function () {
    $this->get('/clients/the-dry-standard/reviews/spirits/ritual-zero-proof-tequila/')
        ->assertOk()
        ->assertSee('United States', escape: false);
});

it('serves a Dry Standard 404 instead of the parent chrome', function () {
    $this->get('/clients/the-dry-standard/reviews/wine/not-a-real-bottle/')
        ->assertNotFound()
        ->assertSee('That bottle is not on the shelf', escape: false)
        ->assertSee('Browse the cellar', escape: false)
        ->assertDontSee('Book a conversation', escape: false);
});

it('disallows crawlers while staged and puts Best in the primary nav', function () {
    $this->get('/clients/the-dry-standard/robots.txt')
        ->assertOk()
        ->assertSee('Disallow: /', escape: false);

    $this->get('/clients/the-dry-standard/')
        ->assertOk()
        ->assertSee('>Best</a>', escape: false)
        ->assertSee('>Compare</a>', escape: false)
        ->assertSee('>Brands</a>', escape: false)
        ->assertSee('>How it’s made</a>', escape: false)
        ->assertSee('>Styles</a>', escape: false)
        ->assertSee('Find a bottle by how it was made', escape: false)
        ->assertDontSee('stats-grid', escape: false)
        ->assertSee('fonts/fraunces.woff2', escape: false)
        ->assertDontSee('fonts.googleapis.com', escape: false);
});

it('serves bottle compare for two to four slugs', function () {
    $this->get('/clients/the-dry-standard/compare/')
        ->assertOk()
        ->assertSee('Compare bottles', escape: false)
        ->assertSee('Style clusters ready to weigh', escape: false);

    $this->get('/clients/the-dry-standard/compare/?slugs=guinness-0-0,athletic-brewing-run-wild-ipa,halfway-crooks-brevet-ipa')
        ->assertOk()
        ->assertSee('Guinness', escape: false)
        ->assertSee('Run Wild', escape: false)
        ->assertSee('Brevet IPA', escape: false)
        ->assertSee('>Score</th>', escape: false)
        ->assertSee('>Flavor</th>', escape: false)
        ->assertSee('>Structure</th>', escape: false)
        ->assertSee('compare-table', escape: false);
});

it('labels Evidence sources without Unspecified source', function () {
    $this->get('/clients/the-dry-standard/reviews/wine/ohla-rose/')
        ->assertOk()
        ->assertSee('Evidence', escape: false)
        ->assertSee('Retail listing', escape: false)
        ->assertSee('morewines.com', escape: false)
        ->assertDontSee('Unspecified source', escape: false)
        ->assertSee('score-band', escape: false)
        ->assertSee('Recommended', escape: false);
});

it('publishes the NA beer buying guide', function () {
    $this->get('/clients/the-dry-standard/guides/buying-na-beer/')
        ->assertOk()
        ->assertSee('How to buy non-alcoholic beer', escape: false)
        ->assertSee('named process', escape: false);
});

it('emits responsive stills and archive fragments', function () {
    $this->get('/clients/the-dry-standard/reviews/')
        ->assertOk()
        ->assertSee('type="image/webp"', escape: false)
        ->assertSee('sizes="', escape: false)
        ->assertSee('data-facet="style"', escape: false)
        ->assertSee('h2 class="visually-hidden">Reviews', escape: false);

    $fragment = $this->get('/clients/the-dry-standard/reviews/?fragment=archive')->assertOk();
    expect($fragment->getContent())->toContain('data-archive')
        ->and($fragment->getContent())->not->toContain('<html');
});

it('collapses Leitz aliases onto one brand page', function () {
    $this->get('/clients/the-dry-standard/brands/weingut-leitz/')
        ->assertRedirect('/clients/the-dry-standard/brands/leitz/')
        ->assertStatus(301);

    $this->get('/clients/the-dry-standard/brands/weingut-josef-leitz/')
        ->assertRedirect('/clients/the-dry-standard/brands/leitz/')
        ->assertStatus(301);

    $index = $this->get('/clients/the-dry-standard/brands/?q=Weingut+Leitz')->assertOk();
    expect(substr_count($index->getContent(), '<h3><a href="'))->toBeGreaterThan(0);
    $index->assertSee('<h3><a href="', escape: false)
        ->assertSee('brands/leitz/', escape: false)
        ->assertDontSee('brands/weingut-leitz/', escape: false);

    $this->get('/clients/the-dry-standard/brands/leitz/')
        ->assertOk()
        ->assertSee('leitz-eins-zwei-zero-riesling', escape: false)
        ->assertSee('leitz-sparkling-rose', escape: false);
});

it('does not write the catalog on a review GET', function () {
    $pdo = new PDO('sqlite:'.base_path('clients/the-dry-standard/data/catalog.sqlite'));
    $before = $pdo->query(
        "SELECT image_source, brand_slug, style_slug, method_facet FROM products WHERE slug = 'leitz-eins-zwei-zero-riesling'"
    )->fetch(PDO::FETCH_ASSOC);

    $this->get('/clients/the-dry-standard/reviews/wine/leitz-eins-zwei-zero-riesling/')->assertOk();

    $after = $pdo->query(
        "SELECT image_source, brand_slug, style_slug, method_facet FROM products WHERE slug = 'leitz-eins-zwei-zero-riesling'"
    )->fetch(PDO::FETCH_ASSOC);

    expect($after)->toBe($before)
        ->and($before['brand_slug'])->toBe('leitz')
        ->and($before['style_slug'])->toBe('riesling')
        ->and($before['image_source'])->not->toBeEmpty();
});

it('sends a client CSP and first-party analytics flag', function () {
    $response = $this->get('/clients/the-dry-standard/')->assertOk();
    $csp = (string) $response->headers->get('Content-Security-Policy');

    expect($csp)->toContain("default-src 'self'")
        ->and($csp)->toContain("script-src 'self'")
        ->and($csp)->toContain("object-src 'none'");

    $response->assertSee('data-analytics="1"', escape: false)
        ->assertDontSee('window.__dryStandardAnalytics', escape: false);
});

it('serves the purchased Ohla, Hitachino, Dr. Lo, Pierre sparkling, and Lyre\'s mule reviews', function () {
    $this->get('/clients/the-dry-standard/reviews/wine/ohla-rose/')
        ->assertOk()
        ->assertSee('Production type: Dealcoholized', escape: false)
        ->assertSee('Syrah and Cabernet Sauvignon', escape: false)
        ->assertSee('media/reviews/ohla-rose.jpg', escape: false)
        ->assertSee('Ohla! Rosé', escape: false)
        ->assertSee('At a glance', escape: false)
        ->assertSee('<dt>Flavor profile</dt>', escape: false)
        ->assertSee('How wine-like is it?', escape: false)
        ->assertSee('watermelon candy', escape: false)
        ->assertDontSee('TDS-0104', escape: false);

    $this->get('/clients/the-dry-standard/reviews/wine/ohla-rosado/')
        ->assertNotFound();

    $this->get('/clients/the-dry-standard/reviews/beer/hitachino-nest-non-ale/')
        ->assertOk()
        ->assertSee('Production type: Naturally low alcohol', escape: false)
        ->assertSee('<0.5%', escape: false)
        ->assertSee('media/reviews/hitachino-nest-non-ale.jpg', escape: false);

    $this->get('/clients/the-dry-standard/reviews/wine/dr-lo-alcohol-removed-riesling/')
        ->assertOk()
        ->assertSee('Vacuum distillation', escape: false)
        ->assertSee('Mosel', escape: false)
        ->assertSee('media/reviews/dr-lo-alcohol-removed-riesling.jpg', escape: false);

    $this->get('/clients/the-dry-standard/reviews/wine/pierre-zero-sparkling-rose/')
        ->assertOk()
        ->assertSee('Spinning cone', escape: false)
        ->assertSee('Chardonnay and Merlot', escape: false)
        ->assertDontSee('3 litres (bag-in-box)', escape: false);

    $this->get('/clients/the-dry-standard/reviews/cocktails/lyres-rum-mule/')
        ->assertOk()
        ->assertSee('Production type: Alternative', escape: false)
        ->assertSee('Formulated', escape: false)
        ->assertSee('media/reviews/lyres-rum-mule.jpg', escape: false);

    $this->get('/clients/the-dry-standard/styles/riesling/')
        ->assertOk()
        ->assertSee('dr-lo-alcohol-removed-riesling', escape: false);

    $this->get('/clients/the-dry-standard/styles/sparkling-rose/')
        ->assertOk()
        ->assertSee('pierre-zero-sparkling-rose', escape: false);
});

it('keeps Guinness card title and related-by-style', function () {
    $this->get('/clients/the-dry-standard/reviews/beer/guinness-0-0/')
        ->assertOk()
        ->assertDontSee('Other Stout', escape: false)
        ->assertSee('More from the cellar', escape: false)
        ->assertSee('id="how-it-was-made"', escape: false)
        ->assertSee('class="identity"', escape: false)
        ->assertSee('#how-it-was-made', escape: false)
        ->assertSee('#tasting', escape: false)
        ->assertSee('#facts', escape: false)
        ->assertSee('#how-to-drink', escape: false);

    $this->get('/clients/the-dry-standard/reviews/?q=guinness')
        ->assertOk()
        ->assertSee('Guinness 0.0', escape: false);

    $this->get('/clients/the-dry-standard/reviews/beer/athletic-brewing-run-wild-ipa/')
        ->assertOk()
        ->assertSee('Other IPA', escape: false)
        ->assertDontSee('Guinness 0.0', escape: false)
        ->assertDontSee('>Kölsch</a>', escape: false);
});
