<?php

namespace App\Support;

use Illuminate\Support\Facades\Vite;

/**
 * CDN-friendly Link preload values. Proxies that support Early Hints
 * (Cloudflare, FrankenPHP/Caddy) can upgrade these into 103s.
 * Site fonts are also emitted as <link rel="preload"> in the layout
 * so browsers fetch them even without a Vite manifest / Link header.
 */
final class PreloadLinks
{
    /**
     * First-paint font files (Bebas display, Barlow body, JetBrains mono).
     *
     * @return list<string>
     */
    public static function fonts(): array
    {
        return [
            'resources/fonts/bebas-neue-latin-400-normal.woff2',
            'node_modules/@fontsource/barlow-semi-condensed/files/barlow-semi-condensed-latin-400-normal.woff2',
            'node_modules/@fontsource/barlow-semi-condensed/files/barlow-semi-condensed-latin-400-italic.woff2',
            'node_modules/@fontsource/barlow-semi-condensed/files/barlow-semi-condensed-latin-500-normal.woff2',
            'node_modules/@fontsource/barlow-semi-condensed/files/barlow-semi-condensed-latin-600-normal.woff2',
            'node_modules/@fontsource/barlow-semi-condensed/files/barlow-semi-condensed-latin-700-normal.woff2',
            'node_modules/@fontsource/jetbrains-mono/files/jetbrains-mono-latin-400-normal.woff2',
            'node_modules/@fontsource/jetbrains-mono/files/jetbrains-mono-latin-500-normal.woff2',
        ];
    }

    /**
     * Resolved public URLs for {@see fonts()}, skipping files Vite cannot map.
     *
     * @return list<string>
     */
    public static function fontUrls(): array
    {
        $urls = [];

        foreach (self::fonts() as $path) {
            try {
                $urls[] = Vite::asset($path);
            } catch (\Throwable) {
                // Manifest or Vite dev server may be unavailable in some test paths.
            }
        }

        return $urls;
    }

    /**
     * @return list<string>
     */
    public static function all(): array
    {
        $links = [];

        foreach (self::fontUrls() as $url) {
            $links[] = '<'.$url.'>; rel=preload; as=font; type=font/woff2; crossorigin';
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

        $dictUrl = CompressionDictionary::url();
        if ($dictUrl !== null) {
            $links[] = '<'.$dictUrl.'>; rel="compression-dictionary"';
        }

        return array_values(array_unique($links));
    }
}
