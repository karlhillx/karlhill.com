<?php

namespace App\Http\Controllers;

use App\Support\GitHubRepository;
use App\Support\PageMeta;
use App\Support\ProjectCatalog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
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
            projects: ProjectCatalog::listed(),
            activeTag: null,
            supporting: ProjectCatalog::supporting(),
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
            supporting: collect(),
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

    protected function renderIndex(PageMeta $meta, Collection $projects, ?string $activeTag, Collection $supporting): View
    {
        return view('work.index', [
            'meta' => $meta,
            'projects' => $projects,
            'supporting' => $supporting,
            'activeTag' => $activeTag,
            'allTags' => ProjectCatalog::allTags(),
            'tagCounts' => ProjectCatalog::tagCounts(),
            'sectors' => ProjectCatalog::sectors(),
            'sectorCounts' => ProjectCatalog::sectorCounts(),
            'githubRepos' => $this->github->topRepos(),
        ]);
    }
}
