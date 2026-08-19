<?php

use App\Support\ProjectCatalog;
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
    $response->assertSee('Worked with', escape: false);
    $response->assertSee('SSAI / NASA Goddard', escape: false);
    $response->assertSee('id="experience"', escape: false);
    $response->assertSee('id="credentials"', escape: false);
    $response->assertSee('GeoHorizons', escape: false);
    $response->assertSee('ss-geohorizons', escape: false);
    $response->assertSee('Karl M. Hill', escape: false);
    $response->assertSee('Published 5 May 2026', escape: false);
    $response->assertSee('Global Water and Flood Mapping System', escape: false);
    $response->assertSee('arc behind the work', escape: false);
    $response->assertSee('cloud-native platforms for aerospace, NASA', escape: false);
    $response->assertSee('SSAI / NASA Goddard Space Flight Center', escape: false);
    $response->assertSee('Verizon Business', escape: false);
    $response->assertSee('$105M', escape: false);
    $response->assertSee('Ticomix', escape: false);
    $response->assertSee('SAFe® Agilist', escape: false);
    $response->assertSee('In progress', escape: false);
    $response->assertDontSee('Certified ScrumMaster', escape: false);
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
    $response->assertSee('hero-mesh', escape: false);
    $response->assertSee('page-spotlight', escape: false);
    $response->assertSee('magnetic-btn', escape: false);
    $response->assertSee('data-idle-cta', escape: false);
    $response->assertSee('data-features="contact pointer', escape: false);
    $response->assertDontSee('id="experience"', escape: false);
    $response->assertDontSee('id="open-source"', escape: false);
});

it('work cards link to case studies and live projects', function () {
    $response = $this->get('/work');

    $response->assertSee('nasa-earth-observatory', escape: false);
    $response->assertSee('Read case study', escape: false);
});

it('work index shows sticky filter chrome and project count', function () {
    $count = ProjectCatalog::all()->count();

    $this->get('/work')
        ->assertOk()
        ->assertSee('site-toolbar--sticky', escape: false)
        ->assertSee('tag-filter--scroll', escape: false)
        ->assertSee((string) $count, escape: false)
        ->assertDontSee('Clear filter', escape: false);

    $this->get('/work/tag/laravel')
        ->assertOk()
        ->assertSee('Clear filter', escape: false)
        ->assertSee('Laravel', escape: false);
});

it('case study pages expose skim path, toc, and lightbox', function () {
    $caseStudy = $this->get('/work/nasa-earth-observatory');
    $caseStudy->assertStatus(200);
    $caseStudy->assertSee('Visit live project', escape: false);
    $caseStudy->assertSee('https://earthobservatory.nasa.gov', escape: false);
    $caseStudy->assertSee('case-study-media', escape: false);
    $caseStudy->assertSee('Case study', escape: false);
    $caseStudy->assertSee('case-study-glance', escape: false);
    $caseStudy->assertSee('id="overview"', escape: false);
    $caseStudy->assertSee('id="snapshot"', escape: false);
    $caseStudy->assertSee('id="article-toc"', escape: false);
    $caseStudy->assertSee('data-lightbox-open', escape: false);
    $caseStudy->assertSee('data-media-lightbox', escape: false);
    $caseStudy->assertSee('>Outcome</h2>', escape: false);
    $caseStudy->assertSee('>Stack</h2>', escape: false);
    $caseStudy->assertSee('>Role</h2>', escape: false);
    $caseStudy->assertSee('id="decisions"', escape: false);
    $caseStudy->assertSee('>Decisions</h2>', escape: false);
    $caseStudy->assertSee('1.5M+', escape: false);
    $caseStudy->assertSee('Self-serve', escape: false);
});

it('flagship flood mapping case study centers decisions and latency', function () {
    $this->get('/work/flood-mapping-system')
        ->assertOk()
        ->assertSee('id="decisions"', escape: false)
        ->assertSee('Hours', escape: false)
        ->assertSee('Automated pipeline', escape: false)
        ->assertSee('remove humans from the latency path', escape: false);
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
    $response->assertSee('id="leadership"', escape: false);
    $response->assertSee('article-sticky-title', escape: false);
});

it('now page renders focus and em intent', function () {
    $response = $this->get('/now');

    $response->assertStatus(200);
    $response->assertSee('Engineering Manager', escape: false);
    $response->assertSee('Aerospace mission software delivery', escape: false);
    $response->assertSee('August 12, 2026', escape: false);
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
    $resume->assertSee('/files/Karl-Hill-Resume.pdf', escape: false);
    $resume->assertDontSee('Download ATS PDF', escape: false);
    $resume->assertDontSee('Print / Save PDF', escape: false);
    $resume->assertDontSee('<a href="/work/flood-mapping-system"', escape: false);
});

