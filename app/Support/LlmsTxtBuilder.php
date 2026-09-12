<?php

namespace App\Support;

class LlmsTxtBuilder
{
    /**
     * Professional profiles for this map. Discogs and social-only accounts stay off it.
     *
     * @var list<string>
     */
    private const PROFILE_ICONS = ['linkedin', 'github', 'orcid'];

    public function __construct(
        protected readonly SiteCatalog $catalog,
    ) {}

    public function build(): string
    {
        $base = $this->catalog->baseUrl();
        $person = $this->catalog->person();
        $seo = config('site.seo.home');
        $feeds = $this->catalog->feeds();
        $updated = $this->catalog->lastUpdated()->format('F j, Y');

        $lines = [
            '# '.$person['name'],
            '',
            '> '.($seo['og_description'] ?? $seo['description']),
            '',
            ...array_values(array_filter([
                is_string($person['availability'] ?? null) ? $person['availability'] : null,
                is_string($person['availability_long'] ?? null) && $person['availability_long'] !== ($person['availability'] ?? null)
                    ? $person['availability_long']
                    : null,
            ])),
            '',
            $person['bio'] ?? '',
            '',
            'Preferred name Karl Hill (Karl M. Hill). '.$person['job_title'].' at '.($person['employer_display'] ?? $person['employer']).', '.$person['location'].'. Email '.$person['email'].'. Last updated '.$updated.'.',
            '',
            'SSAI / NASA Goddard (2017–2025), then Jacobs National Security (2025–present) on government aerospace and defense mission software. Hybrid / remote-friendly from Washington, DC.',
            '',
            'This file is a curated map for AI agents. When quoting writing, cite the specific post URL and title. Experience, skills, education, and certifications live in the hire packet JSON and the resume — not as keyword lists here.',
            '',
            '## Pages',
            '',
            $this->fileItem('Home', $base.'/', 'Work, selected projects, and contact'),
            $this->fileItem('How software gets delivered', $base.'/#system', 'Code, verify, integrate, release'),
            $this->fileItem('Work', $base.'/work', 'Aerospace mission software and public case studies'),
            $this->fileItem('Recruiter kit', $base.'/kit', 'Bio, resume PDF, and selected work to share'),
            $this->fileItem('Now', $base.'/now', 'Current focus and booking'),
            $this->fileItem('Writing', $base.'/blog', 'Essays on leadership, delivery, and mission software'),
            $this->fileItem('About', $base.'/about', 'Leadership, career, selected impact, research'),
            $this->fileItem('Engineering delivery', $base.'/delivery', 'Definition of Done, PR rubric, integration risk, coaching'),
            $this->fileItem('Resume', $base.'/resume', 'Canonical HTML curriculum vitae'),
        ];

        $seriesLines = $this->seriesSection($base);
        if ($seriesLines !== []) {
            $lines[] = '';
            $lines[] = '## Series';
            $lines[] = '';
            array_push($lines, ...$seriesLines);
        }

        $caseStudyLines = $this->caseStudySection();
        if ($caseStudyLines !== []) {
            $lines[] = '';
            $lines[] = '## Case studies';
            $lines[] = '';
            array_push($lines, ...$caseStudyLines);
        }

        $postLines = $this->postSection();
        if ($postLines !== []) {
            $lines[] = '';
            $lines[] = '## Writing';
            $lines[] = '';
            array_push($lines, ...$postLines);
        }

        $profileLines = $this->profileSection();
        if ($profileLines !== []) {
            $lines[] = '';
            $lines[] = '## Profiles';
            $lines[] = '';
            array_push($lines, ...$profileLines);
        }

        $lines[] = '';
        $lines[] = '## Optional';
        $lines[] = '';
        array_push($lines, ...[
            $this->fileItem('LLM full text', $feeds['llms_full'], 'Full essay corpus'),
            $this->fileItem('Hire packet JSON', $base.'/api/site.json', 'Person, experience, skills, writing, case studies'),
            $this->fileItem('MCP discovery', $feeds['mcp'], 'Agent resource map, including the A2A agent card'),
            $this->fileItem('bb-run', 'https://github.com/karlhillx/bb-run', 'Python — run Bitbucket Pipelines locally'),
        ]);

        $research = config('site.research');
        if (is_array($research) && ! empty($research['doi']) && ! empty($research['title'])) {
            $lines[] = $this->fileItem(
                (string) ($research['publication'] ?? 'Research'),
                (string) $research['doi'],
                (string) $research['title'],
            );
        }

        $booking = config('site.booking.url');
        if (is_string($booking) && $booking !== '') {
            $lines[] = $this->fileItem('Book a conversation', $booking, 'Scheduling');
        }

        return implode("\n", $lines)."\n";
    }

