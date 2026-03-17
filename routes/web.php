<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/api/github/repos', [App\Http\Controllers\GitHubController::class, 'getTopRepos']);
