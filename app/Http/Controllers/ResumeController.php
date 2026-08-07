<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class ResumeController extends Controller
{
    public function __invoke(): View
    {
        $social = collect(config('site.social'));

        return view('resume.index', [
            'meta' => PageMeta::resume(),
            'person' => config('site.person'),
            'experience' => config('site.experience'),
            'education' => config('site.education', []),
            'certifications' => config('site.certifications', []),
            'stack' => config('site.stack', []),
            'pdf' => config('site.footer.resume'),
            'linkedin' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'linkedin'),
            'github' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'github'),
        ]);
    }
}
