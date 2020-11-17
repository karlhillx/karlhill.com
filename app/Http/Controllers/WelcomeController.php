<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class WelcomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     */
    public function index()
    {
        return view('welcome');
    }

    /**
     * @return RedirectResponse
     */
    public function portfolio(): RedirectResponse
    {
        toastr()->error('Sorry, this feature has not been implemented yet.');

        return back();
    }
}
