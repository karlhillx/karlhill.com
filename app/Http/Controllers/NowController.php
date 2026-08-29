<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class NowController extends Controller
{
    public function __invoke(): View
    {
        $now = config('site.now');
        $rail = [
            ['id' => 'focus', 'label' => 'Focus', 'href' => '#focus'],
        ];
        if (! empty($now['recruiters'])) {
            $rail[] = ['id' => 'recruiters', 'label' => 'Hiring', 'href' => '#recruiters'];
        }
        if (filled(config('site.booking.embed_src'))) {
            $rail[] = ['id' => 'book', 'label' => 'Book', 'href' => '#book'];
        }
        $rail[] = ['id' => 'contact', 'label' => 'Contact', 'href' => '#contact'];

        return view('now.index', [
            'meta' => PageMeta::now(),
            'now' => $now,
            'sectionRail' => $rail,
        ]);
    }
}
