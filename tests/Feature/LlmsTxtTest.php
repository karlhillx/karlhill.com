<?php

namespace Tests\Feature;

use App\Support\LlmsTxtBuilder;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class LlmsTxtTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
    }

    public function test_llms_txt_returns_plain_text_with_required_sections(): void
    {
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
        $this->assertStringContainsString('/blog/release-governance', $body);
        $this->assertStringContainsString('What 20 Years Taught Me About Release Governance', $body);
        $this->assertStringContainsString('Preferred name: **Karl Hill**', $body);
        $this->assertStringContainsString('https://karlhill.com/feed.xml', $body);
        $this->assertStringContainsString('https://karlhill.com/feed.json', $body);
        $this->assertStringContainsString('https://karlhill.com/llms-full.txt', $body);
        $this->assertStringContainsString('## Series', $body);
        $this->assertStringContainsString('Engineering Manager craft', $body);
    }

    public function test_llms_txt_builder_lists_social_profiles_and_resume(): void
    {
        /** @var LlmsTxtBuilder $builder */
        $builder = $this->app->make(LlmsTxtBuilder::class);
        $body = $builder->build();

        $this->assertStringContainsString('[LinkedIn](https://www.linkedin.com/in/khill/)', $body);
        $this->assertStringContainsString('[GitHub](https://github.com/karlhillx)', $body);
        $this->assertStringContainsString('/resume', $body);
        $this->assertStringContainsString('/files/karlhill-resume.pdf', $body);
        $this->assertStringContainsString('GeoHorizons', $body);
        $this->assertStringContainsString('August 7, 2026', $body);
    }

    public function test_llms_full_txt_includes_essay_bodies(): void
    {
        $response = $this->get('/llms-full.txt');

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/plain; charset=utf-8');
        $body = $response->getContent();
        $this->assertStringContainsString('## Full essays', $body);
        $this->assertStringContainsString('A release is a decision', $body);
        $this->assertStringContainsString('unit of work', $body);
    }

    public function test_homepage_includes_speculation_rules_for_blog_prefetch(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<script type="speculationrules"', escape: false);
        $response->assertSee('"/blog"', escape: false);
        $response->assertSee('"/now"', escape: false);
        $response->assertSee('"prerender"', escape: false);
        $response->assertSee('"href_matches":"/blog*"', escape: false);
    }

    public function test_blog_index_includes_speculation_rules_for_post_prefetch(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('<script type="speculationrules"', escape: false);
        $response->assertSee('"prerender"', escape: false);
        $response->assertSee('"href_matches":"/blog/*"', escape: false);
    }
}
