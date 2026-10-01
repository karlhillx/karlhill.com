<?php

use App\Support\HomeStructuredData;
use App\Support\Images;
use App\Support\ProjectCatalog;
use Illuminate\Support\Facades\Http;

it('organizes every primary project into one explicit portfolio collection', function () {
    $collections = ProjectCatalog::collections();
    expect($collections->keys()->all())->toBe(['mission', 'nasa', 'tooling', 'product'])
        ->and($collections['mission']['projects']->pluck('slug')->all())->toBe(['jacobs-mission-software'])
        ->and($collections['nasa']['projects']->pluck('slug')->all())->toBe([
            'flood-mapping-system', 'laads-daac', 'nasa-earth-observatory', 'direct-readout-laboratory', 'esscor',
        ]);

    $slugs = $collections->pluck('projects')->flatten(1)->pluck('slug');
    expect($slugs)->toHaveCount(8)
        ->and($slugs->unique())->toHaveCount(8)
        ->and(ProjectCatalog::earlier())->toHaveCount(2);
});

it('features five evidence-rich projects in editorial order', function () {
    $projects = ProjectCatalog::featured(5);
    expect($projects->pluck('slug')->all())->toBe([
        'jacobs-mission-software', 'flood-mapping-system', 'laads-daac',
        'developer-tooling', 'the-dry-standard',
    ]);

    foreach ($projects as $project) {
        expect($project['summary'])->toHaveKeys(['problem', 'contribution', 'impact', 'note'])
            ->and($project['case_study']['role'])->not->toBeEmpty()
            ->and($project['tags'])->not->toBeEmpty();
    }

    // Home renders the first three; its ItemList describes exactly what is on the page.
    $graph = HomeStructuredData::build(collect())['@graph'];
    $list = collect($graph)->firstWhere('@type', 'ItemList')['itemListElement'];
    expect(array_column($list, 'position'))->toBe([1, 2, 3])
        ->and(array_column($list, 'name'))->toBe($projects->take(3)->pluck('title')->all());
});

it('renders the complete portfolio without a GitHub dependency', function (string $path) {
    Http::preventStrayRequests();
    $response = $this->get($path)->assertOk();
    foreach (['bb-run', 'testrisk', 'pipeguard'] as $name) {
        $response->assertSee('https://github.com/karlhillx/'.$name, false);
    }
    $response->assertSeeInOrder([
        'https://github.com/karlhillx/bb-run',
        'https://github.com/karlhillx/testrisk',
        'https://github.com/karlhillx/pipeguard',
    ], false)->assertDontSee('sim-rs')->assertDontSee('driftlens')->assertDontSee('drift-rs');
    Http::assertNothingSent();
})->with(['/work', '/work/developer-tooling', '/resume', '/llms.txt']);

it('publishes consistent tooling and product proof on every discovery surface', function (string $slug) {
    $project = ProjectCatalog::findOrFail($slug);
    $this->get('/work/'.$slug)->assertOk()
        ->assertSee('rel="canonical" href="https://karlhill.com/work/'.$slug.'"', false)
        ->assertSee('aria-label="Breadcrumb"', false)
        ->assertSee('property="og:image:width" content="1200"', false)
        ->assertSee('property="og:image:height" content="630"', false);
    $this->get('/sitemap.xml')->assertSee('/work/'.$slug);
    $this->get('/llms.txt')->assertSee('/work/'.$slug);
    $this->get('/api/site.json')->assertJsonFragment(['slug' => $slug]);
    $this->get('/api/commands.json')->assertJsonFragment(['url' => '/work/'.$slug]);

    $size = getimagesize(ProjectCatalog::ogImagePath($slug));
    expect([$size[0], $size[1]])->toBe([1200, 630]);
    expect(is_file(public_path($project['image'])))->toBeTrue();
})->with(['developer-tooling', 'the-dry-standard']);

