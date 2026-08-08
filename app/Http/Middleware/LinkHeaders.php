<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

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

        $links = $this->preloadLinks();
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
        // Don't preload karlhill.com Vite assets into client staging HTML.
        if (str_starts_with($request->path(), 'clients/')) {
            return false;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');

        return $contentType === '' || str_contains($contentType, 'text/html');
    }

    /**
     * @return array<int, string>
     */
    protected function preloadLinks(): array
    {
        $links = [];

        try {
            $font = Vite::asset('resources/fonts/bebas-neue-latin-400-normal.woff2');
            $links[] = '<'.$font.'>; rel=preload; as=font; type=font/woff2; crossorigin';
        } catch (Throwable) {
            // Manifest missing during fresh installs / tests without a build.
        }

        foreach (Vite::preloadedAssets() as $url => $attributes) {
            $as = $attributes['as'] ?? null;
            if (! is_string($as) || $as === '') {
                continue;
            }

            $parts = ['<'.$url.'>', 'rel=preload', 'as='.$as];
            if (($attributes['crossorigin'] ?? false) === true || ($attributes['crossorigin'] ?? null) === '') {
                $parts[] = 'crossorigin';
            }
            if (isset($attributes['type']) && is_string($attributes['type'])) {
                $parts[] = 'type='.$attributes['type'];
            }

            $links[] = implode('; ', $parts);
        }

        return array_values(array_unique($links));
    }
}
