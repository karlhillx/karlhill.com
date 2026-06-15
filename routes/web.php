<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\FeedController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LlmsTxtController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])
    ->where('slug', '[a-z0-9-]+')
    ->name('blog.show');

Route::get('/feed.xml', FeedController::class)->name('feed');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/llms.txt', LlmsTxtController::class)->name('llms');
