<?php

namespace Tests\Feature;

use Tests\TestCase;

class SiteAssetsTest extends TestCase
{
    public function test_configured_site_image_paths_exist(): void
    {
        $paths = [];

        $paths[] = '/img/webp/profile.webp';
        $paths[] = '/img/og-home.jpg';

        foreach (config('site.projects', []) as $project) {
            $paths[] = $project['image'];
            if (isset($project['logo']['path'])) {
                $paths[] = $project['logo']['path'];
            }
        }

        $paths[] = config('site.footer.resume');

        foreach (array_unique($paths) as $path) {
            $this->assertFileExists(
                public_path(ltrim($path, '/')),
                "Missing public asset: {$path}",
            );
        }
    }
}