it('keeps tool names searchable and qualifies quantitative claims', function () {
    $this->get('/api/commands.json')->assertSee('bb-run, testrisk, and pipeguard')
        ->assertDontSee('sim-rs')->assertDontSee('driftlens')->assertDontSee('drift-rs');
    $this->get('/')->assertSee('Collaborative scientific result', false)
        ->assertSee('Shared ownership', false);
    $this->get('/work/nasa-earth-observatory')->assertSee('Historical platform scale', false);
    $this->get('/work/the-dry-standard')->assertSee('generated SQLite runtime catalog')
        ->assertSee('Publication builds the catalog')
        ->assertDontSee('sub-second')
        ->assertDontSee('Content integrity is guaranteed');
});

it('offers responsive dry standard assets and leaves vector paths intact', function () {
    expect(Images::srcset('/img/webp/ss-dry-standard.webp'))->toContain('400w', '800w')
        ->and(Images::hasAvif('/img/webp/ss-dry-standard.webp'))->toBeTrue()
        ->and(Images::webp('/img/developer-tooling.svg'))->toBe('/img/developer-tooling.svg');
});

it('lists only the seven displayed studies in work collection metadata', function () {
    $html = $this->get('/work')->assertOk()->getContent();
    preg_match_all('~<script type="application/ld\+json"[^>]*>(.*?)</script>~s', $html, $matches);
    $page = collect($matches[1])->map(fn ($json) => json_decode($json, true))
        ->firstWhere('@type', 'CollectionPage');
    $items = $page['mainEntity']['itemListElement'];
    expect($items)->toHaveCount(7)
        ->and(array_unique(array_column($items, 'url')))->toHaveCount(7);
    expect(array_column($items, 'url'))->not->toContain(
        url('/work/nasa-earth-observatory'),
        url('/work/direct-readout-laboratory'),
        url('/work/esscor'),
    );
});

it('keeps supporting NASA studies available through resume and discovery surfaces', function (string $slug) {
    $this->get('/resume')->assertOk()->assertSee('href="/work/'.$slug.'"', false);
    $this->get('/work/'.$slug)->assertOk();
    $this->get('/sitemap.xml')->assertSee('/work/'.$slug);
    $this->get('/api/site.json')->assertJsonFragment(['slug' => $slug]);
})->with(['nasa-earth-observatory', 'direct-readout-laboratory', 'esscor']);

it('case study navigation follows collection order including supporting work', function () {
    expect(ProjectCatalog::adjacent('laads-daac')['next']['slug'])->toBe('nasa-earth-observatory')
        ->and(ProjectCatalog::adjacent('esscor')['next']['slug'])->toBe('developer-tooling')
        ->and(ProjectCatalog::adjacent('developer-tooling')['next']['slug'])->toBe('the-dry-standard')
        ->and(ProjectCatalog::adjacent('direct-readout-laboratory')['previous']['slug'])->toBe('nasa-earth-observatory');
});

it('keeps global links and repository choices deliberately small', function (string $path) {
    $html = $this->get($path)->assertOk()->getContent();
    preg_match('~<nav[^>]*aria-label="Site"[^>]*>(.*?)</nav>~s', $html, $footer);
    preg_match_all('~href="([^"]+)"~', $footer[1], $links);
    expect($links[1])->toBe([
        '/blog', '/resume', 'https://github.com/karlhillx',
        'https://www.linkedin.com/in/khill/', '/privacy',
    ]);
    foreach (['/kit', '/now', '/delivery', '/lead'] as $retired) {
        expect($html)->not->toContain('href="'.$retired.'"')
            ->not->toContain('href="'.$retired.'#');
    }
    // Repo links appear once on /work (tooling card) and not at all on the shorter home page.
    preg_match('~<main\b[^>]*>(.*?)</main>~s', $html, $main);
    foreach (['bb-run', 'testrisk', 'pipeguard'] as $repo) {
        expect(substr_count($main[1], 'href="https://github.com/karlhillx/'.$repo.'"'))->toBe($path === '/' ? 0 : 1);
    }
})->with(['/', '/work']);

it('keeps contact available when scheduling is disabled', function () {
    config(['site.booking.url' => null, 'site.booking.embed_src' => null]);
    $this->get('/')->assertOk()
        ->assertSee('href="/#contact"', false)
        ->assertSee('id="contact-form"', false)
        ->assertSee('id="book"', false)
        ->assertDontSee('booking-embed__frame', false);
});
