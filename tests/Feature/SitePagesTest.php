<?php

use App\Support\PlainText;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('work page renders projects and open source', function () {
    $response = $this->get('/work');

    $response->assertStatus(200);
    $response->assertSee('Mission Software', escape: false);
    $response->assertSee('Platform Engineering / Developer Experience', escape: false);
    $response->assertSee('LAADS DAAC', escape: false);
    $response->assertSee('jacobs-mission-software', escape: false);
    $response->assertSee('id="open-source"', escape: false);
    $response->assertSee('github.com/karlhillx', escape: false);
    $response->assertDontSee('github.com/karlhillx ↗', escape: false);
    $response->assertSee('scroll-progress', escape: false);
    $response->assertDontSee('section-rail', escape: false);
});

it('about page renders career, current focus, opportunities, and music', function () {
    $response = $this->get('/about');

    $response->assertStatus(200);
    $response->assertSee('>About</', escape: false);
    $response->assertDontSee('id="identity"', escape: false);
    $response->assertDontSee('This Karl Hill', escape: false);
    $response->assertDontSee('Karl Hill (Karl M. Hill) is a software engineer in Washington, DC', escape: false);
    $response->assertDontSee('A different person from the Scottish novelist who writes thrillers', escape: false);
    $response->assertSee('Staff Aerospace Software Engineer at Jacobs combining hands-on engineering with cross-program technical leadership', escape: false);
    $response->assertSee('The work connects software other people depend on', escape: false);
    $response->assertDontSee('The work has grown from building software other people depend on', escape: false);
    $response->assertDontSee('From NASA systems at operational scale to Staff-level leadership', escape: false);
    $response->assertDontSee('id="how-i-lead"', escape: false);
    $response->assertDontSee('Technical leadership stays close to the code', escape: false);
    $response->assertDontSee('lead-principles', escape: false);
    $response->assertDontSee('Turn priorities into engineering work', escape: false);
    $response->assertDontSee('Review for correctness and growth', escape: false);
    $response->assertDontSee('Build standards into the system', escape: false);
    $response->assertDontSee('Develop independent engineers', escape: false);
    $response->assertDontSee('Formal personnel management remains with management', escape: false);
    $response->assertDontSee('1:1s that surface risk', escape: false);
    $response->assertDontSee('Sequence the work', escape: false);
    $response->assertDontSee('Put the bar in the system', escape: false);
    $response->assertDontSee('id="delivery"', escape: false);
    $response->assertDontSee('Reliable delivery is an engineering problem', escape: false);
    $response->assertDontSee('How I run delivery', escape: false);
    $response->assertDontSee('The operating principles are straightforward', escape: false);
    $response->assertDontSee('Define scope, ownership, dependencies, and interface assumptions early', escape: false);
    $response->assertDontSee('href="/delivery"', escape: false);
    $response->assertSee('Sorry About Your Daughter', escape: false);
    $response->assertSee('SSAI / NASA Goddard Space Flight Center', escape: false);
    $response->assertSee('id="focus"', escape: false);
    $response->assertSee('id="approach"', escape: false);
    $response->assertSee(config('site.person.availability'), escape: false);
    $response->assertSee(config('site.now.focus'), escape: false);
    $response->assertSee('id="experience"', escape: false);
    $response->assertSee('Career', escape: false);
    $response->assertSee('Jacobs National Security · 2025–present', escape: false);
    $response->assertSee('The common thread has been software that matters operationally', escape: false);
    $response->assertSee('The map, Find Data, and Earth Observatory are public', escape: false);
    $response->assertSee('https://floodmapping.gsfc.nasa.gov/', escape: false);
    $response->assertSee('Open Find Data', escape: false);
    $response->assertSee('/work/jacobs-mission-software', escape: false);
    $response->assertSee('Read the case study', escape: false);
    $response->assertDontSee('Led development of an AWS-based flood-mapping system', escape: false);
    $response->assertDontSee('Modernized LAADS DAAC', escape: false);
    $response->assertDontSee('Modernized NASA Earth Observatory', escape: false);
    $response->assertSee('Earlier engineering work', escape: false);
    $response->assertSee('Before NASA, built case-management, CRM, travel, and enterprise software', escape: false);
    $response->assertDontSee('NASA is the public proof', escape: false);
    $response->assertDontSee('The scope widened', escape: false);
    $response->assertDontSee('InformedDNA', escape: false);
    $response->assertDontSee('Ticomix', escape: false);
    $response->assertDontSee('href="/research/global-flood-mapping"', escape: false);
    $response->assertSee('href="/resume"', escape: false);
    $response->assertDontSee('id="research"', escape: false);
    $response->assertSee('Beyond the work', escape: false);
    $response->assertSee('When not writing software, solving engineering problems, or working with a team', escape: false);
    $response->assertSee('songwriter and musician', escape: false);
    $response->assertSee('Independent label work has also supported', escape: false);
    $response->assertSee('Recording and performance credits are available on', escape: false);
    $response->assertSee('discogs.com', escape: false);
    $response->assertSee('>Discogs</a>', escape: false);
    $response->assertDontSee('I’m a musician', escape: false);
    $response->assertDontSee('I’ve also been involved', escape: false);
    $response->assertDontSee('Drummer in Sorry About Your Daughter, a Washington, DC rock band', escape: false);
    $response->assertDontSee('Atlantic to Adrenaline', escape: false);
    $response->assertDontSee('When I’m not leading or coding, I make music', escape: false);
    $response->assertDontSee('hard problem, whiteboard', escape: false);
    $response->assertSee('id="beyond"', escape: false);
    $response->assertDontSee('aria-label="On this page"', escape: false);
    $response->assertDontSee('id="impact"', escape: false);
    $response->assertDontSee('Experience in numbers', escape: false);
    $response->assertDontSee('Monthly visitors during that work · Earth Observatory', escape: false);
    $response->assertDontSee('Years building software', escape: false);
    $response->assertDontSee('Years on NASA Goddard Earth science systems', escape: false);
    $response->assertDontSee('Repositories across the current environment', escape: false);
    $response->assertDontSee('Selected impact', escape: false);
    $response->assertDontSee('NASA Earth science software supporting disaster response', escape: false);
    $response->assertSee('The full history, technologies, education, and certifications are available on the resume', escape: false);
    $response->assertDontSee('Verify credential', escape: false);
    $response->assertDontSee('href="/kit"', escape: false);
    $response->assertDontSee('>Recruiter Kit</a>', escape: false);
    $response->assertDontSee('Definition of Done', escape: false);
    $response->assertDontSee('Pull request rubric', escape: false);
    $response->assertDontSee('href="/lead"', escape: false);
    $response->assertDontSee('href="#stack"', escape: false);
    $response->assertDontSee('id="stack"', escape: false);
    $response->assertDontSee('Certified ScrumMaster', escape: false);
    $response->assertDontSee('Open conversations', escape: false);
    $response->assertDontSee('$105M', escape: false);
    $response->assertDontSee('The hire ask', escape: false);
    $response->assertDontSee('hero-open', escape: false);
    $response->assertDontSee('at least 80% repository test coverage', escape: false);
    $response->assertDontSee('releases are safer and more predictable', escape: false);
    $response->assertDontSee('href="/music"', escape: false);
});

