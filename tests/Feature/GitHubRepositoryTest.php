<?php

use App\Support\GitHubRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

it('top repos returns featured public repositories', function () {
    Http::preventStrayRequests();

    Http::fake([
        'api.github.com/*' => Http::response([
            [
                'name' => 'drift-rs',
                'description' => 'Rust drift detection',
                'html_url' => 'https://github.com/karlhillx/drift-rs',
                'stargazers_count' => 12,
                'language' => 'Rust',
                'topics' => ['rust'],
                'fork' => false,
                'archived' => false,
                'updated_at' => '2026-06-01T00:00:00Z',
            ],
            [
                'name' => 'karlhill.com',
                'description' => 'Site repo',
                'html_url' => 'https://github.com/karlhillx/karlhill.com',
                'stargazers_count' => 1,
                'language' => 'PHP',
                'topics' => [],
                'fork' => false,
                'archived' => false,
                'updated_at' => '2026-06-02T00:00:00Z',
            ],
        ], 200),
    ]);

    $repos = app(GitHubRepository::class)->topRepos();

    $this->assertGreaterThanOrEqual(1, $repos->count());
    $this->assertTrue($repos->contains(fn ($repo) => $repo->name === 'drift-rs'));
    $this->assertFalse($repos->contains(fn ($repo) => $repo->name === 'karlhill.com'));
});

it('falls back to curated repos when api fails', function () {
    Http::fake([
        'api.github.com/*' => Http::response('rate limited', 403),
    ]);

    $repos = app(GitHubRepository::class)->topRepos();

    $this->assertGreaterThanOrEqual(1, $repos->count());
    $this->assertTrue($repos->contains(fn ($repo) => $repo->name === 'sim-rs'));
});

it('work page shows fallback repos instead of empty state', function () {
    Http::fake([
        'api.github.com/*' => Http::response('server error', 500),
    ]);

    $response = $this->get('/work');

    $response->assertOk();
    $response->assertSee('id="open-source"', escape: false);
    $response->assertDontSee('No public repositories were returned');
});

it('work page renders server side github repos', function () {
    Http::fake([
        'api.github.com/*' => Http::response([
            [
                'name' => 'sim-rs',
                'description' => 'Simulation runtime',
                'html_url' => 'https://github.com/karlhillx/sim-rs',
                'stargazers_count' => 4,
                'language' => 'Rust',
                'topics' => ['simulation'],
                'fork' => false,
                'archived' => false,
                'updated_at' => '2026-06-01T00:00:00Z',
            ],
        ], 200),
    ]);

    $response = $this->get('/work');

    $response->assertOk();
    $response->assertSee('id="open-source"', escape: false);
    $response->assertSee('sim-rs', escape: false);
    $response->assertDontSee('id="github-repos"', escape: false);
});
