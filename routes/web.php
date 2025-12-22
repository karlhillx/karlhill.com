<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PortfolioController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\GitHubController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
// Contact Routes
Route::post('/contact', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.post');

// Newsletter Routes
Route::prefix('newsletter')->group(function () {
    Route::post('/', [NewsletterSubscriptionController::class, 'store'])->name('newsletter.post');
});

// Health Check
Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});

Route::get('/api/github/languages', [GitHubController::class, 'getLanguageStats']);