it('homepage is a focused landing page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertDontSee('id="writing"', escape: false);
    $response->assertDontSee('id="why"', escape: false);
    $response->assertDontSee('id="impact"', escape: false);
    $response->assertSee('id="work"', escape: false);
    $response->assertSee('id="system"', escape: false);
    $response->assertDontSee('id="path"', escape: false);
    $response->assertSee('Make delivery repeatable.', escape: false);
    $response->assertDontSee('role="radiogroup"', escape: false);
    $response->assertSee('href="/work/jacobs-mission-software#delivery-system"', escape: false);
    $response->assertDontSee('mypy', escape: false);
    $response->assertDontSee('mutation testing', escape: false);
    $response->assertDontSee('href="/delivery"', escape: false);
    $response->assertDontSee('Cloud &amp; Containers', escape: false);
    $response->assertSee('All work', escape: false);
    $response->assertDontSee('View all work', escape: false);
    $response->assertDontSee('Also at Goddard', escape: false);
    $response->assertSee('Featured Work', escape: false);
    $response->assertSee('href="'.url('/work/flood-mapping-system').'"', escape: false);
    $response->assertSee('href="'.url('/work/developer-tooling').'"', escape: false);
    $response->assertSee('Flood Mapping System', escape: false);
    $response->assertSee('Engineering the feedback loop', escape: false);
    $response->assertSee('GeoHorizons 2026', escape: false);
    $response->assertDontSee('href="/research/global-flood-mapping"', escape: false);
    $response->assertDontSee('NASA Earth science systems from Goddard are still public.', escape: false);
    $response->assertDontSee('logo-jacobs-mark', escape: false);
    $response->assertDontSee('mission-proof', escape: false);
    $response->assertSee('project-visual', escape: false);
    $response->assertSee('System Topology', escape: false);
    $response->assertSee('portfolio-card__impact', escape: false);
    $response->assertSee('portfolio-card__brief', escape: false);
    $response->assertDontSee('work-card--logo', escape: false);
    $response->assertDontSee('work-card--constraint', escape: false);
    $response->assertDontSee('work-card--scope', escape: false);
    $response->assertSee('portfolio-card--mission', escape: false);
    $response->assertSee('Cross-program mission software', escape: false);
    // Home carries mission, tooling, and flood mapping; the full catalog lives on /work.
    expect(substr_count($response->getContent(), '<article id="'))->toBe(3);
    $response->assertSee('portfolio-card--tooling', escape: false);
    $response->assertSee('id="notes"', escape: false);
    $response->assertSee('All writing', escape: false);
    $response->assertSee('href="/about"', escape: false);
    $response->assertDontSee('>DevSecOps</span>', escape: false);
    $response->assertDontSee('>Repositories</dt>', escape: false);
    $response->assertDontSee('surface-chip-overlay', escape: false);
    $response->assertDontSee('portfolio-card__stack', escape: false);
    $response->assertDontSee('hero-mesh', escape: false);
    $response->assertDontSee('hero-dot-grid', escape: false);
    $response->assertDontSee('hero-visual__scrim', escape: false);
    $response->assertDontSee('hero--visual', escape: false);
    $response->assertDontSee('page-spotlight', escape: false);
    $response->assertDontSee('hero-arc', escape: false);
    $response->assertDontSee('data-idle-cta', escape: false);
    $response->assertSee('data-features="contact reveal media"', escape: false);
    $response->assertSee('id="contact-form"', escape: false);
    $response->assertDontSee('OG cards in Python', escape: false);
    $response->assertDontSee('scripts/generate-og-images.py', escape: false);
    $response->assertDontSee('id="experience"', escape: false);
    $response->assertDontSee('id="open-source"', escape: false);
});

