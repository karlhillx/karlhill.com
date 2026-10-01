<?php

namespace App\Http\Controllers;

use App\Support\CaseStudyPage;
use App\Support\PageMeta;
use App\Support\ProjectCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkController extends Controller
{
    public function index(): View|RedirectResponse
    {
        if (request()->query('tag')) {
            return redirect()->route('work', status: 301);
        }

        return view('work.index', [
            'meta' => PageMeta::work(),
            'collections' => ProjectCatalog::collections(),
            'earlierProjects' => ProjectCatalog::earlier(),
        ]);
    }

    /**
     * Legacy stack/sector filter URLs. Chips are labels now; send bookmarks to /work.
     */
    public function tag(string $tag): RedirectResponse
    {
        return redirect()->route('work', status: 301);
    }

    public function show(string $slug): View
    {
        $project = ProjectCatalog::findOrFail($slug);
        $adjacent = ProjectCatalog::adjacent($slug);
        $related = ProjectCatalog::related($project);

        return view('work.show', [
            'meta' => PageMeta::forProject($project),
            'project' => $project,
            'caseStudy' => $project['case_study'],
            'previousProject' => $adjacent['previous'],
            'nextProject' => $adjacent['next'],
            'relatedProjects' => $related,
            'page' => new CaseStudyPage($project, $related->isNotEmpty()),
            'collection' => config('site.work.collections.'.$project['portfolio_group']),
        ]);
    }
}
