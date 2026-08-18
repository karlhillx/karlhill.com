<?php

namespace App\Support;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

final class WebmentionVerifier
{
    /**
     * @return array{ok: bool, mention?: array<string, mixed>, error?: string}
     */
    public static function verify(string $source, string $target): array
    {
        $sourceUrl = self::normalizeUrl($source);
        $targetUrl = self::normalizeUrl($target);

        if ($sourceUrl === null || $targetUrl === null) {
            return ['ok' => false, 'error' => 'invalid_url'];
        }

        $expectedHost = parse_url(PageMeta::siteUrl(), PHP_URL_HOST);
        $targetHost = parse_url($targetUrl, PHP_URL_HOST);
        $targetPath = parse_url($targetUrl, PHP_URL_PATH) ?? '';

        if ($expectedHost === null || $targetHost !== $expectedHost) {
            return ['ok' => false, 'error' => 'target_not_local'];
        }

        if (! preg_match('#^/blog/([a-z0-9-]+)/?$#', $targetPath, $matches)) {
            return ['ok' => false, 'error' => 'target_not_post'];
        }

        $slug = $matches[1];
        $post = app(BlogPostRepository::class)->find($slug);
        if ($post === null) {
            return ['ok' => false, 'error' => 'unknown_post'];
        }

        try {
            $response = Http::timeout(8)
                ->withHeaders(['Accept' => 'text/html, application/xhtml+xml'])
                ->withUserAgent('karlhill.com-webmention/1.0')
                ->get($sourceUrl);
        } catch (ConnectionException) {
            return ['ok' => false, 'error' => 'source_unreachable'];
        }

        if (! $response->successful()) {
            return ['ok' => false, 'error' => 'source_unreachable'];
        }

        $html = $response->body();
        if (! str_contains($html, $targetUrl) && ! str_contains($html, $targetPath)) {
            return ['ok' => false, 'error' => 'source_missing_link'];
        }

        $title = self::extractTitle($html) ?? $sourceUrl;
        $author = self::extractAuthor($html);

        return [
            'ok' => true,
            'mention' => [
                'source' => $sourceUrl,
                'target' => $targetUrl,
                'slug' => $slug,
                'title' => Str::limit(trim($title), 180),
                'author' => $author,
                'verified' => true,
                'received_at' => now()->toIso8601String(),
            ],
        ];
    }

    public static function normalizeUrl(string $url): ?string
    {
        $url = trim($url);
        if ($url === '' || ! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $parts = parse_url($url);
        $scheme = strtolower((string) ($parts['scheme'] ?? ''));
        if (! in_array($scheme, ['http', 'https'], true)) {
            return null;
        }

        return $url;
    }

    protected static function extractTitle(string $html): ?string
    {
        if (preg_match('/<title[^>]*>([^<]+)<\/title>/i', $html, $match)) {
            return html_entity_decode(trim($match[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return null;
    }

    protected static function extractAuthor(string $html): ?string
    {
        if (preg_match('/class="[^"]*p-author[^"]*"[^>]*>([^<]+)/i', $html, $match)) {
            return html_entity_decode(trim($match[1]), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return null;
    }
}