it('work cards lead to case studies rather than competing live project exits', function () {
    $response = $this->get('/work');

    $response->assertSee('laads-daac', escape: false);
    $response->assertSee('Read case study', escape: false);
});

it('work index has no tag filter and legacy tag urls redirect', function () {
    $this->get('/work')
        ->assertOk()
        ->assertDontSee('aria-label="Filter by domain"', escape: false)
        ->assertDontSee('aria-label="Filter by stack"', escape: false)
        ->assertDontSee('tag-filter--scroll', escape: false)
        ->assertDontSee('site-toolbar--sticky', escape: false)
        ->assertDontSee('Clear filter', escape: false)
        ->assertDontSee('>Projects</', escape: false);

    $this->get('/work/tag/kubernetes')
        ->assertRedirect('/work')
        ->assertStatus(301);
});

it('case study pages expose skim path, toc, and lightbox', function () {
    $caseStudy = $this->get('/work/laads-daac');
    $caseStudy->assertStatus(200);
    $caseStudy->assertSee('Open Find Data', escape: false);
    $caseStudy->assertSee('https://ladsweb.modaps.eosdis.nasa.gov/search/', escape: false);
    $caseStudy->assertSee('Broader LAADS site', escape: false);
    $caseStudy->assertSee('href="https://ladsweb.modaps.eosdis.nasa.gov/"', escape: false);
    $caseStudy->assertSee('case-study-media', escape: false);
    $caseStudy->assertSee('Case study', escape: false);
    $caseStudy->assertDontSee('case-study-glance', escape: false);
    $caseStudy->assertDontSee('id="overview"', escape: false);
    $caseStudy->assertSee('id="snapshot"', escape: false);
    $caseStudy->assertDontSee('id="platform"', escape: false);
    $caseStudy->assertDontSee('work-diagram', escape: false);
    $caseStudy->assertSee('id="article-toc"', escape: false);
    $caseStudy->assertSee('data-lightbox-open', escape: false);
    $caseStudy->assertSee('data-media-lightbox', escape: false);
    $caseStudy->assertSee('case-study-masthead__stack', escape: false);
    $caseStudy->assertSee('id="outcome"', escape: false);
    $caseStudy->assertSee('case-study-brief__heading', escape: false);
    $caseStudy->assertDontSee('>Stack</h2>', escape: false);
    $caseStudy->assertDontSee('>Role</h2>', escape: false);
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
    $response->assertSee('/research/global-flood-mapping', escape: false);
    $response->assertDontSee('/kit</loc>', escape: false);
    $response->assertDontSee('>https://karlhill.com/lead</loc>', escape: false);
    $response->assertDontSee('/lead</loc>', escape: false);
});

