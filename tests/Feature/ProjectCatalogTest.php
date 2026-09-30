<?php

use App\Support\ProjectCatalog;

it('jacobs mission software is always first', function () {
    $this->assertSame('jacobs-mission-software', ProjectCatalog::all()->first()['slug']);
    $this->assertSame('jacobs-mission-software', ProjectCatalog::featured()->first()['slug']);
    $this->assertSame('jacobs-mission-software', ProjectCatalog::filteredByTag('AWS')->first()['slug']);
    $this->assertSame('flood-mapping-system', ProjectCatalog::all()[1]['slug']);
    $this->assertSame('flood-mapping-system', ProjectCatalog::featured()[1]['slug']);
    $this->assertSame('laads-daac', ProjectCatalog::featured()[2]['slug']);
});

it('portfolio lists trajectory chapters and keeps supporting studies routable', function () {
    $listed = ProjectCatalog::listed()->pluck('slug')->all();

    expect($listed)->toBe([
        'jacobs-mission-software',
        'flood-mapping-system',
        'laads-daac',
        'the-dry-standard',
        'nasa-earth-observatory',
        'developer-tooling',
    ]);

    expect(ProjectCatalog::supporting()->pluck('slug')->all())->toBe([
        'direct-readout-laboratory',
        'esscor',
    ]);

    $this->get('/work')
        ->assertOk()
        ->assertSee('jacobs-mission-software', escape: false)
        ->assertSee('flood-mapping-system', escape: false)
        ->assertSee('laads-daac', escape: false)
        ->assertSee('the-dry-standard', escape: false)
        ->assertSee('The Dry Standard', escape: false)
        ->assertSee('finium', escape: false)
        ->assertSee('informeddna-platform', escape: false)
        ->assertDontSee('$105M', escape: false)
        ->assertSee('90.9% overall accuracy', escape: false)
        ->assertSee('MODIS and VIIRS access', escape: false)
        ->assertSee('<title>Work — Karl Hill</title>', escape: false)
        ->assertSee('NASA Platforms', escape: false)
        ->assertSee('id="chapters"', escape: false)
        ->assertSee('Also at Goddard', escape: false)
        ->assertSee('Eight years connecting satellite data', escape: false)
        ->assertSee('Developer Tooling / Open Source', escape: false)
        ->assertSee('6 engineers onboarded', escape: false)
        ->assertSee('portable messaging', escape: false)
        ->assertDontSee('at least 80% repository test coverage', escape: false)
        ->assertDontSee('releases are safer and more predictable', escape: false)
        ->assertDontSee('Supporting chapters, not a second flagship set', escape: false)
        ->assertDontSee('Software other people depend on, then the engineering system around it', escape: false)
        ->assertSee('/work/esscor', escape: false)
        ->assertSee('/work/direct-readout-laboratory', escape: false)
        ->assertSee('/work/nasa-earth-observatory', escape: false);

    $this->get('/work/esscor')->assertOk();
    $this->get('/work/direct-readout-laboratory')->assertOk();
    $this->get('/work/informeddna-platform')->assertOk();
    $this->get('/work/nasa-earth-observatory')->assertOk();
    $this->get('/work/finium')->assertOk();
    $this->get('/work/tag/healthcare')->assertRedirect('/work')->assertStatus(301);
    $this->get('/work/tag/laravel')->assertRedirect('/work')->assertStatus(301);
});

it('portfolio pages preserve mission anchors and product presentation', function (string $path, string $missionHref) {
    $response = $this->get($path)
        ->assertOk()
        ->assertSee('href="'.$missionHref.'"', escape: false)
        ->assertSee('id="work"', escape: false)
        ->assertDontSee('#mission-software', escape: false)
        ->assertDontSee('Explore products on /work', escape: false)
        ->assertDontSee('View all tools on /work', escape: false);

    $html = $response->getContent();
    $this->assertMatchesRegularExpression(
        '~<img\b[^>]*src="/img/webp/ss-dry-standard\.webp"[^>]*width="1200"[^>]*height="675"[^>]*loading="lazy"~',
        $html,
    );
    $this->assertMatchesRegularExpression(
        '~<a\b[^>]*href="https://drinkdrystandard\.com/"[^>]*data-no-ext~',
        $html,
    );
})->with([
    'home' => ['/', '/work#work'],
    'work' => ['/work', '#work'],
]);

