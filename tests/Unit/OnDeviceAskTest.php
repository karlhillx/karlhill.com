<?php

use App\Support\OnDeviceAsk;

it('builds a resume brief with the current role and next-step copy', function () {
    $brief = OnDeviceAsk::resumeBrief(
        config('site.person'),
        config('site.resume'),
        config('site.experience'),
    );

    expect($brief)
        ->toContain('Open to:')
        ->toContain('Current role:')
        ->toContain('Sept 2025')
        ->toContain('Research:')
        ->toContain('GeoHorizons');
});
