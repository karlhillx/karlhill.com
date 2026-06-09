<?php

namespace App\Support;

use Carbon\CarbonImmutable;

final class BlogPost
{
    /**
     * @param  array<int, string>  $tags
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
     * Absolute URL for Open Graph / Twitter cards. Prefers a 1200×630 card at
     * public/img/og/blog/{slug}.jpg when present, then hero, then site default.
     */
    public function ogImageUrl(): string
    {
        $base = rtrim(config('app.url', 'https://karlhill.com'), '/');

        if (is_file(public_path("img/og/blog/{$this->slug}.jpg"))) {
            return "{$base}/img/og/blog/{$this->slug}.jpg";
        }

        return $this->heroImageUrl() ?? "{$base}/img/og-home.jpg";
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
}
