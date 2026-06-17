<?php

namespace App\Http\Controllers;

use App\Support\BlogPostRepository;
use App\Support\PageMeta;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function __construct(
        protected readonly BlogPostRepository $posts,
    ) {}

    public function index(): View|RedirectResponse
    {
        if ($tag = request()->query('tag')) {
            return redirect()->route('blog.tag', ['tag' => $tag], 301);
        }

        return $this->renderIndex(
            meta: PageMeta::blogIndex(),
            posts: $this->posts->all(),
            activeTag: null,
        );
    }

    public function tag(string $tag): View
    {
        $posts = $this->posts->all()
            ->filter(fn ($post) => in_array($tag, $post->tags, true))
            ->values();

        abort_if($posts->isEmpty(), 404);

        return $this->renderIndex(
            meta: PageMeta::blogTag($tag),
            posts: $posts,
            activeTag: $tag,
        );
    }

    public function show(string $slug): View
    {
        $post = $this->posts->findOrFail($slug);

        return view('blog.show', [
            'meta' => PageMeta::forPost($post),
            'post' => $post,
            'adjacentPosts' => $this->posts->adjacent($post),
            'relatedPosts' => $this->posts->all()
                ->reject(fn ($candidate) => $candidate->slug === $post->slug)
                ->filter(fn ($candidate) => count(array_intersect($candidate->tags, $post->tags)) > 0)
                ->take(2)
                ->values(),
        ]);
    }

    protected function renderIndex(PageMeta $meta, $posts, ?string $activeTag): View
    {
        $allTags = $this->posts->all()
            ->flatMap(fn ($post) => $post->tags)
            ->unique()
            ->sort()
            ->values();

        return view('blog.index', [
            'meta' => $meta,
            'posts' => $posts,
            'activeTag' => $activeTag,
            'allTags' => $allTags,
        ]);
    }
}
