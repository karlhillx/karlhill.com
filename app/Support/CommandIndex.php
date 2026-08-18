<?php

namespace App\Support;

class CommandIndex
{
    public function __construct(
        protected readonly SiteCatalog $catalog,
    ) {}

    /**
     * Search index for the command palette.
     *
     * @return array{
     *     posts: array<int, array{label: string, url: string, keywords: string, group: string}>,
     *     projects: array<int, array{label: string, url: string, keywords: string, group: string}>
     * }
     */
    public function build(): array
    {
        return [
            'posts' => $this->catalog->posts()
                ->map(fn (BlogPost $post) => [
                    'label' => $post->title,
                    'url' => '/blog/'.$post->slug,
                    'keywords' => $this->postKeywords($post),
                    'group' => 'writing',
                    'terms' => SemanticIndex::vector($post->title.' '.$post->excerpt.' '.implode(' ', $post->tags)),
                ])->values()->all(),
            'projects' => collect($this->catalog->caseStudies())
                ->map(fn (array $project) => [
                    'label' => $project['title'],
                    'url' => '/work/'.$project['slug'],
                    'keywords' => trim(implode(' ', [
                        implode(' ', $project['tags'] ?? []),
                        $project['description'] ?? '',
                        'work portfolio case study',
                    ])),
                    'group' => 'work',
                    'terms' => SemanticIndex::vector(implode(' ', [
                        $project['title'] ?? '',
                        $project['description'] ?? '',
                        implode(' ', $project['tags'] ?? []),
                    ])),
                ])
                ->values()
                ->all(),
        ];
    }

    protected function postKeywords(BlogPost $post): string
    {
        $body = preg_replace('/[#>*_`\[\]\(\)!-]/', ' ', $post->bodyMarkdown) ?? '';
        $body = preg_replace('/\s+/', ' ', strip_tags($body)) ?? '';
        $body = mb_substr(trim($body), 0, 2400);

        $series = BlogSeries::forPost($post);
        $seriesBits = $series
            ? $series['title'].' series '.$series['id']
            : '';

        return trim(implode(' ', [
            $post->title,
            implode(' ', $post->tags),
            $post->excerpt,
            $body,
            $seriesBits,
            'writing blog essay',
        ]));
    }
}
