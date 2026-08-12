<?php

namespace App\Support;

final class Booking
{
    /**
     * Build an iframe-friendly scheduler URL for Calendly / Cal.com embeds.
     */
    public static function embedSrc(?string $url): ?string
    {
        if (! is_string($url) || trim($url) === '') {
            return null;
        }

        $url = trim($url);
        $parts = parse_url($url);
        if (($parts['scheme'] ?? null) !== 'https' || empty($parts['host'])) {
            return null;
        }

        $host = strtolower($parts['host']);

        if (str_ends_with($host, 'calendly.com')) {
            $separator = str_contains($url, '?') ? '&' : '?';

            return $url.$separator.'hide_gdpr_banner=1&hide_landing_page_details=1';
        }

        if (str_ends_with($host, 'cal.com')) {
            $separator = str_contains($url, '?') ? '&' : '?';

            return $url.$separator.'embed=true';
        }

        // Unknown hosts: still allow iframe if https (user opted in via BOOKING_URL).
        return $url;
    }
}
