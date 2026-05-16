<?php

namespace App\Providers;

use App\Support\BlogPostRepository;
use Illuminate\Support\Facades\View;
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
        View::composer('layouts.site', function (\Illuminate\View\View $view): void {
            $active = $view->getData()['activeNav'] ?? null;
            $view->with('navLinkClass', static function (string $key) use ($active): string {
                return 'transition-colors duration-200 '.($active === $key ? 'text-orange-500' : 'hover:text-orange-500');
            });
        });
    }
}
