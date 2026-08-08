<?php

it('same as is derived from social urls', function () {
    $socialUrls = collect(config('site.social'))
        ->pluck('url')
        ->map(fn (string $url) => rtrim($url, '/'))
        ->unique()
        ->values()
        ->all();

    expect(config('site.same_as'))->toBe($socialUrls);
});

it('google is the default analytics provider', function () {
    expect(config('site.analytics.provider'))->toBe('google')
        ->and(config('site.analytics.google.enabled'))->toBeTrue()
        ->and(config('site.analytics.plausible.enabled'))->toBeFalse();
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
