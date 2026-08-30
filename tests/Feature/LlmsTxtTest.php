<?php

use App\Support\LlmsTxtBuilder;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('llms txt returns a v2 file-list map', function () {
    $response = $this->get('/llms.txt');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');

    $body = $response->getContent();

    $this->assertStringStartsWith('# Karl Hill', $body);
    $this->assertStringContainsString('> '.config('site.seo.home.og_description'), $body);
    $this->assertStringContainsString('open to two paths: Engineering Manager or Staff/Principal roles', $body);
    $this->assertStringContainsString('Last updated', $body);
    $this->assertStringContainsString('August 29, 2026', $body);
    $this->assertStringContainsString('## Pages', $body);
    $this->assertStringContainsString('## Writing', $body);
    $this->assertStringContainsString('## Profiles', $body);
    $this->assertStringContainsString('## Optional', $body);
    $this->assertStringContainsString('## Case studies', $body);
    $this->assertStringContainsString('## Series', $body);
    $this->assertStringContainsString('Engineering Manager craft', $body);
    $this->assertStringContainsString('/work/nasa-earth-observatory', $body);
    $this->assertStringContainsString('/kit', $body);
    $this->assertStringContainsString('/blog/release-governance', $body);
    $this->assertStringContainsString('What 20 Years Taught Me About Release Governance', $body);
    $this->assertStringContainsString('Preferred name Karl Hill', $body);
    $this->assertStringContainsString('https://karlhill.com/llms-full.txt', $body);
    $this->assertStringContainsString('https://karlhill.com/api/site.json', $body);
    $this->assertStringContainsString('https://karlhill.com/.well-known/mcp.json', $body);

    $this->assertStringNotContainsString('## Citation', $body);
    $this->assertStringNotContainsString('## For recruiters', $body);
    $this->assertStringNotContainsString('Skills:', $body);
    $this->assertStringNotContainsString('/files/Karl-Hill-Resume.pdf', $body);
    $this->assertStringNotContainsString('https://karlhill.com/feed.xml', $body);
    $this->assertStringNotContainsString('rel="alternate"', $body);
});

it('every h2 section is a markdown file list with unique urls', function () {
    /** @var LlmsTxtBuilder $builder */
    $builder = $this->app->make(LlmsTxtBuilder::class);
    $body = $builder->build();

    $inFileList = false;
    foreach (explode("\n", $body) as $line) {
        if (str_starts_with($line, '## ')) {
            $inFileList = true;

            continue;
        }

        if ($inFileList && $line === '') {
            continue;
        }

        if ($inFileList && $line !== '') {
            expect($line)->toMatch('/^- \[[^\[\]]+\]\([^)]+\)(: .+)?$/');
        }
    }

    preg_match_all('/\[[^\[\]]+\]\((https?:[^)]+)\)/', $body, $matches);
    $urls = $matches[1];
    $unique = array_values(array_unique($urls));

    expect($urls)->not->toBeEmpty()
        ->and($urls)->toHaveCount(count($unique))
        ->and(count($unique))->toBeGreaterThanOrEqual(25)
        ->and(count($unique))->toBeLessThanOrEqual(32);

    $withoutUrls = preg_replace('~https?://\S+~', '', $body) ?? $body;
    $words = str_word_count($withoutUrls);
    expect($words)->toBeGreaterThanOrEqual(400)
        ->and($words)->toBeLessThanOrEqual(900);
});

it('llms txt builder lists professional profiles and resume once', function () {
    /** @var LlmsTxtBuilder $builder */
    $builder = $this->app->make(LlmsTxtBuilder::class);
    $body = $builder->build();

    $this->assertStringContainsString('[LinkedIn](https://www.linkedin.com/in/khill/)', $body);
    $this->assertStringContainsString('[GitHub](https://github.com/karlhillx)', $body);
    $this->assertSame(1, substr_count($body, '/resume'));
    $this->assertSame(1, substr_count($body, '/kit'));
    $this->assertSame(1, substr_count($body, '/now'));
    $this->assertStringContainsString('GeoHorizons', $body);
    $this->assertStringContainsString('August 29, 2026', $body);
});

it('llms txt is served without a session', function () {
    $response = $this->get('/llms.txt');

    $response->assertOk();
    expect($response->headers->get('Set-Cookie'))->toBeNull()
        ->and($response->headers->get('X-Powered-By'))->toBeNull();
});

it('homepage advertises llms txt as describedby', function () {
    $response = $this->get('/');

    $response->assertOk();
    $response->assertSee('<link rel="describedby" href="/llms.txt">', escape: false);
    $response->assertDontSee('rel="alternate" type="text/plain"', escape: false);
    expect((string) $response->headers->get('Link'))->toContain('rel="describedby"')
        ->and((string) $response->headers->get('Link'))->toContain('/llms.txt');
});

it('llms full txt includes essay bodies', function () {
    $response = $this->get('/llms-full.txt');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
    $body = $response->getContent();
    $this->assertStringContainsString('## Full essays', $body);
    $this->assertStringContainsString('A release is a decision', $body);
    $this->assertStringContainsString('unit of work', $body);
});

it('homepage includes speculation rules for blog prefetch', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
    $response->assertSee('<script type="speculationrules"', escape: false);
    $response->assertSee('"/blog"', escape: false);
    $response->assertSee('"/now"', escape: false);
    $response->assertSee('"prerender"', escape: false);
    $response->assertSee('"href_matches":"/blog*"', escape: false);
    $response->assertSee('expects_no_vary_search', escape: false);
    $response->assertSee('utm_source', escape: false);
});

it('blog index includes speculation rules for post prefetch', function () {
    $response = $this->get('/blog');

    $response->assertStatus(200);
    $response->assertSee('<script type="speculationrules"', escape: false);
    $response->assertSee('"prerender"', escape: false);
    $response->assertSee('"href_matches":"/blog/*"', escape: false);
});
