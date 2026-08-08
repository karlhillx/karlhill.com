<?php

use App\Support\BlogPost;
use App\Support\BlogPostRepository;
use Carbon\CarbonImmutable;

function firstOgSlug(): string
{
    $post = app(BlogPostRepository::class)->all()->first();

    if ($post === null) {
        skip('No blog posts available to render an OG card.');
    }

    return $post->slug;
}

it('blog og route returns a png', function () {
    if (! function_exists('imagettftext')) {
        skip('GD with FreeType is required to render OG cards.');
    }

    $response = $this->get('/og/blog/'.firstOgSlug().'.png');

    $response->assertOk();
    $response->assertHeader('Content-Type', 'image/png');

    $info = getimagesizefromstring($response->getContent());

    expect($info)->not->toBeFalse()
        ->and($info[0])->toBe(1200)
        ->and($info[1])->toBe(630);
});

it('unknown slug returns 404', function () {
    $this->get('/og/blog/this-post-does-not-exist.png')->assertNotFound();
});

it('post without static card falls back to the dynamic route', function () {
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
        ->toContain('/og/blog/a-post-without-a-hand-made-card.png')
        ->not->toContain('/img/og/blog/');
});
