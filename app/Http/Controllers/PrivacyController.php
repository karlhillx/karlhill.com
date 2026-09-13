<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class PrivacyController extends Controller
{
    public function __invoke(): View
    {
        $bookingUrl = (string) config('site.booking.url', '');
        $bookingHost = parse_url($bookingUrl, PHP_URL_HOST);
        $bookingProvider = match (true) {
            is_string($bookingHost) && str_ends_with(strtolower($bookingHost), 'cal.com') => 'Cal.com',
            is_string($bookingHost) && str_ends_with(strtolower($bookingHost), 'calendly.com') => 'Calendly',
            filled($bookingUrl) => 'the scheduling provider linked from this site',
            default => null,
        };

        $analyticsProvider = (string) config('site.analytics.provider', 'none');

        return view('privacy.index', [
            'meta' => PageMeta::privacy(),
            'privacy' => config('site.privacy'),
            'person' => config('site.person'),
            'bookingProvider' => $bookingProvider,
            'analyticsProvider' => $analyticsProvider,
            'turnstileEnabled' => filled(config('site.turnstile.site_key'))
                && filled(config('site.turnstile.secret_key')),
            'pushEnabled' => filled(config('site.push.public_key')),
        ]);
    }
}
