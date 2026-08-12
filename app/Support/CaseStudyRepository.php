<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Spatie\YamlFrontMatter\YamlFrontMatter;

/**
 * Load case-study narratives from resources/work/{slug}.md (YAML front matter).
 * Project card metadata stays in config/site/projects.php.
 */
final class CaseStudyRepository
{
    public function __construct(
        protected readonly string $directory,
    ) {}

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $slug): ?array
    {
        $all = $this->all();

        return $all[$slug] ?? null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        $signature = $this->signature();

        return Cache::remember(
            "work.case-studies.{$signature}",
            now()->addHour(),
            fn () => $this->loadAll(),
        );
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function loadAll(): array
    {
        if (! is_dir($this->directory)) {
            return [];
        }

        $studies = [];
        foreach (File::files($this->directory) as $file) {
            if (! str_ends_with($file->getFilename(), '.md')) {
                continue;
            }

            $slug = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $parsed = $this->parse($file->getPathname());
            if ($parsed !== null) {
                $studies[$slug] = $parsed;
            }
        }

        return $studies;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function parse(string $path): ?array
    {
        $document = YamlFrontMatter::parseFile($path);
        $matter = $document->matter();

        if ($matter === []) {
            return null;
        }

        /** @var array<string, mixed> $study */
        $study = $matter;

        // Keep approach as a fallback alias for decisions in older files.
        if (empty($study['decisions']) && ! empty($study['approach'])) {
            $study['decisions'] = $study['approach'];
        }

        $body = trim((string) $document->body());
        if ($body !== '') {
            $study['notes_markdown'] = $body;
        }

        $study['source_path'] = $path;

        return $study;
    }

    protected function signature(): string
    {
        if (! is_dir($this->directory)) {
            return 'empty';
        }

        $bits = [];
        foreach (File::files($this->directory) as $file) {
            if (! str_ends_with($file->getFilename(), '.md')) {
                continue;
            }
            $bits[] = $file->getFilename().':'.$file->getMTime().':'.$file->getSize();
        }

        sort($bits);

        return md5(implode('|', $bits));
    }
}
