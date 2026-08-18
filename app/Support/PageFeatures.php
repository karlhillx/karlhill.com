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

        // Footer contact form is on nearly every page. Pointer loads site-wide
        // so the page spotlight can wander on idle; magnetic/tilt no-op when
        // those nodes are absent.
        $features = ['contact', 'pointer'];

        if ($name === 'home') {
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
                $features[] = 'push';
                $features[] = 'share';
            }

            if ($name === 'work.show' && $request->route('slug') === 'flood-mapping-system' && SiteFeatures::webgpu()) {
                $features[] = 'webgpu';
            }

            return array_values(array_unique($features));
        }

        if (
            str_starts_with((string) $name, 'work')
            || str_starts_with((string) $name, 'blog')
            || in_array($name, ['about', 'now', 'kit'], true)
        ) {
            $features[] = 'reveal';
        }

        if (str_starts_with((string) $name, 'work') || str_starts_with((string) $name, 'blog')) {
            $features[] = 'media';
            $features[] = 'soft-nav';
        }

        if (str_starts_with((string) $name, 'blog')) {
            $features[] = 'push';
        }

        return array_values(array_unique($features));
    }
}
