<?php

namespace App\Http\Controllers;

use App\Support\GitHubRepository;
use App\Support\PageMeta;
use Illuminate\View\View;

class WorkController extends Controller
{
    public function __construct(
        protected readonly GitHubRepository $github,
    ) {}

    public function __invoke(): View
    {
        return view('work.index', [
            'meta' => PageMeta::work(),
            'githubRepos' => $this->github->topRepos(),
            'sectionRail' => [
                ['id' => 'work', 'label' => 'Projects', 'href' => '#work'],
                ['id' => 'open-source', 'label' => 'Open Source', 'href' => '#open-source'],
            ],
        ]);
    }
}
