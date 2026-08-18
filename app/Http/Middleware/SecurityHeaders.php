<?php

namespace App\Http\Middleware;

use App\Support\CompressionDictionary;
use App\Support\SiteFeatures;
use App\Support\Turnstile;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        $response->headers->set('X-Frame-Options', 'DENY');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', implode(', ', [
            'accelerometer=()',
            'autoplay=()',
            'camera=()',
            'display-capture=()',
            'encrypted-media=()',
            'fullscreen=(self)',
            'geolocation=()',
            'gyroscope=()',
            'magnetometer=()',
            'microphone=()',
            'midi=()',
            'payment=()',
            'picture-in-picture=()',
            'publickey-credentials-get=()',
            'screen-wake-lock=()',
            'usb=()',
            'web-share=(self)',
            'xr-spatial-tracking=()',
        ]));

        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        $reportUrl = $this->reportUrl($request);
        if (SiteFeatures::reporting()) {
            $response->headers->set('Reporting-Endpoints', 'default="'.$reportUrl.'"');
            $response->headers->set(
                'NEL',
                '{"report_to":"default","max_age":86400,"include_subdomains":true,"success_fraction":0.0,"failure_fraction":1.0}',
            );
            // Report-Only so missing SRI on Vite tags cannot break the site.
            $response->headers->set(
                'Integrity-Policy-Report-Only',
                'blocked-destinations=(script), endpoints=(default)',
            );
        }

        if ($this->shouldSendCsp($request, $response)) {
            $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy($request));
        }

        $this->annotateDocumentDictionary($request, $response);

        return $response;
    }

    protected function reportUrl(Request $request): string
    {
        $base = rtrim((string) config('app.url', $request->getSchemeAndHttpHost()), '/');

        return $base.'/report';
    }

    protected function annotateDocumentDictionary(Request $request, Response $response): void
    {
        if (! SiteFeatures::compressionDictionary()) {
            return;
        }
        $contentType = (string) $response->headers->get('Content-Type', '');
        if (! str_contains($contentType, 'text/html') && $contentType !== '') {
            return;
        }
        if (str_starts_with($request->path(), 'clients/')) {
            return;
        }

        $response->headers->set(
            'Available-Dictionary',
            ':'.CompressionDictionary::hash().':',
        );
    }

    /**
     * The CSP is skipped for local development (and whenever the Vite HMR
     * server is active via `public/hot`) because Vite injects cross-origin
     * module scripts and a websocket that a strict policy would block.
     * Production and the test suite still get it.
     */
    protected function shouldSendCsp(Request $request, Response $response): bool
    {
        if (app()->environment('local')) {
            return false;
        }

        if (str_starts_with($request->path(), 'clients/') && $request->path() !== 'clients') {
            return false;
        }

        if (! app()->runningUnitTests() && file_exists(public_path('hot'))) {
            return false;
        }

        $contentType = (string) $response->headers->get('Content-Type', '');

        return $contentType === '' || str_contains($contentType, 'text/html');
    }

    protected function contentSecurityPolicy(Request $request): string
    {
        $nonce = Vite::cspNonce();
        $scriptNonce = $nonce ? " 'nonce-{$nonce}'" : '';

        $scriptSrc = ["'self'".$scriptNonce];
        $connectSrc = ["'self'"];
        $imgSrc = ["'self'", 'data:'];
        $frameSrc = ["'self'"];

        if (config('site.analytics.google.enabled') && filled(config('site.analytics.google.id'))) {
            $scriptSrc[] = 'https://www.googletagmanager.com';
            $imgSrc[] = 'https://www.googletagmanager.com';
            $imgSrc[] = 'https://www.google-analytics.com';
            $connectSrc[] = 'https://www.google-analytics.com';
            $connectSrc[] = 'https://www.googletagmanager.com';
            $connectSrc[] = 'https://region1.google-analytics.com';
        }

        if (config('site.analytics.plausible.enabled') && filled(config('site.analytics.plausible.domain'))) {
            $scriptSrc[] = 'https://plausible.io';
            $connectSrc[] = 'https://plausible.io';
        }

        if (Turnstile::enabled()) {
            $scriptSrc[] = 'https://challenges.cloudflare.com';
            $frameSrc[] = 'https://challenges.cloudflare.com';
            $connectSrc[] = 'https://challenges.cloudflare.com';
        }

        $bookingHost = parse_url((string) config('site.booking.url'), PHP_URL_HOST);
        if (is_string($bookingHost) && $bookingHost !== '') {
            $frameSrc[] = 'https://'.$bookingHost;
            if (str_ends_with($bookingHost, 'calendly.com')) {
                $frameSrc[] = 'https://calendly.com';
                $frameSrc[] = 'https://www.calendly.com';
            }
            if (str_ends_with($bookingHost, 'cal.com')) {
                $frameSrc[] = 'https://cal.com';
                $frameSrc[] = 'https://app.cal.com';
            }
        }

        $reportUrl = $this->reportUrl($request);

        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "object-src 'none'",
            "font-src 'self'",
            "worker-src 'self'",
            "style-src 'self' 'unsafe-inline'",
            'img-src '.implode(' ', $imgSrc),
            'script-src '.implode(' ', $scriptSrc),
            'connect-src '.implode(' ', $connectSrc),
            'frame-src '.implode(' ', $frameSrc),
        ];

        if (SiteFeatures::reporting()) {
            $directives[] = 'report-uri '.$reportUrl;
            $directives[] = 'report-to default';
        }

        if ($request->secure()) {
            $directives[] = 'upgrade-insecure-requests';
        }

        return implode('; ', $directives);
    }
}
