<?php

namespace App\Support;

/**
 * Kill switches for optional platform surfaces.
 * Core pages, feeds, and the hire packet stay on regardless.
 */
final class SiteFeatures
{
    public static function enabled(string $feature): bool
    {
        return filter_var(config('site.features.'.$feature), FILTER_VALIDATE_BOOLEAN);
    }

    public static function webmention(): bool
    {
        return self::enabled('webmention');
    }

    public static function reporting(): bool
    {
        return self::enabled('reporting');
    }

    public static function compressionDictionary(): bool
    {
        return self::enabled('compression_dictionary');
    }

    public static function contentCredentials(): bool
    {
        return self::enabled('content_credentials');
    }

    public static function webgpu(): bool
    {
        return self::enabled('webgpu');
    }
}
