<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

/**
 * Legacy URL — delivery packet now lives at /delivery.
 */
class LeadController extends Controller
{
    public function __invoke(): RedirectResponse
    {
        return redirect()->to('/delivery', 301);
    }
}
