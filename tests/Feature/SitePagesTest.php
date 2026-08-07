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
        $response->assertSee('id="how-i-lead"', escape: false);
        $response->assertSee('How I lead', escape: false);
        $response->assertSee('lead-principles', escape: false);
        $response->assertSee('1:1s that surface risk', escape: false);
        $response->assertSee('Standards over heroics', escape: false);
        $response->assertSee('id="experience"', escape: false);
        $response->assertSee('id="credentials"', escape: false);
        $response->assertSee('GeoHorizons', escape: false);
        $response->assertSee('arc behind the work', escape: false);
        $response->assertSee('cloud-native platforms for aerospace, NASA', escape: false);
        $response->assertSee('SSAI / NASA Goddard Space Flight Center', escape: false);
        $response->assertSee('Verizon Business', escape: false);
        $response->assertSee('$105M', escape: false);
        $response->assertSee('Ticomix', escape: false);
        $response->assertSee('Certified ScrumMaster', escape: false);
        $response->assertSee('href="/work/flood-mapping-system"', escape: false);
        $response->assertSee('href="/work/nasa-earth-observatory"', escape: false);
        $response->assertSee('href="/work/finium"', escape: false);
        $response->assertSee('Beyond the work', escape: false);
        $response->assertSee('multi-environment release readiness', escape: false);
        $response->assertSee('Flood Mapping System on AWS', escape: false);
        $response->assertSee('Laravel-based case management platform', escape: false);
        $response->assertSee('cut backlog ~90%', escape: false);
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
        $caseStudy->assertSee('case-study-media', escape: false);
        $caseStudy->assertSee('Case study', escape: false);
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
        $response->assertSee('href="/now"', escape: false);
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
        $response->assertSee('Team &amp; leadership', escape: false);
        $response->assertSee('Hard decision', escape: false);
    }

    public function test_now_page_renders_focus_and_em_intent(): void
    {
        $response = $this->get('/now');

        $response->assertStatus(200);
        $response->assertSee('Engineering Manager', escape: false);
        $response->assertSee('Aerospace platform delivery', escape: false);
        $response->assertSee('August 7, 2026', escape: false);
        $response->assertSee('href="/about#how-i-lead"', escape: false);
        $response->assertSee('For recruiters', escape: false);
        $response->assertSee('id="contact-form"', escape: false);
        $response->assertSee('name="return_to"', escape: false);
        $response->assertSee('id="contact"', escape: false);
        $response->assertSee('Get in Touch', escape: false);
    }

    public function test_about_and_resume_pages_include_contact_and_live_cv(): void
    {
        $about = $this->get('/about');
        $about->assertStatus(200);
        $about->assertSee('id="contact-form"', escape: false);
        $about->assertSee('href="/resume"', escape: false);
        $about->assertSee('id="contact"', escape: false);

        $resume = $this->get('/resume');
        $resume->assertStatus(200);
        $resume->assertSee('Staff Aerospace Software Engineer', escape: false);
        $resume->assertSee('Jacobs', escape: false);
        $resume->assertSee('id="contact-form"', escape: false);
        $resume->assertSee('Print / Save PDF', escape: false);
        $resume->assertDontSee('<a href="/work/flood-mapping-system"', escape: false);
    }

    public function test_booking_cta_appears_when_configured(): void
    {
        config(['site.booking.url' => 'https://cal.com/example', 'site.booking.label' => 'Book a conversation']);

        $now = $this->get('/now');
        $now->assertStatus(200);
        $now->assertSee('https://cal.com/example', escape: false);
        $now->assertSee('Book a conversation', escape: false);

        $home = $this->get('/');
        $home->assertSee('data-booking-url="https://cal.com/example"', escape: false);
    }

    public function test_service_worker_and_offline_page_are_available(): void
    {
        $this->assertFileExists(public_path('sw.js'));
        $this->assertFileExists(public_path('offline.html'));
        $this->assertStringContainsString("You're offline", (string) file_get_contents(public_path('offline.html')));
        $this->assertStringContainsString('karlhill-offline-v3', (string) file_get_contents(public_path('sw.js')));
        $this->assertStringContainsString("'/now'", (string) file_get_contents(public_path('sw.js')));
        $this->assertStringContainsString("'/about'", (string) file_get_contents(public_path('sw.js')));
        $this->assertStringContainsString("'/work'", (string) file_get_contents(public_path('sw.js')));
        $this->assertStringContainsString("'/resume'", (string) file_get_contents(public_path('sw.js')));
    }

    public function test_footer_includes_site_explore_links(): void
    {
        $response = $this->get('/work');

        $response->assertSee('aria-label="Site"', escape: false);
        $response->assertSee('Explore', escape: false);
        $response->assertSee('href="/now"', escape: false);
    }

    public function test_homepage_hero_links_to_em_funnel(): void
    {
        $response = $this->get('/');

        $response->assertSee('>Now<', escape: false);
        $response->assertSee('href="/now"', escape: false);
        $response->assertSee('How I Lead', escape: false);
        $response->assertSee('href="/about#how-i-lead"', escape: false);
        $response->assertSee('Open to Engineering Manager', escape: false);
    }

    public function test_sitemap_includes_now_and_resume_pages(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee('/now', escape: false);
        $response->assertSee('/resume', escape: false);
        $response->assertSee('<priority>0.9</priority>', escape: false);
    }
}
