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
        $tag = request()->query('tag');
        $posts = $this->posts->all();

        if (is_string($tag) && $tag !== '') {
            $posts = $posts->filter(fn ($post) => in_array($tag, $post->tags, true))->values();
        }

        $allTags = $this->posts->all()
            ->flatMap(fn ($post) => $post->tags)
            ->unique()
            ->sort()
            ->values();

        return view('blog.index', [
            'meta' => PageMeta::blogIndex(),
            'posts' => $posts,
            'activeTag' => is_string($tag) ? $tag : null,
            'allTags' => $allTags,
        ]);
    }

    public function show(string $slug): View
    {
        $post = $this->posts->findOrFail($slug);

        return view('blog.show', [
            'meta' => PageMeta::forPost($post),
            'post' => $post,
            'relatedPosts' => $this->posts->all()
                ->reject(fn ($candidate) => $candidate->slug === $post->slug)
                ->filter(fn ($candidate) => count(array_intersect($candidate->tags, $post->tags)) > 0)
                ->take(2)
                ->values(),
        ]);
    }
}
