<?php

namespace App\Support;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

final class WebmentionSender
{
    /**
     * Discover a webmention endpoint and send a mention.
     *
     * @return array{ok: bool, endpoint?: string, status?: int, error?: string}
     */
    public static function send(string $source, string $target): array
    {
        $endpoint = self::discover($target);
        if ($endpoint === null) {
            return ['ok' => false, 'error' => 'no_endpoint'];
        }

        try {
            $response = Http::timeout(8)
                ->asForm()
                ->withUserAgent('karlhill.com-webmention/1.0')
                ->post($endpoint, [
                    'source' => $source,
                    'target' => $target,
                ]);
        } catch (ConnectionException) {
            return ['ok' => false, 'error' => 'endpoint_unreachable', 'endpoint' => $endpoint];
        }

        $status = $response->status();

        return [
            'ok' => $status >= 200 && $status < 300,
            'endpoint' => $endpoint,
            'status' => $status,
        ];
    }

    /**
     * External http(s) hrefs in a post body, excluding this site.
     *
     * @return list<string>
     */
    public static function externalTargets(BlogPost $post): array
    {
        $host = parse_url(PageMeta::siteUrl(), PHP_URL_HOST);
        preg_match_all('/https?:\/\/[^\s)"\']+/i', $post->bodyMarkdown.' '.$post->bodyHtml, $matches);

        $urls = [];
        foreach ($matches[0] as $url) {
            $url = rtrim($url, '.,);');
            $urlHost = parse_url($url, PHP_URL_HOST);
            if (! is_string($urlHost) || $urlHost === $host) {
                continue;
            }
            $urls[$url] = true;
        }

        return array_keys($urls);
    }

    public static function discover(string $target): ?string
    {
        try {
            $response = Http::timeout(8)
                ->withUserAgent('karlhill.com-webmention/1.0')
                ->head($target);
        } catch (ConnectionException) {
            $response = null;
        }

        if ($response !== null) {
            $fromHeader = self::endpointFromLinkHeader((string) $response->header('Link'), $target);
            if ($fromHeader !== null) {
                return $fromHeader;
            }
        }

        try {
            $get = Http::timeout(8)
                ->withUserAgent('karlhill.com-webmention/1.0')
                ->get($target);
        } catch (ConnectionException) {
            return null;
        }

        if (! $get->successful()) {
            return null;
        }

        $fromHeader = self::endpointFromLinkHeader((string) $get->header('Link'), $target);
        if ($fromHeader !== null) {
            return $fromHeader;
        }

        if (preg_match('/<link[^>]+rel=["\'][^"\']*webmention[^"\']*["\'][^>]*>/i', $get->body(), $match)) {
            if (preg_match('/href=["\']([^"\']+)["\']/', $match[0], $href)) {
                return self::absolutize($href[1], $target);
            }
        }

        return null;
    }

    protected static function endpointFromLinkHeader(string $header, string $base): ?string
    {
        if ($header === '') {
            return null;
        }

        foreach (explode(',', $header) as $part) {
            if (! str_contains(strtolower($part), 'rel="webmention"') && ! str_contains(strtolower($part), 'rel=webmention')) {
                continue;
            }
            if (preg_match('/<([^>]+)>/', $part, $match)) {
                return self::absolutize($match[1], $base);
            }
        }

        return null;
    }

    protected static function absolutize(string $href, string $base): string
    {
        if (str_starts_with($href, 'http://') || str_starts_with($href, 'https://')) {
            return $href;
        }

        $parts = parse_url($base);
        $origin = ($parts['scheme'] ?? 'https').'://'.($parts['host'] ?? '');

        if (str_starts_with($href, '/')) {
            return $origin.$href;
        }

        return $origin.'/'.$href;
    }
}
