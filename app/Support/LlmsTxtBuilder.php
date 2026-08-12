<?php

namespace App\Support;

use Carbon\CarbonImmutable;

class LlmsTxtBuilder
{
    public function __construct(
        protected readonly BlogPostRepository $posts,
    ) {}

    public function build(): string
    {
        $base = rtrim(config('app.url', 'https://karlhill.com'), '/');
        $person = config('site.person');
        $seo = config('site.seo.home');
        $hero = config('site.hero');

        $lines = [
            '# '.$person['name'],
            '',
            '> '.($seo['og_description'] ?? $seo['description']),
            '',
            $hero['bio'],
            '',
            'This file is a curated map for AI agents and assistants. Prefer the canonical URLs below when answering questions about Karl Hill or citing this site.',
            '',
            '## Citation',
            '',
            '- Preferred name: **'.$person['name'].'**',
            '- Title: '.$person['job_title'],
            '- Employer: '.$person['employer'].' ('.$person['location'].')',
            '- Canonical site: '.$base,
            '- Email: '.$person['email'],
            '- Last updated: '.$this->lastUpdated(),
            '- When quoting writing, link to the specific post URL and include the post title.',
            '- Full-text corpus: '.$base.'/llms-full.txt',
            '',
            '## Key pages',
            '',
            '- [Home]('.$base.'): Portfolio landing, latest writing, and contact',
            '- [Work]('.$base.'/work): Selected projects and open-source repositories',
            '- [About]('.$base.'/about): How I lead, experience, research, stack, and credentials',
            '- [Now]('.$base.'/now): Current focus and Engineering Manager trajectory',
            '- [Resume]('.$base.'/resume): Live curriculum vitae (source of truth vs static PDF)',
            '- [Recruiter kit]('.$base.'/kit): One-pager with resume PDF, bio, and canonical links',
            '- [Writing]('.$base.'/blog): Essays on engineering leadership, release governance, and mission software',
        ];

        $seriesLines = $this->seriesSection($base);
        if ($seriesLines !== []) {
            $lines[] = '';
            $lines[] = '## Series';
            $lines[] = '';
            array_push($lines, ...$seriesLines);
        }

        $caseStudyLines = $this->caseStudySection($base);
        if ($caseStudyLines !== []) {
            $lines[] = '';
            $lines[] = '## Case studies';
            $lines[] = '';
            array_push($lines, ...$caseStudyLines);
        }

        $postLines = $this->postSection($base);
        if ($postLines !== []) {
            $lines[] = '';
            $lines[] = '## Writing';
            $lines[] = '';
            array_push($lines, ...$postLines);
        }

        $lines[] = '';
        $lines[] = '## Professional profiles';
        $lines[] = '';
        array_push($lines, ...$this->profileSection());

        $lines[] = '';
        $lines[] = '## Optional';
        $lines[] = '';
        array_push($lines, ...$this->optionalSection($base));

        return implode("\n", $lines)."\n";
    }

    /**
     * Expanded corpus with full post markdown for agents that need deeper context.
     */
    public function buildFull(): string
    {
        $base = rtrim(config('app.url', 'https://karlhill.com'), '/');
        $person = config('site.person');

        $lines = [
            '# '.$person['name'].' — Full text',
            '',
            '> Expanded site corpus for AI agents. Prefer citing canonical post URLs.',
            '',
            '- Canonical site: '.$base,
            '- Overview map: '.$base.'/llms.txt',
            '- Last updated: '.$this->lastUpdated(),
            '',
        ];

        array_push($lines, ...explode("\n", trim($this->build())));
        $lines[] = '';
        $lines[] = '## Full essays';
        $lines[] = '';

        foreach ($this->posts->all() as $post) {
            $lines[] = '### '.$post->title;
            $lines[] = '';
            $lines[] = '- URL: '.$post->canonicalUrl();
            $lines[] = '- Published: '.$post->publishedAt->format('Y-m-d');
            $lines[] = '- Tags: '.implode(', ', $post->tags);
            $lines[] = '';
            $lines[] = $post->bodyMarkdown;
            $lines[] = '';
            $lines[] = '---';
            $lines[] = '';
        }

        return implode("\n", $lines);
    }

