<?php

namespace Tests\Feature;

use Tests\TestCase;

class ClientSiteTest extends TestCase
{
    public function test_clients_index_lists_staged_sites(): void
    {
        $response = $this->get('/clients');

        $response->assertStatus(200);
        $response->assertSee('Client staging', escape: false);
        $response->assertSee('keithhillmusic.com', escape: false);
        $response->assertSee('name="robots" content="noindex"', escape: false);
        $response->assertSee('href="/clients/keithhillmusic.com/"', escape: false);
    }

    public function test_client_site_serves_html_with_base_href(): void
    {
        foreach (['/clients/keithhillmusic.com', '/clients/keithhillmusic.com/', '/clients/keithhillmusic.com/index.html'] as $url) {
            $html = $this->get($url);
            $html->assertStatus(200);
            $html->assertHeader('X-Robots-Tag', 'noindex, nofollow');
            $html->assertSee('Keith Hill', escape: false);
            $html->assertSee('<base href="/clients/keithhillmusic.com/">', escape: false);
        }
    }

    public function test_client_site_serves_static_assets(): void
    {
        $css = $this->get('/clients/keithhillmusic.com/styles.css');
        $css->assertStatus(200);
        $this->assertStringContainsString('text/css', (string) $css->headers->get('content-type'));

        $asset = $this->get('/clients/keithhillmusic.com/assets/keith-live.png');
        $asset->assertStatus(200);
        $this->assertStringContainsString('image/', (string) $asset->headers->get('content-type'));
    }

    public function test_client_site_blocks_path_traversal_and_unknown_clients(): void
    {
        $this->get('/clients/keithhillmusic.com/../site.php')->assertNotFound();
        $this->get('/clients/keithhillmusic.com/%2e%2e/config/site.php')->assertNotFound();
        $this->get('/clients/not-a-real-client/')->assertNotFound();
        $this->get('/clients/.hidden/')->assertNotFound();
    }

    public function test_clients_are_not_in_sitemap(): void
    {
        $this->get('/sitemap.xml')
            ->assertStatus(200)
            ->assertDontSee('/clients', escape: false);
    }
}