it('public projects expose a live artifact url', function () {
    expect(ProjectCatalog::liveUrl(ProjectCatalog::find('jacobs-mission-software')))->toBeNull()
        ->and(ProjectCatalog::liveUrl(ProjectCatalog::find('flood-mapping-system')))->toBe('https://floodmapping.gsfc.nasa.gov/')
        ->and(ProjectCatalog::artifactLabel(ProjectCatalog::find('laads-daac')))->toBe('Open Find Data')
        ->and(ProjectCatalog::liveUrl(ProjectCatalog::find('laads-daac')))->toBe('https://ladsweb.modaps.eosdis.nasa.gov/search/')
        ->and(ProjectCatalog::alsoLinks(ProjectCatalog::find('laads-daac')))->toBe([
            [
                'label' => 'Broader LAADS site',
                'href' => 'https://ladsweb.modaps.eosdis.nasa.gov/',
            ],
        ])
        ->and(ProjectCatalog::alsoLinks(ProjectCatalog::find('flood-mapping-system')))->toBe([])
        ->and(ProjectCatalog::liveUrl(ProjectCatalog::find('nasa-earth-observatory')))->toBe('https://earthobservatory.nasa.gov/')
        ->and(ProjectCatalog::artifactLine(ProjectCatalog::find('flood-mapping-system')))->toContain('Public satellite flood maps');
});

it('featured projects have case studies', function () {
    $featured = ProjectCatalog::all()->where('featured', true);

    $this->assertGreaterThan(0, $featured->count());
    foreach ($featured as $project) {
        $this->assertTrue(ProjectCatalog::hasCaseStudy($project), $project['slug']);
        $this->assertStringContainsString('/work/', ProjectCatalog::cardUrl($project));
    }
});

it('unknown case study returns 404', function () {
    $response = $this->get('/work/not-a-real-project');

    $response->assertStatus(404);
});

it('adjacent case studies', function () {
    $studies = ProjectCatalog::withCaseStudies()->values();
    $this->assertGreaterThan(2, $studies->count());

    $middle = $studies[1];
    $adjacent = ProjectCatalog::adjacent($middle['slug']);

    $this->assertSame($studies[0]['slug'], $adjacent['previous']['slug']);
    $this->assertSame($studies[2]['slug'], $adjacent['next']['slug']);
});

it('related projects share tags', function () {
    $project = ProjectCatalog::find('flood-mapping-system');
    $related = ProjectCatalog::related($project);

    $this->assertGreaterThan(0, $related->count());
    foreach ($related as $candidate) {
        $this->assertNotSame($project['slug'], $candidate['slug']);
        $this->assertNotEmpty(array_intersect($project['tags'], $candidate['tags']));
    }
});

it('tag slug round trip', function () {
    $slug = ProjectCatalog::tagSlug('NASA Earth Science');
    $this->assertSame('nasa-earth-science', $slug);
    $this->assertSame('NASA Earth Science', ProjectCatalog::tagFromSlug($slug));
});

it('tag counts match project membership', function () {
    $counts = ProjectCatalog::tagCounts();

    $this->assertTrue($counts->has('AWS'));
    $this->assertSame(
        ProjectCatalog::filteredByTag('AWS')->count(),
        $counts->get('AWS'),
    );
});

it('case study snippets name Karl Hill and the NASA or Jacobs affiliation', function () {
    $this->get('/work/flood-mapping-system')
        ->assertOk()
        ->assertSee('<title>Flood Mapping System — Karl Hill</title>', escape: false)
        ->assertSee('name="description" content="Karl Hill, NASA Goddard Earth observation software.', escape: false);

    $this->get('/work/laads-daac')
        ->assertOk()
        ->assertSee('<title>LAADS DAAC — Karl Hill</title>', escape: false);

    $this->get('/work/jacobs-mission-software')
        ->assertOk()
        ->assertSee('<title>Engineering mission software at scale — Karl Hill</title>', escape: false);
});

it('work cards expose stack tags as labels, not filter urls', function () {
    $this->get('/work')
        ->assertOk()
        ->assertSee('AWS', false)
        ->assertDontSee('/work/tag/', false);

    $this->get('/work/tag/aws')
        ->assertRedirect('/work')
        ->assertStatus(301);
});
