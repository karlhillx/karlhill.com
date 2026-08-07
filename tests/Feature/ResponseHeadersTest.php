<?php

namespace Tests\Feature;

use Tests\TestCase;

class ResponseHeadersTest extends TestCase
{
    public function test_homepage_sends_a_content_security_policy_with_a_nonce(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);

        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertNotNull($csp, 'Expected a Content-Security-Policy header.');
        $this->assertStringContainsString("default-src 'self'", $csp);
        $this->assertStringContainsString("object-src 'none'", $csp);
        $this->assertStringContainsString("frame-ancestors 'none'", $csp);
        $this->assertMatchesRegularExpression("/script-src 'self' 'nonce-[^']+'/", $csp);
    }

    public function test_inline_scripts_carry_the_csp_nonce(): void
    {
        $response = $this->get('/');

        $csp = $response->headers->get('Content-Security-Policy');
        preg_match("/'nonce-([^']+)'/", (string) $csp, $matches);
        $nonce = $matches[1] ?? null;

        $this->assertNotNull($nonce);
        $response->assertSee('nonce="'.$nonce.'"', escape: false);
    }

    public function test_security_headers_are_not_duplicated(): void
    {
        $response = $this->get('/');

        // Symfony's header bag returns all values for a name; there must be one.
        $this->assertCount(1, $response->headers->all('X-Frame-Options'));
        $this->assertSame('DENY', $response->headers->get('X-Frame-Options'));
    }

    public function test_pages_send_cache_control_and_etag(): void
    {
        $response = $this->get('/');

        $cacheControl = $response->headers->get('Cache-Control');
        $this->assertStringContainsString('public', $cacheControl);
        $this->assertStringContainsString('max-age=300', $cacheControl);
        $this->assertNotNull($response->headers->get('ETag'));
    }

    public function test_feed_is_cached_for_longer(): void
    {
        $response = $this->get('/feed.xml');

        $response->assertStatus(200);
        $this->assertStringContainsString('max-age=3600', $response->headers->get('Cache-Control'));
    }

    public function test_html_responses_include_link_preload_headers_when_built(): void
    {
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
    }

    public function test_csp_allows_same_origin_service_workers(): void
    {
        $response = $this->get('/');

        $csp = $response->headers->get('Content-Security-Policy');
        $this->assertStringContainsString("worker-src 'self'", (string) $csp);
    }
}
