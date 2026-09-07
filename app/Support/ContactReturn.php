<?php

namespace App\Support;

final class ContactReturn
{
    /**
     * Same-origin path the contact form may redirect back to.
     */
    public static function path(?string $candidate): string
    {
        if (! is_string($candidate) || $candidate === '') {
            return '/';
        }

        $path = parse_url($candidate, PHP_URL_PATH);
        if (! is_string($path) || $path === '') {
            return '/';
        }

        $path = '/'.ltrim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/') ?: '/';
        }

        if (str_contains($path, '..') || str_contains($path, '//')) {
            return '/';
        }

        return self::isAllowed($path) ? $path : '/';
    }

    private static function isAllowed(string $path): bool
    {
        return (bool) preg_match(
            '#^/(?:(?:now|about|resume|kit)|work(?:/tag/[a-z0-9-]+)?|work/[a-z0-9-]+|blog(?:/tag/[a-z0-9-]+)?|blog/[a-z0-9-]+)?$#',
            $path,
        );
    }
}