it('nav links to work about and contact', function () {
    $response = $this->get('/');

    $response->assertSee('href="/work"', escape: false);
    $response->assertSee('href="/about"', escape: false);
    $response->assertSee('href="/#contact"', escape: false);
    $response->assertDontSee('href="/now#book"', escape: false);
});

it('legacy work tag routes redirect to the work index', function () {
    $this->get('/work/tag/kubernetes')
        ->assertRedirect('/work')
        ->assertStatus(301);

    $this->get('/work?tag=kubernetes')
        ->assertRedirect('/work')
        ->assertStatus(301);
});

it('case studies with empty metrics hide the facts strip', function () {
    $html = $this->get('/work/informeddna-platform')->assertOk()->getContent();

    expect($html)
        ->not->toContain('data-final="$30K"')
        ->and(substr_count($html, 'case-study-facts__row'))->toBe(0);
});

it('jacobs scale facts remain readable in the evidence section without animated counters', function () {
    $jacobs = $this->get('/work/jacobs-mission-software')->assertOk()->getContent();

    expect($jacobs)
        ->toContain('case-study-facts')
        ->toContain('id="snapshot"')
        ->toContain('>~10</dd>')
        ->toContain('>~20</dd>')
        ->toContain('>≥80%</dd>')
        ->not->toContain('data-counter', 'logo-ink');

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

it('now redirects directly to booking beside contact', function () {
    $this->get('/now')->assertStatus(301)->assertRedirect('/#book');
    $this->get('/')->assertOk()
        ->assertSee('id="book"', false)
        ->assertSee('booking-embed__frame', false)
        ->assertSee('id="contact-form"', false);
});

it('about and resume pages include contact and live cv', function () {
    $about = $this->get('/about');
    $about->assertStatus(200);
    $about->assertSee('<title>About — Karl Hill</title>', escape: false);
    $about->assertSee('"@type": "Person"', escape: false);
    $about->assertSee('"@type": "ProfilePage"', escape: false);
    $about->assertSee('"headline":', escape: false);
    $about->assertSee('2026-07-07', escape: false);
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
    $resume->assertSee('https://www.credly.com/badges/1874ba29-99d7-4dae-8335-1a915795d956', escape: false);
    $resume->assertSee('https://www.credly.com/badges/da27e50e-ef55-41f0-bc14-ca26d9e3e0ff', escape: false);
    $resume->assertSee('Technical Expertise', escape: false);
    $resume->assertDontSee('Selected Leadership Impact', escape: false);
    $resume->assertSee('Leadership &amp; Engineering Scope', escape: false);
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
    $resume->assertSee('Software Architecture', escape: false);
    $resume->assertSee('Cross-Program Technical Leadership', escape: false);
    $resume->assertSee('Platform Engineering &amp; Developer Experience', escape: false);
    $resume->assertSee('Agile Delivery', escape: false);
    $resume->assertSee('CI/CD', escape: false);
    $resume->assertSee('bb-run', escape: false);
    $resume->assertSee('testrisk', escape: false);
    $resume->assertDontSee('pipeguard', escape: false);
    $resume->assertSee('Provide cross-program technical leadership', escape: false);
    $resume->assertSee('Lead technical execution for a core team of about 10 engineers across roughly 20 Python repositories', escape: false);
    $resume->assertSee('structured growth plans', escape: false);
    $resume->assertDontSee('≥80% repository test coverage', escape: false);
    $resume->assertDontSee('two-approval PR governance', escape: false);
    $resume->assertSee('new PHP applications', escape: false);
    $resume->assertSee('Sabre', escape: false);
    $resume->assertSee('Onboarded and coached approximately six engineers', escape: false);
    $resume->assertSee('Architected and developed an AWS-based platform', escape: false);
    $resume->assertSee('25+ years delivering mission-critical software', escape: false);
    $resume->assertSee('id="resume-publications"', escape: false);
    $resume->assertSee('Peer-reviewed research', escape: false);
    $resume->assertSee('href="/research/global-flood-mapping"', escape: false);
    $resume->assertSee('Software (Equal)', escape: false);
    $resume->assertSee('Delivered LAADS DAAC Find Data search, ordering, and near-real-time access', escape: false);
    $resume->assertSee('Helped rebuild NASA Earth Observatory', escape: false);
    $resume->assertSee('approximately 1.5 million monthly visitors', escape: false);
    $resume->assertSee('NASA Group Achievement Award', escape: false);
    $resume->assertSee('approximately 60%', escape: false);
    $resume->assertSee('Led Agile technical delivery across engineers, scientists, operations teams', escape: false);
    $resume->assertDontSee('Led design and development of an AWS-based flood-mapping system', escape: false);
    $resume->assertDontSee('Modernized LAADS DAAC', escape: false);
    $resume->assertDontSee('Modernized NASA Earth Observatory', escape: false);
    $resume->assertDontSee('roughly 60%', escape: false);
    $resume->assertSee('Computer Science coursework', escape: false);
    $resume->assertSee('University of Maryland, Baltimore County', escape: false);
    $resume->assertSee('Project Management Certificate', escape: false);
    $resume->assertSee('Rutgers University', escape: false);
    $resume->assertDontSee('Project Management studies', escape: false);
    $resume->assertDontSee('Professional Scrum Developer', escape: false);
    $resume->assertDontSee('Download ATS PDF', escape: false);
    $resume->assertDontSee('Print / Save PDF', escape: false);
    $resume->assertDontSee('<a href="/work/flood-mapping-system"', escape: false);
});

it('resume pdf template lists ty not mypy', function () {
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
        ->toContain('uv, Ruff, ty, pytest, pre-commit')
        ->toContain('dependency management, linting, type checking, testing, and automated quality gates')
        ->and($html)->not->toContain('mypy', 'pipeguard');

    $text = preg_replace('/\s+/', ' ', PlainText::fromHtml($html));
    expect($text)
        ->toContain(config('site.experience.intro'))
        ->toContain('Leadership & Engineering Scope');

    foreach (array_merge(config('site.experience.current.highlights'), config('site.experience.roles.0.highlights')) as $bullet) {
        expect($text)->toContain($bullet);
    }

    foreach (config('site.resume.expertise') as $scope) {
        expect($text)->toContain($scope);
    }
});

it('booking cta appears when configured', function () {
    config([
        'site.booking.url' => 'https://cal.com/example',
        'site.booking.label' => 'Book a conversation',
        'site.booking.embed_src' => 'https://cal.com/example?embed=true',
    ]);

    $now = $this->get('/');
    $now->assertStatus(200);
    $now->assertSee('https://cal.com/example', escape: false);
    $now->assertSee('Book a conversation', escape: false);
    $now->assertSee('id="book"', escape: false);
    $now->assertSee('booking-embed__frame', escape: false);
    $now->assertDontSee('data-idle-cta', escape: false);

    $home = $this->get('/');
    $home->assertSee('data-booking-url="https://cal.com/example"', escape: false);
    $home->assertDontSee('data-idle-cta', escape: false);
});

it('service worker and offline page are available', function () {
    $this->assertFileExists(public_path('sw.js'));
    $this->assertFileExists(public_path('offline.html'));
    $this->assertStringContainsString("You're offline", (string) file_get_contents(public_path('offline.html')));
    $sw = (string) file_get_contents(public_path('sw.js'));
    $this->assertStringContainsString('karlhill-offline-v14', $sw);
    // Readable pages are cached on visit, not precached on install.
    $this->assertStringContainsString("const PRECACHE = ['/offline.html', '/site.webmanifest'];", $sw);
    $this->assertStringNotContainsString("'/now'", $sw);
    $this->assertStringContainsString("'/about'", $sw);
    $this->assertStringNotContainsString("'/delivery'", $sw);
    $this->assertStringContainsString("'/privacy'", $sw);
    $this->assertStringContainsString("'/work'", $sw);
    $this->assertStringContainsString("'/resume'", $sw);
    $this->assertStringNotContainsString("'/kit'", $sw);
    $this->assertStringNotContainsString("'/lead'", $sw);
});

it('resume project names link to their configured urls on web and pdf', function () {
    $web = $this->get('/resume')->assertOk();
    $social = collect(config('site.social'));
    $pdf = view('resume.pdf', [
        'person' => config('site.person'),
        'resume' => config('site.resume'),
        'experience' => config('site.experience'),
        'education' => config('site.education', []),
        'certifications' => config('site.certifications', []),
        'stack' => config('site.stack', []),
        'research' => config('site.research', []),
        'linkedin' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'linkedin'),
        'github' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'github'),
    ])->render();

    foreach (array_merge(config('site.resume.products'), config('site.resume.tooling')) as $item) {
        $web->assertSee('href="'.e($item['url']).'"', escape: false);
        expect($pdf)->toContain('<a href="'.e($item['url']).'"><strong>'.e($item['name']).'</strong></a>');
    }

    $safeUrl = 'https://www.credly.com/badges/55c2cb68-b3d6-4da6-8f76-dd6ff2fa37b4/public_url';
    $web->assertSee('href="'.$safeUrl.'"', false)
        ->assertDontSee('https://www.credly.com/earner/earned/share/');
    expect($pdf)->toContain('href="'.$safeUrl.'"')
        ->not->toContain('https://www.credly.com/earner/earned/share/');
    $this->get('/api/site.json')->assertJsonFragment(['url' => $safeUrl]);
});

