<?php

it('fails for an unknown slug', function () {
    $this->artisan('post:publish', ['slug' => 'no-such-post'])
        ->expectsOutputToContain('No post found')
        ->assertExitCode(1);
});

it('accepts a known slug when asset steps are skipped', function () {
    $this->artisan('post:publish', [
        'slug' => 'release-governance',
        '--skip-assets' => true,
        '--skip-og' => true,
    ])
        ->expectsOutputToContain('Publishing')
        ->expectsOutputToContain('/blog/release-governance')
        ->assertExitCode(0);
});
