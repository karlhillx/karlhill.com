<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class ResumeController extends Controller
{
    public function __invoke(): View
    {
        return view('resume.index', [
            'meta' => PageMeta::resume(),
            'person' => config('site.person'),
            'experience' => config('site.experience'),
            'education' => config('site.education', []),
            'availability' => config('site.person.availability'),
            'pdf' => config('site.footer.resume'),
            'linkedin' => collect(config('site.social'))
                ->first(fn (array $link) => ($link['icon'] ?? '') === 'linkedin'),
        ]);
    }
}
