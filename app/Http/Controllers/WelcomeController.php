<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
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
     * @return Application|Factory|View
     */
    public function portfolio(): View
    {
        return view('portfolio');
    }

    /**
     * @return Application|Factory|View
     */
    public function house(): View
    {
        return view('house');
    }
}
