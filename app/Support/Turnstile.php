<?php

namespace App\Support;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

final class Turnstile
{
    public static function enabled(): bool
    {
        return filled(config('site.turnstile.site_key'))
            && filled(config('site.turnstile.secret_key'));
    }

    public static function verify(?string $token, ?string $remoteIp = null): bool
    {
        if (! self::enabled()) {
            return true;
        }

        if (! is_string($token) || $token === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array_filter([
                    'secret' => config('site.turnstile.secret_key'),
                    'response' => $token,
                    'remoteip' => $remoteIp,
                ]));
        } catch (ConnectionException $e) {
            // Cloudflare blip / timeout — do not 500 the form or block a recruiter.
            Log::warning('Turnstile siteverify unreachable', ['message' => $e->getMessage()]);

            return true;
        }

        if (! $response->successful()) {
            Log::warning('Turnstile siteverify HTTP error', ['status' => $response->status()]);

            return true;
        }

        return $response->json('success') === true;
    }
}
