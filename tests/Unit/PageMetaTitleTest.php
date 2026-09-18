<?php

use App\Support\PageMeta;
use App\Support\ProjectCatalog;

it('homepage title disambiguates in search and interior titles use a single brand suffix', function () {
    expect(PageMeta::titled('Karl Hill'))->toBe('Karl Hill')
        ->and(PageMeta::titled('Work'))->toBe('Work — Karl Hill')
        ->and(PageMeta::titled('Work — Karl Hill'))->toBe('Work — Karl Hill')
        ->and(PageMeta::home()->title)->toBe('Karl Hill · Staff Aerospace Software Engineer')
        ->and(PageMeta::home()->ogTitle)->toBe(PageMeta::home()->title)
        ->and(PageMeta::about()->title)->toBe('About — Karl Hill')
        ->and(PageMeta::work()->title)->toBe('Work — Karl Hill')
        ->and(PageMeta::blogIndex()->title)->toBe('Writing — Karl Hill')
        ->and(PageMeta::now()->title)->toBe('Now — Karl Hill')
        ->and(PageMeta::kit()->title)->toBe('Recruiter kit — Karl Hill')
        ->and(PageMeta::resume()->title)->toBe('Resume — Karl Hill')
        ->and(PageMeta::delivery()->title)->toBe('Engineering delivery — Karl Hill')
        ->and(PageMeta::privacy()->title)->toBe('Privacy — Karl Hill')
        ->and(PageMeta::research()->title)->toBe('NASA Global Water and Flood Mapping Research — Karl Hill')
        ->and(PageMeta::research()->ogTitle)->toBe(PageMeta::research()->title);

    $flood = ProjectCatalog::findOrFail('flood-mapping-system');
    $jacobs = ProjectCatalog::findOrFail('jacobs-mission-software');

    expect(PageMeta::forProject($flood)->title)->toBe('Flood Mapping System — Karl Hill')
        ->and(PageMeta::forProject($jacobs)->title)->toBe('Engineering mission software at scale — Karl Hill')
        ->and(mb_strlen(PageMeta::research()->description))->toBeLessThanOrEqual(155)
        ->and(PageMeta::research()->description)->toContain('>90%');
});
