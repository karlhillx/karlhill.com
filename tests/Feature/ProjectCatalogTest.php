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
    ]);

    expect(ProjectCatalog::supporting()->pluck('slug')->all())->toBe([
        'nasa-earth-observatory',
        'direct-readout-laboratory',
        'esscor',
    ]);

    $this->get('/work')
        ->assertOk()
        ->assertSee('jacobs-mission-software', escape: false)
        ->assertSee('flood-mapping-system', escape: false)
        ->assertSee('laads-daac', escape: false)
        ->assertDontSee('finium', escape: false)
        ->assertDontSee('$105M', escape: false)
        ->assertSee('near-real-time Earth observation products', escape: false)
        ->assertSee('NASA MODIS and VIIRS satellite data', escape: false)
        ->assertSee('<title>Work — Karl Hill</title>', escape: false)
        ->assertSee('Karl Hill — mission software, Earth science systems, and engineering infrastructure', escape: false)
        ->assertSee('id="chapters"', escape: false)
        ->assertSee('Also at Goddard', escape: false)
        ->assertSee('Additional Earth science systems developed and supported during eight years at NASA Goddard', escape: false)
        ->assertSee('Independent tools focused on software delivery', escape: false)
        ->assertSee('Delivery gates, portable messaging, and stronger tests are in use', escape: false)
        ->assertDontSee('Supporting chapters, not a second flagship set', escape: false)
        ->assertDontSee('Software other people depend on, then the engineering system around it', escape: false)
        ->assertSee('/work/esscor', escape: false)
        ->assertSee('/work/direct-readout-laboratory', escape: false)
        ->assertSee('/work/nasa-earth-observatory', escape: false)
        ->assertDontSee('informeddna-platform', escape: false);

    $this->get('/work/esscor')->assertOk();
    $this->get('/work/direct-readout-laboratory')->assertOk();
    $this->get('/work/informeddna-platform')->assertOk();
    $this->get('/work/nasa-earth-observatory')->assertOk();
    $this->get('/work/finium')->assertOk();
    $this->get('/work/tag/healthcare')->assertNotFound();
    $this->get('/work/tag/laravel')->assertNotFound();
});

it('public projects expose a live artifact url', function () {
    expect(ProjectCatalog::liveUrl(ProjectCatalog::find('jacobs-mission-software')))->toBeNull()
        ->and(ProjectCatalog::liveUrl(ProjectCatalog::find('flood-mapping-system')))->toBe('https://floodmapping.gsfc.nasa.gov/')
        ->and(ProjectCatalog::artifactLabel(ProjectCatalog::find('laads-daac')))->toBe('Open Find Data')
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

it('work cards still expose stack tags and tagged urls', function () {
    $this->get('/work')
        ->assertOk()
        ->assertSee('AWS', false);

    $this->get('/work/tag/aws')
        ->assertOk()
        ->assertSee('Clear filter', false)
        ->assertSee('AWS', false);
});
