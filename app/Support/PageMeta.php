<?php

namespace App\Support;

use Illuminate\Support\Str;

final class PageMeta
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly ?string $canonical = null,
        public readonly ?string $ogTitle = null,
        public readonly ?string $ogDescription = null,
        public readonly ?string $ogImage = null,
        public readonly string $ogType = 'website',
        public readonly ?string $activeNav = null,
        public readonly bool $noindex = false,
        public readonly ?string $articlePublishedTime = null,
        public readonly ?string $articleModifiedTime = null,
        public readonly ?string $articleAuthor = null,
    ) {}

    public static function siteUrl(): string
    {
        return rtrim(config('app.url', 'https://karlhill.com'), '/');
    }

    public static function home(): self
    {
        $seo = config('site.seo.home');
        $url = self::siteUrl();

        return new self(
            title: $seo['title'],
            description: $seo['description'],
            canonical: $url,
            ogTitle: $seo['title'],
            ogDescription: $seo['og_description'],
            ogImage: "{$url}/img/og-home.jpg",
        );
    }

    public static function blogIndex(): self
    {
        $seo = config('site.seo.blog_index');
        $url = self::siteUrl();

        return new self(
            title: $seo['title'],
            description: $seo['description'],
            canonical: "{$url}/blog",
            ogTitle: $seo['title'],
            ogDescription: $seo['og_description'],
            ogImage: "{$url}/img/og-home.jpg",
            activeNav: 'writing',
        );
    }

    public static function forPost(BlogPost $post): self
    {
        $author = config('site.person.name');

        return new self(
            title: "{$post->title} — Karl Hill",
            description: Str::limit($post->excerpt, 155, '…'),
            canonical: $post->canonicalUrl(),
            ogTitle: $post->title,
            ogDescription: Str::limit($post->excerpt, 120, '…'),
            ogImage: $post->ogImageUrl(),
            ogType: 'article',
            activeNav: 'writing',
            articlePublishedTime: $post->publishedAt->toIso8601String(),
            articleModifiedTime: $post->modifiedAt()->toIso8601String(),
            articleAuthor: $author,
        );
    }

    public static function notFound(): self
    {
        return new self(
            title: 'Page not found — Karl Hill',
            description: 'This page does not exist or has moved.',
            canonical: self::siteUrl().'/',
            ogTitle: 'Page not found — Karl Hill',
            ogDescription: 'This page does not exist or has moved.',
            noindex: true,
        );
    }

    /**
     * @return array<string, mixed>
     */
    public function toViewData(): array
    {
        $url = self::siteUrl();

        return [
            'title' => $this->title,
            'description' => $this->description,
            'canonical' => $this->canonical ?? $url,
            'ogTitle' => $this->ogTitle ?? $this->title,
            'ogDescription' => $this->ogDescription ?? $this->description,
            'ogImage' => $this->ogImage ?? "{$url}/img/og-home.jpg",
            'ogType' => $this->ogType,
            'activeNav' => $this->activeNav,
            'noindex' => $this->noindex,
            'articlePublishedTime' => $this->articlePublishedTime,
            'articleModifiedTime' => $this->articleModifiedTime,
            'articleAuthor' => $this->articleAuthor,
        ];
    }
}
