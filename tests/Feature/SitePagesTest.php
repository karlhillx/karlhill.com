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

    public function test_work_cards_link_to_case_studies_and_live_projects(): void
    {
        $response = $this->get('/work');

        $response->assertSee('nasa-earth-observatory', escape: false);
        $response->assertSee('Read case study', escape: false);

        $caseStudy = $this->get('/work/nasa-earth-observatory');
        $caseStudy->assertStatus(200);
        $caseStudy->assertSee('Visit live project', escape: false);
        $caseStudy->assertSee('https://earthobservatory.nasa.gov', escape: false);
    }

    public function test_case_study_pages_are_in_sitemap(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertSee('/work/nasa-earth-observatory', escape: false);
        $response->assertSee('/work/flood-mapping-system', escape: false);
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

    public function test_work_tag_route_filters_projects(): void
    {
        $response = $this->get('/work/tag/laravel');

        $response->assertStatus(200);
        $response->assertSee('NASA Earth Observatory', escape: false);
        $response->assertSee('/work/tag/laravel', escape: false);
    }

    public function test_case_study_includes_navigation_and_structured_data(): void
    {
        $response = $this->get('/work/flood-mapping-system');

        $response->assertStatus(200);
        $response->assertSee('Case study navigation', escape: false);
        $response->assertSee('Related projects', escape: false);
        $response->assertSee('CreativeWork', escape: false);
    }

    public function test_footer_includes_site_explore_links(): void
    {
        $response = $this->get('/work');

        $response->assertSee('aria-label="Site"', escape: false);
        $response->assertSee('Explore', escape: false);
    }

    public function test_homepage_hero_links_to_work(): void
    {
        $response = $this->get('/');

        $response->assertSee('View Work', escape: false);
        $response->assertSee('href="/work"', escape: false);
    }
}
