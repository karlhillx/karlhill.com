<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/**
 * Compression Dictionary Transport — a shared Brotli/Zstd dictionary built
 * from the HTML chrome so subsequent document responses compress against it.
 */
final class CompressionDictionary
{
    public static function path(): string
    {
        return storage_path('app/dict/html-shell.dat');
    }

    public static function url(): ?string
    {
        if (! SiteFeatures::compressionDictionary()) {
            return null;
        }

        return '/dict/html-shell.dat';
    }

    public static function bytes(): string
    {
        $path = self::path();
        if (is_file($path) && filesize($path) > 32) {
            return (string) File::get($path);
        }

        return self::build();
    }

    public static function hash(): string
    {
        return rtrim(strtr(base64_encode(hash('sha256', self::bytes(), true)), '+/', '-_'), '=');
    }

    public static function persist(): string
    {
        $bytes = self::build();
        File::ensureDirectoryExists(dirname(self::path()));
        File::put(self::path(), $bytes);

        return self::path();
    }

    public static function build(): string
    {
        $layout = is_file(resource_path('views/layouts/site.blade.php'))
            ? (string) File::get(resource_path('views/layouts/site.blade.php'))
            : '';
        $nav = is_file(resource_path('views/components/site/nav.blade.php'))
            ? (string) File::get(resource_path('views/components/site/nav.blade.php'))
            : '';
        $footer = is_file(resource_path('views/components/site/footer.blade.php'))
            ? (string) File::get(resource_path('views/components/site/footer.blade.php'))
            : '';

        $raw = $layout."\n".$nav."\n".$footer;
        $raw = preg_replace('/\{\{.+?\}\}/s', ' ', $raw) ?? $raw;
        $raw = preg_replace('/\{!!.+?!!\}/s', ' ', $raw) ?? $raw;
        $raw = preg_replace('/@\w+(\([^)]*\))?/', ' ', $raw) ?? $raw;
        $raw = preg_replace('/\s+/', ' ', $raw) ?? $raw;

        $tokens = ' KARL HILL Work About Writing Now Resume Contact Jump to command-palette site-shell site-gutter font-mono font-display tracking-widest uppercase text-accent border-neutral-800 ';

        return mb_substr($raw.$tokens.$raw, 0, 8192);
    }
}
