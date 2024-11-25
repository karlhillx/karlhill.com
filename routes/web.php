<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PortfolioController;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');
Route::get('/portfolio', [PortfolioController::class, 'index'])->name('portfolio');Route::get('house', [WelcomeController::class, 'house'])->name('house');
Route::post('contact', [ContactController::class, 'store'])->name('contact.post');
