<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class PrivacyController extends Controller
{
    public function __invoke(): View
    {
        $analyticsProvider = (string) config('site.analytics.provider', 'none');

        return view('privacy.index', [
            'meta' => PageMeta::privacy(),
            'privacy' => config('site.privacy'),
            'person' => config('site.person'),
            'analyticsProvider' => $analyticsProvider,
            'pushEnabled' => filled(config('site.push.public_key')),
        ]);
    }
}
