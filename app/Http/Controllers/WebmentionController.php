<?php

namespace App\Http\Controllers;

use App\Support\SiteFeatures;
use App\Support\WebmentionStore;
use App\Support\WebmentionVerifier;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WebmentionController extends Controller
{
    public function store(Request $request): Response
    {
        if (! SiteFeatures::webmention()) {
            return response('disabled', 404, [
                'Content-Type' => 'text/plain; charset=utf-8',
            ]);
        }
        $source = (string) $request->input('source', '');
        $target = (string) $request->input('target', '');

        $result = WebmentionVerifier::verify($source, $target);
        if (! ($result['ok'] ?? false)) {
            return response($result['error'] ?? 'invalid', 400, [
                'Content-Type' => 'text/plain; charset=utf-8',
            ]);
        }

        /** @var array<string, mixed> $mention */
        $mention = $result['mention'];
        WebmentionStore::add((string) $mention['slug'], $mention);

        return response('', 202, [
            'Content-Type' => 'text/plain; charset=utf-8',
        ]);
    }
}