it('booking cta appears when configured', function () {
    config([
        'site.booking.url' => 'https://cal.com/example',
        'site.booking.label' => 'Book a conversation',
        'site.booking.embed_src' => 'https://cal.com/example?embed=true',
    ]);

    $now = $this->get('/now');
    $now->assertStatus(200);
    $now->assertSee('https://cal.com/example', escape: false);
    $now->assertSee('Book a conversation', escape: false);
    $now->assertSee('id="book"', escape: false);
    $now->assertSee('booking-embed__frame', escape: false);
    $now->assertSee('data-idle-cta', escape: false);

    $home = $this->get('/');
    $home->assertSee('data-booking-url="https://cal.com/example"', escape: false);
});

it('service worker and offline page are available', function () {
    $this->assertFileExists(public_path('sw.js'));
    $this->assertFileExists(public_path('offline.html'));
    $this->assertStringContainsString("You're offline", (string) file_get_contents(public_path('offline.html')));
    $this->assertStringContainsString('karlhill-offline-v8', (string) file_get_contents(public_path('sw.js')));
    $this->assertStringContainsString("'/now'", (string) file_get_contents(public_path('sw.js')));
    $this->assertStringContainsString("'/about'", (string) file_get_contents(public_path('sw.js')));
    $this->assertStringContainsString("'/work'", (string) file_get_contents(public_path('sw.js')));
    $this->assertStringContainsString("'/resume'", (string) file_get_contents(public_path('sw.js')));
    $this->assertStringContainsString("'/kit'", (string) file_get_contents(public_path('sw.js')));
});

it('footer includes site explore links', function () {
    $response = $this->get('/work');

    $response->assertSee('aria-label="Site"', escape: false);
    $response->assertSee('Explore', escape: false);
    $response->assertSee('href="/now"', escape: false);
});

it('homepage hero links to em funnel', function () {
    $response = $this->get('/');

    $response->assertSee('Book a conversation', escape: false);
    $response->assertSee('href="/now#book"', escape: false);
    $response->assertSee('>Work<', escape: false);
    $response->assertSee('href="/work"', escape: false);
    $response->assertSee('href="/#contact"', escape: false);
    $response->assertSee('Resume PDF', escape: false);
    $response->assertSee('download="Karl-Hill-Resume.pdf"', escape: false);
    $response->assertSee('Jacobs', escape: false);
    $response->assertSee(config('site.hero.subtitle'), escape: false);
    $response->assertSee('Open to Engineering Manager', escape: false);
});

it('desktop nav includes resume and a single contact CTA', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)
        ->toContain('href="/resume"')
        ->toContain('Get in Touch')
        ->not->toContain('href="mailto:'.config('site.person.email').'" class="btn-sweep hidden md:inline-flex');

    expect(substr_count($html, 'data-nav-section="contact"'))->toBe(1);
});

it('now page embeds the booking scheduler', function () {
    $response = $this->get('/now');

    $response->assertOk();
    $response->assertSee('id="book"', escape: false);
    $response->assertSee('booking-embed', escape: false);
    $response->assertSee('calendly.com/karlhill', escape: false);
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
    $response->assertSee('/kit', escape: false);
    $response->assertSee('<priority>0.9</priority>', escape: false);
});

it('recruiter kit one-pager links resume pdf bio and booking', function () {
    $response = $this->get('/kit');

    $response->assertOk();
    $response->assertSee('Recruiter kit', escape: false);
    $response->assertSee(config('site.footer.resume'), escape: false);
    $response->assertSee('Download resume PDF', escape: false);
    $response->assertSee('download="Karl-Hill-Resume.pdf"', escape: false);
    $response->assertSee('/now#book', escape: false);
    $response->assertSee('/work/nasa-earth-observatory', escape: false);
    $response->assertSee('/work/flood-mapping-system', escape: false);
    $response->assertSee(config('site.person.email'), escape: false);
    $response->assertSee('kit-doc', escape: false);
    $response->assertSee('Print kit', escape: false);
    $response->assertSee('href="#contact"', escape: false);
    $response->assertSee('id="kit-glance-heading"', escape: false);
    $response->assertSee('id="kit-links-heading"', escape: false);
    $response->assertSee('data-print', escape: false);
});

it('now page shows a fresh updated date and kit link', function () {
    $this->get('/now')
        ->assertOk()
        ->assertSee('Updated August 12, 2026', escape: false)
        ->assertSee('href="/kit"', escape: false)
        ->assertSee('Recruiter kit', escape: false);
});

it('footer hides resume and kit self-links', function () {
    $this->get('/resume')
        ->assertOk()
        ->assertSee('href="/kit"', escape: false)
        ->assertSee('Recruiter kit', escape: false);

    $kit = $this->get('/kit')->assertOk()->getContent();
    expect($kit)->toContain('href="/resume"')
        ->and($kit)->not->toContain('btn-sweep inline-flex items-center gap-3 border border-neutral-700 text-neutral-300 font-semibold px-6 py-3 text-xs uppercase tracking-widest w-fit">');
});

it('loads pointer effects on hire and interior pages', function () {
    $this->get('/')->assertSee('data-features="contact pointer', escape: false);
    $this->get('/now')->assertSee('data-features="contact pointer', escape: false);
    $this->get('/work')->assertSee('data-features="contact pointer', escape: false);
    $this->get('/about')->assertSee('data-features="contact pointer', escape: false);
});
