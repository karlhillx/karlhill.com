<?php

namespace App\Http\Middleware;

use App\Support\PreloadLinks;
use App\Support\SiteFeatures;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Emit CDN-friendly Link preload headers. Proxies that support Early Hints
 * (Cloudflare, some nginx/FrankenPHP setups) can upgrade these into 103s.
 */
class LinkHeaders
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        if (! $this->shouldAnnotate($request, $response)) {
            return $response;
        }

        $links = PreloadLinks::all();

        if ($request->routeIs('blog.show') && SiteFeatures::webmention()) {
            $links[] = '</webmention>; rel="webmention"';
        }

        if ($links === []) {
            return $response;
        }

        $existing = $response->headers->get('Link');
        $combined = $existing
            ? $existing.', '.implode(', ', $links)
            : implode(', ', $links);

        $response->headers->set('Link', $combined);

        return $response;
    }

    protected function shouldAnnotate(Request $request, Response $response): bool
    {
        if (str_starts_with($request->path(), 'clients/')) {
            return false;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');

        return $contentType === '' || str_contains($contentType, 'text/html');
    }
}
