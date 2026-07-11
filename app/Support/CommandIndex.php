<?php

namespace App\Support;

class CommandIndex
{
    /**
     * Lightweight search index for the command palette (embedded once per page).
     *
     * @return array{posts: array<int, array{label: string, url: string, keywords: string}>, projects: array<int, array{label: string, url: string, keywords: string}>}
     */
    public static function build(): array
    {
        $posts = app(BlogPostRepository::class)->all();

        return [
            'posts' => $posts->map(fn (BlogPost $post) => [
                'label' => $post->title,
                'url' => '/blog/'.$post->slug,
                'keywords' => implode(' ', $post->tags).' writing blog essay',
            ])->values()->all(),
            'projects' => ProjectCatalog::withCaseStudies()
                ->map(fn (array $project) => [
                    'label' => $project['title'],
                    'url' => '/work/'.$project['slug'],
                    'keywords' => implode(' ', $project['tags'] ?? []).' work portfolio case study',
                ])
                ->values()
                ->all(),
        ];
    }
}
