<?php

use App\Support\Booking;

it('same as is derived from schema-eligible social urls', function () {
    $socialUrls = collect(config('site.social'))
        ->filter(fn (array $link) => ($link['schema'] ?? true) !== false)
        ->pluck('url')
        ->map(fn (string $url) => rtrim($url, '/'))
        ->unique()
        ->values()
        ->all();

    expect(config('site.same_as'))->toBe($socialUrls)
        ->and(collect(config('site.same_as'))->implode(' '))->not->toContain('discogs.com')
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

it('experience fragment powers about and resume', function () {
    expect(config('site.experience.current.title'))->not->toBeEmpty()
        ->and(config('site.experience.roles'))->not->toBeEmpty();

    expect(config_path('site/experience.php'))->toBeFile()
        ->and(config_path('site/now.php'))->toBeFile()
        ->and(config_path('site/projects.php'))->toBeFile()
        ->and(config_path('site/resume.php'))->toBeFile();

    expect(config('site.resume.phone'))->not->toBeEmpty()
        ->and(config('site.resume.impact'))->not->toBeEmpty()
        ->and(config('site.resume.expertise'))->not->toBeEmpty();
});
