<?php

namespace DryStandard;

use Illuminate\Support\Collection;
use Spatie\YamlFrontMatter\YamlFrontMatter;

final class PageDocument
{
    /**
     * @param  array<string, mixed>  $matter
     */
    public function __construct(
        public readonly string $title,
        public readonly string $slug,
        public readonly string $summary,
        public readonly string $bodyHtml,
        public readonly string $sourcePath,
        public readonly array $matter = [],
    ) {}

    /**
     * @return Collection<int, self>
     */
    public static function loadDirectory(string $directory): Collection
    {
        if (! is_dir($directory)) {
            return collect();
        }

        $files = glob($directory.DIRECTORY_SEPARATOR.'*.md') ?: [];

        return collect($files)
            ->map(fn (string $file): self => self::load($file))
            ->sortBy(fn (self $page): string => (string) ($page->matter['order'] ?? $page->title))
            ->values();
    }

    public static function load(string $file): self
    {
        $document = YamlFrontMatter::parseFile($file);
        $slug = (string) ($document->matter('slug') ?: pathinfo($file, PATHINFO_FILENAME));

        return new self(
            title: (string) ($document->matter('title') ?: $slug),
            slug: $slug,
            summary: (string) ($document->matter('summary') ?: $document->matter('excerpt') ?: ''),
            bodyHtml: Markdown::toHtml($document->body()),
            sourcePath: $file,
            matter: $document->matter(),
        );
    }
}
