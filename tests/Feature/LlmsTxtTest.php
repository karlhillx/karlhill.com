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
        $this->assertStringContainsString('> Staff Software Engineer', $body);
        $this->assertStringContainsString('## Citation', $body);
        $this->assertStringContainsString('## Key pages', $body);
        $this->assertStringContainsString('## Writing', $body);
        $this->assertStringContainsString('## Professional profiles', $body);
        $this->assertStringContainsString('## Optional', $body);
        $this->assertStringContainsString('/work', $body);
        $this->assertStringContainsString('/about', $body);
        $this->assertStringContainsString('/blog/release-governance', $body);
        $this->assertStringContainsString('What 20 Years Taught Me About Release Governance', $body);
        $this->assertStringContainsString('Preferred name: **Karl Hill**', $body);
        $this->assertStringContainsString('https://karlhill.com/feed.xml', $body);
    }

    public function test_llms_txt_builder_lists_social_profiles_and_resume(): void
    {
        /** @var LlmsTxtBuilder $builder */
        $builder = $this->app->make(LlmsTxtBuilder::class);
        $body = $builder->build();

        $this->assertStringContainsString('[LinkedIn](https://www.linkedin.com/in/khill/)', $body);
        $this->assertStringContainsString('[GitHub](https://github.com/karlhillx)', $body);
        $this->assertStringContainsString('/files/karlhill-resume.pdf', $body);
        $this->assertStringContainsString('GeoHorizons', $body);
    }

    public function test_homepage_includes_speculation_rules_for_blog_prefetch(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('<script type="speculationrules">', escape: false);
        $response->assertSee('"/blog"', escape: false);
        $response->assertSee('"/blog/release-governance"', escape: false);
        $response->assertSee('"href_matches":"/blog*"', escape: false);
    }

    public function test_blog_index_includes_speculation_rules_for_post_prefetch(): void
    {
        $response = $this->get('/blog');

        $response->assertStatus(200);
        $response->assertSee('<script type="speculationrules">', escape: false);
        $response->assertSee('"/blog/release-governance"', escape: false);
        $response->assertSee('"href_matches":"/blog/*"', escape: false);
    }
}