it('footer exposes secondary utilities without a second sitemap', function () {
    $response = $this->get('/work');

    $response->assertSee('aria-label="Site"', escape: false);
    $response->assertDontSee('href="/kit"', escape: false);
    $response->assertSee('href="/blog"', escape: false);
    $response->assertSee('href="/privacy"', escape: false);
    $response->assertSee('href="/resume"', escape: false);
    $response->assertDontSee('href="/research/global-flood-mapping"', escape: false);
    $response->assertDontSee('How I Deliver', escape: false);
    $response->assertDontSee('How I run delivery', escape: false);
});

it('keeps scholarly indexes on sameAs and off the footer', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)->toContain('https://www.scilit.com/scholars/019f42b58ad870d181875c7fd187375e')
        ->and($html)->toContain('https://sciprofiles.com/profile/author/MlNoK0RnM3hZUE9BRXNSUnhhclJJZz09')
        ->and($html)->not->toContain('aria-label="Scilit')
        ->and($html)->not->toContain('aria-label="SciProfiles');
});

it('homepage hero prioritizes work and contact over hiring documents', function () {
    $response = $this->get('/');
    $html = $response->assertOk()->getContent();

    $response->assertSee('Book a conversation', escape: false);
    $response->assertDontSee('href="/now#book"', escape: false);
    $response->assertDontSee('Recruiter kit', escape: false);
    $response->assertSee('id="contact-form"', escape: false);
    $response->assertDontSee('Resume PDF', escape: false);
    $response->assertSee('Jacobs', escape: false);
    $response->assertDontSee('hero-subtitle', escape: false);
    $response->assertSee(config('site.hero.statement'), escape: false);
    $response->assertSee(config('site.hero.lede'), escape: false);
    $response->assertDontSee('hero-open', escape: false);
    $response->assertDontSee('This month', escape: false);
    $response->assertDontSee('Local checks that match CI', escape: false);
    $response->assertDontSee('Principal Software Engineer or Engineering Manager', escape: false);
    $response->assertDontSee('>Open to</', escape: false);
    $response->assertSee('portfolio-portrait', escape: false);
    $response->assertSee('aria-label="Explore the portfolio"', escape: false);
    $response->assertDontSee('Seeking Engineering Manager', escape: false);
    $response->assertDontSee('hero-availability', escape: false);
    $response->assertDontSee('hero-arc', escape: false);
    $response->assertDontSee('aria-label="Career arc"', escape: false);
    $response->assertDontSee('hero-mesh', escape: false);

    preg_match('/<div data-home-actions.*?<\/div>/s', $html, $heroCta);
    expect($heroCta[0] ?? '')->not->toContain('href="/kit"')
        ->and($heroCta[0] ?? '')->toContain('href="/work"')
        ->and($heroCta[0] ?? '')->toContain('href="/#contact"');
});

