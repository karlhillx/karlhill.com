<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class NowController extends Controller
{
    public function __invoke(): View
    {
        return view('now.index', [
            'meta' => PageMeta::now(),
            'now' => config('site.now'),
        ]);
    }
}
