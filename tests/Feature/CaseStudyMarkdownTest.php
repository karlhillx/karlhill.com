<?php

use App\Support\CaseStudyRepository;
use App\Support\ProjectCatalog;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('loads case studies from markdown front matter', function () {
    /** @var CaseStudyRepository $repo */
    $repo = app(CaseStudyRepository::class);

    $eo = $repo->find('nasa-earth-observatory');
    expect($eo)->toBeArray()
        ->and($eo['lede'] ?? null)->toBeString()
        ->and($eo['decisions'] ?? null)->toBeArray()
        ->and($eo['metrics'] ?? null)->toBeArray();

    $project = ProjectCatalog::findOrFail('nasa-earth-observatory');
    expect($project['case_study']['lede'])->toBe($eo['lede']);

    $jacobs = $this->get('/work/jacobs-mission-software');
    $jacobs->assertOk()
        ->assertSee('Engineering mission software at scale', escape: false)
        ->assertSee('Program-specific architecture and operational details are not included here', escape: false)
        ->assertDontSee('Visit live project', escape: false);
});

it('case study markdown files exist for every catalog study', function () {
    foreach (ProjectCatalog::withCaseStudies() as $project) {
        $path = resource_path('work/'.$project['slug'].'.md');
        expect($path)->toBeFile();
    }
});

it('every case study has a real narrative body and an updated date', function () {
    /** @var CaseStudyRepository $repo */
    $repo = app(CaseStudyRepository::class);

    foreach (ProjectCatalog::withCaseStudies() as $project) {
        $study = $repo->find($project['slug']);

        expect($study)->toBeArray()
            ->and($study['body_html'] ?? null)->toBeString("{$project['slug']} still has the scaffold body")
            ->and(str_word_count(strip_tags((string) $study['body_html'])))->toBeGreaterThan(80, "{$project['slug']} narrative is too thin")
            ->and($study['updated'] ?? null)->toMatch('/^\d{4}-\d{2}-\d{2}$/');
    }
});

