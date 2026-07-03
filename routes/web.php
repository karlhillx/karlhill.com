<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LlmsTxtController;
use App\Http\Controllers\OgImageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

// HTML pages: the site is effectively static (flat-file blog, cached GitHub
// data) so a short public TTL plus an ETag lets browsers and any future CDN
// revalidate cheaply (304s) without serving stale content.
Route::middleware('cache.headers:public;max_age=300;etag')->group(function (): void {
    Route::get('/', HomeController::class)->name('home');
    Route::get('/work/tag/{tag}', [WorkController::class, 'tag'])
        ->where('tag', '[a-z0-9-]+')
        ->name('work.tag');
    Route::get('/work', [WorkController::class, 'index'])->name('work');
    Route::get('/work/{slug}', [WorkController::class, 'show'])
        ->where('slug', '[a-z0-9-]+')
        ->name('work.show');
    Route::get('/about', AboutController::class)->name('about');

    Route::get('/blog/tag/{tag}', [BlogController::class, 'tag'])
        ->where('tag', '[a-z0-9-]+')
        ->name('blog.tag');
    Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
    Route::get('/blog/{slug}', [BlogController::class, 'show'])
        ->where('slug', '[a-z0-9-]+')
        ->name('blog.show');
});

// Contact form: not cacheable (POST), rate-limited to blunt spam/abuse.
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')
    ->name('contact.store');

// A fresh CSRF token for the contact form. Because the home page is publicly
// cacheable, its embedded token could be stale (or absent, if a shared CDN
// serves a visitor who never hit the origin). This endpoint is never cached,
// so hitting it both starts a session and returns a token that matches it.
Route::get('/csrf-token', fn () => response()
    ->json(['token' => csrf_token()])
    ->header('Cache-Control', 'no-store, private'))
    ->name('csrf-token');

// Machine-readable feeds change less often — cache them for an hour.
Route::middleware('cache.headers:public;max_age=3600;etag')->group(function (): void {
    Route::get('/feed.xml', FeedController::class)->name('feed');
    Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
    Route::get('/llms.txt', LlmsTxtController::class)->name('llms');
});

// Dynamically-generated per-post Open Graph cards (1200×630 PNG), cached hard.
Route::middleware('cache.headers:public;max_age=86400;etag')->group(function (): void {
    Route::get('/og/blog/{slug}.png', [OgImageController::class, 'blog'])
        ->where('slug', '[a-z0-9-]+')
        ->name('og.blog');
});
