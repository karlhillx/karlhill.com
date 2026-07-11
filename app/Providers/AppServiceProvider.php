<?php

namespace App\Providers;

use App\Support\BlogPostRepository;
use App\Support\CommandIndex;
use App\Support\GitHubRepository;
use App\Support\PageMeta;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(BlogPostRepository::class, function () {
            return new BlogPostRepository(
                directory: resource_path('posts'),
            );
        });

        $this->app->singleton(GitHubRepository::class);
    }

    public function boot(): void
    {
        // Generate a per-request CSP nonce and let Vite stamp it onto the
        // <script>/<style> tags it injects. The SecurityHeaders middleware
        // reads the same nonce back via Vite::cspNonce() to build the header.
        Vite::useCspNonce();

        View::composer('layouts.site', function (\Illuminate\View\View $view): void {
            $data = $view->getData();

            if (isset($data['meta']) && $data['meta'] instanceof PageMeta) {
                foreach ($data['meta']->toViewData() as $key => $value) {
                    $view->with($key, $value);
                }
            }

            $view->with('commandIndex', CommandIndex::build());
        });
    }
}
