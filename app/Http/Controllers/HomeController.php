<?php

namespace App\Http\Controllers;

use App\Support\BlogPostRepository;
use App\Support\GitHubRepository;
use App\Support\HomeStructuredData;
use App\Support\PageMeta;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(
        protected readonly BlogPostRepository $posts,
        protected readonly GitHubRepository $github,
    ) {}

    public function __invoke(): View
    {
        $posts = $this->posts->all();

        return view('home.index', [
            'meta' => PageMeta::home(),
            'latestPost' => $posts->first(),
            'githubRepos' => $this->github->topRepos(),
            'structuredData' => HomeStructuredData::build($posts->take(12)),
        ]);
    }
}
