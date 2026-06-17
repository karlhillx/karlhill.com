<?php

namespace App\Http\Controllers;

use App\Support\BlogPostRepository;
use App\Support\HomeStructuredData;
use App\Support\PageMeta;
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
            'latestPost' => $posts->first(),
            'structuredData' => HomeStructuredData::build($posts->take(12)),
            'sectionRail' => [
                ['id' => 'writing', 'label' => 'Writing', 'href' => '#writing'],
                ['id' => 'why', 'label' => 'Why Me', 'href' => '#why'],
                ['id' => 'work', 'label' => 'Work', 'href' => '#work'],
                ['id' => 'contact', 'label' => 'Contact', 'href' => '#contact'],
            ],
        ]);
    }
}
