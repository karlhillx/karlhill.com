<?php

namespace App\Http\Controllers;

use App\Support\ReportingStore;
use App\Support\SiteFeatures;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportingController extends Controller
{
    public function store(Request $request): Response
    {
        if (! SiteFeatures::reporting()) {
            return response('', 204);
        }
        $payload = $request->json()->all();
        if (! is_array($payload) || $payload === []) {
            $decoded = json_decode((string) $request->getContent(), true);
            $payload = is_array($decoded) ? $decoded : ['raw' => mb_substr((string) $request->getContent(), 0, 4000)];
        }

        ReportingStore::record($payload);

        return response('', 204);
    }
}
