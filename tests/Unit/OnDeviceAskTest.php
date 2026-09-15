<?php

use App\Support\OnDeviceAsk;

it('builds a kit brief that includes what Karl is open to', function () {
    $brief = OnDeviceAsk::kitBrief(config('site.person'), config('site.kit'));

    expect($brief)
        ->toContain('Karl Hill')
        ->toContain('Staff Aerospace Software Engineer')
        ->toContain('Open to:')
        ->toContain('Principal Software Engineer')
        ->toContain('Engineering Manager')
        ->toContain('At a glance:')
        ->toContain('Current scope:')
        ->toContain('Selected evidence:')
        ->toContain('Engineering mission software at scale');
});

it('builds a resume brief with the current role and next-step copy', function () {
    $brief = OnDeviceAsk::resumeBrief(
        config('site.person'),
        config('site.resume'),
        config('site.experience'),
    );

    expect($brief)
        ->toContain('Open to:')
        ->toContain('Current role:')
        ->toContain('Sept 2025');
});
