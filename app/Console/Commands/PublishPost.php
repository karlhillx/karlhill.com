<?php

namespace App\Console\Commands;

use App\Support\BlogPostRepository;
use Illuminate\Console\Command;

class PublishPost extends Command
{
    protected $signature = 'post:publish
        {slug : Blog post slug (e.g. release-governance)}
        {--syndicate : Also cross-post to dev.to after assets are ready}
        {--skip-assets : Skip WebP/AVIF/LQIP generation}
        {--skip-og : Skip Open Graph card generation}
        {--skip-webmentions : Skip outbound webmentions}
        {--skip-push : Skip Web Push broadcast}';

    protected $description = 'Prepare a blog post for publish: assets, OG card, optional syndication';

    public function handle(BlogPostRepository $posts): int
    {
        $slug = (string) $this->argument('slug');
        $post = $posts->find($slug);

        if ($post === null) {
            $this->error("No post found for slug [{$slug}].");

            return self::FAILURE;
        }

        $this->info("Publishing [{$post->title}] (/blog/{$slug})");

        if (! $this->option('skip-assets')) {
            $this->components->task('Generating WebP / AVIF / LQIP variants', function () {
                return $this->call('assets:webp') === self::SUCCESS;
            });
        }

        if (! $this->option('skip-og')) {
            $this->components->task('Generating Open Graph card', function () use ($slug) {
                return $this->call('og:generate', ['slug' => $slug]) === self::SUCCESS;
            });
        }

        if ($this->option('syndicate')) {
            $this->components->task('Syndicating to dev.to', function () use ($slug) {
                return $this->call('post:syndicate', ['slug' => $slug]) === self::SUCCESS;
            });
        }

        if (! $this->option('skip-webmentions')) {
            $this->components->task('Sending webmentions', function () use ($slug) {
                return $this->call('webmention:send', ['slug' => $slug]) === self::SUCCESS;
            });
        }

        if (! $this->option('skip-push')) {
            $this->components->task('Notifying push subscribers', function () use ($slug) {
                return $this->call('push:broadcast', ['slug' => $slug]) === self::SUCCESS;
            });
        }

        $this->newLine();
        $this->line('Live at: '.rtrim((string) config('app.url'), '/').'/blog/'.$slug);
        $this->comment('Tip: commit the post markdown + generated img/og assets together.');

        return self::SUCCESS;
    }
}
