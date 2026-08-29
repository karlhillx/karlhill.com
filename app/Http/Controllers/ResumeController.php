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
            'sectionRail' => [
                ['id' => 'resume-summary', 'label' => 'Summary', 'href' => '#resume-summary'],
                ['id' => 'resume-experience', 'label' => 'Experience', 'href' => '#resume-experience'],
                ['id' => 'stack', 'label' => 'Expertise', 'href' => '#stack'],
                ['id' => 'credentials', 'label' => 'Credentials', 'href' => '#credentials'],
                ['id' => 'contact', 'label' => 'Contact', 'href' => '#contact'],
            ],
            'person' => config('site.person'),
            'resume' => config('site.resume'),
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
