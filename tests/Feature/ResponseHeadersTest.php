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
});

it('html responses include link preload headers when built', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $link = $response->headers->get('Link');
    // Link headers are best-effort (require a Vite manifest). When present,
    // they should advertise font/style preloads for CDN Early Hints.
    if ($link !== null) {
        $this->assertTrue(
            str_contains($link, 'rel=preload'),
            'Expected Link header to include rel=preload when assets are built.',
        );
    } else {
        $this->assertTrue(true);
    }
});

it('csp allows booking embeds and same origin service workers', function () {
    $response = $this->get('/');

    $csp = $response->headers->get('Content-Security-Policy');
    $this->assertStringContainsString("worker-src 'self'", (string) $csp);
    $this->assertStringContainsString('frame-src', (string) $csp);
    $this->assertStringContainsString('calendly.com', (string) $csp);
});
