<?php

namespace App\Support;

use Illuminate\Support\Facades\Vite;

/**
 * CDN-friendly Link preload values. Proxies that support Early Hints
 * (Cloudflare, FrankenPHP/Caddy) can upgrade these into 103s.
 * Display-font preload lives in the layout so browsers fetch it even
 * without a Vite manifest / Link header.
 */
final class PreloadLinks
{
    /**
     * @return list<string>
     */
    public static function all(): array
    {
        $links = [];

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

        $dictUrl = CompressionDictionary::url();
        if ($dictUrl !== null) {
            $links[] = '<'.$dictUrl.'>; rel="compression-dictionary"';
        }

        return array_values(array_unique($links));
    }
}
