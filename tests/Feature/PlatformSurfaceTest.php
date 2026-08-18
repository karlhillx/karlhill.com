<?php

use App\Support\CompressionDictionary;
use Illuminate\Support\Facades\File;

it('sends reporting integrity and nel headers', function () {
    $response = $this->get('/');

    $response->assertOk();
    expect($response->headers->get('Reporting-Endpoints'))->toContain('/report')
        ->and($response->headers->get('Integrity-Policy-Report-Only'))->toContain('blocked-destinations=(script)')
        ->and($response->headers->get('NEL'))->toContain('report_to')
        ->and($response->headers->get('Permissions-Policy'))->toContain('web-share=(self)')
        ->and($response->headers->get('Available-Dictionary'))->toStartWith(':');
});

it('accepts reporting api posts', function () {
    $path = storage_path('app/reports/latest.json');
    if (is_file($path)) {
        unlink($path);
    }

    $this->postJson('/report', [
        ['type' => 'csp-violation', 'url' => 'https://karlhill.com/'],
    ])->assertNoContent();

    expect(is_file($path))->toBeTrue();
});

it('serves a compression dictionary with use-as-dictionary', function () {
    $this->get('/dict/html-shell.dat')
        ->assertOk()
        ->assertHeader('Use-As-Dictionary')
        ->assertHeader('Content-Type', 'application/octet-stream');

    expect(strlen(CompressionDictionary::bytes()))->toBeGreaterThan(32);
});

it('generates content credential sidecars', function () {
    $this->artisan('credentials:generate')->assertExitCode(0);

    $path = public_path('files/content-credentials.json');
    expect($path)->toBeFile();

    $json = json_decode((string) File::get($path), true);
    expect($json['claim_generator'])->toBe('karlhill.com/credentials')
        ->and($json['assets'])->toBeArray()->not->toBeEmpty();

    $this->get('/api/credentials.json')
        ->assertOk()
        ->assertJsonPath('claim_generator', 'karlhill.com/credentials');

    $this->get('/kit')->assertSee('Content credentials', escape: false);

    File::delete($path);
    $sidecar = public_path(ltrim((string) config('site.footer.resume'), '/').'.c2pa.json');
    if (is_file($sidecar)) {
        File::delete($sidecar);
    }
});

it('nav uses invoker commands and work cards use interest invokers', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('command="toggle-popover"', escape: false)
        ->assertSee('commandfor="command-palette"', escape: false);

    $this->get('/work')
        ->assertOk()
        ->assertSee('interestfor="work-preview-', escape: false)
        ->assertSee('popover="hint"', escape: false)
        ->assertSee('data-soft-nav', escape: false)
        ->assertSee('data-soft-nav-target', escape: false);
});

it('flood case study includes the webgpu canvas hook', function () {
    $this->get('/work/flood-mapping-system')
        ->assertOk()
        ->assertSee('data-webgpu-flood', escape: false)
        ->assertSee('data-features="', escape: false);

    $html = $this->get('/work/flood-mapping-system')->getContent();
    expect($html)->toContain('webgpu');
});

it('blog index includes interest previews and highlight is on posts', function () {
    $this->get('/blog')
        ->assertOk()
        ->assertSee('interestfor="post-preview-', escape: false);

    $html = $this->get('/blog/release-governance')->assertOk()->getContent();
    expect($html)->toContain('highlight');
});
