<?php

use App\Support\CompressionDictionary;
use App\Support\IntegrityPolicy;
use App\Support\ReportingStore;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

it('sends reporting integrity and nel headers', function () {
    $response = $this->get('/');

    $response->assertOk();
    expect($response->headers->get('Reporting-Endpoints'))->toContain('/report')
        ->and($response->headers->get(IntegrityPolicy::headerName()))->toContain('blocked-destinations=(script)')
        ->and($response->headers->get('NEL'))->toContain('report_to')
        ->and($response->headers->get('Permissions-Policy'))->toContain('web-share=(self)')
        ->and($response->headers->get('Permissions-Policy'))->toContain('compute-pressure=(self)')
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

it('detects integrity violations in retained reports', function () {
    $path = storage_path('app/reports/latest.json');
    if (is_file($path)) {
        unlink($path);
    }

    expect(ReportingStore::hasIntegrityViolations())->toBeFalse();

    ReportingStore::record(['type' => 'integrity-violation', 'url' => 'https://karlhill.com/build/app.js']);

    expect(ReportingStore::hasIntegrityViolations())->toBeTrue();
});

it('keeps integrity policy report-only until sri and clean reports allow enforce', function () {
    config(['site.integrity_policy' => 'report-only']);
    expect(IntegrityPolicy::shouldEnforce())->toBeFalse()
        ->and(IntegrityPolicy::headerName())->toBe('Integrity-Policy-Report-Only');

    config(['site.integrity_policy' => 'auto']);
    // Pest pins INTEGRITY_POLICY=report-only via phpunit.xml; flipping config
    // still needs Vite SRI in the manifest before auto can enforce.
    expect(IntegrityPolicy::manifestHasIntegrity())->toBeTrue();
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

it('nav uses invoker commands and blog cards use interest invokers', function () {
    $this->get('/')
        ->assertOk()
        ->assertSee('command="toggle-popover"', escape: false)
        ->assertSee('commandfor="command-palette"', escape: false)
        ->assertSee('data-theme-toggle', escape: false)
        ->assertSee('<search', escape: false);

    $this->get('/work')
        ->assertOk()
        ->assertDontSee('interestfor="work-preview-', escape: false)
        ->assertSee('data-soft-nav', escape: false)
        ->assertSee('data-soft-nav-target', escape: false);

    $this->get('/blog')
        ->assertOk()
        ->assertSee('interestfor="post-preview-', escape: false)
        ->assertSee('popover="hint"', escape: false)
        ->assertSee('data-soft-nav', escape: false)
        ->assertSee('data-soft-nav-target', escape: false);
});

it('flood case study gates webgpu when the feature is on', function () {
    $html = $this->get('/work/flood-mapping-system')->assertOk()->getContent();

    expect($html)
        ->toContain('data-webgpu-flood')
        ->and($html)->toMatch('/data-features="[^"]*\bwebgpu\b/');

    config(['site.features.webgpu' => false]);
    $off = $this->get('/work/flood-mapping-system')->assertOk()->getContent();
    expect($off)
        ->not->toContain('data-webgpu-flood')
        ->and($off)->not->toMatch('/data-features="[^"]*\bwebgpu\b/');
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

it('keeps summarizer on essays and on-device ask on kit and resume', function () {
    $this->get('/about')
        ->assertOk()
        ->assertDontSee('data-on-device-summary', escape: false)
        ->assertDontSee('data-on-device-ask', escape: false);

    $this->get('/kit')
        ->assertOk()
        ->assertSee('data-on-device-ask', escape: false)
        ->assertSee('data-ask-from="[data-ask-source]"', escape: false)
        ->assertSee('data-ask-brief', escape: false)
        ->assertSee('What is Karl open to?', escape: false)
        ->assertSee('What is the current work?', escape: false)
        ->assertSee('What public evidence is there?', escape: false)
        ->assertSee('Ask a hiring question', escape: false)
        ->assertSee('hidden="until-found"', escape: false);

    $this->get('/resume')
        ->assertOk()
        ->assertSee('data-on-device-ask', escape: false)
        ->assertSee('What is the current role?', escape: false);

    $html = $this->get('/blog/release-governance')->assertOk()->getContent();
    expect($html)
        ->toContain('data-on-device-summary')
        ->and($html)->toMatch('/data-features="[^"]*\bsummarizer\b/');
});

it('plausible ships a first-party fetch later transport instead of their script', function () {
    $response = $this->get('/');
    $html = $response->assertOk()->getContent();
    $csp = (string) $response->headers->get('Content-Security-Policy');

    expect($html)
        ->toContain('window.__siteAnalytics')
        ->and($html)->toContain("'plausible'")
        ->and($html)->not->toContain('plausible.io/js/script.js')
        ->and($html)->not->toContain('src="https://plausible.io');

    preg_match('/script-src ([^;]+)/', $csp, $scriptSrc);
    preg_match('/connect-src ([^;]+)/', $csp, $connectSrc);

    expect($scriptSrc[1] ?? '')->not->toContain('plausible.io')
        ->and($connectSrc[1] ?? '')->toContain('https://plausible.io');
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
        ->and($response->headers->get('Available-Dictionary'))->toBeNull()
        ->and((string) $response->headers->get('Link'))->not->toContain('compression-dictionary');
});
