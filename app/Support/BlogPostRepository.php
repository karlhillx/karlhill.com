<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Tempest\Highlight\CommonMark\HighlightExtension;

class BlogPostRepository
{
    public function __construct(
        protected readonly string $directory,
    ) {}

    /**
     * @return Collection<int, BlogPost>
     */
    public function all(): Collection
    {
        $signature = $this->signature();

        // Cache only primitive arrays — Laravel 13 ships with
        // `serializable_classes => false` which would otherwise turn cached
        // BlogPost / Carbon / Collection instances into __PHP_Incomplete_Class
        // on read. Hydrate to value objects after we have the array back.
        $rows = Cache::remember(
            "blog.posts.index.{$signature}",
            now()->addHour(),
            fn () => $this->loadAllAsArrays(),
        );

        return collect($rows)->map(fn (array $row) => $this->hydrate($row));
    }

    public function find(string $slug): ?BlogPost
    {
        return $this->all()->firstWhere('slug', $slug);
    }

    public function findOrFail(string $slug): BlogPost
    {
        $post = $this->find($slug);
        if (! $post) {
            abort(404);
        }

        return $post;
    }

    /**
     * @return array{previous: ?BlogPost, next: ?BlogPost}
     */
    public function adjacent(BlogPost $post): array
    {
        $posts = $this->all()->values();
        $index = $posts->search(fn (BlogPost $candidate) => $candidate->slug === $post->slug);

        if ($index === false) {
            return ['previous' => null, 'next' => null];
        }

        return [
            'previous' => $index < $posts->count() - 1 ? $posts[$index + 1] : null,
            'next' => $index > 0 ? $posts[$index - 1] : null,
        ];
    }

