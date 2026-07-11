<?php

namespace App\Support;

use Carbon\CarbonImmutable;

final class BlogPost
{
    /**
     * @param  array<int, string>  $tags
     * @param  array<int, array{id: string, text: string, level: int}>  $tableOfContents
     */
    public function __construct(
        public readonly string $slug,
        public readonly string $title,
        public readonly string $excerpt,
        public readonly CarbonImmutable $publishedAt,
        public readonly ?CarbonImmutable $updatedAt,
        public readonly array $tags,
        public readonly ?string $heroImage,
        public readonly string $bodyHtml,
        public readonly string $bodyMarkdown,
        public readonly string $sourcePath,
        public readonly ?int $devToId,
        public readonly int $readMinutes,
        public readonly array $tableOfContents = [],
    ) {}

    public function url(): string
    {
        return route('blog.show', ['slug' => $this->slug]);
    }

    public function canonicalUrl(): string
    {
        return rtrim(config('app.url', 'https://karlhill.com'), '/')
            .'/blog/'.$this->slug;
    }

    /**
     * Relative URL suitable for `<img src>` (works in any environment).
     */
    public function heroImagePath(): ?string
    {
        if ($this->heroImage === null || $this->heroImage === '') {
            return null;
        }

        if (str_starts_with($this->heroImage, 'http://') || str_starts_with($this->heroImage, 'https://')) {
            return $this->heroImage;
        }

        return '/'.ltrim($this->heroImage, '/');
    }

    /**
     * Absolute URL for Open Graph / Twitter cards. A hand-made card at
     * public/img/og/blog/{slug}.jpg always wins; otherwise we serve a branded
     * card generated on the fly at /og/blog/{slug}.png.
     */
    public function ogImageUrl(): string
    {
        $base = rtrim(config('app.url', 'https://karlhill.com'), '/');

        if (is_file(public_path("img/og/blog/{$this->slug}.jpg"))) {
            return "{$base}/img/og/blog/{$this->slug}.jpg";
        }

        return route('og.blog', ['slug' => $this->slug]);
    }

    /**
     * Absolute URL for OG tags and external syndication. Always uses APP_URL.
     */
    public function heroImageUrl(): ?string
    {
        $path = $this->heroImagePath();
        if ($path === null) {
            return null;
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return rtrim(config('app.url', 'https://karlhill.com'), '/').$path;
    }

    public function isoDate(): string
    {
        return $this->publishedAt->toIso8601String();
    }

    public function modifiedAt(): CarbonImmutable
    {
        return $this->updatedAt ?? $this->publishedAt;
    }

    public function wasUpdated(): bool
    {
        return $this->updatedAt !== null && $this->updatedAt->gt($this->publishedAt);
    }
}
