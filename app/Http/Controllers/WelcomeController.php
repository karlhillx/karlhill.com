<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class WelcomeController extends Controller
{
    /**
     * Display the welcome page
     */
    public function index(): View
    {
        return view('welcome');
    }
}
