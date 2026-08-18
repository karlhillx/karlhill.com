<?php

namespace App\Support;

use Illuminate\Support\Facades\File;

/**
 * Atomic JSON file persistence for optional, no-database features
 * (webmentions, push subscriptions, reporting).
 */
final class JsonFileStore
{
    /**
     * @return array<string, mixed>
     */
    public static function read(string $path): array
    {
        if (! is_file($path)) {
            return [];
        }

        $raw = File::get($path);
        $decoded = json_decode($raw, true);

        return is_array($decoded) ? $decoded : [];
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public static function write(string $path, array $data): void
    {
        File::ensureDirectoryExists(dirname($path));

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            return;
        }

        $tmp = $path.'.'.bin2hex(random_bytes(4)).'.tmp';
        File::put($tmp, $json.PHP_EOL);
        File::move($tmp, $path);
    }
}
