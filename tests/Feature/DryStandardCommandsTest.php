<?php

use DryStandard\Paths;
use DryStandard\StillPipeline;

it('validates and builds The Dry Standard site', function () {
    $this->artisan('dry-standard:validate')->assertSuccessful();
    $this->artisan('dry-standard:validate', [
        'slug' => 'leitz-eins-zwei-zero-riesling',
        '--publish' => true,
    ])->assertSuccessful();
    $this->artisan('dry-standard:build')->assertSuccessful();
    $this->artisan('dry-standard:status')
        ->expectsOutputToContain('Completeness')
        ->assertSuccessful();
});

it('audits Dry Standard stills', function () {
    $this->artisan('dry-standard:audit-stills', [
        'slug' => 'leitz-eins-zwei-zero-riesling',
    ])->assertSuccessful();
    $this->artisan('dry-standard:audit-stills', [
        'slug' => 'penns-best-lager',
    ])->assertFailed();
});

it('normalizes a Dry Standard still onto white', function () {
    $paths = Paths::default();
    $slug = 'giesen-0-rose';
    $jpeg = $paths->path('media/reviews/'.$slug.'.jpg');
    $backup = sys_get_temp_dir().DIRECTORY_SEPARATOR.'tds-normalize-backup-'.$slug.'.jpg';
    expect(is_file($jpeg))->toBeTrue();
    copy($jpeg, $backup);

    try {
        $this->artisan('dry-standard:normalize-stills', [
            'slug' => $slug,
            '--white' => true,
        ])->assertSuccessful();

        $out = imagecreatefromjpeg($jpeg);
        $rgb = imagecolorat($out, 8, 8);
        expect(($rgb >> 16) & 255)->toBe(255)
            ->and(($rgb >> 8) & 255)->toBe(255)
            ->and($rgb & 255)->toBe(255);
    } finally {
        copy($backup, $jpeg);
        @unlink($backup);
        (new StillPipeline($paths))->ensureDerivatives($jpeg);
    }
});

it('refuses to queue a duplicate product', function () {
    $this->artisan('dry-standard:queue', [
        'product' => 'Run Wild IPA',
        '--brand' => 'Athletic Brewing',
    ])->assertFailed();
});
