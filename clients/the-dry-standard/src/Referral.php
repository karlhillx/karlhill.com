<?php

namespace DryStandard;

/**
 * Site-wide outbound referral. One slug, appended at render time to external http(s) links.
 * Partner destinations still live in RetailPartners; they call append() rather than hardcoding the slug.
 */
final class Referral
{
    public const SLUG = 'the-dry-standard';

    public const QUERY_KEY = 'ref';

    public static function slug(): string
    {
        return self::SLUG;
    }

    /**
     * Append ?ref=the-dry-standard (or &ref=) to outbound http(s) URLs.
     * Idempotent when ref is already present. Leaves relative, mailto, tel, and fragments alone.
     */
    public static function append(string $url): string
    {
        $url = trim($url);
        if ($url === '' || ! self::isOutboundHttp($url)) {
            return $url;
        }

        $parts = parse_url($url);
        if ($parts === false || empty($parts['host'])) {
            return $url;
        }

        $query = [];
        if (isset($parts['query']) && $parts['query'] !== '') {
            parse_str($parts['query'], $query);
        }

        if (array_key_exists(self::QUERY_KEY, $query) && $query[self::QUERY_KEY] !== '') {
            return $url;
        }

        $query[self::QUERY_KEY] = self::SLUG;

        $rebuilt = (isset($parts['scheme']) ? $parts['scheme'].'://' : '//')
            .(isset($parts['user']) ? $parts['user'].(isset($parts['pass']) ? ':'.$parts['pass'] : '').'@' : '')
            .$parts['host']
            .(isset($parts['port']) ? ':'.$parts['port'] : '')
            .($parts['path'] ?? '')
            .'?'.http_build_query($query)
            .(isset($parts['fragment']) ? '#'.$parts['fragment'] : '');

        return $rebuilt;
    }

    /**
     * Rewrite external hrefs inside an HTML fragment (markdown output, etc.).
     */
    public static function decorateHtml(string $html): string
    {
        if ($html === '' || ! str_contains($html, 'href=')) {
            return $html;
        }

        return (string) preg_replace_callback(
            '/\bhref=("|\')([^"\']+)\1/i',
            static function (array $matches): string {
                $quote = $matches[1];
                $href = html_entity_decode($matches[2], ENT_QUOTES | ENT_HTML5, 'UTF-8');

                return 'href='.$quote.Str::e(self::append($href)).$quote;
            },
            $html,
        );
    }

    public static function isOutboundHttp(string $url): bool
    {
        if (! preg_match('#^https?://#i', $url)) {
            return false;
        }

        $host = strtolower((string) (parse_url($url, PHP_URL_HOST) ?: ''));
        if ($host === '') {
            return false;
        }

        // Never decorate our own host when absolute URLs appear in content.
        foreach (self::internalHosts() as $siteHost) {
            if ($host === $siteHost || str_ends_with($host, '.'.$siteHost)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Hosts treated as first-party for markdown ExternalLink and outbound checks.
     *
     * @return list<string>
     */
    public static function internalHosts(): array
    {
        $hosts = [];
        $siteHost = strtolower((string) (parse_url((string) config('app.url', ''), PHP_URL_HOST) ?: ''));
        if ($siteHost !== '') {
            $hosts[] = $siteHost;
        }

        foreach (['localhost', '127.0.0.1'] as $local) {
            if (! in_array($local, $hosts, true)) {
                $hosts[] = $local;
            }
        }

        return $hosts;
    }

    /**
     * HTML attributes for an outbound http(s) anchor.
     * Standalone links (buy list, sources) get class external-link for the ↗ marker;
     * inline sentence links omit the class.
     *
     * @param  list<string>  $relTokens
     * @return array{target?: string, rel?: string, class?: string}
     */
    public static function externalAttributes(string $url, bool $standalone = false, array $relTokens = ['nofollow', 'noopener', 'noreferrer']): array
    {
        if (! self::isOutboundHttp($url)) {
            return [];
        }

        $rel = array_values(array_unique(array_filter($relTokens, fn (string $token): bool => $token !== '')));
        $attrs = [
            'target' => '_blank',
            'rel' => implode(' ', $rel),
        ];

        if ($standalone) {
            $attrs['class'] = 'external-link';
        }

        return $attrs;
    }

    /**
     * Render attribute string (leading space when non-empty) for an outbound anchor.
     *
     * @param  list<string>  $relTokens
     */
    public static function externalAttributeHtml(string $url, bool $standalone = false, array $relTokens = ['nofollow', 'noopener', 'noreferrer']): string
    {
        $html = '';
        foreach (self::externalAttributes($url, $standalone, $relTokens) as $name => $value) {
            $html .= ' '.$name.'="'.Str::e($value).'"';
        }

        return $html;
    }
}
