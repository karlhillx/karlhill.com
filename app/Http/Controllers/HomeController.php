<?php

namespace App\Http\Controllers;

use App\Support\BlogPostRepository;
use App\Support\HomeStructuredData;
use App\Support\PageMeta;
use App\Support\ProjectCatalog;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected readonly BlogPostRepository $posts,
    ) {}

    public function __invoke(): View
    {
        $posts = $this->posts->all();
        $featuredPosts = collect(config('site.writing.featured'))
            ->map(fn (string $slug) => $this->posts->findOrFail($slug));

        return view('home.index', [
            'meta' => PageMeta::home(),
            'featuredPosts' => $featuredPosts,
            'featuredProjects' => ProjectCatalog::featured(3),
            'collections' => ProjectCatalog::collections(),
            'structuredData' => HomeStructuredData::build($posts->take(12)),
        ]);
    }
}
