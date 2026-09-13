<?php

namespace App\Http\Middleware;

use App\Support\PreloadLinks;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Flush HTTP 103 Early Hints from the existing Link preload set.
 * Enabled when EARLY_HINTS=true and the runtime can emit interim responses
 * (FrankenPHP), or when EARLY_HINTS_FORCE=true behind a 103-capable proxy.
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

        if (! $this->runtimeSupportsInterimResponses()) {
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

    /**
     * php artisan serve / php-fpm cannot flush a real 103; FrankenPHP can.
     */
    protected function runtimeSupportsInterimResponses(): bool
    {
        if (isset($_SERVER['FRANKENPHP_VERSION']) || isset($_SERVER['frankenphp_version'])) {
            return true;
        }

        return filter_var(env('EARLY_HINTS_FORCE', false), FILTER_VALIDATE_BOOLEAN);
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
