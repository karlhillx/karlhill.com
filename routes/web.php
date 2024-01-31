<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('portfolio', [WelcomeController::class, 'portfolio'])->name('portfolio');
Route::get('house', [WelcomeController::class, 'house'])->name('house');
Route::post('contact', [ContactController::class, 'store'])->name('contact.post');
