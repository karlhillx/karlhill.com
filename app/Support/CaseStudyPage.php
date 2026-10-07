<?php

namespace App\Support;

final readonly class CaseStudyPage
{
    public ?string $liveUrl;

    public string $liveLabel;

    public string $canonical;

    public string $frameTitle;

    public bool $isJacobs;

    public bool $hasScope;

    public bool $hasDiagram;

    /** @var list<array{label: string, href: string}> */
    public array $alsoLinks;

    /** @var list<string> */
    public array $decisions;

    /** @var array<string, string> */
    public array $jobScope;

    /** @var list<array{id: string, text: string, level?: int}> */
    public array $toc;

    /** @var list<array{src: string, alt: string, label: string, position: string}> */
    public array $gallery;

    /** @param array<string, mixed> $project */
    public function __construct(array $project, bool $hasRelated)
    {
        $study = $project['case_study'];
        $this->liveUrl = ProjectCatalog::liveUrl($project);
        $this->liveLabel = ProjectCatalog::artifactLabel($project);
        $this->alsoLinks = ProjectCatalog::alsoLinks($project);
        $this->canonical = PageMeta::siteUrl().'/work/'.$project['slug'];
        $this->isJacobs = $project['slug'] === 'jacobs-mission-software';
        $this->decisions = $study['decisions'];
        $this->jobScope = $this->isJacobs ? config('site.experience.current.scope', []) : [];
        $this->hasScope = filled($this->jobScope['owned'] ?? null)
            && filled($this->jobScope['influence'] ?? null);
        $this->hasDiagram = ! empty($study['diagram']['zones']) || ! empty($study['diagram']['stages']);
        $this->frameTitle = $this->isJacobs
            ? 'Technical delivery'
            : (($this->liveUrl ? parse_url($this->liveUrl, PHP_URL_HOST) : null) ?: $project['title']);

        $bodyH2s = array_filter($study['body_toc'] ?? [], fn (array $item): bool => $item['level'] === 2);
        $this->toc = array_values(array_filter([
            ['id' => 'problem', 'text' => 'Problem'],
            ['id' => 'decisions', 'text' => 'Decisions'],
            $this->hasDiagram ? ['id' => 'delivery-system', 'text' => $study['diagram']['title']] : null,
            ['id' => 'snapshot', 'text' => 'Evidence'],
            ['id' => 'outcome', 'text' => 'Outcome'],
            $this->hasScope ? ['id' => 'scope', 'text' => 'Scope'] : null,
            ! empty($study['leadership']) ? ['id' => 'leadership', 'text' => 'Team & contribution'] : null,
            ...$bodyH2s,
            $hasRelated ? ['id' => 'related', 'text' => 'Related'] : null,
        ]));

        $defaults = [
            'alt' => $project['image_alt'] ?? 'Screenshot of '.$project['title'],
            'label' => $project['title'],
            'position' => $project['imagePosition'] ?? 'object-center',
        ];
        $gallery = array_map(
            fn (array|string $shot): array => array_merge($defaults, is_string($shot) ? ['src' => $shot] : $shot),
            $project['gallery'] ?? [],
        );
        if ($gallery === [] && ! $this->isJacobs) {
            $gallery[] = [...$defaults, 'src' => $project['image']];
        }
        $this->gallery = $gallery;
    }
}
