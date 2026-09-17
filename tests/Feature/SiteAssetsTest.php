<?php

use App\Support\BlogPostRepository;
use App\Support\Images;

it('configured site image paths exist', function () {
    $paths = [];

    $paths[] = '/img/profile.jpg';
    $paths[] = '/img/webp/profile.webp';
    $paths[] = '/img/og-home.jpg';
    $paths[] = '/img/favicon.svg';
    $paths[] = '/img/favicon-48x48.png';
    $paths[] = '/favicon.ico';
    $paths[] = config('site.research.image');

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
    $this->assertSame('https://karlhill.com/', $manifest['id']);
    $this->assertNotEmpty($manifest['description']);
    $this->assertContains('portfolio', $manifest['categories']);
    $this->assertContains('standalone', $manifest['display_override']);
    $this->assertSame(['/kit', '/now', '/work'], array_column($manifest['shortcuts'], 'url'));
    $this->assertFileExists(public_path('img/maskable-192x192.png'));
    $this->assertFileExists(public_path('img/maskable-512x512.png'));
    $this->assertTrue(
        collect($manifest['icons'])->contains(fn ($icon) => ($icon['purpose'] ?? '') === 'maskable'),
    );
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

it('robots txt is google-valid and documents ai preferences as comments', function () {
    $path = public_path('robots.txt');
    $this->assertFileExists($path);

    $body = file_get_contents($path);
    expect($body)->not->toMatch('/^Content-Signal:/m')
        ->and($body)->toContain('# Content-Signal (contentsignals.org)')
        ->and($body)->toContain('Sitemap: https://karlhill.com/sitemap.xml')
        ->and($body)->toContain('/.well-known/agent-card.json')
        ->and($body)->toContain('User-agent: *');
});

it('progressive css is linked for selectors lightningcss cannot parse', function () {
    $this->assertFileExists(public_path('css/progressive.css'));

    $this->get('/')
        ->assertOk()
        ->assertSee('css/progressive.css', escape: false);
});

it('decoupled print stylesheet is linked with media print', function () {
    $html = $this->get('/')->assertOk()->getContent();

    expect($html)->toContain('media="print"')
        ->and($html)->toMatch('/print(?:-[^"\']+)?\.css/');
});
