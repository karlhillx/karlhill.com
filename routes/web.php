<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LlmsTxtController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\WorkController;
use Illuminate\Support\Facades\Route;

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

Route::get('/feed.xml', FeedController::class)->name('feed');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/llms.txt', LlmsTxtController::class)->name('llms');
