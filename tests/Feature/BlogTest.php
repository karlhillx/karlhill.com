<?php

namespace Tests\Feature;

use App\Support\BlogPostRepository;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class BlogTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_blog_index_renders_with_release_governance_post(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('Notes from');
        $response->assertSee('What 20 Years Taught Me About Release Governance', escape: false);
        $response->assertSee('Release governance', escape: false);
    }

    public function test_blog_show_renders_for_known_slug(): void
    {
        $response = $this->get('/blog/release-governance');

        $response->assertStatus(200);
        $response->assertSee('What 20 Years Taught Me About Release Governance', escape: false);
        $response->assertSee('A release is a decision', escape: false);
        $response->assertSee('rel="canonical" href="', escape: false);
        $response->assertSee('/blog/release-governance', escape: false);
        $response->assertSee('BlogPosting', escape: false);
    }

    public function test_blog_show_returns_404_for_unknown_slug(): void
    {
        $response = $this->get('/blog/no-such-post');
        $response->assertStatus(404);
        $response->assertSee('Page not found', escape: false);
    }

    public function test_unknown_web_route_renders_custom_404(): void
    {
        $response = $this->get('/this-path-does-not-exist');
        $response->assertStatus(404);
        $response->assertSee('Page not found', escape: false);
        $response->assertSee('name="robots" content="noindex"', escape: false);
    }

    public function test_blog_post_repository_parses_frontmatter(): void
    {
        /** @var BlogPostRepository $repository */
        $repository = $this->app->make(BlogPostRepository::class);
        $post = $repository->find('release-governance');

        $this->assertNotNull($post);
        $this->assertSame('What 20 Years Taught Me About Release Governance', $post->title);
        $this->assertSame('release-governance', $post->slug);
        $this->assertContains('engineering', $post->tags);
        $this->assertContains('governance', $post->tags);
        $this->assertSame(2026, $post->publishedAt->year);
        $this->assertGreaterThan(0, $post->readMinutes);
        $this->assertStringContainsString('img/blog/release-governance.jpg', $post->heroImage);
    }

    public function test_atom_feed_is_valid_xml(): void
    {
        $response = $this->get('/feed.xml');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/atom+xml; charset=utf-8');

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml, 'Feed should be valid XML');
        $this->assertSame('feed', $xml->getName());
        $this->assertGreaterThan(0, count($xml->entry));
        $body = $response->getContent();
        $this->assertStringContainsString(
            '<id>'.rtrim(config('app.url'), '/').'/feed.xml</id>',
            $body,
            'Feed id should match the self URL (stable syndication identity)',
        );
        $this->assertStringContainsString('<category term="engineering"/>', $body);
        $this->assertStringContainsString('<category term="governance"/>', $body);
    }

    public function test_dynamic_sitemap_includes_blog_posts(): void
    {
        $response = $this->get('/sitemap.xml');

        $response->assertStatus(200);
        $response->assertSee('/blog', escape: false);
        $response->assertSee('/blog/release-governance', escape: false);

        $xml = simplexml_load_string($response->getContent());
        $this->assertNotFalse($xml);
    }

    public function test_homepage_links_to_writing(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('href="/blog"', escape: false);
        $response->assertSee('Writing', escape: false);
    }

    public function test_share_buttons_present_on_post(): void
    {
        $response = $this->get('/blog/release-governance');

        $response->assertSee('linkedin.com/sharing/share-offsite', escape: false);
        $response->assertSee('twitter.com/intent/tweet', escape: false);
        $response->assertSee('data-copy-link', escape: false);
    }

    /**
     * Regression: Laravel 13 ships `serializable_classes => false`, which
     * breaks any cached object graph (Collection / BlogPost / Carbon become
     * __PHP_Incomplete_Class). The repository must cache plain arrays only,
     * and hydrate value objects on read.
     */
    public function test_repository_caches_primitive_arrays_only(): void
    {
        /** @var BlogPostRepository $repository */
        $repository = $this->app->make(BlogPostRepository::class);
        $repository->all();

        $cached = collect(Cache::get(
            'blog.posts.index.'.(function () use ($repository) {
                $reflect = new \ReflectionMethod($repository, 'signature');
                $reflect->setAccessible(true);

                return $reflect->invoke($repository);
            })()
        ));

        $this->assertNotNull($cached);
        $this->assertGreaterThan(0, $cached->count());
        foreach ($cached as $row) {
            $this->assertIsArray($row, 'Cached rows must be plain arrays, not objects.');
            foreach ($row as $value) {
                $this->assertTrue(
                    is_scalar($value) || is_array($value) || is_null($value),
                    'Cached values must be primitives (got '.get_debug_type($value).').',
                );
            }
        }

        $repository->all();
        $this->assertNotNull($repository->find('release-governance'));
    }

    public function test_repository_round_trips_through_file_cache(): void
    {
        config(['cache.default' => 'file']);
        Cache::flush();

        /** @var BlogPostRepository $repository */
        $repository = $this->app->make(BlogPostRepository::class);

        $repository->all();
        $post = $repository->find('release-governance');

        $this->assertNotNull($post);
        $this->assertSame('What 20 Years Taught Me About Release Governance', $post->title);
        $this->assertSame(2026, $post->publishedAt->year);

        Cache::flush();
    }
}
