<?php

use App\Support\BlogPostRepository;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('blog index renders with release governance post', function () {
    $response = $this->get('/blog');

    $response->assertStatus(200);
    $response->assertSee('Notes from', escape: false);
    $response->assertSee('What 20 Years Taught Me About Release Governance', escape: false);
    $response->assertSee('Release governance', escape: false);
});

it('blog show renders for known slug', function () {
    $response = $this->get('/blog/release-governance');

    $response->assertStatus(200);
    $response->assertSee('What 20 Years Taught Me About Release Governance', escape: false);
    $response->assertSee('A release is a decision', escape: false);
    $response->assertSee('rel="canonical" href="', escape: false);
    $response->assertSee('/blog/release-governance', escape: false);
    $response->assertSee('BlogPosting', escape: false);
});

it('blog show returns 404 for unknown slug', function () {
    $response = $this->get('/blog/no-such-post');
    $response->assertStatus(404);
    $response->assertSee('Page not found', escape: false);
});

it('unknown web route renders custom 404', function () {
    $response = $this->get('/this-path-does-not-exist');
    $response->assertStatus(404);
    $response->assertSee('Page not found', escape: false);
    $response->assertSee('name="robots" content="noindex"', escape: false);
});

it('blog post repository parses frontmatter', function () {
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
});

it('atom feed is valid xml', function () {
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
});

it('dynamic sitemap includes blog posts', function () {
    $response = $this->get('/sitemap.xml');

    $response->assertStatus(200);
    $response->assertSee('/blog', escape: false);
    $response->assertSee('/blog/release-governance', escape: false);

    $xml = simplexml_load_string($response->getContent());
    $this->assertNotFalse($xml);
});

it('blog tag route filters posts', function () {
    $response = $this->get('/blog/tag/automation');

    $response->assertStatus(200);
    $response->assertSee('Why Automation Matters More When the Data Is Mission-Critical', escape: false);
    $response->assertDontSee('href="/blog/leading-teams"', escape: false);
    $response->assertSee('/blog/tag/automation', escape: false);
});

it('blog index shows tag counts', function () {
    $response = $this->get('/blog');

    $response->assertOk();
    $response->assertSee('engineering', false);
    // Every published post currently carries the engineering tag.
    $engineeringCount = app(BlogPostRepository::class)->all()
        ->filter(fn ($post) => in_array('engineering', $post->tags, true))
        ->count();

    $response->assertSee(
        'engineering&nbsp;<span class="tabular-nums opacity-60">('.$engineeringCount.')</span>',
        false,
    );
});

it('legacy blog tag query redirects to tag route', function () {
    $response = $this->get('/blog?tag=automation');

    $response->assertRedirect('/blog/tag/automation');
});

it('blog index filters by tag', function () {
    $this->get('/blog/tag/leadership')
        ->assertStatus(200)
        ->assertSee('The Unglamorous Work of Leading Engineering Teams', escape: false)
        ->assertSee('What 20 Years Taught Me About Release Governance', escape: false);
});

it('blog show includes breadcrumbs and related reading', function () {
    $response = $this->get('/blog/release-governance');

    $response->assertStatus(200);
    $response->assertSee('aria-label="Breadcrumb"', escape: false);
    $response->assertSee('On this site', escape: false);
    $response->assertSee('href="/work"', escape: false);
    $response->assertSee('Post navigation', escape: false);
});

it('homepage links to writing', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('href="/blog"', escape: false);
    $response->assertSee('Writing', escape: false);
});

it('share buttons present on post', function () {
    $response = $this->get('/blog/release-governance');

    $response->assertSee('linkedin.com/sharing/share-offsite', escape: false);
    $response->assertSee('twitter.com/intent/tweet', escape: false);
    $response->assertSee('data-copy-link', escape: false);
    $response->assertSee('data-native-share', escape: false);
});

it('json feed is valid', function () {
    $response = $this->get('/feed.json');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/feed+json; charset=utf-8');

    $feed = $response->json();
    $this->assertSame('https://jsonfeed.org/version/1.1', $feed['version']);
    $this->assertNotEmpty($feed['items']);
    $this->assertSame(
        rtrim(config('app.url'), '/').'/blog/release-governance',
        collect($feed['items'])->firstWhere('title', 'What 20 Years Taught Me About Release Governance')['url'] ?? null,
    );
});

it('em craft series appears on posts and index', function () {
    $index = $this->get('/blog');
    $index->assertStatus(200);
    $index->assertSee('Engineering Manager craft', escape: false);
    $index->assertSee('id="em-craft"', escape: false);
    $index->assertSee('series-chapters', escape: false);
    $index->assertSee('Swipe to browse', escape: false);

    $show = $this->get('/blog/staff-to-em-first-90-days');
    $show->assertStatus(200);
    $show->assertSee('aria-label="Series"', escape: false);
    $show->assertSee('Saying No to Roadmap Pressure', escape: false);
    $show->assertSee('aria-label="Series navigation"', escape: false);
    $show->assertDontSee('aria-label="Post navigation"', escape: false);
    $show->assertSee('data-article-sticky-title', escape: false);
    $show->assertSee('series-chapters', escape: false);
    $show->assertSee('Part 1 of 3', escape: false);
});

it('homepage hero is a tight first viewport', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee(config('site.hero.headline'), escape: false);
    $response->assertSee(config('site.hero.positioning'), escape: false);
    $response->assertDontSee(config('site.hero.bio'), escape: false);
    $response->assertDontSee('Platforms · Delivery · Engineering Leadership', escape: false);
});

it('command index includes post body keywords', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    // Phrase from the Staff→EM essay body — proves full-text indexing.
    $response->assertSee('unit of work', escape: false);
});

it('blog show renders heading anchors and table of contents', function () {
    $response = $this->get('/blog/release-governance');

    $response->assertStatus(200);
    $response->assertSee('id="a-release-is-a-decision"', escape: false);
    $response->assertSee('heading-permalink', escape: false);
    $response->assertSee('id="article-toc"', escape: false);
    $response->assertSee('A release is a decision', escape: false);
    $response->assertSee('data-toc-link', escape: false);
});

it('blog show displays updated date when present', function () {
    $response = $this->get('/blog/release-governance');

    $response->assertStatus(200);
    $response->assertSee('Updated', escape: false);
    $response->assertSee('Jun 1, 2026', escape: false);
});

it('blog show includes speculation rules for adjacent posts', function () {
    $response = $this->get('/blog/release-governance');

    $response->assertStatus(200);
    $response->assertSee('<script type="speculationrules"', escape: false);
    $response->assertSee('"/blog"', escape: false);
});

it('layout includes command index for palette search', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('id="command-index"', escape: false);
    $response->assertSee('What 20 Years Taught Me About Release Governance', escape: false);
    $response->assertSee('"/blog/release-governance"', escape: false);
    $response->assertSee('"group":"writing"', escape: false);
    $response->assertSee('"group":"work"', escape: false);
});

it('repository caches primitive arrays only', function () {
    /** @var BlogPostRepository $repository */
    $repository = $this->app->make(BlogPostRepository::class);
    $repository->all();

    $cached = collect(Cache::get(
        'blog.posts.index.'.(function () use ($repository) {
            $reflect = new ReflectionMethod($repository, 'signature');
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
});

it('repository round trips through file cache', function () {
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
});
