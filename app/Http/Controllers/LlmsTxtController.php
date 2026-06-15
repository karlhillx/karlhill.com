<?php

namespace App\Http\Controllers;

use App\Support\LlmsTxtBuilder;
use Illuminate\Http\Response;

class LlmsTxtController extends Controller
{
    public function __construct(
        protected readonly LlmsTxtBuilder $builder,
    ) {}

    public function __invoke(): Response
    {
        return response($this->builder->build(), 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
