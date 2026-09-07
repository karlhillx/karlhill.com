<?php

use App\Support\CompressionDictionary;
use App\Support\ReportingStore;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

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

it('forwards browser reports to the log so they reach the alert sink', function () {
    Log::spy();

    $this->postJson('/report', [
        ['type' => 'csp-violation', 'url' => 'https://karlhill.com/'],
        ['type' => 'network-error', 'body' => ['phase' => 'dns']],
    ])->assertNoContent();

    Log::shouldHaveReceived('log')
        ->once()
        ->withArgs(function (string $level, string $message, array $context): bool {
            return $level === 'warning'
                && str_contains($message, 'csp-violation')
                && str_contains($message, 'network-error')
                && ($context['count'] ?? null) === 2;
        });
});

it('does not log browser reports when the reporting log level is none', function () {
    config(['site.reporting_log_level' => 'none']);
    Log::spy();

    $this->postJson('/report', [['type' => 'csp-violation']])->assertNoContent();

    Log::shouldNotHaveReceived('log');
});

it('drops browser reports when the reporting surface is off', function () {
    config(['site.features.reporting' => false]);
    $path = storage_path('app/reports/latest.json');
    if (is_file($path)) {
        unlink($path);
    }

    $this->postJson('/report', [['type' => 'csp-violation']])->assertNoContent();

    expect(is_file($path))->toBeFalse();
});

it('keeps only the latest fifty browser reports', function () {
    $path = storage_path('app/reports/latest.json');
    if (is_file($path)) {
        unlink($path);
    }

    for ($i = 1; $i <= 55; $i++) {
        ReportingStore::record(['type' => 'csp-violation', 'n' => $i]);
    }

    $stored = json_decode((string) file_get_contents($path), true);
    expect($stored['reports'])->toHaveCount(50)
        ->and($stored['reports'][0]['report']['n'])->toBe(6)
        ->and($stored['reports'][49]['report']['n'])->toBe(55);
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
        ->assertDontSee('data-soft-nav', escape: false)
        ->assertDontSee('data-soft-nav-target', escape: false);
});

it('flood case study keeps an optional webgpu canvas hook', function () {
    $html = $this->get('/work/flood-mapping-system')->assertOk()->getContent();

    expect($html)
        ->toContain('data-webgpu-flood')
        ->and($html)->not->toMatch('/data-features="[^"]*\bwebgpu\b/');
});

it('blog index includes interest previews and highlight is on posts', function () {
    $this->get('/blog')
        ->assertOk()
        ->assertSee('interestfor="post-preview-', escape: false);

    $html = $this->get('/blog/release-governance')->assertOk()->getContent();
    expect($html)->toContain('highlight');
});

it('contact error fixture is uncached and exposes invalid fields', function () {
    $this->get('/__a11y/contact-errors')
        ->assertOk()
        ->assertSee('aria-invalid="true"', escape: false)
        ->assertSee('id="a11y-name-error"', escape: false)
        ->assertSee('noindex', escape: false);

    $cache = (string) $this->get('/__a11y/contact-errors')->headers->get('Cache-Control');
    expect($cache)->toContain('no-store');
});

it('does not load summarizer chrome on the hire path', function () {
    $this->get('/kit')
        ->assertOk()
        ->assertDontSee('data-on-device-summary', escape: false);

    $this->get('/about')
        ->assertOk()
        ->assertDontSee('data-on-device-summary', escape: false);

    $html = $this->get('/blog/release-governance')->assertOk()->getContent();
    expect($html)
        ->not->toContain('data-on-device-summary')
        ->and($html)->not->toMatch('/data-features="[^"]*\bsummarizer\b/');
});

it('omits reporting and dictionary headers when those features are off', function () {
    config([
        'site.features.reporting' => false,
        'site.features.compression_dictionary' => false,
    ]);

    $response = $this->get('/');
    $response->assertOk();
    expect($response->headers->get('Reporting-Endpoints'))->toBeNull()
        ->and($response->headers->get('NEL'))->toBeNull()
        ->and($response->headers->get('Available-Dictionary'))->toBeNull();
});
