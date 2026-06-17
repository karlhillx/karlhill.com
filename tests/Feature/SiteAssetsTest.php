<?php

namespace Tests\Feature;

use App\Support\BlogPostRepository;
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

        $posts = app(BlogPostRepository::class)->all();
        foreach ($posts as $post) {
            if ($post->heroImage) {
                $paths[] = '/'.ltrim($post->heroImage, '/');
                $paths[] = '/img/og/blog/'.$post->slug.'.jpg';
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
