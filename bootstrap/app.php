<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Return JSON for all JSON requests
        $exceptions->render(function (\Throwable $e, $request) {
            if ($request->expectsJson() || $request->wantsJson() || $request->header('Accept') === 'application/json') {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                
                return response()->json([
                    'message' => $e->getMessage() ?? 'An error occurred.',
                    'error' => config('app.debug') ? [
                        'message' => $e->getMessage(),
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                    ] : null,
                ], $status);
            }
            
            return null; // Let Laravel handle non-JSON requests normally
        });
    })->create();
