<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        $rail = [
            ['id' => 'how-i-lead', 'label' => 'How I lead', 'href' => '#how-i-lead'],
            ['id' => 'experience', 'label' => 'Career arc', 'href' => '#experience'],
            ['id' => 'research', 'label' => 'Research', 'href' => '#research'],
        ];
        if (filled(config('site.about.beyond'))) {
            $rail[] = ['id' => 'beyond', 'label' => 'Beyond', 'href' => '#beyond'];
        }

        return view('about.index', [
            'meta' => PageMeta::about(),
            'sectionRail' => $rail,
        ]);
    }
}
