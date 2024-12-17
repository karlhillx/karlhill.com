<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Response;

class GitHubController extends Controller
{
    public function getLanguageStats()
    {
        $token = config('services.github.token');

        if (!$token) {
            return response()->json(['error' => 'GitHub token not configured'], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token,
            ])->get('https://api.github.com/users/karlhillx/repos');

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'GitHub API error'], Response::HTTP_SERVICE_UNAVAILABLE);
        }
    }
}
