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
    $response->assertSee('LAADS DAAC', escape: false);
    $response->assertSee('jacobs-mission-software', escape: false);
    $response->assertSee('id="open-source"', escape: false);
    $response->assertSee('scroll-progress', escape: false);
    $response->assertDontSee('section-rail', escape: false);
});

it('about page renders leadership, delivery, experience, impact, and research', function () {
    $response = $this->get('/about');

    $response->assertStatus(200);
    $response->assertSee('About Karl', escape: false);
    $response->assertSee('id="how-i-lead"', escape: false);
    $response->assertSee('Technical leadership', escape: false);
    $response->assertSee('lead-principles', escape: false);
    $response->assertSee('Sequence the work', escape: false);
    $response->assertSee('Put the bar in the system', escape: false);
    $response->assertSee('Review as teaching', escape: false);
    $response->assertSee('id="delivery"', escape: false);
    $response->assertSee('Engineering delivery', escape: false);
    $response->assertSee('Definition of Done', escape: false);
    $response->assertSee('Pull request rubric', escape: false);
    $response->assertSee('Sorry About Your Daughter', escape: false);
    $response->assertSee('SSAI / NASA Goddard Space Flight Center', escape: false);
    $response->assertSee('GeoHorizons', escape: false);
    $response->assertSee('id="experience"', escape: false);
    $response->assertSee('Career', escape: false);
    $response->assertSee('Jacobs — National Security', escape: false);
    $response->assertSee('Python services, shared interfaces, CI/CD, and coaching across about 20 repositories.', escape: false);
    $response->assertSee('NASA is the public proof', escape: false);
    $response->assertSee('SSAI / NASA Goddard Space Flight Center', escape: false);
    $response->assertSee('InformedDNA', escape: false);
    $response->assertSee('href="/resume"', escape: false);
    $response->assertSee('ss-geohorizons', escape: false);
    $response->assertSee('Karl M. Hill', escape: false);
    $response->assertSee('Published online 5 May 2026', escape: false);
    $response->assertSee('Global Water and Flood Mapping System', escape: false);
    $response->assertSee('Beyond the work', escape: false);
    $response->assertSee('id="beyond"', escape: false);
    $response->assertSee('href="#delivery"', escape: false);
    $response->assertSee('id="how-i-lead"', escape: false);
    $response->assertSee('aria-label="On this page"', escape: false);
    $response->assertSee('href="#how-i-lead"', escape: false);
    $response->assertSee('id="impact"', escape: false);
    $response->assertSee('href="/resume#credentials"', escape: false);
    $response->assertSee('Education, certifications, and technical skills are on the', escape: false);
    $response->assertDontSee('Verify credential', escape: false);
    $response->assertSee('href="/kit"', escape: false);
    $response->assertSee('Recruiter kit', escape: false);
    $response->assertDontSee('href="/lead"', escape: false);
    $response->assertDontSee('href="#stack"', escape: false);
    $response->assertDontSee('id="stack"', escape: false);
    $response->assertDontSee('Certified ScrumMaster', escape: false);
    $response->assertDontSee('Open conversations', escape: false);
});

it('homepage is a focused landing page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertDontSee('id="writing"', escape: false);
    $response->assertDontSee('id="why"', escape: false);
    $response->assertDontSee('id="impact"', escape: false);
    $response->assertSee('id="work"', escape: false);
    $response->assertSee('id="system"', escape: false);
    $response->assertSee('id="path"', escape: false);
    $response->assertSee('How software gets delivered', escape: false);
    $response->assertSee('role="radiogroup"', escape: false);
    $response->assertSee('Ruff', escape: false);
    $response->assertSee('mutation testing', escape: false);
    $response->assertSee('distributed services', escape: false);
    $response->assertSee('environment promotion', escape: false);
    $response->assertSee('href="/about#delivery"', escape: false);
    $response->assertDontSee('Cloud &amp; Containers', escape: false);
    $response->assertSee('View all work', escape: false);
    $response->assertSee('Jacobs is current. NASA Earth science systems from Goddard are still public.', escape: false);
    $response->assertSee('logo-jacobs-mark', escape: false);
    $response->assertSee('work-card--logo', escape: false);
    $response->assertDontSee('work-card--constraint', escape: false);
    $response->assertSee('hero-mesh', escape: false);
    $response->assertSee('hero-dot-grid', escape: false);
    $response->assertDontSee('hero-visual__scrim', escape: false);
    $response->assertDontSee('hero--visual', escape: false);
    $response->assertDontSee('page-spotlight', escape: false);
    $response->assertDontSee('hero-arc', escape: false);
    $response->assertSee('data-idle-cta', escape: false);
    $response->assertSee('data-features="contact reveal media"', escape: false);
    $response->assertSee('id="contact-form"', escape: false);
    $response->assertSee('OG cards in Python', escape: false);
    $response->assertSee('scripts/generate-og-images.py', escape: false);
    $response->assertDontSee('id="experience"', escape: false);
    $response->assertDontSee('id="open-source"', escape: false);
});

