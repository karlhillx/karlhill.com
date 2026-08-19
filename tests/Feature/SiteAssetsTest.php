<?php

use App\Support\BlogPostRepository;
use App\Support\Images;

it('configured site image paths exist', function () {
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

    foreach (array_unique($paths) as $path) {
        $this->assertFileExists(
            public_path(ltrim($path, '/')),
            "Missing public asset: {$path}",
        );
    }
});

it('web manifest includes required pwa fields', function () {
    $manifest = json_decode(
        file_get_contents(public_path('site.webmanifest')),
        true,
        flags: JSON_THROW_ON_ERROR,
    );

    $this->assertSame('/', $manifest['start_url']);
    $this->assertNotEmpty($manifest['description']);
    $this->assertContains('portfolio', $manifest['categories']);
});

it('image helpers map avif and srcset widths', function () {
    $this->assertSame('/img/avif/blog/release-governance.avif', Images::avif('/img/blog/release-governance.jpg'));
    $this->assertSame('/img/avif/profile.avif', Images::avif('/img/webp/profile.webp'));
    $this->assertSame([400, 800, 1200, 1600], Images::SRCSET_WIDTHS);
});

it('lqip helper maps generated placeholders', function () {
    $this->assertSame(
        '/img/lqip/blog/release-governance.webp',
        Images::lqip('/img/blog/release-governance.jpg'),
    );
    $this->assertFileExists(public_path('img/lqip/blog/release-governance.webp'));
});

it('security txt is present with required fields', function () {
    $path = public_path('.well-known/security.txt');
    $this->assertFileExists($path);

    $body = file_get_contents($path);
    $this->assertStringContainsString('Contact: mailto:karlhillx@gmail.com', $body);
    $this->assertStringContainsString('Canonical: https://karlhill.com/.well-known/security.txt', $body);
    $this->assertStringContainsString('Expires:', $body);
});

it('progressive css is linked for selectors lightningcss cannot parse', function () {
    $this->assertFileExists(public_path('css/progressive.css'));

    $this->get('/')
        ->assertOk()
        ->assertSee('css/progressive.css', escape: false);
});
