<?php

namespace App\Http\Middleware;

use App\Support\PreloadLinks;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Flush HTTP 103 Early Hints from the existing Link preload set.
 * Enabled when EARLY_HINTS=true (FrankenPHP / HTTP/2+ proxies).
 */
class EarlyHints
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->shouldSend($request)) {
            $this->flush();
        }

        return $next($request);
    }

    protected function shouldSend(Request $request): bool
    {
        if (! filter_var(config('site.early_hints'), FILTER_VALIDATE_BOOLEAN)) {
            return false;
        }

        if (app()->runningUnitTests()) {
            return false;
        }

        if ($request->method() !== 'GET') {
            return false;
        }

        if (str_starts_with($request->path(), 'clients/')) {
            return false;
        }

        return ! headers_sent();
    }

    protected function flush(): void
    {
        $links = PreloadLinks::all();
        if ($links === []) {
            return;
        }

        foreach ($links as $link) {
            header('Link: '.$link, false);
        }

        http_response_code(103);
        flush();
    }
}
