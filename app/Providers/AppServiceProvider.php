<?php

namespace App\Providers;

use App\Support\BlogPostRepository;
use App\Support\PageMeta;
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
            $data = $view->getData();

            if (isset($data['meta']) && $data['meta'] instanceof PageMeta) {
                foreach ($data['meta']->toViewData() as $key => $value) {
                    $view->with($key, $value);
                }
            }
        });
    }
}
