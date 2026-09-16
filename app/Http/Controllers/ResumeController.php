<?php

namespace App\Http\Controllers;

use App\Support\OnDeviceAsk;
use App\Support\PageMeta;
use Illuminate\View\View;

class ResumeController extends Controller
{
    public function __invoke(): View
    {
        $social = collect(config('site.social'));
        $person = config('site.person');
        $resume = config('site.resume');
        $experience = config('site.experience');

        return view('resume.index', [
            'meta' => PageMeta::resume(),
            'person' => $person,
            'resume' => $resume,
            'experience' => $experience,
            'education' => config('site.education', []),
            'certifications' => config('site.certifications', []),
            'stack' => config('site.stack', []),
            'research' => config('site.research', []),
            'pdf' => config('site.footer.resume'),
            'linkedin' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'linkedin'),
            'github' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'github'),
            'askBrief' => OnDeviceAsk::resumeBrief($person, $resume, $experience),
            'askPrompts' => $resume['ask_prompts'] ?? [],
        ]);
    }
}
