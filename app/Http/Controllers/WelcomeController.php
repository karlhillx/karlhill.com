<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('welcome');
    }

    /**
     * @return View
     */
    public function portfolio(): View
    {
        return view('portfolio');
    }

    /**
     * @return View
     */
    public function house(): View
    {
        return view('house');
    }
}
