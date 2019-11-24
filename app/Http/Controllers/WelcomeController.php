<?php

namespace App\Http\Controllers;

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
}
