<?php

namespace App\Http\Controllers;

use App\Support\PushSubscriptionStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PushController extends Controller
{
    public function subscribe(Request $request): JsonResponse
    {
        if (! PushSubscriptionStore::enabled()) {
            return response()->json(['error' => 'push_disabled'], 404);
        }

        $validated = $request->validate([
            'endpoint' => ['required', 'url', 'max:2048'],
            'keys.p256dh' => ['required', 'string', 'max:512'],
            'keys.auth' => ['required', 'string', 'max:256'],
        ]);

        PushSubscriptionStore::put($validated);

        return response()->json(['ok' => true], 201);
    }

    public function unsubscribe(Request $request): JsonResponse
    {
        $endpoint = (string) $request->input('endpoint', '');
        if ($endpoint !== '') {
            PushSubscriptionStore::forget($endpoint);
        }

        return response()->json(['ok' => true]);
    }
}
