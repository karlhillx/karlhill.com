<?php

namespace Tests\Feature;

use App\Support\BlogPost;
use App\Support\BlogPostRepository;
use Carbon\CarbonImmutable;
use Tests\TestCase;

class OgImageTest extends TestCase
{
    protected function firstSlug(): string
    {
        $post = app(BlogPostRepository::class)->all()->first();

        if ($post === null) {
            $this->markTestSkipped('No blog posts available to render an OG card.');
        }

        return $post->slug;
    }

    public function test_blog_og_route_returns_a_png(): void
    {
        if (! function_exists('imagettftext')) {
            $this->markTestSkipped('GD with FreeType is required to render OG cards.');
        }

        $response = $this->get('/og/blog/'.$this->firstSlug().'.png');

        $response->assertOk();
        $response->assertHeader('Content-Type', 'image/png');

        $info = getimagesizefromstring($response->getContent());

        $this->assertNotFalse($info);
        $this->assertSame(1200, $info[0]);
        $this->assertSame(630, $info[1]);
    }

    public function test_unknown_slug_returns_404(): void
    {
        $this->get('/og/blog/this-post-does-not-exist.png')->assertNotFound();
    }

    public function test_post_without_static_card_falls_back_to_the_dynamic_route(): void
    {
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
        );

        $this->assertStringContainsString('/og/blog/a-post-without-a-hand-made-card.png', $post->ogImageUrl());
        $this->assertStringNotContainsString('/img/og/blog/', $post->ogImageUrl());
    }
}
