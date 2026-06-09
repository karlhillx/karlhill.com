<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubRepository
{
    private const FEATURED_REPOS = [
        'sim-rs',
        'pipeguard',
        'bb-run',
        'driftlens',
        'drift-rs',
    ];

    /**
     * @return Collection<int, GitHubRepo>
     */
    public function topRepos(int $limit = 6): Collection
    {
        $username = trim((string) config('services.github.username', 'karlhillx'));
        if ($username === '') {
            $username = 'karlhillx';
        }

        $rows = Cache::remember(
            "github.repos.{$username}.{$limit}",
            now()->addHour(),
            fn () => $this->fetchRepoRows($username, $limit),
        );

        return collect($rows)->map(fn (array $row) => GitHubRepo::fromArray($row));
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function fetchRepoRows(string $username, int $limit): array
    {
        try {
            $baseHeaders = [
                'Accept' => 'application/vnd.github+json',
                'X-GitHub-Api-Version' => '2022-11-28',
                'User-Agent' => 'karlhill.com',
            ];

            $query = ['sort' => 'updated', 'direction' => 'desc', 'per_page' => 100, 'type' => 'public'];
            $token = config('services.github.token');

            $headers = $baseHeaders;
            if (! empty($token)) {
                $headers['Authorization'] = 'Bearer '.$token;
            }

            $response = Http::withHeaders($headers)->timeout(8)->retry(2, 250)->get(
                "https://api.github.com/users/{$username}/repos",
                $query,
            );

            if (
                ! $response->successful() &&
                ! empty($token) &&
                in_array($response->status(), [401, 403, 429], true)
            ) {
                Log::warning('GitHub authenticated request failed; retrying anonymously', [
                    'status' => $response->status(),
                ]);
                $response = Http::withHeaders($baseHeaders)->timeout(8)->retry(2, 250)->get(
                    "https://api.github.com/users/{$username}/repos",
                    $query,
                );
            }

            if (! $response->successful()) {
                Log::error('GitHub API error', [
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return [];
            }

            $exclude = [strtolower($username), 'karlhillx', 'karlhill.com'];
            $allRepos = is_array($response->json()) ? $response->json() : [];

            $preferred = array_values(array_filter($allRepos, fn ($repo) => ! ($repo['fork'] ?? false)
                && ! ($repo['archived'] ?? false)
                && ! in_array(strtolower((string) ($repo['name'] ?? '')), $exclude, true)));

            $repos = count($preferred) > 0
                ? $preferred
                : array_values(array_filter($allRepos, fn ($repo) => ! ($repo['archived'] ?? false)
                    && ! in_array(strtolower((string) ($repo['name'] ?? '')), $exclude, true)));

            $repos = $this->sortFeaturedRepos($repos);

            return array_map(fn ($repo) => [
                'name' => $repo['name'],
                'description' => $repo['description'],
                'url' => $repo['html_url'],
                'stars' => $repo['stargazers_count'],
                'language' => $repo['language'],
                'topics' => $repo['topics'] ?? [],
            ], array_slice($repos, 0, $limit));
        } catch (\Throwable $e) {
            Log::warning('GitHub API exception', ['message' => $e->getMessage()]);

            return [];
        }
    }

    protected function sortFeaturedRepos(array $repos): array
    {
        $featuredRank = array_flip(self::FEATURED_REPOS);

        usort($repos, function ($a, $b) use ($featuredRank) {
            $aName = strtolower((string) ($a['name'] ?? ''));
            $bName = strtolower((string) ($b['name'] ?? ''));

            $aRank = $featuredRank[$aName] ?? PHP_INT_MAX;
            $bRank = $featuredRank[$bName] ?? PHP_INT_MAX;

            if ($aRank !== $bRank) {
                return $aRank <=> $bRank;
            }

            return strcmp((string) ($b['updated_at'] ?? ''), (string) ($a['updated_at'] ?? ''));
        });

        return $repos;
    }
}
