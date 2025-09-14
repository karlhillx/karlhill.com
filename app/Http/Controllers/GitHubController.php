<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class GitHubController extends Controller
{
    public function getLanguageStats()
    {
        $token = config('services.github.token');

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

            $query = [
                'visibility' => 'all',
                'per_page' => 100,
                'sort' => 'updated'
            ];

            // Primary: authenticated request when token available; otherwise public user endpoint
            $username = config('services.github.username');
            $primaryUrl = $token ? 'https://api.github.com/user/repos' : "https://api.github.com/users/{$username}/repos";
            $primaryHeaders = $token ? $authHeaders : $headers;

            $response = Http::withHeaders($primaryHeaders)->get($primaryUrl, $query);

            if ($response->successful()) {
                return response()->json($response->json());
            }

            // Fallback: try public user repos without Authorization
            $fallbackUrl = "https://api.github.com/users/{$username}/repos";
            $fallbackResponse = Http::withHeaders($headers)->get($fallbackUrl, $query);
            if ($fallbackResponse->successful()) {
                return response()->json($fallbackResponse->json());
            }

            // Both failed: return richer error details
            return response()->json([
                'error' => 'GitHub API error',
                'details' => [
                    'primary' => [
                        'url' => $primaryUrl,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ],
                    'fallback' => [
                        'url' => $fallbackUrl,
                        'status' => $fallbackResponse->status(),
                        'body' => $fallbackResponse->body(),
                    ],
                ],
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'GitHub API exception',
                'message' => $e->getMessage(),
            ], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
