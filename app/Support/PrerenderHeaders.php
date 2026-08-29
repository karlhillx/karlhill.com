<?php

namespace App\Support;

use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Headers that let Chrome reuse Speculation Rules prerenders across tracking
 * query strings, and opt the document into credentialed same-site prerender.
 *
 * @see https://developer.chrome.com/docs/web-platform/prerender-pages
 * @see https://developer.mozilla.org/en-US/docs/Web/HTTP/Reference/Headers/No-Vary-Search
 */
final class PrerenderHeaders
{
    /**
     * Structured Fields dictionary. Tracking params do not identify a different
     * document on this site (routes are path-based).
     */
    public const NO_VARY_SEARCH = 'key-order, params=("utm_source" "utm_medium" "utm_campaign" "utm_content" "utm_term" "utm_id" "gclid" "gbraid" "wbraid" "fbclid" "msclkid" "ref" "mc_cid" "mc_eid")';

    public const SUPPORTS_LOADING_MODE = 'credentialed-prerender';

    public static function shouldAnnotate(Request $request, Response $response): bool
    {
        if ($request->method() !== 'GET' && $request->method() !== 'HEAD') {
            return false;
        }

        if (str_starts_with($request->path(), 'clients/')) {
            return false;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');

        return $contentType === '' || str_contains($contentType, 'text/html');
    }
}
