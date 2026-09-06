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
            'given_name' => $person['given_name'] ?? null,
            'family_name' => $person['family_name'] ?? null,
            'job_title' => $person['job_title'],
            'employer' => $person['employer'],
            'employer_display' => $person['employer_display'] ?? $person['employer'],
            'location' => $person['location'],
            'email' => $person['email'],
            'tagline' => $person['tagline'] ?? null,
            'headline' => $person['linkedin_headline'] ?? $person['tagline'] ?? null,
            'linkedin_headline' => $person['linkedin_headline'] ?? null,
            'bio' => $person['bio'] ?? null,
            'availability' => $person['availability'] ?? null,
            'trajectory' => $person['trajectory'] ?? null,
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
                    'link' => $item['link'] ?? null,
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
     * Grouped + flat skills for recruiter/AI matchers (resume stack + search terms).
     *
     * @return array{grouped: list<array{category: string, skills: list<string>}>, flat: list<string>}
     */
    public function skills(): array
    {
        $grouped = collect(config('site.stack', []))
            ->merge(config('site.skills', []))
            ->filter(fn ($group): bool => is_array($group) && ! empty($group['skills']))
            ->map(fn (array $group): array => [
                'category' => (string) ($group['category'] ?? ''),
                'skills' => collect($group['skills'] ?? [])
                    ->filter(fn ($skill): bool => is_string($skill) && $skill !== '')
                    ->values()
                    ->all(),
            ])
            ->values()
            ->all();

        $flat = collect($grouped)
            ->pluck('skills')
            ->flatten()
            ->unique()
            ->values()
            ->all();

        return [
            'grouped' => $grouped,
            'flat' => $flat,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function education(): array
    {
        return collect(config('site.education', []))
            ->filter(fn ($entry): bool => is_array($entry) && ! empty($entry['school']))
            ->map(fn (array $entry): array => [
                'degree' => $entry['degree'] ?? null,
                'school' => $entry['school'],
            ])
            ->values()
            ->all();
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function certifications(): array
    {
        return collect(config('site.certifications', []))
            ->filter(fn ($entry): bool => is_array($entry) && ! empty($entry['name']))
            ->map(fn (array $entry): array => [
                'abbr' => $entry['abbr'] ?? null,
                'name' => $entry['name'],
                'issuer' => $entry['issuer'] ?? null,
                'url' => $entry['url'] ?? null,
                'status' => $entry['status'] ?? null,
            ])
            ->values()
            ->all();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function publication(): ?array
    {
        $research = config('site.research');
        if (! is_array($research) || empty($research['title'])) {
            return null;
        }

        return [
            'title' => $research['title'],
            'publication' => $research['publication'] ?? null,
            'citation' => $research['citation'] ?? null,
            'doi' => $research['doi'] ?? null,
            'published' => $research['published'] ?? null,
        ];
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
                    'updated' => $this->caseStudyUpdated($study)?->toDateString(),
                ];
            })
            ->values()
            ->all();
    }

    /**
     * Editorial `updated:` from the case-study front matter, else the source
     * file's mtime. Never "today" — a sitemap that claims every page changed
     * every day teaches crawlers to ignore lastmod entirely.
     *
     * @param  array<string, mixed>  $study
     */
    protected function caseStudyUpdated(array $study): ?CarbonImmutable
    {
        $raw = $study['updated'] ?? null;
        if (is_string($raw) && $raw !== '') {
            try {
                return CarbonImmutable::parse($raw);
            } catch (\Throwable) {
                // Fall through to the file mtime.
            }
        }

        $path = $study['source_path'] ?? null;
        if (is_string($path) && is_file($path)) {
            $mtime = filemtime($path);
            if ($mtime !== false) {
                return CarbonImmutable::createFromTimestamp($mtime);
            }
        }

        return null;
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
            'mcp' => $base.'/.well-known/mcp.json',
            'agent_card' => $base.'/.well-known/agent-card.json',
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
        $siteUpdated = $this->lastUpdated()->toDateString();
        $latestPost = $this->latestPostModified()?->toDateString() ?? $siteUpdated;
        $latestWork = $this->latestCaseStudyUpdated()?->toDateString() ?? $siteUpdated;
        $nowUpdated = $this->nowUpdated()?->toDateString() ?? $siteUpdated;

        // Evergreen pages have no editorial date of their own; the most recent
        // change anywhere on the site is the honest upper bound.
        $urls = [
            ['loc' => $base.'/', 'lastmod' => $siteUpdated, 'changefreq' => 'monthly', 'priority' => '1.0'],
            ['loc' => $base.'/work', 'lastmod' => $latestWork, 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => $base.'/about', 'lastmod' => $siteUpdated, 'changefreq' => 'monthly', 'priority' => '0.9'],
            ['loc' => $base.'/now', 'lastmod' => $nowUpdated, 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => $base.'/resume', 'lastmod' => $siteUpdated, 'changefreq' => 'monthly', 'priority' => '0.85'],
            ['loc' => $base.'/kit', 'lastmod' => $siteUpdated, 'changefreq' => 'monthly', 'priority' => '0.85'],
            ['loc' => $base.'/blog', 'lastmod' => $latestPost, 'changefreq' => 'weekly', 'priority' => '0.8'],
        ];

        foreach ($this->posts() as $post) {
            $urls[] = [
                'loc' => $post->canonicalUrl(),
                'lastmod' => $post->modifiedAt()->toDateString(),
                'changefreq' => 'yearly',
                'priority' => '0.7',
            ];
        }

        foreach ($this->caseStudies() as $study) {
            $urls[] = [
                'loc' => $study['url'],
                'lastmod' => $study['updated'] ?? $latestWork,
                'changefreq' => 'yearly',
                'priority' => '0.75',
            ];
        }

        return $urls;
    }

    /** Most recent editorial change anywhere: posts, case studies, or /now. */
    public function lastUpdated(): CarbonImmutable
    {
        $latest = collect([
            $this->latestPostModified(),
            $this->latestCaseStudyUpdated(),
            $this->nowUpdated(),
        ])->filter()->max();

        return $latest ?? CarbonImmutable::now();
    }

    public function latestPostModified(): ?CarbonImmutable
    {
        return $this->posts()
            ->map(fn (BlogPost $post) => $post->modifiedAt())
            ->max();
    }

    public function latestCaseStudyUpdated(): ?CarbonImmutable
    {
        return collect($this->caseStudies())
            ->map(fn (array $study) => is_string($study['updated'] ?? null)
                ? CarbonImmutable::parse($study['updated'])
                : null)
            ->filter()
            ->max();
    }

    public function nowUpdated(): ?CarbonImmutable
    {
        $raw = config('site.now.updated');
        if (! is_string($raw) || $raw === '') {
            return null;
        }

        try {
            return CarbonImmutable::parse($raw);
        } catch (\Throwable) {
            return null; // Ignore unparseable editorial dates.
        }
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

        $skills = collect($role['skills'] ?? [])
            ->filter(fn ($skill): bool => is_string($skill) && $skill !== '')
            ->values()
            ->all();

        return [
            'title' => $role['title'] ?? null,
            'company' => $role['company'] ?? null,
            'location' => $role['location'] ?? null,
            'period' => $role['period'] ?? null,
            'current' => $current,
            'summary' => $role['summary'] ?? null,
            'highlights' => $highlights,
            'skills' => $skills,
        ];
    }
}
