<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Progressive JS features loaded per route (see resources/js/app.js).
 * Core modules (nav, ⌘K, toast, SW, view transitions) always boot.
 */
final class PageFeatures
{
    /**
     * @return list<string>
     */
    public static function forRequest(?Request $request = null): array
    {
        $request ??= request();
        $name = $request->route()?->getName() ?? '';

        // Pointer loads site-wide so the page spotlight can wander on idle;
        // magnetic/tilt no-op when those nodes are absent. The contact chunk is
        // only flagged where the form renders (home footer); app.js also loads
        // it whenever [data-contact-form] is present in the markup.
        $features = ['pointer'];

        if ($name === 'home') {
            $features[] = 'contact';
            $features[] = 'reveal';
            $features[] = 'cmdk-tip';
            // Portrait + work cards use LQIP / media enhancements.
            $features[] = 'media';

            return array_values(array_unique($features));
        }

        if (in_array($name, ['work.show', 'blog.show'], true)) {
            $features[] = 'media';
            $features[] = 'reveal';
            $features[] = 'highlight';

            if ($name === 'blog.show') {
                if (self::pushEnabled()) {
                    $features[] = 'push';
                }
                $features[] = 'share';
                $features[] = 'summarizer';
            }

            if ($name === 'work.show' && $request->route('slug') === 'flood-mapping-system' && SiteFeatures::webgpu()) {
                $features[] = 'webgpu';
            }

            return array_values(array_unique($features));
        }

        if (
            str_starts_with((string) $name, 'work')
            || str_starts_with((string) $name, 'blog')
            || in_array($name, ['about', 'now', 'kit', 'lead'], true)
        ) {
            $features[] = 'reveal';
        }

        if (str_starts_with((string) $name, 'work') || str_starts_with((string) $name, 'blog')) {
            $features[] = 'media';
            $features[] = 'soft-nav';
        }

        if (str_starts_with((string) $name, 'blog') && self::pushEnabled()) {
            $features[] = 'push';
        }

        if ($name === 'kit' || $name === 'lead') {
            $features[] = 'summarizer';
        }

        return array_values(array_unique($features));
    }

    /** The subscribe button only renders with a VAPID public key; skip the chunk otherwise. */
    private static function pushEnabled(): bool
    {
        return filled(config('site.push.public_key'));
    }
}
