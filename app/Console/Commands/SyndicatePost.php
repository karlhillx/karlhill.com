<?php

namespace App\Console\Commands;

use App\Support\BlogPost;
use App\Support\BlogPostRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Yaml\Yaml;

class SyndicatePost extends Command
{
    protected $signature = 'post:syndicate
        {slug : The post slug (e.g. release-governance)}
        {--platform=dev.to : Target platform (currently only dev.to)}
        {--dry-run : Print the payload without sending}
        {--update : Force-update an already-syndicated post}';

    protected $description = 'Syndicate a blog post to an external platform with karlhill.com as the canonical URL.';

    public function handle(BlogPostRepository $repository): int
    {
        $slug = (string) $this->argument('slug');
        $platform = (string) $this->option('platform');

        if ($platform !== 'dev.to') {
            $this->error("Unknown platform: {$platform}. Only 'dev.to' is supported.");

            return self::FAILURE;
        }

        $post = $repository->find($slug);
        if (! $post) {
            $this->error("No post found with slug '{$slug}'.");

            return self::FAILURE;
        }

        $payload = $this->buildDevToPayload($post);

        if ($this->option('dry-run')) {
            $this->info('DRY RUN — payload that would be sent to dev.to:');
            $this->line(json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            $this->newLine();
            $this->line($post->devToId
                ? "Would PUT https://dev.to/api/articles/{$post->devToId}"
                : 'Would POST https://dev.to/api/articles'
            );

            return self::SUCCESS;
        }

        $apiKey = config('services.devto.api_key');
        if (empty($apiKey)) {
            $this->error('DEVTO_API_KEY is not set. Add it to your .env file.');

            return self::FAILURE;
        }

        $http = Http::withHeaders([
            'api-key' => $apiKey,
            'Accept' => 'application/vnd.forem.api-v1+json',
            'Content-Type' => 'application/json',
            'User-Agent' => 'karlhill.com-syndicator/1.0',
        ])->timeout(20);

        $shouldUpdate = $post->devToId !== null && ! $this->option('update');

        if ($post->devToId !== null) {
            $this->info("Post already syndicated (dev.to id {$post->devToId}). Sending update…");
            $response = $http->put("https://dev.to/api/articles/{$post->devToId}", $payload);
        } else {
            $this->info('Creating new dev.to article…');
            $response = $http->post('https://dev.to/api/articles', $payload);
        }

        if (! $response->successful()) {
            $this->error("dev.to API error ({$response->status()}):");
            $this->line($response->body());

            return self::FAILURE;
        }

        $body = $response->json();
        $devToId = $body['id'] ?? null;
        $devToUrl = $body['url'] ?? $body['canonical_url'] ?? null;

        if ($devToId && $post->devToId === null) {
            $this->writeFrontmatterId($post, (int) $devToId);
            Cache::flush();
            $this->info("Wrote dev_to_id={$devToId} back to {$post->sourcePath}");
        }

        $this->info('Done.');
        if ($devToUrl) {
            $this->line("  dev.to:    {$devToUrl}");
        }
        $this->line("  canonical: {$post->canonicalUrl()}");

        return self::SUCCESS;
    }

    /**
     * @return array{article: array<string, mixed>}
     */
    protected function buildDevToPayload(BlogPost $post): array
    {
        return [
            'article' => array_filter([
                'title' => $post->title,
                'body_markdown' => $post->bodyMarkdown,
                'published' => true,
                'tags' => array_slice(array_map(
                    fn ($t) => preg_replace('/[^a-z0-9]/', '', strtolower((string) $t)),
                    $post->tags
                ), 0, 4),
                'canonical_url' => $post->canonicalUrl(),
                'main_image' => $post->heroImageUrl(),
                'description' => $post->excerpt,
            ], fn ($v) => $v !== null && $v !== '' && $v !== []),
        ];
    }

    protected function writeFrontmatterId(BlogPost $post, int $devToId): void
    {
        $raw = File::get($post->sourcePath);

        if (! preg_match('/^---\R(.*?)\R---\R(.*)$/s', $raw, $matches)) {
            $this->warn('Could not locate YAML frontmatter; dev_to_id NOT written.');

            return;
        }

        $frontmatter = Yaml::parse($matches[1]) ?? [];
        $body = $matches[2];

        $frontmatter['dev_to_id'] = $devToId;

        $newYaml = Yaml::dump($frontmatter, 4, 2, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
        $newRaw = "---\n".rtrim($newYaml, "\n")."\n---\n".ltrim($body, "\r\n");

        File::put($post->sourcePath, $newRaw);
    }
}
