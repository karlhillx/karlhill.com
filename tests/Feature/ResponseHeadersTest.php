<?php

it('homepage sends a content security policy with a nonce', function () {
    $response = $this->get('/');

    $response->assertStatus(200);

    $csp = $response->headers->get('Content-Security-Policy');
    $this->assertNotNull($csp, 'Expected a Content-Security-Policy header.');
    $this->assertStringContainsString("default-src 'self'", $csp);
    $this->assertStringContainsString("object-src 'none'", $csp);
    $this->assertStringContainsString("frame-ancestors 'none'", $csp);
    $this->assertMatchesRegularExpression("/script-src 'self' 'nonce-[^']+'/", $csp);
});

it('inline scripts carry the csp nonce', function () {
    $response = $this->get('/');

    $csp = $response->headers->get('Content-Security-Policy');
    preg_match("/'nonce-([^']+)'/", (string) $csp, $matches);
    $nonce = $matches[1] ?? null;

    $this->assertNotNull($nonce);
    $response->assertSee('nonce="'.$nonce.'"', escape: false);
});

it('security headers are not duplicated', function () {
    $response = $this->get('/');

    // Symfony's header bag returns all values for a name; there must be one.
    $this->assertCount(1, $response->headers->all('X-Frame-Options'));
    $this->assertSame('DENY', $response->headers->get('X-Frame-Options'));
});

it('pages send cache control and etag', function () {
    $response = $this->get('/');

    $cacheControl = $response->headers->get('Cache-Control');
    $this->assertStringContainsString('public', $cacheControl);
    $this->assertStringContainsString('max-age=300', $cacheControl);
    $this->assertNotNull($response->headers->get('ETag'));
});

it('feed is cached for longer', function () {
    $response = $this->get('/feed.xml');

    $response->assertStatus(200);
    $this->assertStringContainsString('max-age=3600', $response->headers->get('Cache-Control'));
    expect($response->headers->get('Set-Cookie'))->toBeNull();
});

it('machine readable json omits session cookies', function () {
    $response = $this->get('/api/site.json');

    $response->assertOk();
    expect($response->headers->get('Set-Cookie'))->toBeNull()
        ->and($response->headers->get('X-Powered-By'))->toBeNull();
});

it('html preloads bebas barlow and jetbrains fonts', function () {
    $response = $this->get('/');
    $html = $response->assertOk()->getContent();

    expect($html)
        ->toContain('rel="preload" as="font" type="font/woff2"')
        ->toContain('bebas-neue-latin-400-normal')
        ->toContain('barlow-semi-condensed-latin-400-normal')
        ->toContain('barlow-semi-condensed-latin-700-normal')
        ->toContain('jetbrains-mono-latin-400-normal')
        ->toContain('jetbrains-mono-latin-500-normal');

    $link = $response->headers->get('Link');
    if ($link !== null) {
        expect($link)
            ->toContain('as=font')
            ->toContain('barlow-semi-condensed')
            ->toContain('jetbrains-mono');
    }
});

it('html responses include link preload headers when built', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $link = $response->headers->get('Link');
    // Link headers are best-effort (require a Vite manifest). When present,
    // they should advertise font/style preloads for CDN Early Hints.
    if ($link !== null) {
        $this->assertTrue(
            str_contains($link, 'rel=preload') || str_contains($link, 'compression-dictionary'),
            'Expected Link header to include rel=preload or compression-dictionary when present.',
        );
    } else {
        $this->assertTrue(true);
    }
});

it('html documents opt into credentialed prerender and ignore tracking params', function () {
    $home = $this->get('/');
    $home->assertOk();
    expect($home->headers->get('Supports-Loading-Mode'))->toBe('credentialed-prerender')
        ->and($home->headers->get('No-Vary-Search'))->toContain('utm_source')
        ->and($home->headers->get('Permissions-Policy'))->toContain('summarizer=(self)');

    $json = $this->get('/api/site.json');
    $json->assertOk();
    expect($json->headers->get('Supports-Loading-Mode'))->toBeNull()
        ->and($json->headers->get('No-Vary-Search'))->toBeNull();
});

it('csp allows booking embeds and same origin service workers', function () {
    $response = $this->get('/');

    $csp = $response->headers->get('Content-Security-Policy');
    $this->assertStringContainsString("worker-src 'self'", (string) $csp);
    $this->assertStringContainsString('frame-src', (string) $csp);
    $this->assertStringContainsString('calendly.com', (string) $csp);
});
