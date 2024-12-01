<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PortfolioController extends Controller
{
    /**
     * Display the portfolio page
     */
    public function index(): View
    {
        return view('portfolio');
    }
}