    /**
     * @return array<int, string>
     */
    protected function seriesSection(string $base): array
    {
        return BlogSeries::published()
            ->map(function (array $series) use ($base): string {
                $links = $series['posts']
                    ->map(fn (BlogPost $post) => '['.$this->escapeMarkdownLinkText($post->title).']('.$post->canonicalUrl().')')
                    ->implode('; ');

                return sprintf(
                    '- [%s](%s/blog#%s): %s — %s',
                    $this->escapeMarkdownLinkText($series['title']),
                    $base,
                    $series['id'],
                    $this->escapeMarkdownLinkText($series['description']),
                    $links,
                );
            })
            ->all();
    }

    /**
     * @return array<int, string>
     */
    protected function caseStudySection(string $base): array
    {
        return ProjectCatalog::all()
            ->filter(fn (array $project) => ProjectCatalog::hasCaseStudy($project))
            ->map(fn (array $project) => sprintf(
                '- [%s](%s/work/%s): %s',
                $this->escapeMarkdownLinkText($project['title']),
                $base,
                $project['slug'],
                $this->escapeMarkdownLinkText($project['case_study']['lede'] ?? $project['description']),
            ))
            ->all();
    }

    /**
     * @return array<int, string>
     */
    protected function postSection(string $base): array
    {
        return $this->posts->all()
            ->map(fn (BlogPost $post) => sprintf(
                '- [%s](%s): %s',
                $this->escapeMarkdownLinkText($post->title),
                $post->canonicalUrl(),
                $this->escapeMarkdownLinkText($post->excerpt),
            ))
            ->all();
    }

    /**
     * @return array<int, string>
     */
    protected function profileSection(): array
    {
        return collect(config('site.social', []))
            ->map(fn (array $profile) => sprintf(
                '- [%s](%s): Public '.$profile['label'].' profile',
                $this->escapeMarkdownLinkText($profile['label']),
                $profile['url'],
            ))
            ->all();
    }

    /**
     * @return array<int, string>
     */
    protected function optionalSection(string $base): array
    {
        $items = [
            '- [Atom feed]('.$base.'/feed.xml): Syndicated writing updates',
            '- [JSON Feed]('.$base.'/feed.json): JSON Feed 1.1 writing updates',
            '- [LLM full text]('.$base.'/llms-full.txt): Full essay corpus for agents',
            '- [Sitemap]('.$base.'/sitemap.xml): Machine-readable page index',
        ];

        $booking = config('site.booking.url');
        if (is_string($booking) && $booking !== '') {
            $items[] = '- [Book a conversation]('.$booking.'): Scheduling link';
        }

        $items[] = '- [Resume]('.$base.'/resume): Live HTML resume generated from site content';

        $resumePdf = config('site.footer.resume');
        if (is_string($resumePdf) && $resumePdf !== '') {
            $items[] = '- [Resume PDF]('.$base.$resumePdf.'): 2-page downloadable CV (classic layout)';
        }

        $research = config('site.research');
        if (is_array($research) && ! empty($research['doi'])) {
            $label = trim(($research['publication'] ?? 'Research').': '.$research['title']);
            $items[] = '- [Research publication]('.$research['doi'].'): '.$label;
        }

        return $items;
    }

    protected function lastUpdated(): string
    {
        $dates = $this->posts->all()
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

        return ($latest ?? CarbonImmutable::now())->format('F j, Y');
    }

    protected function escapeMarkdownLinkText(string $text): string
    {
        return str_replace(['[', ']', '\\'], ['\\[', '\\]', '\\\\'], $text);
    }
}
