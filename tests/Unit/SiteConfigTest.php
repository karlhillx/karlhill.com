<?php

namespace Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class SiteConfigTest extends TestCase
{
    #[Test]
    public function same_as_is_derived_from_social_urls(): void
    {
        $socialUrls = collect(config('site.social'))
            ->pluck('url')
            ->map(fn (string $url) => rtrim($url, '/'))
            ->unique()
            ->values()
            ->all();

        $this->assertSame($socialUrls, config('site.same_as'));
    }

    #[Test]
    public function google_is_the_default_analytics_provider(): void
    {
        $this->assertSame('google', config('site.analytics.provider'));
        $this->assertTrue(config('site.analytics.google.enabled'));
        $this->assertFalse(config('site.analytics.plausible.enabled'));
    }

    #[Test]
    public function experience_fragment_powers_about_and_resume(): void
    {
        $this->assertNotEmpty(config('site.experience.current.title'));
        $this->assertNotEmpty(config('site.experience.roles'));
        $this->assertFileExists(config_path('site/experience.php'));
        $this->assertFileExists(config_path('site/now.php'));
        $this->assertFileExists(config_path('site/projects.php'));
    }
}
