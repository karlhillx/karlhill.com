<?php

namespace App\Support;

use Illuminate\Support\Facades\Http;

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

        $response = Http::asForm()
            ->timeout(5)
            ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', array_filter([
                'secret' => config('site.turnstile.secret_key'),
                'response' => $token,
                'remoteip' => $remoteIp,
            ]));

        return $response->successful() && ($response->json('success') === true);
    }
}
