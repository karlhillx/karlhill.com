<?php

use App\Support\ProjectCatalog;
use Illuminate\Support\Facades\Blade;

it('renders appropriate artifact types for each project group', function (string $slug, string $expectedClass, string $expectedContent) {
    $project = ProjectCatalog::find($slug);
    expect($project)->not->toBeNull();

    $rendered = Blade::render('<x-site.project-visual :project="$project" />', ['project' => $project]);

    expect($rendered)
        ->toContain($expectedClass)
        ->toContain($expectedContent);
})->with([
    ['jacobs-mission-software', 'project-visual--diagram', 'DEVSECOPS GATE'],
    ['flood-mapping-system', 'project-visual--screenshot', 'floodmapping.gsfc.nasa.gov'],
    ['laads-daac', 'project-visual--screenshot', 'ladsweb.modaps.eosdis.nasa.gov'],
    ['developer-tooling', 'project-visual--terminal', 'testrisk --changed'],
    ['the-dry-standard', 'project-visual--screenshot', 'drinkdrystandard.com'],
]);

it('supports compact variant on project visuals', function () {
    $project = ProjectCatalog::find('jacobs-mission-software');
    $rendered = Blade::render('<x-site.project-visual :project="$project" :compact="true" />', ['project' => $project]);

    expect($rendered)->toContain('project-visual--compact');
});
