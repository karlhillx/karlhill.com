<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubController extends Controller
{
    private const FEATURED_REPOS = [
        'sim-rs',
        'pipeguard',
        'bb-run',
        'driftlens',
        'drift-rs',
    ];

    public function getTopRepos()
    {
        $token    = config('services.github.token');
        $username = trim((string) config('services.github.username', 'karlhillx'));
        if ($username === '') {
            $username = 'karlhillx';
        }

        try {
            $baseHeaders = [
                'Accept'             => 'application/vnd.github+json',
                'X-GitHub-Api-Version' => '2022-11-28',
                'User-Agent'         => 'karlhill.com',
            ];

            $query = ['sort' => 'updated', 'direction' => 'desc', 'per_page' => 100, 'type' => 'public'];

            $headers = $baseHeaders;
            if (!empty($token)) {
                $headers['Authorization'] = 'Bearer ' . $token;
            }

            $response = Http::withHeaders($headers)->timeout(8)->retry(2, 250)->get(
                "https://api.github.com/users/{$username}/repos",
                $query
            );

            // If auth headers fail (expired/revoked token, auth rate limit),
            // retry anonymously so public repos still load.
            if (
                !$response->successful() &&
                !empty($token) &&
                in_array($response->status(), [401, 403, 429], true)
            ) {
                Log::warning('GitHub authenticated request failed; retrying anonymously', [
                    'status' => $response->status(),
                ]);
                $response = Http::withHeaders($baseHeaders)->timeout(8)->retry(2, 250)->get(
                    "https://api.github.com/users/{$username}/repos",
                    $query
                );
            }

            if (!$response->successful()) {
                Log::error('GitHub API error in getTopRepos', [
                    'status' => $response->status(),
                    'body'   => $response->body(),
                ]);
                return response()->json([], 200)
                    ->header('Cache-Control', 'public, max-age=300');
            }

            $exclude = [strtolower($username), 'karlhillx'];
            $allRepos = is_array($response->json()) ? $response->json() : [];

            // Prefer non-fork, non-archived repos first.
            $preferred = array_values(array_filter($allRepos, fn($r) =>
                !($r['fork'] ?? false) &&
                !($r['archived'] ?? false) &&
                !in_array(strtolower((string) ($r['name'] ?? '')), $exclude, true)
            ));

            // If filtering is too strict (common when most repos are forks),
            // relax it to include forks rather than returning an empty list.
            $repos = count($preferred) > 0
                ? $preferred
                : array_values(array_filter($allRepos, fn($r) =>
                    !($r['archived'] ?? false) &&
                    !in_array(strtolower((string) ($r['name'] ?? '')), $exclude, true)
                ));

            $repos = $this->sortFeaturedRepos($repos);

            $top = array_map(fn($r) => [
                'name'        => $r['name'],
                'description' => $r['description'],
                'url'         => $r['html_url'],
                'stars'       => $r['stargazers_count'],
                'forks'       => $r['forks_count'],
                'language'    => $r['language'],
                'topics'      => $r['topics'] ?? [],
            ], array_slice($repos, 0, 6));

            return response()->json($top)
                ->header('Cache-Control', 'public, max-age=3600');
        } catch (\Exception $e) {
            Log::warning('GitHub API exception in getTopRepos', [
                'message' => $e->getMessage(),
            ]);
            return response()->json([], 200)
                ->header('Cache-Control', 'public, max-age=300');
        }
    }

    private function sortFeaturedRepos(array $repos): array
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
