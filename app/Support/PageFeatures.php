<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Progressive JS features loaded per route (see resources/js/app.js).
 * Core modules (nav, ⌘K, toast, SW, view transitions, theme) always boot.
 * Ambient platform chrome (pointer spotlight, soft-nav, summarizer, WebGPU,
 * ⌘K tip) stays off the default hire path.
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

        $features = [];

        if ($name === 'home') {
            $features[] = 'contact';
            $features[] = 'reveal';
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
            }

            return array_values(array_unique($features));
        }

        if (
            str_starts_with((string) $name, 'work')
            || str_starts_with((string) $name, 'blog')
            || in_array($name, ['about', 'now', 'kit', 'resume'], true)
        ) {
            $features[] = 'reveal';
        }

        if (str_starts_with((string) $name, 'work') || str_starts_with((string) $name, 'blog')) {
            $features[] = 'media';
        }

        if (str_starts_with((string) $name, 'blog') && self::pushEnabled()) {
            $features[] = 'push';
        }

        return array_values(array_unique($features));
    }

    /** The subscribe button only renders with a VAPID public key; skip the chunk otherwise. */
    private static function pushEnabled(): bool
    {
        return filled(config('site.push.public_key'));
    }
}
