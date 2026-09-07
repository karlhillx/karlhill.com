<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

/**
 * Legacy URL — delivery OS now lives on About (#delivery).
 */
class LeadController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->to('/about#delivery', 301);
    }
}
