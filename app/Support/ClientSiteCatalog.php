<?php

namespace App\Support;

use Illuminate\Support\Collection;

/**
 * Flat-file client staging sites under /clients/{slug}.
 *
 * Each directory with an index.html is a previewable client site.
 */
final class ClientSiteCatalog
{
    public function root(): string
    {
        return base_path('clients');
    }

    /**
     * @return Collection<int, array{slug: string, title: string, path: string}>
     */
    public function all(): Collection
    {
        $root = $this->root();

        if (! is_dir($root)) {
            return collect();
        }

        return collect(scandir($root) ?: [])
            ->filter(fn (string $entry): bool => $entry !== '.' && $entry !== '..')
            ->filter(fn (string $entry): bool => is_dir($root.DIRECTORY_SEPARATOR.$entry))
            ->filter(fn (string $entry): bool => $this->isValidSlug($entry))
            ->filter(fn (string $entry): bool => is_file($root.DIRECTORY_SEPARATOR.$entry.DIRECTORY_SEPARATOR.'index.html'))
            ->sort()
            ->values()
            ->map(fn (string $slug): array => [
                'slug' => $slug,
                'title' => $this->titleFor($slug),
                'path' => '/clients/'.$slug.'/',
            ]);
    }

    public function exists(string $slug): bool
    {
        return $this->isValidSlug($slug)
            && is_file($this->root().DIRECTORY_SEPARATOR.$slug.DIRECTORY_SEPARATOR.'index.html');
    }

    public function isValidSlug(string $slug): bool
    {
        return (bool) preg_match('/^[a-z0-9][a-z0-9.-]*$/i', $slug);
    }

    /**
     * Resolve a file inside a client site, or null when missing / unsafe.
     */
    public function resolveFile(string $slug, string $relativePath): ?string
    {
        if (! $this->exists($slug)) {
            return null;
        }

        $relativePath = str_replace('\\', '/', $relativePath);
        $relativePath = ltrim($relativePath, '/');

        if ($relativePath === '' || str_ends_with($relativePath, '/')) {
            $relativePath = rtrim($relativePath, '/').'/index.html';
            $relativePath = ltrim($relativePath, '/');
        }

        if ($relativePath === '' || str_contains($relativePath, "\0")) {
            return null;
        }

        // Block hidden files/dirs and parent traversal segments.
        foreach (explode('/', $relativePath) as $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..' || str_starts_with($segment, '.')) {
                return null;
            }
        }

        $clientRoot = realpath($this->root().DIRECTORY_SEPARATOR.$slug);
        if ($clientRoot === false) {
            return null;
        }

        $candidate = $clientRoot.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
        $real = realpath($candidate);

        if ($real === false || ! is_file($real)) {
            return null;
        }

        $prefix = $clientRoot.DIRECTORY_SEPARATOR;
        if ($real !== $clientRoot && ! str_starts_with($real, $prefix)) {
            return null;
        }

        return $real;
    }

    public function titleFor(string $slug): string
    {
        $index = $this->root().DIRECTORY_SEPARATOR.$slug.DIRECTORY_SEPARATOR.'index.html';
        if (! is_file($index)) {
            return $slug;
        }

        $html = file_get_contents($index) ?: '';
        if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $matches) === 1) {
            $title = trim(html_entity_decode(strip_tags($matches[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            if ($title !== '') {
                return $title;
            }
        }

        return $slug;
    }
}
