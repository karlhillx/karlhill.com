<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class AboutController extends Controller
{
    public function __invoke(): View
    {
        return view('about.index', [
            'meta' => PageMeta::about(),
            'sectionRail' => [
                ['id' => 'experience', 'label' => 'Experience', 'href' => '#experience'],
                ['id' => 'research', 'label' => 'Research', 'href' => '#research'],
                ['id' => 'stack', 'label' => 'Stack', 'href' => '#stack'],
                ['id' => 'credentials', 'label' => 'Credentials', 'href' => '#credentials'],
            ],
        ]);
    }
}
