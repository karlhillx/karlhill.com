<?php

namespace App\Http\Controllers;

use App\Support\GitHubRepository;
use App\Support\PageMeta;
use App\Support\ProjectCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkController extends Controller
{
    public function __construct(
        protected readonly GitHubRepository $github,
    ) {}

    public function index(): View|RedirectResponse
    {
        if ($tag = request()->query('tag')) {
            return redirect()->route('work.tag', ['tag' => ProjectCatalog::tagSlug($tag)], 301);
        }

        return $this->renderIndex(
            meta: PageMeta::work(),
            projects: ProjectCatalog::all(),
            activeTag: null,
        );
    }

    public function tag(string $tag): View
    {
        $label = ProjectCatalog::tagFromSlug($tag);
        abort_if($label === null, 404);

        $projects = ProjectCatalog::filteredByTag($label);
        abort_if($projects->isEmpty(), 404);

        return $this->renderIndex(
            meta: PageMeta::workTag($label),
            projects: $projects,
            activeTag: $label,
        );
    }

    public function show(string $slug): View
    {
        $project = ProjectCatalog::findOrFail($slug);
        $adjacent = ProjectCatalog::adjacent($slug);

        return view('work.show', [
            'meta' => PageMeta::forProject($project),
            'project' => $project,
            'caseStudy' => $project['case_study'],
            'previousProject' => $adjacent['previous'],
            'nextProject' => $adjacent['next'],
            'relatedProjects' => ProjectCatalog::related($project),
        ]);
    }

    protected function renderIndex(PageMeta $meta, $projects, ?string $activeTag): View
    {
        return view('work.index', [
            'meta' => $meta,
            'projects' => $projects,
            'activeTag' => $activeTag,
            'allTags' => ProjectCatalog::allTags(),
            'githubRepos' => $this->github->topRepos(),
            'sectionRail' => [
                ['id' => 'work', 'label' => 'Projects', 'href' => '#work'],
                ['id' => 'open-source', 'label' => 'Open Source', 'href' => '#open-source'],
            ],
        ]);
    }
}
