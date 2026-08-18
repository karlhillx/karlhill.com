<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;

/**
 * Canonical site collections for machine and HTML surfaces.
 * Formatters (llms.txt, sitemap, hire packet, ⌘K) project from here.
 */
final class SiteCatalog
{
    public function __construct(
        protected readonly BlogPostRepository $posts,
    ) {}

    public function baseUrl(): string
    {
        return PageMeta::siteUrl();
    }

    /**
     * @return Collection<int, BlogPost>
     */
    public function posts(): Collection
    {
        return $this->posts->all();
    }

    /**
     * @return array<string, mixed>
     */
    public function person(): array
    {
        $base = $this->baseUrl();
        $person = config('site.person');

        return [
            'name' => $person['name'],
            'job_title' => $person['job_title'],
            'employer' => $person['employer'],
            'location' => $person['location'],
            'email' => $person['email'],
            'tagline' => $person['tagline'] ?? null,
            'bio' => $person['bio'] ?? null,
            'availability' => $person['availability'] ?? null,
            'url' => $base,
            'image' => $base.'/img/webp/profile.webp',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public function now(): array
    {
        $now = config('site.now', []);

        return [
            'url' => $this->baseUrl().'/now',
            'updated' => $now['updated'] ?? null,
            'focus' => collect($now['focus'] ?? [])
                ->filter(fn ($item): bool => is_array($item))
                ->map(fn (array $item): array => [
                    'title' => $item['title'] ?? '',
                    'body' => $item['body'] ?? '',
                ])
                ->values()
                ->all(),
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function experience(): array
    {
        $experience = config('site.experience', []);
        $roles = [];

        if (is_array($experience['current'] ?? null)) {
            $roles[] = $this->role($experience['current'], current: true);
        }

        foreach ($experience['roles'] ?? [] as $role) {
            if (is_array($role)) {
                $roles[] = $this->role($role, current: false);
            }
        }

        if (is_array($experience['earlier'] ?? null)) {
            $roles[] = $this->role($experience['earlier'], current: false);
        }

        return $roles;
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function caseStudies(): array
    {
        $base = $this->baseUrl();

        return ProjectCatalog::withCaseStudies()
            ->map(function (array $project) use ($base): array {
                $study = is_array($project['case_study'] ?? null) ? $project['case_study'] : [];

                return [
                    'slug' => $project['slug'],
                    'title' => $project['title'],
                    'url' => $base.'/work/'.$project['slug'],
                    'live_url' => $project['url'] ?? null,
                    'description' => $project['description'] ?? null,
                    'lede' => $study['lede'] ?? null,
                    'tags' => $project['tags'] ?? [],
                ];
            })
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function writing(): array
    {
        return $this->posts()
            ->map(fn (BlogPost $post): array => [
                'slug' => $post->slug,
                'title' => $post->title,
                'url' => $post->canonicalUrl(),
                'date' => $post->publishedAt->toDateString(),
                'excerpt' => $post->excerpt,
                'tags' => $post->tags,
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function series(): array
    {
        return BlogSeries::published()
            ->map(fn (array $series): array => [
                'id' => $series['id'],
                'title' => $series['title'],
                'description' => $series['description'],
                'posts' => $series['posts']
                    ->map(fn (BlogPost $post): array => [
                        'slug' => $post->slug,
                        'title' => $post->title,
                        'url' => $post->canonicalUrl(),
                    ])
                    ->values()
                    ->all(),
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array{label: string, url: string}>
     */
    public function profiles(): array
    {
        return collect(config('site.social', []))
            ->filter(fn (array $link): bool => ($link['schema'] ?? true) !== false)
            ->map(fn (array $link): array => [
                'label' => $link['label'],
                'url' => $link['url'],
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<string, string>
     */
    public function feeds(): array
    {
        $base = $this->baseUrl();

        return [
            'atom' => $base.'/feed.xml',
            'json' => $base.'/feed.json',
            'llms' => $base.'/llms.txt',
            'llms_full' => $base.'/llms-full.txt',
            'sitemap' => $base.'/sitemap.xml',
            'webmention' => $base.'/webmention',
            'commands' => $base.'/api/commands.json',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function kit(): array
    {
        $base = $this->baseUrl();

        return [
            'url' => $base.'/kit',
            'resume_html' => $base.'/resume',
            'resume_pdf' => $base.config('site.footer.resume'),
            'booking' => (string) config('site.booking.url'),
            'content_credentials' => $base.'/api/credentials.json',
        ];
    }

    /**
     * @return list<array{loc: string, lastmod: string, changefreq: string, priority: string}>
     */
    public function sitemapUrls(): array
    {
        $base = $this->baseUrl();
        $today = now()->toDateString();
        $latestPost = $this->posts()->first()?->publishedAt->toDateString() ?? $today;

        $urls = [
            ['loc' => $base.'/', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '1.0'],
            ['loc' => $base.'/work', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => $base.'/about', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => $base.'/now', 'lastmod' => $today, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => $base.'/resume', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.85'],
            ['loc' => $base.'/kit', 'lastmod' => $today, 'changefreq' => 'monthly', 'priority' => '0.85'],
            ['loc' => $base.'/blog', 'lastmod' => $latestPost, 'changefreq' => 'weekly', 'priority' => '0.8'],
        ];

        foreach ($this->posts() as $post) {
            $urls[] = [
                'loc' => $post->canonicalUrl(),
                'lastmod' => $post->publishedAt->toDateString(),
                'changefreq' => 'yearly',
                'priority' => '0.7',
            ];
        }

        foreach ($this->caseStudies() as $study) {
            $urls[] = [
                'loc' => $study['url'],
                'lastmod' => $today,
                'changefreq' => 'yearly',
                'priority' => '0.75',
            ];
        }

        return $urls;
    }

    public function lastUpdated(): CarbonImmutable
    {
        $dates = $this->posts()
            ->map(fn (BlogPost $post) => $post->modifiedAt())
            ->all();

        $nowUpdated = config('site.now.updated');
        if (is_string($nowUpdated) && $nowUpdated !== '') {
            try {
                $dates[] = CarbonImmutable::parse($nowUpdated);
            } catch (\Throwable) {
                // Ignore unparseable editorial dates.
            }
        }

        $latest = collect($dates)->filter()->max();

        return $latest ?? CarbonImmutable::now();
    }

    /**
     * @param  array<string, mixed>  $role
     * @return array<string, mixed>
     */
    protected function role(array $role, bool $current): array
    {
        $highlights = collect($role['highlights'] ?? [])
            ->filter(fn ($item): bool => is_string($item) && $item !== '')
            ->map(fn (string $item): string => PlainText::fromHtml($item))
            ->values()
            ->all();

        return [
            'title' => $role['title'] ?? null,
            'company' => $role['company'] ?? null,
            'location' => $role['location'] ?? null,
            'period' => $role['period'] ?? null,
            'current' => $current,
            'highlights' => $highlights,
        ];
    }
}
