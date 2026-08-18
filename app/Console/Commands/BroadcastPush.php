<?php

namespace App\Console\Commands;

use App\Support\BlogPostRepository;
use App\Support\PushSender;
use App\Support\PushSubscriptionStore;
use Illuminate\Console\Command;

class BroadcastPush extends Command
{
    protected $signature = 'push:broadcast
        {slug : Blog post slug to notify subscribers about}';

    protected $description = 'Send a Web Push notification for a published post';

    public function handle(BlogPostRepository $posts, PushSender $sender): int
    {
        if (! PushSubscriptionStore::enabled()) {
            $this->warn('Push is disabled — set VAPID_PUBLIC_KEY and VAPID_PRIVATE_KEY.');

            return self::SUCCESS;
        }

        $slug = (string) $this->argument('slug');
        $post = $posts->find($slug);
        if ($post === null) {
            $this->error("No post found for slug [{$slug}].");

            return self::FAILURE;
        }

        $payload = [
            'title' => $post->title,
            'body' => $post->excerpt,
            'url' => $post->canonicalUrl(),
        ];

        $sent = 0;
        foreach (PushSubscriptionStore::all() as $subscription) {
            if ($sender->send($subscription, $payload)) {
                $sent++;
            }
        }

        $this->info("Push sent to {$sent} subscriber(s) for /blog/{$slug}.");

        return self::SUCCESS;
    }
}
