<?php

namespace App\Http\Controllers;

use App\Support\LlmsTxtBuilder;
use Illuminate\Http\Response;

class LlmsTxtController extends Controller
{
    public function __construct(
        protected readonly LlmsTxtBuilder $builder,
    ) {}

    public function index(): Response
    {
        return $this->plain($this->builder->build());
    }

    public function full(): Response
    {
        return $this->plain($this->builder->buildFull());
    }

    protected function plain(string $body): Response
    {
        return response($body, 200, [
            'Content-Type' => 'text/plain; charset=utf-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
