<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;

class GitHubController extends Controller
{
    public function getLanguageStats()
    {
        $token = config('services.github.token');
        $username = config('services.github.username');

        try {
            $headers = [
                'Accept' => 'application/vnd.github+json',
                'X-GitHub-Api-Version' => '2022-11-28',
                'User-Agent' => 'karlhill.com'
            ];

            $authHeaders = $headers;
            if ($token) {
                $authHeaders['Authorization'] = 'Bearer ' . $token;
            }

            $allRepos = [];
            $perPage = 100;
            $maxPages = 10; // Safety limit to prevent infinite loops (1000 repos max)

            // Strategy: Make TWO API calls and merge results
            // 1. Use /user/repos with affiliation to get owned/collaborator repos (includes private)
            // 2. Use /users/{username}/repos with type=all to explicitly get ALL repos including forks
            // Then merge and deduplicate by full_name
            
            $endpointsToTry = [];
            
            if ($token) {
                // First: Authenticated endpoint for owned/collaborator/org repos (includes private)
                $endpointsToTry[] = [
                    'url' => 'https://api.github.com/user/repos',
                    'headers' => $authHeaders,
                    'query' => [
                        'affiliation' => 'owner,collaborator,organization_member',
                        'visibility' => 'all',
                        'per_page' => $perPage,
                        'sort' => 'updated'
                    ]
                ];
            }
            
            // Second: Public endpoint format with type=all to explicitly include forks
            // This endpoint with type=all is the most reliable way to get ALL repos including forks
            $endpointsToTry[] = [
                'url' => "https://api.github.com/users/{$username}/repos",
                'headers' => $token ? $authHeaders : $headers, // Use auth if available for private repos
                'query' => [
                    'type' => 'all', // Explicitly includes forks
                    'per_page' => $perPage,
                    'sort' => 'updated'
                ]
            ];
            
            // Fetch from all endpoints and merge
            foreach ($endpointsToTry as $endpointConfig) {
                $page = 1;
                $hasMore = true;
                $baseUrl = $endpointConfig['url'];
                $requestHeaders = $endpointConfig['headers'];
                $baseQuery = $endpointConfig['query'];
                
                Log::info('Fetching repos from endpoint', ['url' => $baseUrl]);
                
                while ($hasMore && $page <= $maxPages) {
                    $query = array_merge($baseQuery, ['page' => $page]);

                    Log::debug('GitHub API Request', [
                        'page' => $page,
                        'url' => $baseUrl,
                        'query' => $query,
                        'has_token' => !empty($token)
                    ]);

                    $response = Http::withHeaders($requestHeaders)->get($baseUrl, $query);

                    if (!$response->successful()) {
                        Log::warning('GitHub API Request Failed', [
                            'page' => $page,
                            'status' => $response->status(),
                            'body' => $response->body(),
                            'query' => $query,
                            'url' => $baseUrl
                        ]);
                        break; // Stop pagination on error for this endpoint
                    }

                    $repos = $response->json();
                    
                    if (empty($repos) || !is_array($repos)) {
                        Log::debug('No repos returned or invalid response', [
                            'page' => $page,
                            'response_type' => gettype($repos),
                            'is_empty' => empty($repos)
                        ]);
                        $hasMore = false;
                        break;
                    }

                    Log::debug('GitHub API Page Response', [
                        'endpoint' => $baseUrl,
                        'page' => $page,
                        'repos_count' => count($repos),
                        'link_header' => $response->header('Link'),
                        'repo_names' => array_column($repos, 'full_name')
                    ]);

                    // Merge repos, using full_name as unique key to avoid duplicates
                    foreach ($repos as $repo) {
                        $key = $repo['full_name'] ?? $repo['name'];
                        if (!isset($allRepos[$key])) {
                            $allRepos[$key] = $repo;
                        }
                    }

                    // Check if there are more pages
                    $linkHeader = $response->header('Link');
                    $hasMoreFromLink = $linkHeader && strpos($linkHeader, 'rel="next"') !== false;
                    $hasMoreFromCount = count($repos) === $perPage;
                    $hasMore = $linkHeader ? $hasMoreFromLink : $hasMoreFromCount;
                    
                    Log::debug('Pagination check', [
                        'hasMoreFromLink' => $hasMoreFromLink,
                        'hasMoreFromCount' => $hasMoreFromCount,
                        'finalHasMore' => $hasMore,
                        'repos_this_page' => count($repos),
                        'unique_repos_so_far' => count($allRepos)
                    ]);

                    $page++;
                }
            }
            
            // Convert back to indexed array
            $allRepos = array_values($allRepos);

            // Fetch language data for repos that don't have a language field
            // GitHub API sometimes returns null for language, especially for forks
            // Use authenticated headers if token is available, otherwise use public headers
            $langHeaders = $token ? $authHeaders : $headers;
            
            foreach ($allRepos as &$repo) {
                if (empty($repo['language']) && !empty($repo['full_name'])) {
                    try {
                        $langUrl = "https://api.github.com/repos/{$repo['full_name']}/languages";
                        $langResponse = Http::withHeaders($langHeaders)->get($langUrl);
                        
                        if ($langResponse->successful()) {
                            $languages = $langResponse->json();
                            if (is_array($languages) && !empty($languages)) {
                                // Get the language with the most bytes (primary language)
                                arsort($languages);
                                $repo['language'] = array_key_first($languages);
                                Log::debug('Fetched language for repo', [
                                    'repo' => $repo['full_name'],
                                    'language' => $repo['language'],
                                    'all_languages' => $languages
                                ]);
                            }
                        }
                    } catch (\Exception $e) {
                        // Silently continue if language fetch fails
                        Log::debug('Failed to fetch language for repo', [
                            'repo' => $repo['full_name'] ?? 'unknown',
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
            unset($repo); // Unset reference

            // Log statistics for debugging
            $reposWithLanguages = array_filter($allRepos, function($repo) {
                return !empty($repo['language']);
            });
            $uniqueLanguages = array_unique(array_column($reposWithLanguages, 'language'));
            
            // Log all repo names to help debug
            $repoNames = array_column($allRepos, 'full_name');
            $repoVisibility = array_column($allRepos, 'private');
            $repoArchived = array_column($allRepos, 'archived');
            $repoFork = array_column($allRepos, 'fork');
            
            Log::info('GitHub API Response', [
                'total_repos' => count($allRepos),
                'repos_with_languages' => count($reposWithLanguages),
                'unique_languages' => count($uniqueLanguages),
                'languages' => array_values($uniqueLanguages),
                'pages_fetched' => $page - 1,
                'using_token' => !empty($token),
                'repo_names' => $repoNames,
                'private_count' => count(array_filter($repoVisibility)),
                'archived_count' => count(array_filter($repoArchived)),
                'fork_count' => count(array_filter($repoFork))
            ]);

            if (empty($allRepos)) {
                return response()->json([
                    'error' => 'No repositories found',
                    'message' => 'Unable to fetch repositories from GitHub API'
                ], Response::HTTP_SERVICE_UNAVAILABLE)
                    ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                    ->header('Pragma', 'no-cache')
                    ->header('Expires', '0');
            }

            return response()->json($allRepos)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        } catch (\Exception $e) {
            Log::error('GitHub API Exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'error' => 'GitHub API exception',
                'message' => $e->getMessage(),
            ], Response::HTTP_SERVICE_UNAVAILABLE)
                ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }
    }
}
