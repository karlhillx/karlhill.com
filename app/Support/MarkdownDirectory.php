<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

/**
 * Shared mtime signature + file listing for flat markdown directories.
 */
final class MarkdownDirectory
{
    /**
     * Cache-busting signature: changes when any matching file is added/edited.
     */
    public static function signature(string $directory, string $extension = 'md'): string
    {
        if (! is_dir($directory)) {
            return 'empty';
        }

        $bits = [];
        foreach (self::files($directory, $extension) as $file) {
            $bits[] = $file->getFilename().':'.$file->getMTime().':'.$file->getSize();
        }

        sort($bits);

        return md5(implode('|', $bits));
    }

    /**
     * @return list<SplFileInfo>
     */
    public static function files(string $directory, string $extension = 'md'): array
    {
        if (! is_dir($directory)) {
            return [];
        }

        $suffix = '.'.$extension;
        $files = [];
        foreach (File::files($directory) as $file) {
            if (str_ends_with($file->getFilename(), $suffix)) {
                $files[] = $file;
            }
        }

        return $files;
    }
}
