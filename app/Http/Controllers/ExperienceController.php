<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class ExperienceController extends Controller
{
    public function __invoke(): View
    {
        return view('experience.index', [
            'meta' => PageMeta::experience(),
        ]);
    }
}
