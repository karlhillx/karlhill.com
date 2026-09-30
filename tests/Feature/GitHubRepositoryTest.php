<?php

use App\Support\GitHubRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

beforeEach(function () {
    Cache::flush();
});

it('ranks featured fallback order so bb-run and testrisk lead live results', function () {
    Http::preventStrayRequests();

    Http::fake([
        'api.github.com/*' => Http::response([
            [
                'name' => 'sim-rs',
                'description' => 'Simulation runtime',
                'html_url' => 'https://github.com/karlhillx/sim-rs',
                'stargazers_count' => 0,
                'language' => 'Rust',
                'topics' => ['simulation'],
                'fork' => false,
                'archived' => false,
                'updated_at' => '2026-06-02T00:00:00Z',
            ],
            [
                'name' => 'testrisk',
                'description' => 'Rank the highest-value Python test gaps',
                'html_url' => 'https://github.com/karlhillx/testrisk',
                'stargazers_count' => 1,
                'language' => 'Python',
                'topics' => ['testing'],
                'fork' => false,
                'archived' => false,
                'updated_at' => '2026-06-03T00:00:00Z',
            ],
            [
                'name' => 'bb-run',
                'description' => 'Run Bitbucket Pipelines locally',
                'html_url' => 'https://github.com/karlhillx/bb-run',
                'stargazers_count' => 12,
                'language' => 'Python',
                'topics' => ['devops'],
                'fork' => false,
                'archived' => false,
                'updated_at' => '2026-06-01T00:00:00Z',
            ],
            [
                'name' => 'pipeguard',
                'description' => 'mission-critical reliability for CI',
                'html_url' => 'https://github.com/karlhillx/pipeguard',
                'stargazers_count' => 0,
                'language' => 'Go',
                'topics' => ['ci-cd'],
                'fork' => false,
                'archived' => false,
                'updated_at' => '2026-06-04T00:00:00Z',
            ],
        ], 200),
    ]);

    $repos = app(GitHubRepository::class)->topRepos();

    expect($repos->pluck('name')->all())->toBe(['bb-run', 'testrisk', 'pipeguard'])
        ->and($repos->firstWhere('name', 'pipeguard')?->description)
        ->toBe('Policy-as-code validation and rule enforcement for Bitbucket Pipelines.');
});

it('uses the curated selection when the api only returns unfeatured repositories', function () {
    Http::preventStrayRequests();

    Http::fake([
        'api.github.com/*' => Http::response([
            [
                'name' => 'sim-rs',
                'description' => 'Rust orbit simulation',
                'html_url' => 'https://github.com/karlhillx/sim-rs',
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

    $this->assertSame(['bb-run', 'testrisk', 'pipeguard'], $repos->pluck('name')->all());
    $this->assertFalse($repos->contains(fn ($repo) => $repo->name === 'sim-rs'));
    $this->assertFalse($repos->contains(fn ($repo) => $repo->name === 'karlhill.com'));
});

it('falls back to curated repos when api fails', function () {
    Http::fake([
        'api.github.com/*' => Http::response('rate limited', 403),
    ]);

    $repos = app(GitHubRepository::class)->topRepos();

    $this->assertSame(['bb-run', 'testrisk', 'pipeguard'], $repos->pluck('name')->all());
});

it('work page shows fallback repos instead of empty state', function () {
    Http::fake([
        'api.github.com/*' => Http::response('server error', 500),
    ]);

    $response = $this->get('/work');

    $response->assertOk();
    $response->assertSee('id="open-source"', escape: false);
    $response->assertSee('bb-run', escape: false);
    $response->assertSee('testrisk', escape: false);
    $response->assertSee('pipeguard', escape: false);
    $response->assertDontSee('sim-rs', escape: false);
    $response->assertDontSee('driftlens', escape: false);
    $response->assertDontSee('drift-rs', escape: false);
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
            [
                'name' => 'bb-run',
                'description' => 'the observability lens',
                'html_url' => 'https://github.com/karlhillx/bb-run',
                'stargazers_count' => 1,
                'language' => 'Python',
                'topics' => ['devops'],
                'fork' => false,
                'archived' => false,
                'updated_at' => '2026-06-02T00:00:00Z',
            ],
        ], 200),
    ]);

    $response = $this->get('/work');

    $response->assertOk();
    $response->assertSee('id="open-source"', escape: false);
    $response->assertSee('bb-run', escape: false);
    $response->assertSee('href="https://github.com/karlhillx/bb-run"', escape: false);
    $response->assertDontSee('the observability lens', escape: false);
    $response->assertDontSee('sim-rs', escape: false);
    $response->assertDontSee('id="github-repos"', escape: false);
    $this->get('/work/developer-tooling')->assertOk()
        ->assertSee('Run Bitbucket Pipelines locally from your existing pipeline file', escape: false);
});
