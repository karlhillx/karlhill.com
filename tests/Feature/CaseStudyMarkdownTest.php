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
        ->assertSee('Engineering leadership for aerospace mission software', escape: false)
        ->assertSee('Program-specific details are omitted', escape: false)
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
            ->and(str_word_count(strip_tags((string) $study['body_html'])))->toBeGreaterThan(250, "{$project['slug']} narrative is too thin")
            ->and($study['updated'] ?? null)->toMatch('/^\d{4}-\d{2}-\d{2}$/');
    }
});

it('parses substantive markdown body and generates html and toc', function () {
    /** @var CaseStudyRepository $repo */
    $repo = app(CaseStudyRepository::class);

    $jacobs = $repo->find('jacobs-mission-software');
    expect($jacobs)->toBeArray()
        ->and($jacobs['body_html'] ?? null)->toBeString()
        ->and($jacobs['body_html'])->toContain('A hard call')
        ->and($jacobs['body_html'])->toContain('Developing engineers')
        ->and($jacobs['body_html'])->toContain('Leading team execution')
        ->and($jacobs['body_html'])->not->toContain('Representative delivery lifecycle')
        ->and($jacobs['body_html'])->not->toContain('Kubernetes Mission Mesh')
        ->and($jacobs['body_html'])->not->toContain('<pre><code>');

    $jacobsResponse = $this->get('/work/jacobs-mission-software');
    $jacobsResponse->assertOk()
        ->assertSee('A hard call', escape: false)
        ->assertSee('Developing engineers', escape: false)
        ->assertSee('id="platform"', escape: false)
        ->assertSee('Sequence the work', escape: false)
        ->assertSee('Schematic', escape: false)
        ->assertSee('Program-specific details are omitted', escape: false)
        ->assertSee('Held a sprint commitment', escape: false)
        ->assertDontSee('Kubernetes Mission Mesh', escape: false)
        ->assertDontSee('Representative delivery lifecycle', escape: false)
        ->assertDontSee('Executive Summary', escape: false);

    $flood = $this->get('/work/flood-mapping-system');
    $flood->assertOk()
        ->assertSee('Operational Context', escape: false)
        ->assertSee('Figure 1: Automated Satellite Ingestion to Multi-Agency Dissemination Architecture', escape: false)
        ->assertSee('href="#system-architecture"', escape: false)
        ->assertDontSee('&lt;!-- Arrow 1 to 2 --&gt;', escape: false);
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

        $firstTitle = $stages[0]['title'];
        $this->get('/work/'.$slug)
            ->assertOk()
            ->assertSee('id="platform"', escape: false)
            ->assertSee($firstTitle)
            ->assertSee('href="#platform"', escape: false);
    }
});
