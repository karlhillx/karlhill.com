<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Symfony\Component\Yaml\Yaml;

/**
 * Client staging sites under /clients/{slug}.
 *
 * A directory is previewable when it has index.html or a Laravel-rendered
 * catalog (data/config.yaml).
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
            ->filter(fn (string $entry): bool => $this->isPreviewable($entry))
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
        return $this->isValidSlug($slug) && $this->isPreviewable($slug);
    }

    public function isPreviewable(string $slug): bool
    {
        $directory = $this->root().DIRECTORY_SEPARATOR.$slug;

        return is_file($directory.DIRECTORY_SEPARATOR.'index.html')
            || is_file($directory.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'config.yaml');
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

        // Directory URLs (e.g. octaves-of-love) resolve to their index.html.
        if (is_dir($candidate) && is_file($candidate.DIRECTORY_SEPARATOR.'index.html')) {
            $candidate = $candidate.DIRECTORY_SEPARATOR.'index.html';
        }

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
        $config = $this->root().DIRECTORY_SEPARATOR.$slug.DIRECTORY_SEPARATOR.'data'.DIRECTORY_SEPARATOR.'config.yaml';
        if (is_file($config)) {
            $parsed = Yaml::parseFile($config);
            $name = is_array($parsed) ? data_get($parsed, 'site.name') : null;
            if (is_string($name) && $name !== '') {
                return $name;
            }
        }

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