it('work cards link to case studies and live projects', function () {
    $response = $this->get('/work');

    $response->assertSee('laads-daac', escape: false);
    $response->assertSee('Read case study', escape: false);
});

it('work index shows a single domain facet and project count', function () {
    $count = ProjectCatalog::listed()->count();

    $this->get('/work')
        ->assertOk()
        ->assertSee('aria-label="Filter by domain"', escape: false)
        // Four flagship cards don't get a second, scrolling stack facet or a sticky bar.
        ->assertDontSee('aria-label="Filter by stack"', escape: false)
        ->assertDontSee('tag-filter--scroll', escape: false)
        ->assertDontSee('site-toolbar--sticky', escape: false)
        ->assertSee((string) $count, escape: false)
        ->assertDontSee('Clear filter', escape: false);

    $this->get('/work/tag/kubernetes')
        ->assertOk()
        ->assertSee('Clear filter', escape: false)
        ->assertSee('Kubernetes', escape: false);
});

it('case study pages expose skim path, toc, and lightbox', function () {
    $caseStudy = $this->get('/work/laads-daac');
    $caseStudy->assertStatus(200);
    $caseStudy->assertSee('Open Find Data', escape: false);
    $caseStudy->assertSee('https://ladsweb.modaps.eosdis.nasa.gov/search/', escape: false);
    $caseStudy->assertSee('case-study-media', escape: false);
    $caseStudy->assertSee('Case study', escape: false);
    $caseStudy->assertSee('case-study-glance', escape: false);
    $caseStudy->assertSee('id="overview"', escape: false);
    $caseStudy->assertSee('id="snapshot"', escape: false);
    $caseStudy->assertSee('id="platform"', escape: false);
    $caseStudy->assertSee('Choose collections', escape: false);
    $caseStudy->assertSee('id="article-toc"', escape: false);
    $caseStudy->assertSee('data-lightbox-open', escape: false);
    $caseStudy->assertSee('data-media-lightbox', escape: false);
    $caseStudy->assertSee('>Outcome</h2>', escape: false);
    $caseStudy->assertSee('>Stack</h2>', escape: false);
    $caseStudy->assertSee('>Role</h2>', escape: false);
    $caseStudy->assertSee('id="decisions"', escape: false);
    $caseStudy->assertSee('case-study-brief__arc', escape: false);
    $caseStudy->assertSee('case-study-brief__step', escape: false);
    $caseStudy->assertSee('Decisions', escape: false);
    $caseStudy->assertSee('Find Data', escape: false);
    $caseStudy->assertSee('Lead Software Engineer', escape: false);
});

it('earth observatory study links the live publishing site', function () {
    $this->get('/work/nasa-earth-observatory')
        ->assertOk()
        ->assertSee('1.5 million monthly visitors', escape: false)
        ->assertSee('Open Earth Observatory', escape: false)
        ->assertSee('https://earthobservatory.nasa.gov/', escape: false)
        ->assertDontSee('Visit live project', escape: false);
});

it('flagship flood mapping case study centers decisions and latency', function () {
    $this->get('/work/flood-mapping-system')
        ->assertOk()
        ->assertSee('id="decisions"', escape: false)
        ->assertSee('Python', escape: false)
        ->assertSee('repeatable processing', escape: false)
        ->assertSee('Open the live map', escape: false)
        ->assertSee('https://floodmapping.gsfc.nasa.gov/', escape: false);
});

