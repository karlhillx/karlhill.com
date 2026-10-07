<?php

use App\Support\BlogPost;
use App\Support\BlogPostRepository;
use Carbon\CarbonImmutable;

it('post with a static card uses the generated jpg', function (string $slug) {
    $post = app(BlogPostRepository::class)->findOrFail($slug);

    $url = $post->ogImageUrl();
    expect($url)->toContain('/img/og/blog/')
        ->and($url)->toEndWith('.jpg');

    $dimensions = getimagesize(public_path("img/og/blog/{$slug}.jpg"));
    expect($dimensions)->not->toBeFalse()
        ->and($dimensions[0])->toBe(1200)
        ->and($dimensions[1])->toBe(630)
        ->and($dimensions['mime'])->toBe('image/jpeg');
})->with([
    'release-governance',
    'engineering-system-is-a-product',
    'integration-is-not-a-phase',
    'standardize-repositories-without-centralizing-decisions',
]);

it('post without a static card falls back to the homepage og image', function () {
    $post = new BlogPost(
        slug: 'a-post-without-a-hand-made-card',
        title: 'A Post Without A Hand-Made Card',
        excerpt: 'Testing the OG fallback.',
        publishedAt: CarbonImmutable::parse('2026-01-01'),
        updatedAt: null,
        tags: ['testing'],
        heroImage: '/img/some-hero.jpg',
        bodyHtml: '<p>Body</p>',
        bodyMarkdown: 'Body',
        sourcePath: 'fake.md',
        devToId: null,
        readMinutes: 3,
        tableOfContents: [],
    );

    expect($post->ogImageUrl())
        ->toEndWith('/img/og-home.jpg')
        ->not->toContain('/og/blog/');
});

it('runtime og png route is retired', function () {
    $this->get('/og/blog/release-governance.png')->assertNotFound();
});

it('homepage og generator does not print next-role copy', function () {
    $script = file_get_contents(base_path('scripts/generate-og-images.py'));

    expect($script)->toContain('Staff Aerospace Software Engineer')
        ->and($script)->not->toContain('Principal or Engineering Manager');
});
