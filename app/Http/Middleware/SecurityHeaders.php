<?php

namespace App\Http\Middleware;

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
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        if ($request->secure()) {
            $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }

        if ($this->shouldSendCsp($request, $response)) {
            $response->headers->set('Content-Security-Policy', $this->contentSecurityPolicy($request));
        }

        return $response;
    }

    /**
     * The CSP is skipped for local development because the Vite dev server
     * injects cross-origin module scripts and a websocket connection that a
     * strict policy would block. Production and the test suite still get it.
     */
    protected function shouldSendCsp(Request $request, Response $response): bool
    {
        if (app()->environment('local')) {
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

        $directives = [
            "default-src 'self'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            "object-src 'none'",
            "font-src 'self'",
            // Inline style attributes (hero animation delays, view-transition
            // names, gradient orbs) require 'unsafe-inline' for styles.
            "style-src 'self' 'unsafe-inline'",
            'img-src '.implode(' ', $imgSrc),
            'script-src '.implode(' ', $scriptSrc),
            'connect-src '.implode(' ', $connectSrc),
        ];

        if ($request->secure()) {
            $directives[] = 'upgrade-insecure-requests';
        }

        return implode('; ', $directives);
    }
}
