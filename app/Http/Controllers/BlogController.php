<?php

namespace App\Http\Controllers;

use App\Support\BlogPostRepository;
use App\Support\PageMeta;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        protected readonly BlogPostRepository $posts,
    ) {}

    public function index(): View
    {
        return view('blog.index', [
            'meta' => PageMeta::blogIndex(),
            'posts' => $this->posts->all(),
        ]);
    }

    public function show(string $slug): View
    {
        $post = $this->posts->findOrFail($slug);

        return view('blog.show', [
            'meta' => PageMeta::forPost($post),
            'post' => $post,
        ]);
    }
}