it('primary navigation offers work writing about and contact in the bar and adds resume plus email in the drawer', function () {
    $html = $this->get('/about')->assertOk()->getContent();

    $doc = new DOMDocument;
    libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8" ?>'.$html);
    libxml_clear_errors();
    $xpath = new DOMXPath($doc);

    $hrefs = fn (string $query): array => array_map(
        fn (DOMElement $a) => $a->getAttribute('href'),
        iterator_to_array($xpath->query($query), false),
    );

    $bar = '//nav[@aria-label="Primary"]/*[not(@id="mobile-menu")]//a';
    $drawer = '//nav[@aria-label="Primary"]//*[@id="mobile-menu"]//a';

    expect($hrefs($bar))->toContain('/', '/work', '/blog', '/about', '/#contact')
        ->not->toContain('/kit', '/resume')
        ->and($hrefs($drawer))->toContain('/work', '/blog', '/about', '/resume', 'mailto:'.config('site.person.email'))
        ->not->toContain('/kit');

    // Exactly one destination reads as current, in both layouts.
    $current = $xpath->query('//nav[@aria-label="Primary"]//a[@aria-current="page"]');
    expect($current->length)->toBe(2)
        ->and($current->item(0)->getAttribute('href'))->toBe('/about')
        ->and($current->item(1)->getAttribute('href'))->toBe('/about');

    // Search affordance exposes its keyboard shortcut; the drawer toggle controls the drawer.
    expect($xpath->query('//nav[@aria-label="Primary"]//button[@aria-keyshortcuts]')->length)->toBe(1)
        ->and($xpath->query('//nav[@aria-label="Primary"]//button[@aria-controls="mobile-menu"][@aria-expanded="false"]')->length)->toBe(1);

    preg_match('~<nav aria-label="Primary".*?</nav>~s', $html, $matches);
    expect($matches[0])->not->toContain('booking_cta_clicked')->not->toContain('Get in Touch');
});

