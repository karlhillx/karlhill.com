<?php

namespace App\Support;

class CommandIndex
{
    /**
     * Search index for the command palette (embedded once per page).
     *
     * @return array{
     *     posts: array<int, array{label: string, url: string, keywords: string, group: string}>,
     *     projects: array<int, array{label: string, url: string, keywords: string, group: string}>
     * }
     */
    public static function build(): array
    {
        $posts = app(BlogPostRepository::class)->all();

        return [
            'posts' => $posts->map(fn (BlogPost $post) => [
                'label' => $post->title,
                'url' => '/blog/'.$post->slug,
                'keywords' => self::postKeywords($post),
                'group' => 'writing',
            ])->values()->all(),
            'projects' => ProjectCatalog::withCaseStudies()
                ->map(fn (array $project) => [
                    'label' => $project['title'],
                    'url' => '/work/'.$project['slug'],
                    'keywords' => trim(implode(' ', [
                        implode(' ', $project['tags'] ?? []),
                        $project['description'] ?? '',
                        'work portfolio case study',
                    ])),
                    'group' => 'work',
                ])
                ->values()
                ->all(),
        ];
    }

    protected static function postKeywords(BlogPost $post): string
    {
        $body = preg_replace('/[#>*_`\[\]\(\)!-]/', ' ', $post->bodyMarkdown) ?? '';
        $body = preg_replace('/\s+/', ' ', strip_tags($body)) ?? '';
        $body = mb_substr(trim($body), 0, 800);

        $series = BlogSeries::forPost($post);
        $seriesBits = $series
            ? $series['title'].' series '.$series['id']
            : '';

        return trim(implode(' ', [
            implode(' ', $post->tags),
            $post->excerpt,
            $body,
            $seriesBits,
            'writing blog essay',
        ]));
    }
}
