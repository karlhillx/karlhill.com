<?php

namespace App\Support;

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Cache;

final class Stack
{
    public static function laravelVersion(): string
    {
        return Application::VERSION;
    }

    /**
     * Exact installed Tailwind version, read from package-lock.json
     * (falls back to the package.json range with the ^/~ stripped).
     * Cached because the lockfile only changes on deploy.
     */
    public static function tailwindVersion(): ?string
    {
        return Cache::remember('stack.tailwind-version', now()->addDay(), function (): ?string {
            $lockPath = base_path('package-lock.json');

            if (is_file($lockPath)) {
                $lock = json_decode((string) file_get_contents($lockPath), true);
                $version = $lock['packages']['node_modules/tailwindcss']['version'] ?? null;

                if (is_string($version)) {
                    return $version;
                }
            }

            $package = json_decode((string) file_get_contents(base_path('package.json')), true);
            $range = $package['devDependencies']['tailwindcss'] ?? $package['dependencies']['tailwindcss'] ?? null;

            return is_string($range) ? ltrim($range, '^~') : null;
        });
    }
}
