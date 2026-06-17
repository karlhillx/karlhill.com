<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class SitePagesTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_work_page_renders_projects_and_open_source(): void
    {
        $response = $this->get('/work');

        $response->assertStatus(200);
        $response->assertSee('Selected Work', escape: false);
        $response->assertSee('NASA Earth Observatory', escape: false);
        $response->assertSee('id="open-source"', escape: false);
        $response->assertSee('scroll-progress', escape: false);
        $response->assertSee('section-rail', escape: false);
    }

    public function test_about_page_renders_experience_and_credentials(): void
    {
        $response = $this->get('/about');

        $response->assertStatus(200);
        $response->assertSee('About Karl', escape: false);
        $response->assertSee('id="experience"', escape: false);
        $response->assertSee('id="credentials"', escape: false);
        $response->assertSee('GeoHorizons', escape: false);
    }

    public function test_homepage_is_a_focused_landing_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('id="writing"', escape: false);
        $response->assertSee('id="why"', escape: false);
        $response->assertSee('id="work"', escape: false);
        $response->assertSee('View all work', escape: false);
        $response->assertDontSee('id="experience"', escape: false);
        $response->assertDontSee('id="open-source"', escape: false);
    }

    public function test_work_cards_link_to_project_urls(): void
    {
        $response = $this->get('/work');

        $response->assertSee('href="https://earthobservatory.nasa.gov"', escape: false);
        $response->assertSee('href="https://floodmap.web.nasa.gov"', escape: false);
    }

    public function test_sitemap_includes_work_and_about_pages(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee('/work', escape: false);
        $response->assertSee('/about', escape: false);
    }

    public function test_nav_links_to_primary_pages(): void
    {
        $response = $this->get('/');

        $response->assertSee('href="/work"', escape: false);
        $response->assertSee('href="/about"', escape: false);
        $response->assertSee('href="/blog"', escape: false);
    }
}