it('home contact embeds the booking scheduler', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('id="book"', escape: false);
    $response->assertSee('booking-embed', escape: false);
    $response->assertSee('calendly.com/karlhill', escape: false);
});

it('homepage sections follow the hire-me funnel order', function () {
    $html = $this->get('/')->assertOk()->getContent();

    $work = strpos($html, 'id="work"');
    $system = strpos($html, 'id="system"');
    $notes = strpos($html, 'id="notes"');
    $contact = strpos($html, 'id="contact"');

    expect($work)->toBeInt()
        ->and($system)->toBeInt()
        ->and($notes)->toBeInt()
        ->and($contact)->toBeInt();

    expect($html)->not->toContain('id="writing"')
        ->and($html)->not->toContain('id="why"')
        ->and($html)->not->toContain('id="impact"')
        ->and($html)->not->toContain('id="path"');

    expect($work)->toBeLessThan($system)
        ->and($system)->toBeLessThan($notes)
        ->and($notes)->toBeLessThan($contact);
});

it('sitemap includes canonical pages but excludes retired destinations', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertDontSee('/now</loc>', escape: false);
    $response->assertSee('/resume', escape: false);
    $response->assertDontSee('/kit</loc>', escape: false);
    $response->assertDontSee('/delivery</loc>', escape: false);
    $response->assertSee('/privacy', escape: false);
    $response->assertDontSee('/lead</loc>', escape: false);
    $response->assertSee('<priority>0.9</priority>', escape: false);

    $freq = collect(iterator_to_array(simplexml_load_string($response->getContent())->url, false))
        ->mapWithKeys(fn ($url) => [(string) $url->loc => (string) $url->changefreq]);

    expect($freq[config('app.url').'/'])->toBe('weekly')
        ->and($freq[config('app.url').'/work'])->toBe('weekly')
        ->and($freq[config('app.url').'/about'])->toBe('monthly');
});

it('privacy page covers contact booking and analytics', function () {
    $response = $this->get('/privacy');

    $response->assertOk()
        ->assertSee('Privacy', escape: false)
        ->assertSee('Messages you send', escape: false)
        ->assertSee('Scheduling a conversation', escape: false)
        ->assertSee('How visits are measured', escape: false)
        ->assertSee(config('site.person.email'), escape: false)
        ->assertSee('does not sell data, run ads, or keep visitor accounts', escape: false);

    $this->get('/')->assertSee('href="/privacy"', escape: false);
});

