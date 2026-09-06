<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\Extension\SmartPunct\SmartPunctExtension;
use League\CommonMark\MarkdownConverter;
use Spatie\YamlFrontMatter\YamlFrontMatter;
use Tempest\Highlight\CommonMark\HighlightExtension;

/**
 * Load case-study narratives from resources/work/{slug}.md (YAML front matter).
 * Project card metadata stays in config/site/projects.php.
 */
final class CaseStudyRepository
{
    public function __construct(
        protected readonly string $directory,
    ) {}

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $slug): ?array
    {
        $all = $this->all();

        return $all[$slug] ?? null;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    public function all(): array
    {
        $signature = $this->signature();

        return Cache::remember(
            "work.case-studies.{$signature}",
            now()->addHour(),
            fn () => $this->loadAll(),
        );
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    protected function loadAll(): array
    {
        if (! is_dir($this->directory)) {
            return [];
        }

        $studies = [];
        foreach (MarkdownDirectory::files($this->directory) as $file) {
            $slug = pathinfo($file->getFilename(), PATHINFO_FILENAME);
            $parsed = $this->parse($file->getPathname());
            if ($parsed !== null) {
                $studies[$slug] = $parsed;
            }
        }

        return $studies;
    }

    /**
     * @return array<string, mixed>|null
     */
    protected function parse(string $path): ?array
    {
        $document = YamlFrontMatter::parseFile($path);
        $matter = $document->matter();

        if ($matter === []) {
            return null;
        }

        /** @var array<string, mixed> $study */
        $study = $matter;

        // Keep approach as a fallback alias for decisions in older files.
        if (empty($study['decisions']) && ! empty($study['approach'])) {
            $study['decisions'] = $study['approach'];
        }

        $body = trim((string) $document->body());
        if ($body !== '') {
            $study['notes_markdown'] = $body;
            $cleanBody = preg_replace('/^\s*#\s+[^\r\n]+[\r\n]*/', '', $body);
            $cleanBody = trim($cleanBody ?? $body);

            if ($cleanBody !== '' && ! str_starts_with($cleanBody, 'Case study narrative for [')) {
                $html = $this->renderMarkdown($cleanBody);
                ['html' => $processedHtml, 'toc' => $bodyToc] = $this->processHeadings($html);
                $study['body_html'] = $processedHtml;
                $study['body_toc'] = $bodyToc;
                $study['body_markdown'] = $cleanBody;
            }
        }

        $study['source_path'] = $path;

        return $study;
    }

    protected function renderMarkdown(string $markdown): string
    {
        // CommonMark specifications treat any blank line inside an HTML block (like <figure>)
        // as terminating the HTML block, causing any subsequent indented lines (like SVG elements)
        // to be parsed as markdown code blocks (<pre><code>). Collapse whitespace-only lines
        // within <figure> tags to ensure clean continuous HTML block parsing.
        $markdown = (string) preg_replace_callback('/<figure[\s\S]*?<\/figure>/i', function (array $matches): string {
            return (string) preg_replace('/^[ \t]*[\r\n]+/m', '', $matches[0]);
        }, $markdown);

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
        $environment->addExtension(new HighlightExtension);

        $converter = new MarkdownConverter($environment);

        return (string) $converter->convert($markdown);
    }

    /**
     * @return array{html: string, toc: array<int, array{id: string, text: string, level: int}>}
     */
    protected function processHeadings(string $html): array
    {
        if (trim($html) === '') {
            return ['html' => $html, 'toc' => []];
        }

        $toc = [];
        $usedIds = [];

        $processed = (string) preg_replace_callback(
            '/<h([23])>(.*?)<\/h\1>/s',
            function (array $matches) use (&$toc, &$usedIds): string {
                $level = (int) $matches[1];
                $inner = $matches[2];
                $text = trim(html_entity_decode(strip_tags($inner), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

                if ($text === '') {
                    return $matches[0];
                }

                $id = $this->uniqueHeadingId($text, $usedIds);
                $toc[] = ['id' => $id, 'text' => $text, 'level' => $level];

                $label = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
                $anchor = '<a class="heading-permalink" href="#'.$id.'" aria-label="Link to section: '.$label.'">'
                    .'<span aria-hidden="true">#</span></a>';

                return '<h'.$level.' id="'.$id.'" class="heading-anchor-wrap">'.$anchor.$inner.'</h'.$level.'>';
            },
            $html,
        );

        return ['html' => $processed, 'toc' => $toc];
    }

    /**
     * @param  array<int, string>  $usedIds
     */
    protected function uniqueHeadingId(string $text, array &$usedIds): string
    {
        $base = Str::slug($text) ?: 'section';
        $id = $base;
        $suffix = 2;

        while (in_array($id, $usedIds, true)) {
            $id = "{$base}-{$suffix}";
            $suffix++;
        }

        $usedIds[] = $id;

        return $id;
    }

    protected function signature(): string
    {
        return MarkdownDirectory::signature($this->directory);
    }
}
