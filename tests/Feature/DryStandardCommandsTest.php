<?php

it('validates and builds The Dry Standard site', function () {
    $this->artisan('dry-standard:validate')->assertSuccessful();
    $this->artisan('dry-standard:validate', [
        'slug' => 'leitz-eins-zwei-zero-riesling',
        '--publish' => true,
    ])->assertSuccessful();
    $this->artisan('dry-standard:build')->assertSuccessful();
    $this->artisan('dry-standard:status')->assertSuccessful();
});

it('audits Dry Standard stills', function () {
    $this->artisan('dry-standard:audit-stills', [
        'slug' => 'leitz-eins-zwei-zero-riesling',
    ])->assertSuccessful();
    $this->artisan('dry-standard:audit-stills', [
        'slug' => 'be-free-chardonnay',
    ])->assertFailed();
});

it('refuses to queue a duplicate product', function () {
    $this->artisan('dry-standard:queue', [
        'product' => 'Run Wild IPA',
        '--brand' => 'Athletic Brewing',
    ])->assertFailed();
});
