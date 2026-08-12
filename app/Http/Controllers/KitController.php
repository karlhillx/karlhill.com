<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class KitController extends Controller
{
    public function __invoke(): View
    {
        $social = collect(config('site.social'));

        return view('kit.index', [
            'meta' => PageMeta::kit(),
            'kit' => config('site.kit'),
            'person' => config('site.person'),
            'pdf' => config('site.footer.resume'),
            'bookingUrl' => config('site.booking.url'),
            'bookingLabel' => config('site.booking.label'),
            'linkedin' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'linkedin'),
            'github' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'github'),
        ]);
    }
}
