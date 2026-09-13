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
        ->assertSee('Delivery gates, portable messaging, and stronger tests are in use', escape: false)
        ->assertDontSee('Architected a shared', escape: false)
        ->assertDontSee('BlackLynx', escape: false)
        ->assertDontSee('RTX', escape: false)
        ->assertDontSee('id="platform"', escape: false)
        ->assertSee('Delivery gates', escape: false)
        ->assertSee('Program-specific architecture and operational details are not included here', escape: false)
        ->assertSee('Hands-on technical leadership', escape: false)
        ->assertSee('id="scope"', escape: false)
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

    $flood = $this->get('/work/flood-mapping-system');
    $flood->assertOk()
        ->assertSee('Processing and delivery', escape: false)
        ->assertSee('Read the paper', escape: false)
        ->assertSee('The public map is the shipped system', escape: false)
        ->assertDontSee('Figure 1: Automated Satellite Ingestion to Multi-Agency Dissemination Architecture', escape: false)
        ->assertDontSee('operational flood data', escape: false);

    $this->get('/work/laads-daac')
        ->assertOk()
        ->assertSee('Find Data is live', escape: false)
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

it('every case study publishes a platform map with three to five stages', function () {
    /** @var CaseStudyRepository $repo */
    $repo = app(CaseStudyRepository::class);

    foreach (ProjectCatalog::withCaseStudies() as $project) {
        $slug = $project['slug'];
        $study = $repo->find($slug);
        $stages = $study['platform']['stages'] ?? null;

        expect($stages)->toBeArray("{$slug} is missing platform.stages")
            ->and(count($stages))->toBeGreaterThanOrEqual(3, "{$slug} needs at least 3 platform stages")
            ->and(count($stages))->toBeLessThanOrEqual(5, "{$slug} has more than 5 platform stages");

        foreach ($stages as $index => $stage) {
            expect($stage['step'] ?? null)->toBeString("{$slug} stage {$index} is missing step")
                ->and($stage['title'] ?? null)->toBeString("{$slug} stage {$index} is missing title")
                ->and($stage['body'] ?? null)->toBeString("{$slug} stage {$index} is missing body")
                ->and(trim((string) $stage['step']))->not->toBe('')
                ->and(trim((string) $stage['title']))->not->toBe('')
                ->and(trim((string) $stage['body']))->not->toBe('');
        }

        $this->get('/work/'.$slug)
            ->assertOk()
            ->assertDontSee('id="platform"', escape: false)
            ->assertDontSee('work-diagram', escape: false)
            ->assertDontSee('href="#platform"', escape: false);
    }
});
