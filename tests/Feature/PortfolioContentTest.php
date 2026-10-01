<?php

use App\Support\CaseStudyPage;
use App\Support\CaseStudyRepository;
use App\Support\PortfolioContent;
use App\Support\ProjectCatalog;

it('validates the complete published catalog and editorial ordering', function () {
    $projects = ProjectCatalog::all();
    expect($projects->pluck('slug')->unique())->toHaveCount($projects->count());
    $featured = $projects->where('featured', true);
    expect($featured->pluck('featured_order')->unique())->toHaveCount($featured->count());

    foreach ($projects as $project) {
        PortfolioContent::validateProject($project);
        PortfolioContent::validateStudy($project['case_study'], $project['slug']);
    }
});

it('rejects incomplete or malformed portfolio metadata', function (string $field, mixed $value) {
    $project = ProjectCatalog::findOrFail('jacobs-mission-software');
    data_set($project, $field, $value);
    expect(fn () => PortfolioContent::validateProject($project))
        ->toThrow(UnexpectedValueException::class);
})->with([
    ['slug', '../invalid'],
    ['portfolio_group', 'unknown'],
    ['summary.contribution', ''],
    ['summary.note', null],
    ['featured_order', 0],
    ['case_study', null],
    ['tags', ['Python', null]],
    ['gallery', [['alt' => 'Missing image source']]],
]);

it('rejects invalid narrative fields with the source name', function () {
    $study = ProjectCatalog::findOrFail('jacobs-mission-software')['case_study'];
    $study['updated'] = 'not-a-date';
    expect(fn () => PortfolioContent::validateStudy($study, 'jacobs-mission-software.md'))
        ->toThrow(UnexpectedValueException::class, 'jacobs-mission-software.md');
});

it('resolves canonical measurements and invalidates cached studies when facts change', function () {
    $repository = app(CaseStudyRepository::class);
    expect($repository->find('jacobs-mission-software')['metrics'][1]['value'])
        ->toBe(config('site.facts.repos_display'));

    config(['site.facts.repos_display' => '~21']);
    expect($repository->find('jacobs-mission-software')['metrics'][1]['value'])->toBe('~21');

    config(['site.facts.repos_display' => null]);
    expect(fn () => $repository->all())->toThrow(UnexpectedValueException::class, 'repos_display');
});

it('prepares case study navigation and media without template-specific branching', function () {
    $project = ProjectCatalog::findOrFail('jacobs-mission-software');
    $page = new CaseStudyPage($project, true);
    expect(array_column($page->toc, 'id'))->toBe([
        'problem', 'decisions', 'delivery-system', 'snapshot', 'outcome',
        'scope', 'leadership', 'delivery-practices', 'related',
    ])->and($page->gallery)->toBe([])
        ->and($page->hasDiagram)->toBeTrue()
        ->and($page->hasScope)->toBeTrue();

    $flood = new CaseStudyPage(ProjectCatalog::findOrFail('flood-mapping-system'), false);
    expect($flood->gallery)->toHaveCount(2)
        ->and($flood->gallery[0])->toHaveKeys(['src', 'alt', 'label', 'position'])
        ->and(array_column($flood->toc, 'id'))->not->toContain('scope', 'related');
});
