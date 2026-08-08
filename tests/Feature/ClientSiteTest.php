<?php

it('clients index lists staged sites', function () {
    $response = $this->get('/clients');

    $response->assertStatus(200);
    $response->assertSee('Client staging', escape: false);
    $response->assertSee('keithhillmusic.com', escape: false);
    $response->assertSee('name="robots" content="noindex"', escape: false);
    $response->assertSee('href="/clients/keithhillmusic.com/"', escape: false);
});

it('client site serves html with base href', function () {
    foreach (['/clients/keithhillmusic.com', '/clients/keithhillmusic.com/', '/clients/keithhillmusic.com/index.html'] as $url) {
        $html = $this->get($url);
        $html->assertStatus(200);
        $html->assertHeader('X-Robots-Tag', 'noindex, nofollow');
        $html->assertSee('Keith Hill', escape: false);
        $html->assertSee('<base href="/clients/keithhillmusic.com/">', escape: false);
    }
});

it('client site serves static assets', function () {
    $css = $this->get('/clients/keithhillmusic.com/styles.css');
    $css->assertStatus(200);
    $this->assertStringContainsString('text/css', (string) $css->headers->get('content-type'));

    $asset = $this->get('/clients/keithhillmusic.com/assets/keith-live.png');
    $asset->assertStatus(200);
    $this->assertStringContainsString('image/', (string) $asset->headers->get('content-type'));
});

it('client site blocks path traversal and unknown clients', function () {
    $this->get('/clients/keithhillmusic.com/../site.php')->assertNotFound();
    $this->get('/clients/keithhillmusic.com/%2e%2e/config/site.php')->assertNotFound();
    $this->get('/clients/not-a-real-client/')->assertNotFound();
    $this->get('/clients/.hidden/')->assertNotFound();
});

it('clients are not in sitemap', function () {
    $this->get('/sitemap.xml')
        ->assertStatus(200)
        ->assertDontSee('/clients', escape: false);
});

it('octaves of love landing page is served', function () {
    $response = $this->get('/clients/keithhillmusic.com/octaves-of-love/');

    $response->assertStatus(200);
    $response->assertSee('Octaves of Love', escape: false);
    $response->assertSee('Contact@octavesoflove.com', escape: false);
    $response->assertSee('<base href="/clients/keithhillmusic.com/octaves-of-love/">', escape: false);
    $response->assertDontSee('Join the mailing list', escape: false);
});

it('octavesoflove domain stub forwards to landing page', function () {
    $response = $this->get('/clients/octavesoflove.com/');

    $response->assertStatus(200);
    $response->assertSee('/clients/keithhillmusic.com/octaves-of-love/', escape: false);
});
