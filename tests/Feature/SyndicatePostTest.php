<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
    config(['services.devto.api_key' => 'test-key-123']);

    $this->fixturePath = resource_path('posts/2026-05-15-release-governance.md');
    $this->originalContents = File::get($this->fixturePath);
});

afterEach(function () {
    if ($this->originalContents !== null) {
        File::put($this->fixturePath, $this->originalContents);
    }
});

it('dry run prints payload without calling api', function () {
    Http::fake();

    $exit = Artisan::call('post:syndicate', [
        'slug' => 'release-governance',
        '--dry-run' => true,
    ]);
    $output = Artisan::output();

    $this->assertSame(0, $exit);
    $this->assertStringContainsString('DRY RUN', $output);
    $this->assertStringContainsString('canonical_url', $output);
    $this->assertStringContainsString('/blog/release-governance', $output);
    $this->assertStringContainsString('Would POST', $output);

    Http::assertNothingSent();
});

it('creates dev to article and writes back id', function () {
    Http::fake([
        'dev.to/api/articles' => Http::response([
            'id' => 987654,
            'url' => 'https://dev.to/karlhill/release-governance-abc',
        ], 201),
    ]);

    $this->artisan('post:syndicate', ['slug' => 'release-governance'])
        ->expectsOutputToContain('Creating new dev.to article')
        ->expectsOutputToContain('987654')
        ->assertExitCode(0);

    Http::assertSent(function ($request) {
        $data = $request->data();

        return $request->method() === 'POST'
            && str_contains($request->url(), 'dev.to/api/articles')
            && $request->header('api-key')[0] === 'test-key-123'
            && isset($data['article'])
            && $data['article']['title'] === 'What 20 Years Taught Me About Release Governance'
            && str_ends_with($data['article']['canonical_url'], '/blog/release-governance')
            && in_array('engineering', $data['article']['tags'], true)
            && count($data['article']['tags']) <= 4
            && $data['article']['published'] === true;
    });

    $updated = File::get($this->fixturePath);
    $this->assertStringContainsString('dev_to_id: 987654', $updated);
});

it('updates existing article when dev to id present', function () {
    $withId = preg_replace(
        '/^---\R/',
        "---\ndev_to_id: 555\n",
        $this->originalContents,
        1
    );
    File::put($this->fixturePath, $withId);
    Cache::flush();

    Http::fake([
        'dev.to/api/articles/555' => Http::response([
            'id' => 555,
            'url' => 'https://dev.to/karlhill/release-governance-abc',
        ], 200),
    ]);

    $this->artisan('post:syndicate', ['slug' => 'release-governance'])
        ->expectsOutputToContain('already syndicated')
        ->assertExitCode(0);

    Http::assertSent(function ($request) {
        return $request->method() === 'PUT'
            && str_contains($request->url(), 'dev.to/api/articles/555');
    });
});

it('fails gracefully without api key', function () {
    config(['services.devto.api_key' => null]);
    Http::fake();

    $this->artisan('post:syndicate', ['slug' => 'release-governance'])
        ->expectsOutputToContain('DEVTO_API_KEY')
        ->assertExitCode(1);

    Http::assertNothingSent();
});

it('fails for unknown slug', function () {
    $this->artisan('post:syndicate', ['slug' => 'no-such-post'])
        ->expectsOutputToContain('No post found')
        ->assertExitCode(1);
});
