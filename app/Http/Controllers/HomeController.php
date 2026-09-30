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

        return view('home.index', [
            'meta' => PageMeta::home(),
            'latestPosts' => $posts->take(3),
            'featuredProjects' => ProjectCatalog::featured(6),
            'collections' => ProjectCatalog::collections(),
            'structuredData' => HomeStructuredData::build($posts->take(12)),
        ]);
    }
}
