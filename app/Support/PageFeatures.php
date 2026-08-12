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

        // Footer contact form is on nearly every page.
        $features = ['contact', 'share'];

        if ($name === 'home') {
            $features[] = 'pointer';
            $features[] = 'reveal';
            $features[] = 'cmdk-tip';
            // Portrait + work cards use LQIP / media enhancements.
            $features[] = 'media';

            return array_values(array_unique($features));
        }

        if (in_array($name, ['work.show', 'blog.show'], true)) {
            $features[] = 'media';
            $features[] = 'reveal';

            return array_values(array_unique($features));
        }

        if (
            str_starts_with((string) $name, 'work')
            || str_starts_with((string) $name, 'blog')
            || in_array($name, ['about', 'now', 'kit'], true)
        ) {
            $features[] = 'reveal';
        }

        // Work/blog indexes render LQIP cards — need media.js or images stay blank.
        if (str_starts_with((string) $name, 'work') || str_starts_with((string) $name, 'blog')) {
            $features[] = 'media';
        }

        return array_values(array_unique($features));
    }
}