it('laads daac case study centers find data and delivery', function () {
    $this->get('/work/laads-daac')
        ->assertOk()
        ->assertSee('Find Data', escape: false)
        ->assertSee('https://ladsweb.modaps.eosdis.nasa.gov/search/', escape: false)
        ->assertSee('Kubernetes', escape: false)
        ->assertSee('GitLab', escape: false);
});

it('case study pages are in sitemap', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertSee('/work/nasa-earth-observatory', escape: false);
    $response->assertSee('/work/flood-mapping-system', escape: false);
    $response->assertSee('/work/laads-daac', escape: false);
});

it('sitemap includes work and about pages', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertSee('/work', escape: false);
    $response->assertSee('/about', escape: false);
    $response->assertSee('/kit', escape: false);
    $response->assertDontSee('>https://karlhill.com/lead</loc>', escape: false);
    $response->assertDontSee('/lead</loc>', escape: false);
});

it('nav links to primary hire path', function () {
    $response = $this->get('/');

    $response->assertSee('href="/work"', escape: false);
    $response->assertSee('href="/kit"', escape: false);
    $response->assertSee('href="/blog"', escape: false);
    $response->assertSee('href="/now#book"', escape: false);
});

it('work tag route filters projects', function () {
    $response = $this->get('/work/tag/kubernetes');

    $response->assertStatus(200);
    $response->assertSee('LAADS DAAC', escape: false);
    $response->assertSee('/work/tag/kubernetes', escape: false);
    $response->assertDontSee('Also shipped at NASA Goddard', escape: false);
});

it('case studies with empty metrics hide the facts strip', function () {
    $html = $this->get('/work/informeddna-platform')->assertOk()->getContent();

    expect($html)
        ->not->toContain('data-final="$30K"')
        ->and(substr_count($html, 'case-study-facts__row'))->toBe(0);
});

it('jacobs scale facts remain in the snapshot footer', function () {
    $jacobs = $this->get('/work/jacobs-mission-software')->assertOk()->getContent();

    expect($jacobs)
        ->toContain('case-study-facts')
        ->toContain('case-study-media__footer')
        ->toContain('data-final="~10"')
        ->toContain('data-final="~20"');

    $eo = $this->get('/work/nasa-earth-observatory')->assertOk()->getContent();
    expect($eo)->not->toContain('data-final="1.5M+"');

    $esscor = $this->get('/work/esscor')->assertOk()->getContent();
    expect($esscor)->not->toContain('data-final="~60%"');
});

it('case study includes navigation and structured data', function () {
    $response = $this->get('/work/flood-mapping-system');

    $response->assertStatus(200);
    $response->assertSee('Case study navigation', escape: false);
    $response->assertSee('Related projects', escape: false);
    $response->assertSee('CreativeWork', escape: false);
    $response->assertSee('Team &amp; contribution', escape: false);
    $response->assertSee('Key decision', escape: false);
    $response->assertSee('id="leadership"', escape: false);
    $response->assertSee('article-sticky-title', escape: false);
});

it('resume page shows the phone number when opted in', function () {
    config()->set('site.resume.phone_on_web', true);

    $this->get('/resume')
        ->assertOk()
        ->assertSee('(202) 599-1442', escape: false)
        ->assertSee('href="tel:+12025991442"', escape: false);
});

it('now page renders focus and em intent', function () {
    $response = $this->get('/now');

    $response->assertStatus(200);
    $response->assertSee('Engineering Manager', escape: false);
    $response->assertSee('Jacobs', escape: false);
    $response->assertSee('September 10, 2026', escape: false);
    $response->assertSee('href="/kit"', escape: false);
    $response->assertSee('Recruiter kit', escape: false);
    $response->assertSee('Hiring', escape: false);
    $response->assertSee('Current engineering system, public NASA software', escape: false);
    $response->assertDontSee('id="contact-form"', escape: false);
    $response->assertSee('id="contact"', escape: false);
    $response->assertSee('id="focus"', escape: false);
    $response->assertDontSee('section-rail', escape: false);
    $response->assertSee('Schedule a conversation or send email', escape: false);
});

