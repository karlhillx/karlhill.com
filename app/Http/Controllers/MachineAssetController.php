<?php

namespace App\Http\Controllers;

use App\Support\CompressionDictionary;
use App\Support\ContentCredentials;
use App\Support\SiteFeatures;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class MachineAssetController extends Controller
{
    public function dictionary(): Response
    {
        abort_unless(SiteFeatures::compressionDictionary(), 404);

        $bytes = CompressionDictionary::bytes();

        return response($bytes, 200, [
            'Content-Type' => 'application/octet-stream',
            'Cache-Control' => 'public, max-age=86400',
            'Use-As-Dictionary' => 'match="/*", match-dest=("document"), id="html-shell"',
        ]);
    }

    public function credentials(): JsonResponse
    {
        abort_unless(SiteFeatures::contentCredentials(), 404);

        return response()->json(ContentCredentials::document());
    }
}
