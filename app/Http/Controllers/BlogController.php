<?php

namespace App\Http\Controllers;

use App\Support\BlogPostRepository;

class BlogController extends Controller
{
    public function __construct(
        protected readonly BlogPostRepository $posts,
    ) {}

    public function index()
    {
        return view('blog.index', [
            'posts' => $this->posts->all(),
        ]);
    }

    public function show(string $slug)
    {
        return view('blog.show', [
            'post' => $this->posts->findOrFail($slug),
        ]);
    }
}
