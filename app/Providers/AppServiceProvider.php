<?php

namespace App\Providers;

use App\Support\BlogPostRepository;
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
    }

    public function boot(): void
    {
        //
    }
}
