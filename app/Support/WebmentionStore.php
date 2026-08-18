<?php

namespace App\Support;

final class WebmentionStore
{
    /**
     * @return list<array<string, mixed>>
     */
    public static function forSlug(string $slug): array
    {
        $data = JsonFileStore::read(self::path($slug));
        $mentions = $data['mentions'] ?? [];

        if (! is_array($mentions)) {
            return [];
        }

        return array_values(array_filter(
            $mentions,
            fn ($mention): bool => is_array($mention) && ($mention['verified'] ?? false) === true
        ));
    }

    /**
     * @param  array<string, mixed>  $mention
     */
    public static function add(string $slug, array $mention): void
    {
        $path = self::path($slug);
        $data = JsonFileStore::read($path);
        $mentions = is_array($data['mentions'] ?? null) ? $data['mentions'] : [];

        $source = (string) ($mention['source'] ?? '');
        $mentions = array_values(array_filter(
            $mentions,
            fn ($existing): bool => ! is_array($existing) || ($existing['source'] ?? '') !== $source
        ));
        $mentions[] = $mention;

        JsonFileStore::write($path, ['mentions' => $mentions]);
    }

    public static function path(string $slug): string
    {
        $safe = preg_replace('/[^a-z0-9-]/', '', $slug) ?: 'unknown';

        return storage_path('app/webmentions/'.$safe.'.json');
    }
}
