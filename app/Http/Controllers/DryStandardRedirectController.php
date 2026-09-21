<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

/**
 * Permanent redirects from the staged /clients/the-dry-standard/ paths
 * to the own-domain apex at drinkdrystandard.com.
 */
class DryStandardRedirectController extends Controller
{
    public function __invoke(?string $path = null): RedirectResponse
    {
        $relative = trim(str_replace('\\', '/', (string) $path), '/');

        // Drop trailing index.html if present
        if ($relative === 'index.html') {
            $relative = '';
        } elseif (str_ends_with($relative, '/index.html')) {
            $relative = substr($relative, 0, -11);
        }

        $target = 'https://drinkdrystandard.com/';
        if ($relative !== '') {
            $target .= $relative;
            // Keep directory-style URLs for document paths
            if (! str_contains(basename($relative), '.') || str_ends_with($relative, '.html')) {
                $target = rtrim($target, '/').'/';
            }
        }

        $query = request()->getQueryString();
        if ($query) {
            $target .= '?'.$query;
        }

        return redirect()->away($target, 301);
    }
}