    public function pathFor(string $slug): ?string
    {
        return $this->all()->firstWhere('slug', $slug)?->sourcePath;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    protected function loadAllAsArrays(): array
    {
        if (! is_dir($this->directory)) {
            return [];
        }

        $rows = [];
        foreach (File::files($this->directory) as $file) {
            if (! str_ends_with($file->getFilename(), '.md')) {
                continue;
            }
            $row = $this->parseToArray($file->getPathname());
            if ($row !== null) {
                $rows[] = $row;
            }
        }

        usort($rows, fn ($a, $b) => $b['published_at'] <=> $a['published_at']);

        return $rows;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function parseToArray(string $path): ?array
    {
        $raw = File::get($path);
        $document = YamlFrontMatter::parse($raw);

        $title = $document->matter('title');
        if (! $title) {
            return null;
        }

        $slug = $document->matter('slug')
            ?? $this->slugFromFilename(basename($path, '.md'));

        $publishedRaw = $document->matter('date')
            ?? $document->matter('published_at')
            ?? $this->dateFromFilename(basename($path, '.md'));

        $publishedAt = $publishedRaw
            ? CarbonImmutable::parse($publishedRaw)
            : CarbonImmutable::createFromTimestamp(filemtime($path));

        $updatedRaw = $document->matter('updated') ?? $document->matter('updated_at');
        $updatedAt = $updatedRaw ? CarbonImmutable::parse($updatedRaw) : null;

        $tags = $document->matter('tags');
        if (is_string($tags)) {
            $tags = array_filter(array_map('trim', explode(',', $tags)));
        }
        $tags = array_values(array_filter(array_map('strval', (array) ($tags ?? []))));

        $bodyMarkdown = trim($document->body());
        $bodyHtml = $this->renderMarkdown($bodyMarkdown);
        $bodyHtml = $this->labelTaskListCheckboxes($bodyHtml);

        $excerpt = $document->matter('excerpt')
            ?? $this->generateExcerpt($bodyMarkdown);

        $devToId = $document->matter('dev_to_id');

        return [
            'slug' => $slug,
            'title' => $title,
            'excerpt' => $excerpt,
            'published_at' => $publishedAt->toIso8601String(),
            'updated_at' => $updatedAt?->toIso8601String(),
            'tags' => $tags,
            'hero_image' => $document->matter('hero_image'),
            'body_html' => $bodyHtml,
            'body_markdown' => $bodyMarkdown,
            'source_path' => $path,
            'dev_to_id' => $devToId !== null ? (int) $devToId : null,
            'read_minutes' => $this->estimateReadMinutes($bodyMarkdown),
        ];
    }

    /**
     * @param  array<string, mixed>  $row
     */
    protected function hydrate(array $row): BlogPost
    {
        return new BlogPost(
            slug: (string) $row['slug'],
            title: (string) $row['title'],
            excerpt: (string) $row['excerpt'],
            publishedAt: CarbonImmutable::parse($row['published_at']),
            updatedAt: isset($row['updated_at']) && $row['updated_at']
                ? CarbonImmutable::parse($row['updated_at'])
                : null,
            tags: (array) $row['tags'],
            heroImage: $row['hero_image'] !== null ? (string) $row['hero_image'] : null,
            bodyHtml: (string) $row['body_html'],
            bodyMarkdown: (string) $row['body_markdown'],
            sourcePath: (string) $row['source_path'],
            devToId: $row['dev_to_id'] !== null ? (int) $row['dev_to_id'] : null,
            readMinutes: (int) $row['read_minutes'],
        );
    }

    protected function renderMarkdown(string $markdown): string
    {
        $environment = new Environment([
            'html_input' => 'allow',
            'allow_unsafe_links' => false,
            'renderer' => [
                'soft_break' => "<br>\n",
            ],
        ]);
        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);
        $environment->addExtension(new SmartPunctExtension);
        // Server-side syntax highlighting for fenced code blocks. Emits
        // `hl-*` classed spans (styled in resources/css/app.css) so there's no
        // client-side JS cost; the result is baked into the cached post HTML.
        $environment->addExtension(new HighlightExtension);

        $converter = new MarkdownConverter($environment);

        return (string) $converter->convert($markdown);
    }

    protected function labelTaskListCheckboxes(string $html): string
    {
        return (string) preg_replace_callback(
            '/<li>(\s*<input[^>]*type="checkbox"[^>]*>)(.*?)<\/li>/is',
            static function (array $matches): string {
                $label = trim(strip_tags($matches[2]));
                $label = $label !== '' ? htmlspecialchars($label, ENT_QUOTES, 'UTF-8') : 'Task item';
                $input = preg_replace('/>\s*$/', ' aria-label="'.$label.'">', $matches[1]);

                return '<li>'.$input.$matches[2].'</li>';
            },
            $html,
        );
    }

    protected function generateExcerpt(string $markdown): string
    {
        $plain = preg_replace('/^#+\s+.*$/m', '', $markdown);
        $plain = preg_replace('/[`*_>~\[\]\(\)]/', '', (string) $plain);
        $plain = trim((string) preg_replace('/\s+/', ' ', (string) $plain));

        return Str::limit($plain, 180);
    }

    protected function estimateReadMinutes(string $markdown): int
    {
        $words = str_word_count(strip_tags($markdown));

        return max(1, (int) ceil($words / 220));
    }

    protected function slugFromFilename(string $filename): string
    {
        return preg_replace('/^\d{4}-\d{2}-\d{2}-/', '', $filename) ?: $filename;
    }

    protected function dateFromFilename(string $filename): ?string
    {
        if (preg_match('/^(\d{4}-\d{2}-\d{2})/', $filename, $match)) {
            return $match[1];
        }

        return null;
    }

    /**
     * Cache invalidation signature: changes when any post is added/edited.
     */
    protected function signature(): string
    {
        if (! is_dir($this->directory)) {
            return 'empty';
        }

        $entries = collect(File::files($this->directory))
            ->map(fn ($file) => $file->getFilename().':'.$file->getMTime())
            ->sort()
            ->values()
            ->all();

        return md5(implode('|', $entries));
    }
}
