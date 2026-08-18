<?php

use App\Support\BlogPost;
use App\Support\BlogPostRepository;
use Carbon\CarbonImmutable;

it('post with a static card uses the generated jpg', function () {
    $post = app(BlogPostRepository::class)->all()->first();
    if ($post === null) {
        skip('No blog posts available.');
    }

    $url = $post->ogImageUrl();
    expect($url)->toContain('/img/og/blog/')
        ->and($url)->toEndWith('.jpg');
});

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