it('about and resume pages include contact and live cv', function () {
    $about = $this->get('/about');
    $about->assertStatus(200);
    $about->assertSee('"@type": "Person"', escape: false);
    $about->assertSee('"@type": "ProfilePage"', escape: false);
    $about->assertSee('"headline":', escape: false);
    $about->assertSee('T00:00:00', escape: false);
    $about->assertDontSee('id="contact-form"', escape: false);
    $about->assertSee('href="/resume"', escape: false);
    $about->assertSee('id="contact"', escape: false);
    $about->assertSee('Schedule a conversation or send email', escape: false);

    $resume = $this->get('/resume');
    $resume->assertStatus(200);
    $resume->assertSee('Staff Aerospace Software Engineer', escape: false);
    $resume->assertSee('Jacobs', escape: false);
    $resume->assertSee('class="resume-doc', escape: false);
    $resume->assertSee('Professional Scrum Master', escape: false);
    $resume->assertSee('Technical Expertise', escape: false);
    $resume->assertDontSee('Selected Leadership Impact', escape: false);
    $resume->assertSee('Core Competencies', escape: false);
    // Phone is PDF-only unless site.resume.phone_on_web opts in.
    $resume->assertDontSee('(202) 599-1442', escape: false);
    $resume->assertSee('Phone on the PDF', escape: false);
    $resume->assertSee('https://karlhill.com', escape: false);
    $resume->assertSee('resume-aside', escape: false);
    $resume->assertSee('id="stack"', escape: false);
    $resume->assertSee('id="credentials"', escape: false);
    $resume->assertDontSee('section-rail', escape: false);
    $resume->assertDontSee('id="contact-form"', escape: false);
    $resume->assertSee('Download PDF', escape: false);
    $resume->assertSee('/files/Karl-Hill-Resume.pdf', escape: false);
    $resume->assertSee('Technical Direction', escape: false);
    $resume->assertSee('Software Engineering', escape: false);
    $resume->assertSee('Agile Delivery', escape: false);
    $resume->assertSee('CI/CD', escape: false);
    $resume->assertSee('bb-run', escape: false);
    $resume->assertSee('testrisk', escape: false);
    $resume->assertDontSee('pipeguard', escape: false);
    $resume->assertSee('Python-based mission software', escape: false);
    $resume->assertSee('messaging integrations', escape: false);
    $resume->assertSee('Onboarded and coached approximately six engineers', escape: false);
    $resume->assertSee('Computer Science coursework', escape: false);
    $resume->assertSee('University of Maryland, Baltimore County', escape: false);
    $resume->assertDontSee('Professional Scrum Developer', escape: false);
    $resume->assertDontSee('Download ATS PDF', escape: false);
    $resume->assertDontSee('Print / Save PDF', escape: false);
    $resume->assertDontSee('<a href="/work/flood-mapping-system"', escape: false);
});

it('resume pdf template lists mypy not ty', function () {
    $html = view('resume.pdf', [
        'person' => config('site.person'),
        'resume' => config('site.resume'),
        'experience' => config('site.experience'),
        'education' => config('site.education', []),
        'certifications' => config('site.certifications', []),
        'stack' => config('site.stack', []),
        'linkedin' => ['url' => 'https://www.linkedin.com/in/khill/'],
        'github' => ['url' => 'https://github.com/karlhillx'],
    ])->render();

    expect($html)
        ->toContain('pytest, mypy, pre-commit')
        ->and($html)->not->toContain('pytest, ty, pre-commit');
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
    $sw = (string) file_get_contents(public_path('sw.js'));
    $this->assertStringContainsString('karlhill-offline-v11', $sw);
    // Readable pages are cached on visit, not precached on install.
    $this->assertStringContainsString("const PRECACHE = ['/offline.html', '/site.webmanifest'];", $sw);
    $this->assertStringContainsString("'/now'", $sw);
    $this->assertStringContainsString("'/about'", $sw);
    $this->assertStringContainsString("'/work'", $sw);
    $this->assertStringContainsString("'/resume'", $sw);
    $this->assertStringContainsString("'/kit'", $sw);
    $this->assertStringNotContainsString("'/lead'", $sw);
});

it('footer includes site explore links', function () {
    $response = $this->get('/work');

    $response->assertSee('aria-label="Site"', escape: false);
    $response->assertSee('Explore', escape: false);
    $response->assertSee('href="/kit"', escape: false);
    $response->assertSee('href="/blog"', escape: false);
    $response->assertDontSee('How I run delivery', escape: false);
});

