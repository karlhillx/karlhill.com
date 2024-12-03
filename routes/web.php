<?php

use App\Http\Controllers\NewsletterSubscriptionController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\PortfolioController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/blog', [PortfolioController::class, 'blog'])->name('blog');

// Contact & Newsletter Routes
Route::prefix('contact')->group(function () {
    Route::post('/', [NewsletterSubscriptionController::class, 'store'])->name('contact.post');
    Route::get('/success', [NewsletterSubscriptionController::class, 'success'])->name('contact.success');
});

// Health Check
Route::get('/health', function () {
    return response()->json(['status' => 'healthy']);
});
