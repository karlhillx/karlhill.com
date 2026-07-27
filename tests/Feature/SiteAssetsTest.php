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

    public function test_web_manifest_includes_required_pwa_fields(): void
    {
        $manifest = json_decode(
            file_get_contents(public_path('site.webmanifest')),
            true,
            flags: JSON_THROW_ON_ERROR,
        );

        $this->assertSame('/', $manifest['start_url']);
        $this->assertNotEmpty($manifest['description']);
        $this->assertContains('portfolio', $manifest['categories']);
    }

    public function test_security_txt_is_present_with_required_fields(): void
    {
        $path = public_path('.well-known/security.txt');
        $this->assertFileExists($path);

        $body = file_get_contents($path);
        $this->assertStringContainsString('Contact: mailto:karlhillx@gmail.com', $body);
        $this->assertStringContainsString('Canonical: https://karlhill.com/.well-known/security.txt', $body);
        $this->assertStringContainsString('Expires:', $body);
    }
}
