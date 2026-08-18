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
        public readonly ?string $ogImageAlt = null,
        public readonly ?int $ogImageWidth = null,
        public readonly ?int $ogImageHeight = null,
        public readonly string $ogType = 'website',
        public readonly ?string $activeNav = null,
        public readonly bool $noindex = false,
        public readonly ?string $articlePublishedTime = null,
        public readonly ?string $articleModifiedTime = null,
        public readonly ?string $articleAuthor = null,
    ) {}

    public static function siteUrl(): string
    {
        return rtrim((string) config('app.url', 'https://karlhill.com'), '/');
    }

    public static function home(): self
    {
        return self::fromSeo('home', '/', 'home');
    }

    public static function blogIndex(): self
    {
        return self::fromSeo('blog_index', '/blog', 'writing');
    }

    public static function work(): self
    {
        return self::fromSeo('work', '/work', 'work');
    }

    public static function about(): self
    {
        return self::fromSeo('about', '/about', 'about');
    }

    public static function now(): self
    {
        return self::fromSeo('now', '/now', 'now');
    }

    public static function resume(): self
    {
        return self::fromSeo('resume', '/resume', 'resume');
    }

    public static function kit(): self
    {
        return self::fromSeo('kit', '/kit', null);
    }

    public static function a11yContactErrors(): self
    {
        return new self(
            title: 'Contact validation fixture — Karl Hill',
            description: 'CI-only contact form error state for accessibility audits.',
            noindex: true,
        );
    }

    public static function clients(): self
    {
        $url = self::siteUrl();

        return new self(
            title: 'Client staging — Karl Hill',
            description: 'Staging previews for client websites in progress.',
            canonical: $url.'/clients',
            ogTitle: 'Client staging — Karl Hill',
            ogDescription: 'Staging previews for client websites in progress.',
            ogImage: $url.'/img/og-home.jpg',
            ogImageAlt: 'Client staging — Karl Hill',
            ogImageWidth: 1200,
            ogImageHeight: 630,
            noindex: true,
        );
    }

    public static function blogTag(string $tag): self
    {
        $label = Str::title(str_replace('-', ' ', $tag));
        $url = self::siteUrl();

        return new self(
            title: "{$label} — Writing — Karl Hill",
            description: "Essays tagged “{$label}” on engineering leadership, mission software, and delivery.",
            canonical: "{$url}/blog/tag/{$tag}",
            ogTitle: "{$label} — Karl Hill",
            ogDescription: "Writing tagged “{$label}”.",
            ogImage: "{$url}/img/og-home.jpg",
            ogImageAlt: "Karl Hill — writing tagged {$label}",
            ogImageWidth: 1200,
            ogImageHeight: 630,
            activeNav: 'writing',
        );
    }

    public static function workTag(string $tag): self
    {
        $url = self::siteUrl();

        return new self(
            title: "{$tag} — Work — Karl Hill",
            description: "Projects tagged with “{$tag}” — mission software, platforms, and engineering leadership.",
            canonical: "{$url}/work/tag/".ProjectCatalog::tagSlug($tag),
            ogTitle: "{$tag} — Karl Hill",
            ogDescription: "Portfolio work tagged “{$tag}”.",
            ogImage: "{$url}/img/og-home.jpg",
            ogImageAlt: "Karl Hill — work tagged {$tag}",
            ogImageWidth: 1200,
            ogImageHeight: 630,
            activeNav: 'work',
        );
    }

    /**
     * @param  array<string, mixed>  $project
     */
    public static function forProject(array $project): self
    {
        $url = self::siteUrl();
        $slug = $project['slug'];
        $study = $project['case_study'];

        $ogCard = ProjectCatalog::ogImageUrl($slug);

        return new self(
            title: "{$project['title']} — Karl Hill",
            description: Str::limit($study['lede'] ?? $project['description'], 155, '…'),
            canonical: "{$url}/work/{$slug}",
            ogTitle: $project['title'],
            ogDescription: Str::limit($study['lede'] ?? $project['description'], 120, '…'),
            ogImage: $ogCard ?? "{$url}{$project['image']}",
            ogImageAlt: $project['title'],
            ogImageWidth: $ogCard ? 1200 : null,
            ogImageHeight: $ogCard ? 630 : null,
            activeNav: 'work',
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
            ogImageAlt: $post->title,
            ogImageWidth: 1200,
            ogImageHeight: 630,
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
            'canonical' => $this->canonical,
            'ogTitle' => $this->ogTitle ?? $this->title,
            'ogDescription' => $this->ogDescription ?? $this->description,
            'ogImage' => $this->ogImage ?? "{$url}/img/og-home.jpg",
            'ogImageAlt' => $this->ogImageAlt,
            'ogImageWidth' => $this->ogImageWidth,
            'ogImageHeight' => $this->ogImageHeight,
            'ogType' => $this->ogType,
            'activeNav' => $this->activeNav,
            'noindex' => $this->noindex,
            'articlePublishedTime' => $this->articlePublishedTime,
            'articleModifiedTime' => $this->articleModifiedTime,
            'articleAuthor' => $this->articleAuthor,
        ];
    }

    private static function fromSeo(string $key, string $path, ?string $activeNav): self
    {
        $seo = config('site.seo.'.$key);
        $url = self::siteUrl();
        $canonical = $path === '/' ? $url : $url.$path;

        return new self(
            title: $seo['title'],
            description: $seo['description'],
            canonical: $canonical,
            ogTitle: $seo['title'],
            ogDescription: $seo['og_description'],
            ogImage: $url.'/img/og-home.jpg',
            ogImageAlt: $seo['title'],
            ogImageWidth: 1200,
            ogImageHeight: 630,
            activeNav: $activeNav,
        );
    }
}