it('parses substantive markdown body and generates html and toc', function () {
    /** @var CaseStudyRepository $repo */
    $repo = app(CaseStudyRepository::class);

    $jacobs = $repo->find('jacobs-mission-software');
    expect($jacobs)->toBeArray()
        ->and($jacobs['body_html'] ?? null)->toBeString()
        ->and($jacobs['body_html'])->toContain('Delivery gates')
        ->and($jacobs['body_html'])->toContain('Portable messaging')
        ->and($jacobs['body_html'])->toContain('Coaching while shipping')
        ->and($jacobs['body_html'])->not->toContain('Representative delivery lifecycle')
        ->and($jacobs['body_html'])->not->toContain('Kubernetes Mission Mesh')
        ->and($jacobs['body_html'])->not->toContain('<pre><code>');

    $jacobsResponse = $this->get('/work/jacobs-mission-software');
    $jacobsResponse->assertOk()
        ->assertSee('Delivery gates', escape: false)
        ->assertSee('Portable messaging', escape: false)
        ->assertSee('at least 80% repository test coverage', escape: false)
        ->assertSee('two-approval pull-request governance', escape: false)
        ->assertSee('automated quality gates', escape: false)
        ->assertSee('safer and more predictable', escape: false)
        ->assertDontSee('still uneven', escape: false)
        ->assertDontSee('90%', escape: false)
        ->assertDontSee('Architected a shared', escape: false)
        ->assertDontSee('BlackLynx', escape: false)
        ->assertDontSee('id="platform"', escape: false)
        ->assertSee('Delivery gates', escape: false)
        ->assertSee('Program-specific architecture and operational details are not included here', escape: false)
        ->assertSee('Hands-on technical leadership', escape: false)
        ->assertSee('id="scope"', escape: false)
        ->assertSee('case-study-flow', escape: false)
        ->assertSee('Engineering delivery system', escape: false)
        ->assertSee('Local development', escape: false)
        ->assertDontSee('Developer feedback', escape: false)
        ->assertSee('Pre-commit', escape: false)
        ->assertSee('format · lint · imports · types · secrets', escape: false)
        ->assertSee('Commit / push', escape: false)
        ->assertSee('case-study-flow__fork-arms', escape: false)
        ->assertSee('Pull request', escape: false)
        ->assertSee('Review', escape: false)
        ->assertSee('2+ approvals', escape: false)
        ->assertSee('CI pipeline', escape: false)
        ->assertSee('unit tests · coverage · SAST', escape: false)
        ->assertSee('Merge gate', escape: false)
        ->assertSee('review + CI pass', escape: false)
        ->assertDontSee('squash merge', escape: false)
        ->assertDontSee('Pull request / merge gate', escape: false)
        ->assertDontSee('passing CI', escape: false)
        ->assertSee('Change intelligence', escape: false)
        ->assertSee('case-study-flow__chain', escape: false)
        ->assertSee('Change detection', escape: false)
        ->assertSee('Delta tagging', escape: false)
        ->assertSee('Cross-repo impact', escape: false)
        ->assertSee('System validation', escape: false)
        ->assertSee('Integration tests', escape: false)
        ->assertSee('E2E tests', escape: false)
        ->assertSee('Environment validation', escape: false)
        ->assertSee('case-study-flow__return', escape: false)
        ->assertSee('Validation feedback', escape: false)
        ->assertDontSee('Affected repos', escape: false)
        ->assertDontSee('Shared packages', escape: false)
        ->assertDontSee('Standards · Reviews · Documentation', escape: false)
        ->assertDontSee('Agile Delivery · Coaching · Governance', escape: false)
        ->assertSee('case-study-logo-plate', escape: false)
        ->assertSee('Local checks run on the workstation; CI provides the authoritative repository gate. Downstream validation covers cross-repository and environment-level behavior. Simplified, unclassified delivery view—not a program architecture.', escape: false)
        ->assertSee('Adopted', escape: false)
        ->assertSee('In progress', escape: false)
        ->assertSee('Delivery status', escape: false)
        ->assertSee('Owns', escape: false)
        ->assertSee('Influences', escape: false)
        ->assertSee('Reserved', escape: false)
        ->assertSee('Staff individual-contributor role; formal personnel decisions remain with management.', escape: false)
        ->assertDontSee('Held a sprint commitment', escape: false)
        ->assertDontSee('Staff IC title — formal personnel decisions remain with management.', escape: false)
        ->assertDontSee('Staff IC with technical and delivery leadership', escape: false)
        ->assertDontSee('What stays out of scope here: formal people-management authority', escape: false)
        ->assertDontSee('Kubernetes Mission Mesh', escape: false)
        ->assertDontSee('Representative delivery lifecycle', escape: false)
        ->assertDontSee('Executive Summary', escape: false);

    $jacobsHtml = $jacobsResponse->getContent();
    expect(preg_match('/<figure class="case-study-media".*?<\/figure>/s', $jacobsHtml, $mediaFigure))->toBe(1)
        ->and($mediaFigure[0])->toContain('case-study-logo-plate')
        ->and($mediaFigure[0])->not->toContain('case-study-flow');

    $disclaimerAt = strpos($jacobsHtml, 'Program-specific architecture and operational details are not included here');
    $flowAt = strpos($jacobsHtml, 'case-study-flow-figure');
    $deliveryHeadingAt = strpos($jacobsHtml, 'id="delivery-gates"');
    expect($disclaimerAt)->toBeInt()->toBeGreaterThan(0)
        ->and($flowAt)->toBeInt()->toBeGreaterThan($disclaimerAt)
        ->and($deliveryHeadingAt)->toBeInt()->toBeGreaterThan($flowAt);

    $flood = $this->get('/work/flood-mapping-system');
    $flood->assertOk()
        ->assertSee('Processing and delivery', escape: false)
        ->assertSee('Read the paper', escape: false)
        ->assertSee('The public map is the shipped system', escape: false)
        ->assertSee('shot-carousel--multi', escape: false)
        ->assertSee('GeoHorizons paper', escape: false)
        ->assertDontSee('Figure 1: Automated Satellite Ingestion to Multi-Agency Dissemination Architecture', escape: false)
        ->assertDontSee('operational flood data', escape: false);

    $this->get('/work/laads-daac')
        ->assertOk()
        ->assertSee('Find Data is live', escape: false)
        ->assertSee('is the broader public portal', escape: false)
        ->assertSee('Find Data remains the walkable artifact', escape: false)
        ->assertSee('href="https://ladsweb.modaps.eosdis.nasa.gov/"', escape: false)
        ->assertSee('Delivery around existing services', escape: false)
        ->assertDontSee('replaced the archive', escape: false);

    $this->get('/work/nasa-earth-observatory')
        ->assertOk()
        ->assertSee('1.5 million monthly visitors', escape: false)
        ->assertSee('not a traffic result', escape: false)
        ->assertSee('The live site is the artifact', escape: false);

    $this->get('/work/direct-readout-laboratory')
        ->assertOk()
        ->assertSee('The public portal is the artifact', escape: false)
        ->assertSee('https://directreadout.sci.gsfc.nasa.gov', escape: false)
        ->assertDontSee('Visit live project', escape: false);

    $this->get('/work/esscor')
        ->assertOk()
        ->assertSee('There is no public demo', escape: false)
        ->assertSee('A percentage is not claimed here', escape: false)
        ->assertDontSee('reduced recurring manual registration', escape: false)
        ->assertDontSee('~60%', escape: false);
});

it('case studies do not render the retired platform map', function () {
    /** @var CaseStudyRepository $repo */
    $repo = app(CaseStudyRepository::class);

    foreach (ProjectCatalog::withCaseStudies() as $project) {
        $slug = $project['slug'];
        $study = $repo->find($slug);

        expect($study['platform'] ?? null)->toBeNull("{$slug} still has unused platform front matter");

        $this->get('/work/'.$slug)
            ->assertOk()
            ->assertDontSee('id="platform"', escape: false)
            ->assertDontSee('work-diagram', escape: false)
            ->assertDontSee('platform-map', escape: false)
            ->assertDontSee('href="#platform"', escape: false);
    }
});

it('only studies with diagram data render a delivery figure', function () {
    /** @var CaseStudyRepository $repo */
    $repo = app(CaseStudyRepository::class);

    foreach (ProjectCatalog::withCaseStudies() as $project) {
        $slug = $project['slug'];
        $study = $repo->find($slug);
        $hasDiagram = ! empty($study['diagram']['zones']) || ! empty($study['diagram']['stages']);
        $response = $this->get('/work/'.$slug)->assertOk();

        if ($hasDiagram) {
            $response->assertSee('case-study-flow-figure', escape: false)
                ->assertSee((string) ($study['diagram']['title'] ?? ''), escape: false);

            continue;
        }

        $response->assertDontSee('case-study-flow', escape: false);
    }
});
