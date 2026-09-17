<?php

use Illuminate\Foundation\DevCommands;

it('artisan dev skips the unused queue worker', function () {
    $this->artisan('dev:list')->assertSuccessful();

    $names = collect(DevCommands::commands())->pluck('name');

    expect($names)->toContain('server')
        ->and($names)->toContain('vite')
        ->and($names)->not->toContain('queue');
});
