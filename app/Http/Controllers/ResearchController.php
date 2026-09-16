<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class ResearchController extends Controller
{
    public function __invoke(): View
    {
        return view('research.show', [
            'meta' => PageMeta::research(),
            'research' => config('site.research'),
        ]);
    }
}
