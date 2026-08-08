<?php

use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('work page renders projects and open source', function () {
    $response = $this->get('/work');

    $response->assertStatus(200);
    $response->assertSee('Selected Work', escape: false);
    $response->assertSee('NASA Earth Observatory', escape: false);
    $response->assertSee('id="open-source"', escape: false);
    $response->assertSee('scroll-progress', escape: false);
    $response->assertSee('section-rail', escape: false);
});

it('about page renders experience and credentials', function () {
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
});

it('homepage is a focused landing page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('id="writing"', escape: false);
    $response->assertSee('id="why"', escape: false);
    $response->assertSee('id="work"', escape: false);
    $response->assertSee('View all work', escape: false);
    $response->assertDontSee('id="experience"', escape: false);
    $response->assertDontSee('id="open-source"', escape: false);
});

it('work cards link to case studies and live projects', function () {
    $response = $this->get('/work');

    $response->assertSee('nasa-earth-observatory', escape: false);
    $response->assertSee('Read case study', escape: false);

    $caseStudy = $this->get('/work/nasa-earth-observatory');
    $caseStudy->assertStatus(200);
    $caseStudy->assertSee('Visit live project', escape: false);
    $caseStudy->assertSee('https://earthobservatory.nasa.gov', escape: false);
    $caseStudy->assertSee('case-study-media', escape: false);
    $caseStudy->assertSee('Case study', escape: false);
});

it('case study pages are in sitemap', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertSee('/work/nasa-earth-observatory', escape: false);
    $response->assertSee('/work/flood-mapping-system', escape: false);
});

it('sitemap includes work and about pages', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertSee('/work', escape: false);
    $response->assertSee('/about', escape: false);
});

it('nav links to primary pages', function () {
    $response = $this->get('/');

    $response->assertSee('href="/work"', escape: false);
    $response->assertSee('href="/about"', escape: false);
    $response->assertSee('href="/blog"', escape: false);
    $response->assertSee('href="/now"', escape: false);
});

it('work tag route filters projects', function () {
    $response = $this->get('/work/tag/laravel');

    $response->assertStatus(200);
    $response->assertSee('NASA Earth Observatory', escape: false);
    $response->assertSee('/work/tag/laravel', escape: false);
});

it('case study includes navigation and structured data', function () {
    $response = $this->get('/work/flood-mapping-system');

    $response->assertStatus(200);
    $response->assertSee('Case study navigation', escape: false);
    $response->assertSee('Related projects', escape: false);
    $response->assertSee('CreativeWork', escape: false);
    $response->assertSee('Team &amp; leadership', escape: false);
    $response->assertSee('Hard decision', escape: false);
});

it('now page renders focus and em intent', function () {
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
});

it('about and resume pages include contact and live cv', function () {
    $about = $this->get('/about');
    $about->assertStatus(200);
    $about->assertSee('id="contact-form"', escape: false);
    $about->assertSee('href="/resume"', escape: false);
    $about->assertSee('id="contact"', escape: false);

    $resume = $this->get('/resume');
    $resume->assertStatus(200);
    $resume->assertSee('Staff Aerospace Software Engineer', escape: false);
    $resume->assertSee('Jacobs', escape: false);
    $resume->assertSee('class="resume-doc', escape: false);
    $resume->assertSee('Professional Scrum Master', escape: false);
    $resume->assertSee('Tech Stack', escape: false);
    $resume->assertSee('Selected Leadership Impact', escape: false);
    $resume->assertSee('Areas of Expertise', escape: false);
    $resume->assertSee('(202) 599-1442', escape: false);
    $resume->assertSee('https://karlhill.com', escape: false);
    $resume->assertSee('resume-aside', escape: false);
    $resume->assertSee('id="contact-form"', escape: false);
    $resume->assertSee('Download PDF', escape: false);
    $resume->assertSee('/files/karlhill-resume.pdf', escape: false);
    $resume->assertDontSee('Download ATS PDF', escape: false);
    $resume->assertDontSee('Print / Save PDF', escape: false);
    $resume->assertDontSee('<a href="/work/flood-mapping-system"', escape: false);
});

it('booking cta appears when configured', function () {
    config(['site.booking.url' => 'https://cal.com/example', 'site.booking.label' => 'Book a conversation']);

    $now = $this->get('/now');
    $now->assertStatus(200);
    $now->assertSee('https://cal.com/example', escape: false);
    $now->assertSee('Book a conversation', escape: false);

    $home = $this->get('/');
    $home->assertSee('data-booking-url="https://cal.com/example"', escape: false);
});

it('service worker and offline page are available', function () {
    $this->assertFileExists(public_path('sw.js'));
    $this->assertFileExists(public_path('offline.html'));
    $this->assertStringContainsString("You're offline", (string) file_get_contents(public_path('offline.html')));
    $this->assertStringContainsString('karlhill-offline-v4', (string) file_get_contents(public_path('sw.js')));
    $this->assertStringContainsString("'/now'", (string) file_get_contents(public_path('sw.js')));
    $this->assertStringContainsString("'/about'", (string) file_get_contents(public_path('sw.js')));
    $this->assertStringContainsString("'/work'", (string) file_get_contents(public_path('sw.js')));
    $this->assertStringContainsString("'/resume'", (string) file_get_contents(public_path('sw.js')));
});

it('footer includes site explore links', function () {
    $response = $this->get('/work');

    $response->assertSee('aria-label="Site"', escape: false);
    $response->assertSee('Explore', escape: false);
    $response->assertSee('href="/now"', escape: false);
});

it('homepage hero links to em funnel', function () {
    $response = $this->get('/');

    $response->assertSee('>Now<', escape: false);
    $response->assertSee('href="/now"', escape: false);
    $response->assertSee('>Work<', escape: false);
    $response->assertSee('href="/work"', escape: false);
    $response->assertSee('Open to Engineering Manager', escape: false);
});

it('homepage sections follow the hire-me funnel order', function () {
    $html = $this->get('/')->assertOk()->getContent();

    $work = strpos($html, 'id="work"');
    $writing = strpos($html, 'id="writing"');
    $why = strpos($html, 'id="why"');
    $impact = strpos($html, 'id="impact"');
    $contact = strpos($html, 'id="contact"');

    expect($work)->toBeInt()
        ->and($writing)->toBeInt()
        ->and($why)->toBeInt()
        ->and($impact)->toBeInt()
        ->and($contact)->toBeInt();

    expect($work)->toBeLessThan($writing)
        ->and($writing)->toBeLessThan($why)
        ->and($why)->toBeLessThan($impact)
        ->and($impact)->toBeLessThan($contact);
});

it('sitemap includes now and resume pages', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertSee('/now', escape: false);
    $response->assertSee('/resume', escape: false);
    $response->assertSee('<priority>0.9</priority>', escape: false);
});
