<?php

namespace App\Http\Controllers;

use App\Support\PageMeta;
use Illuminate\View\View;

class OpenSourceController extends Controller
{
    public function __invoke(): View
    {
        return view('open-source.index', [
            'meta' => PageMeta::openSource(),
        ]);
    }
}
