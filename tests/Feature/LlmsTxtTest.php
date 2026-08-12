<?php

use App\Support\LlmsTxtBuilder;
use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    Cache::flush();
});

it('llms txt returns plain text with required sections', function () {
    $response = $this->get('/llms.txt');

    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');

    $body = $response->getContent();

    $this->assertStringStartsWith('# Karl Hill', $body);
    $this->assertStringContainsString('> '.config('site.seo.home.og_description'), $body);
    $this->assertStringContainsString('## Citation', $body);
    $this->assertStringContainsString('Last updated:', $body);
    $this->assertStringContainsString('## Key pages', $body);
    $this->assertStringContainsString('## Writing', $body);
    $this->assertStringContainsString('## Professional profiles', $body);
    $this->assertStringContainsString('## Optional', $body);
    $this->assertStringContainsString('## Case studies', $body);
    $this->assertStringContainsString('/work/nasa-earth-observatory', $body);
    $this->assertStringContainsString('/kit', $body);
    $this->assertStringContainsString('/blog/release-governance', $body);
    $this->assertStringContainsString('What 20 Years Taught Me About Release Governance', $body);
    $this->assertStringContainsString('Preferred name: **Karl Hill**', $body);
    $this->assertStringContainsString('https://karlhill.com/feed.xml', $body);
    $this->assertStringContainsString('https://karlhill.com/feed.json', $body);
    $this->assertStringContainsString('https://karlhill.com/llms-full.txt', $body);
    $this->assertStringContainsString('## For recruiters & hiring managers', $body);
    $this->assertStringContainsString('Seeking:', $body);
    $this->assertStringContainsString('Engineering Manager', $body);
    $this->assertStringContainsString('## Series', $body);
    $this->assertStringContainsString('Engineering Manager craft', $body);
});

it('llms txt builder lists social profiles and resume', function () {
    /** @var LlmsTxtBuilder $builder */
    $builder = $this->app->make(LlmsTxtBuilder::class);
    $body = $builder->build();

    $this->assertStringContainsString('[LinkedIn](https://www.linkedin.com/in/khill/)', $body);
    $this->assertStringContainsString('[GitHub](https://github.com/karlhillx)', $body);
    $this->assertStringContainsString('/resume', $body);
    $this->assertStringContainsString('/files/Karl-Hill-Resume.pdf', $body);
    $this->assertStringContainsString('GeoHorizons', $body);
    $this->assertStringContainsString('August 12, 2026', $body);
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
});

it('blog index includes speculation rules for post prefetch', function () {
    $response = $this->get('/blog');

    $response->assertStatus(200);
    $response->assertSee('<script type="speculationrules"', escape: false);
    $response->assertSee('"prerender"', escape: false);
    $response->assertSee('"href_matches":"/blog/*"', escape: false);
});