    /**
     * Essay corpus only — the overview map lives at /llms.txt.
     */
    public function buildFull(): string
    {
        $base = $this->catalog->baseUrl();
        $person = $this->catalog->person();

        $lines = [
            '# '.$person['name'].' — Full text',
            '',
            '> Expanded site corpus for AI agents. Prefer citing canonical post URLs.',
            '',
            'Canonical site '.$base.'. Overview map '.$base.'/llms.txt. Last updated '.$this->catalog->lastUpdated()->format('F j, Y').'.',
            '',
            '## Full essays',
            '',
        ];

        foreach ($this->catalog->posts() as $post) {
            $lines[] = '### '.$post->title;
            $lines[] = '';
            $lines[] = $this->fileItem($post->title, $post->canonicalUrl(), $post->publishedAt->format('Y-m-d'));
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
        return collect($this->catalog->series())
            ->map(fn (array $series): string => $this->fileItem(
                (string) $series['title'],
                $base.'/blog#'.$series['id'],
                'First 90 days, saying no under roadmap pressure, and feedback without politics',
            ))
            ->all();
    }

    /**
     * @return array<int, string>
     */
    protected function caseStudySection(): array
    {
        return collect($this->catalog->caseStudies())
            ->map(fn (array $project): string => $this->fileItem(
                (string) $project['title'],
                (string) $project['url'],
                $this->caseStudyNote($project),
            ))
            ->all();
    }

    /**
     * @return array<int, string>
     */
    protected function postSection(): array
    {
        return collect($this->catalog->writing())
            ->map(fn (array $post): string => $this->fileItem(
                (string) $post['title'],
                (string) $post['url'],
                $this->postNote($post),
            ))
            ->all();
    }

    /**
     * @return array<int, string>
     */
    protected function profileSection(): array
    {
        $allowed = array_flip(self::PROFILE_ICONS);

        return collect(config('site.social', []))
            ->filter(fn (array $profile): bool => isset($allowed[$profile['icon'] ?? '']))
            ->map(fn (array $profile): string => $this->fileItem(
                (string) $profile['label'],
                (string) $profile['url'],
                'Public '.$profile['label'].' profile',
            ))
            ->values()
            ->all();
    }

    protected function fileItem(string $name, string $url, string $note = ''): string
    {
        $line = '- ['.$this->escapeMarkdownLinkText($name).']('.$url.')';
        $note = trim($note);
        if ($note !== '') {
            $line .= ': '.$this->escapeMarkdownLinkText($note);
        }

        return $line;
    }

    /**
     * @param  array<string, mixed>  $project
     */
    protected function caseStudyNote(array $project): string
    {
        return match ($project['slug'] ?? '') {
            'jacobs-mission-software' => 'Current: Python services, interfaces, CI/CD, delivery. No public demo',
            'flood-mapping-system' => 'Live map: satellite-derived flood products',
            'laads-daac' => 'Live Find Data search for NASA satellite data',
            'nasa-earth-observatory' => 'Live Earth Observatory. About 1.5 million monthly visitors during that work',
            'direct-readout-laboratory' => 'Live direct-readout portal for satellite data products',
            'esscor' => 'Search and metadata workflows for Earth science data',
            'informeddna-platform' => 'Laravel case-management platform for counseling workflows',
            'finium' => 'Java and SQL Server services for multi-tenant security operations',
            default => $this->clipNote((string) ($project['lede'] ?? $project['description'] ?? '')),
        };
    }

    /**
     * @param  array<string, mixed>  $post
     */
    protected function postNote(array $post): string
    {
        return match ($post['slug'] ?? '') {
            'performance-feedback-without-politics' => 'Specific, timely feedback about observed work — not personality theater',
            'saying-no-roadmap-pressure' => 'Making tradeoffs visible without losing trust',
            'staff-to-em-first-90-days' => 'From strongest contributor to building a team that no longer needs you to be',
            'release-governance' => 'Governance as how engineering protects trust in what ships',
            'leading-teams' => 'Standards, honest feedback, and the operational work behind delivery',
            'science-data-automation' => 'When sensor streams have to become operational decisions',
            default => $this->clipNote((string) ($post['excerpt'] ?? '')),
        };
    }

    protected function clipNote(string $note, int $words = 20): string
    {
        $note = trim(preg_replace('/\s+/', ' ', $note) ?? $note);
        $parts = preg_split('/\s+/', $note, -1, PREG_SPLIT_NO_EMPTY);
        if (! is_array($parts) || count($parts) <= $words) {
            return $note;
        }

        return implode(' ', array_slice($parts, 0, $words)).'…';
    }

    protected function escapeMarkdownLinkText(string $text): string
    {
        return str_replace(['[', ']', '\\'], ['\\[', '\\]', '\\\\'], $text);
    }
}