it('retired recruiter kit redirects to the consolidated about page', function () {
    $this->get('/kit')->assertStatus(301)->assertRedirect('/about');
    $this->get('/about')->assertOk()
        ->assertSee(config('site.person.availability'), false)
        ->assertSee('How I work', false)
        ->assertSee('href="/resume"', false);
    $this->get('/resume')->assertOk()
        ->assertSee('download="Karl-Hill-Resume.pdf"', false);
});

it('homepage hire exits live in the hero and nav, not a path strip', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)
        ->toContain('id="system"')
        ->not->toContain('href="/kit"')
        ->toContain('href="/work"')
        ->toContain('href="/#contact"')
        ->not->toContain('Recruiter kit')
        ->toContain('Book a conversation')
        ->not->toContain('id="path"')
        ->not->toContain('id="why"')
        ->not->toContain('I Set the Bar');
});

it('both legacy delivery urls redirect directly to the Jacobs practices', function () {
    foreach (['/lead', '/delivery'] as $path) {
        $this->get($path)->assertStatus(301)
            ->assertRedirect('/work/jacobs-mission-software#delivery-practices');
    }

    $this->get('/work/jacobs-mission-software')
        ->assertOk()
        ->assertSee('Engineering delivery', escape: false)
        ->assertSee('Definition of Done', escape: false)
        ->assertSee('Pull request rubric', escape: false)
        ->assertSee('<strong>correctness</strong>', escape: false)
        ->assertSee('<strong>evidence</strong>', escape: false)
        ->assertSee('Make integration risk visible', escape: false)
        ->assertSee('Make the practices shared', escape: false)
        ->assertSee('id="delivery-practices"', escape: false)
        ->assertSee('id="definition-of-done"', escape: false)
        ->assertSee('id="pull-request-rubric"', escape: false)
        ->assertSee('id="make-integration-risk-visible"', escape: false)
        ->assertSee('id="make-the-practices-shared"', escape: false)
        ->assertDontSee('href="/kit"', escape: false)
        ->assertDontSee('Kubernetes Mission Mesh', escape: false);
});

it('about keeps current focus evergreen without advertising retired pages', function () {
    $this->get('/about')
        ->assertOk()
        ->assertSee(config('site.now.focus'), escape: false)
        ->assertDontSee('This month', escape: false)
        ->assertDontSee('Updated September', escape: false)
        ->assertDontSee('formal people-management responsibility', escape: false)
        ->assertDontSee('href="/kit"', escape: false)
        ->assertDontSee('href="/now"', escape: false);
});

it('home curates technical and leadership writing independently of publication order', function () {
    $response = $this->get('/')->assertOk();
    $response->assertSee('Portfolio index / 1997–present', false)
        ->assertSee('Mission software across teams and programs.', false)
        ->assertSeeInOrder(array_map(fn (string $slug): string => 'href="/blog/'.$slug.'"', config('site.writing.featured')), false);

    foreach (['engineering-system-is-a-product', 'integration-is-not-a-phase', 'standardize-repositories-without-centralizing-decisions'] as $slug) {
        $this->get('/blog/'.$slug)->assertOk();
        $this->get('/api/commands.json')->assertSee($slug);
        $this->get('/sitemap.xml')->assertSee('/blog/'.$slug);
    }
});

it('footer utilities are consistent across home and resume', function () {
    $home = $this->get('/')->assertOk()->getContent();
    expect($home)
        ->not->toContain('href="/now"')
        ->toContain('href="/blog"')
        ->not->toContain('Recruiter Kit')
        ->not->toContain('>Delivery</a>');

    $this->get('/resume')
        ->assertOk()
        ->assertDontSee('href="/kit"', escape: false)
        ->assertSee('href="/blog"', escape: false);
});

it('keeps the hire path free of ambient pointer chrome', function () {
    $this->get('/')->assertSee('data-features="contact reveal media"', escape: false);
    $this->get('/work')->assertSee('data-features="reveal media"', escape: false);
    $this->get('/about')->assertSee('data-features="reveal"', escape: false);
    $this->get('/')->assertDontSee('page-spotlight', escape: false);
});

it('only flags the contact chunk where the form renders', function () {
    $this->get('/')->assertSee('data-contact-form', escape: false);

    foreach (['/work', '/about', '/resume', '/blog', '/privacy'] as $path) {
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