it('homepage hero links to em funnel', function () {
    $response = $this->get('/');
    $html = $response->assertOk()->getContent();

    $response->assertSee('Book a conversation', escape: false);
    $response->assertSee('href="/now#book"', escape: false);
    $response->assertSee('Recruiter kit', escape: false);
    $response->assertSee('id="contact-form"', escape: false);
    $response->assertDontSee('Resume PDF', escape: false);
    $response->assertSee('Jacobs', escape: false);
    $response->assertDontSee(config('site.hero.subtitle'), escape: false);
    $response->assertSee(config('site.hero.statement'), escape: false);
    $response->assertSee(config('site.hero.lede'), escape: false);
    $response->assertSee('hero-portrait', escape: false);
    $response->assertSee('aria-label="At a glance"', escape: false);
    $response->assertDontSee('Seeking Engineering Manager', escape: false);
    $response->assertDontSee('hero-availability', escape: false);
    $response->assertDontSee('hero-arc', escape: false);
    $response->assertDontSee('aria-label="Career arc"', escape: false);
    $response->assertSee('hero-mesh', escape: false);

    expect($html)->toMatch('/<div class="hero-cta flex[\s\S]*?href="\/kit"[\s\S]*?<\/div>/');

    preg_match('/<div class="hero-cta flex.*?<\/div>/s', $html, $heroCta);
    expect($heroCta[0] ?? '')->toContain('href="/kit"')
        ->and($heroCta[0] ?? '')->not->toContain('href="/work"')
        ->and($heroCta[0] ?? '')->toContain('Recruiter kit');

    foreach (config('site.hero.proof') as $chip) {
        $response->assertSee($chip, escape: false);
    }
});

it('nav includes kit, writing, and one filled booking CTA at every breakpoint', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)
        ->toContain('href="/kit"')
        ->toContain('>Kit</a>')
        ->toContain('href="/blog"')
        ->toContain('>Writing</a>')
        ->toContain('>Contact</a>') // mobile menu + footer keep the contact route
        ->toContain('href="/about"') // mobile More + footer
        ->toContain('href="/resume"') // mobile More + footer
        ->not->toContain('Get in Touch')
        ->not->toContain('href="mailto:'.config('site.person.email').'" class="btn-sweep hidden md:inline-flex');

    // One nav CTA, filled, not split into a desktop "Contact" and a mobile "Book".
    expect(substr_count($html, 'data-analytics-location="nav"'))->toBe(1);
    expect($html)->not->toContain('data-analytics-location="nav-mobile"');
    expect($html)->toMatch('/data-analytics-location="nav"\s+class="btn-accent-fill/');
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
    $system = strpos($html, 'id="system"');
    $path = strpos($html, 'id="path"');
    $contact = strpos($html, 'id="contact"');

    expect($work)->toBeInt()
        ->and($system)->toBeInt()
        ->and($path)->toBeInt()
        ->and($contact)->toBeInt();

    expect($html)->not->toContain('id="writing"')
        ->and($html)->not->toContain('id="why"')
        ->and($html)->not->toContain('id="impact"');

    expect($work)->toBeLessThan($system)
        ->and($system)->toBeLessThan($path)
        ->and($path)->toBeLessThan($contact);
});

