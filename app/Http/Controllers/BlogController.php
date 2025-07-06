<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class BlogController extends Controller
{
    /**
     * Display the blog page
     */
    public function index(): View
    {
        return view('blog');
    }
}
