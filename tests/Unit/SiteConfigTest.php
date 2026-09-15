<?php

use App\Support\Booking;

it('same as is derived from schema-eligible social urls', function () {
    $sameAs = collect(config('site.same_as'));

    expect($sameAs)->toContain('https://www.linkedin.com/in/khill')
        ->and($sameAs)->toContain('https://www.discogs.com/artist/1286669-Karl-Hill')
        ->and($sameAs)->toContain('https://orcid.org/0009-0002-6847-3368')
        ->and($sameAs)->toContain('https://scholar.google.com/citations?user=ykw3hstDPLcC')
        ->and($sameAs)->toContain('https://www.wikidata.org/wiki/Q139902938')
        ->and($sameAs)->toContain('https://gravatar.com/karlhillx')
        ->and($sameAs)->toContain('https://www.crunchbase.com/person/karl-hill-09bb')
        ->and($sameAs)->toContain('https://about.me/karlhill')
        ->and($sameAs)->not->toContain('https://en.wikipedia.org/wiki/Karl_Hill_(musician)')
        ->and($sameAs->implode(' '))->not->toContain('superFilter=')
        ->and(collect(config('site.social'))->pluck('url')->implode(' '))->toContain('discogs.com');
});

it('analytics providers are mutually exclusive', function () {
    $google = (bool) config('site.analytics.google.enabled');
    $plausible = (bool) config('site.analytics.plausible.enabled');

    expect($google && $plausible)->toBeFalse();
    expect(config('site.analytics.provider'))->toBeIn(['plausible', 'google', 'none']);
});

it('booking embed src normalizes calendly and cal urls', function () {
    expect(Booking::embedSrc('https://calendly.com/karlhill'))
        ->toContain('hide_gdpr_banner=1')
        ->toContain('hide_landing_page_details=1');

    expect(Booking::embedSrc('https://cal.com/example'))
        ->toContain('embed=true');

    expect(Booking::embedSrc(null))->toBeNull();
    expect(Booking::embedSrc('not-a-url'))->toBeNull();
});

it('experience fragment powers resume and facts stay consistent', function () {
    expect(config('site.experience.current.title'))->not->toBeEmpty()
        ->and(config('site.experience.roles'))->not->toBeEmpty()
        ->and(config('site.experience.current.company'))->toBe(config('site.facts.employer'))
        ->and(config('site.facts.repos'))->toBe('roughly 20')
        ->and(config('site.facts.team'))->toBe('about 10')
        ->and(config('site.hero.proof'))->toContain(config('site.facts.repos_chip'))
        ->and(config('site.kit.glance.0'))->toContain(config('site.facts.repos'))
        ->and(config('site.now.body'))->toContain(config('site.facts.repos'));

    expect(config_path('site/experience.php'))->toBeFile()
        ->and(config_path('site/facts.php'))->toBeFile()
        ->and(config_path('site/now.php'))->toBeFile()
        ->and(config_path('site/work.php'))->toBeFile()
        ->and(config_path('site/projects.php'))->toBeFile()
        ->and(config_path('site/resume.php'))->toBeFile()
        ->and(config_path('site/lead.php'))->toBeFile();

    expect(config('site.resume.phone'))->not->toBeEmpty()
        ->and(config('site.resume.impact'))->toBeEmpty()
        ->and(config('site.resume.expertise'))->not->toBeEmpty();
});

it('keeps the recruiter kit skim to a short primary row and evidence list', function () {
    $links = collect(config('site.kit.links'));
    $primary = $links->where('group', 'primary')->values();
    $more = $links->where('group', 'more')->values();
    $evidence = collect(config('site.kit.evidence'));

    expect($primary)->toHaveCount(4)
        ->and($primary->pluck('meta')->all())->toBe(['Download', 'Profile', 'Book', 'Current'])
        ->and($more->count())->toBe(7)
        ->and($evidence)->toHaveCount(5)
        ->and($evidence->pluck('path')->filter()->values()->all())->toBe([
            '/work/jacobs-mission-software',
            '/work/flood-mapping-system',
            '/work/laads-daac',
            '/work/nasa-earth-observatory',
        ])
        ->and($links->pluck('url')->filter())->toBeEmpty()
        ->and($links->pluck('path')->filter()->values()->all())->not->toContain('/#system');
});