it('sitemap includes now and resume pages', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertSee('/now', escape: false);
    $response->assertSee('/resume', escape: false);
    $response->assertSee('/kit', escape: false);
    $response->assertDontSee('/lead</loc>', escape: false);
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
    $response->assertSee('/work/jacobs-mission-software', escape: false);
    $response->assertSee('/work/laads-daac', escape: false);
    $response->assertSee('/work/flood-mapping-system', escape: false);
    $response->assertSee('https://floodmapping.gsfc.nasa.gov/', escape: false);
    $response->assertSee('https://ladsweb.modaps.eosdis.nasa.gov/search/', escape: false);
    $response->assertSee('https://earthobservatory.nasa.gov/', escape: false);
    $response->assertSee('https://doi.org/10.1144/gh2025-7', escape: false);
    $response->assertSee('/about#delivery', escape: false);
    $response->assertSee('Engineering delivery', escape: false);
    $response->assertSee('/#system', escape: false);
    $response->assertSee('How software gets delivered', escape: false);
    $response->assertSee('https://github.com/karlhillx/bb-run', escape: false);
    $response->assertSee(config('site.person.email'), escape: false);
    $response->assertSee('kit-doc', escape: false);
    $response->assertSee('Print kit', escape: false);
    $response->assertSee('href="#contact"', escape: false);
    $response->assertSee('id="kit-glance-heading"', escape: false);
    $response->assertSee('id="kit-links-heading"', escape: false);
    $response->assertSee('kit-links-more', escape: false);
    $response->assertSee('More links', escape: false);
    $response->assertSee('data-print', escape: false);
    $response->assertSee(config('site.person.availability'), escape: false);
    $response->assertSee('Current scope', escape: false);
    $response->assertSee('Owns', escape: false);
    $response->assertSee('Influences', escape: false);
    $response->assertSee('Reserved', escape: false);
    $response->assertSee('Engineering Manager is the next container for this scope', escape: false);
    $response->assertSee('kit-bio', escape: false);
    $response->assertSee('Staff Aerospace Software Engineer at Jacobs', escape: false);
    $response->assertSee('Lead Software Engineer at SSAI supporting NASA Goddard', escape: false);
    $response->assertDontSee('Day to day that means', escape: false);
    $response->assertDontSee('I\'m a Staff Aerospace Software Engineer', escape: false);
    $response->assertDontSee('Also open to Staff/Principal IC', escape: false);
    $response->assertDontSee('Primary ask:', escape: false);
});

it('homepage path strip points recruiters to kit work and book', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)
        ->toContain('id="path"')
        ->toContain('id="system"')
        ->toContain('href="/kit"')
        ->toContain('href="/work"')
        ->toContain('href="/now#book"')
        ->toContain('Recruiter kit')
        ->toContain('Selected work')
        ->toContain('Book a conversation')
        ->not->toContain('id="why"')
        ->not->toContain('I Set the Bar');
});

it('legacy delivery url redirects into about', function () {
    $this->get('/lead')
        ->assertRedirect('/about#delivery');

    $this->get('/about')
        ->assertOk()
        ->assertSee('Engineering delivery', escape: false)
        ->assertSee('Definition of Done', escape: false)
        ->assertSee('Pull request rubric', escape: false)
        ->assertSee('Correctness', escape: false)
        ->assertSee('Evidence', escape: false)
        ->assertSee('Make integration risk visible', escape: false)
        ->assertSee('Make the practices shared', escape: false)
        ->assertSee('id="done"', escape: false)
        ->assertSee('id="reviews"', escape: false)
        ->assertSee('id="risk"', escape: false)
        ->assertSee('id="coaching"', escape: false)
        ->assertSee('href="/kit"', escape: false)
        ->assertDontSee('Kubernetes Mission Mesh', escape: false);
});

it('now page shows a fresh updated date and kit link', function () {
    $this->get('/now')
        ->assertOk()
        ->assertSee('Updated September 10, 2026', escape: false)
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

it('keeps the hire path free of ambient pointer chrome', function () {
    $this->get('/')->assertSee('data-features="contact reveal media"', escape: false);
    $this->get('/now')->assertSee('data-features="reveal"', escape: false);
    $this->get('/work')->assertSee('data-features="reveal media"', escape: false);
    $this->get('/about')->assertSee('data-features="reveal"', escape: false);
    $this->get('/')->assertDontSee('page-spotlight', escape: false);
});

it('only flags the contact chunk where the form renders', function () {
    $this->get('/')->assertSee('data-contact-form', escape: false);

    foreach (['/now', '/work', '/about', '/resume', '/kit', '/blog'] as $path) {
        $html = $this->get($path)->assertOk()->getContent();
        expect($html)->not->toContain('data-contact-form')
            ->and($html)->not->toMatch('/data-features="[^"]*\bcontact\b/');
    }
});

it('only flags the push chunk when a vapid key is configured', function () {
    config(['site.push.public_key' => null]);
    expect($this->get('/blog')->getContent())->not->toMatch('/data-features="[^"]*\bpush\b/');

    config(['site.push.public_key' => 'BExampleKey']);
    expect($this->get('/blog')->getContent())->toMatch('/data-features="[^"]*\bpush\b/');
});
