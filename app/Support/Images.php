<?php

namespace App\Support;

final class Images
{
    /**
     * Map an /img/*.png|jpg asset path to its pre-generated /img/webp twin.
     * Paths that are already .webp pass through untouched.
     */
    public static function webp(string $path): string
    {
        if (str_ends_with(strtolower($path), '.webp')) {
            return $path;
        }

        return preg_replace('/\.(png|jpe?g)$/i', '.webp', str_replace('/img/', '/img/webp/', $path));
    }

    /**
     * Build a srcset from the pre-generated -800/-1600 WebP variants
     * (see scripts/generate-webp.py). Returns null when no variants exist
     * so callers can fall back to a plain src.
     */
    public static function srcset(string $webpPath): ?string
    {
        $entries = [];

        foreach ([800, 1600] as $width) {
            $variant = preg_replace('/\.webp$/i', "-{$width}.webp", $webpPath);

            if (is_file(public_path(ltrim($variant, '/')))) {
                $entries[] = "{$variant} {$width}w";
            }
        }

        return $entries === [] ? null : implode(', ', $entries);
    }
}
