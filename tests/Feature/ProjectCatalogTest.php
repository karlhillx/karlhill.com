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
        'finium',
    ]);

    $this->get('/work')
        ->assertOk()
        ->assertSee('jacobs-mission-software', escape: false)
        ->assertSee('flood-mapping-system', escape: false)
        ->assertSee('laads-daac', escape: false)
        ->assertSee('finium', escape: false)
        ->assertSee('$105M', escape: false)
        ->assertDontSee('esscor', escape: false)
        ->assertDontSee('direct-readout-laboratory', escape: false)
        ->assertDontSee('informeddna-platform', escape: false)
        ->assertDontSee('nasa-earth-observatory', escape: false);

    $this->get('/work/esscor')->assertOk();
    $this->get('/work/direct-readout-laboratory')->assertOk();
    $this->get('/work/informeddna-platform')->assertOk();
    $this->get('/work/nasa-earth-observatory')->assertOk();
    $this->get('/work/tag/healthcare')->assertNotFound();
    $this->get('/work/tag/laravel')->assertNotFound();
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

it('work index shows tag counts', function () {
    $count = ProjectCatalog::tagCounts()->get('AWS');

    $this->get('/work')
        ->assertOk()
        ->assertSee('AWS', false)
        ->assertSee('('.$count.')', false);
});
