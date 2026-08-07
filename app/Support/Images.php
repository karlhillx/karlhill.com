<?php

namespace App\Support;

final class Images
{
    /** @var array<int, int> */
    public const SRCSET_WIDTHS = [400, 800, 1200, 1600];

    /**
     * Map an /img/*.png|jpg asset path to its pre-generated /img/webp twin.
     * Paths that are already .webp pass through untouched.
     */
    public static function webp(string $path): string
    {
        if (str_ends_with(strtolower($path), '.webp')) {
            return $path;
        }

        return preg_replace('/\.(png|jpe?g)$/i', '.webp', str_replace('/img/', '/img/webp/', $path)) ?? $path;
    }

    /**
     * Map an /img path to its pre-generated /img/avif twin.
     */
    public static function avif(string $path): string
    {
        $normalized = preg_replace('#^/img/(webp/)?#', '/img/', $path) ?? $path;
        $normalized = preg_replace('/\.(png|jpe?g|webp)$/i', '.avif', $normalized) ?? $normalized;

        return str_replace('/img/', '/img/avif/', $normalized);
    }

    /**
     * Build a srcset from pre-generated width variants (see scripts/generate-webp.py).
     * Returns null when no variants exist so callers can fall back to a plain src.
     */
    public static function srcset(string $path): ?string
    {
        $entries = [];
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        if ($extension === '') {
            return null;
        }

        $pattern = '/\.'.preg_quote($extension, '/').'$/i';

        foreach (self::SRCSET_WIDTHS as $width) {
            $variant = preg_replace($pattern, "-{$width}.{$extension}", $path);
            if (! is_string($variant)) {
                continue;
            }

            if (is_file(public_path(ltrim($variant, '/')))) {
                $entries[] = "{$variant} {$width}w";
            }
        }

        return $entries === [] ? null : implode(', ', $entries);
    }

    /**
     * Whether a generated AVIF (or any of its width variants) exists on disk.
     */
    public static function hasAvif(string $path): bool
    {
        $avif = self::avif($path);

        if (is_file(public_path(ltrim($avif, '/')))) {
            return true;
        }

        return self::srcset($avif) !== null;
    }

    /**
     * Tiny LQIP twin under /img/lqip (see scripts/generate-webp.py).
     */
    public static function lqip(string $path): ?string
    {
        $normalized = preg_replace('#^/img/(webp/|avif/)?#', '/img/', $path) ?? $path;
        $normalized = preg_replace('/\.(png|jpe?g|webp|avif)$/i', '.webp', $normalized) ?? $normalized;
        $lqip = str_replace('/img/', '/img/lqip/', $normalized);

        return is_file(public_path(ltrim($lqip, '/'))) ? $lqip : null;
    }
}
