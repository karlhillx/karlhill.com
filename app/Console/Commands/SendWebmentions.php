<?php

namespace App\Console\Commands;

use App\Support\BlogPostRepository;
use App\Support\WebmentionSender;
use Illuminate\Console\Command;

class SendWebmentions extends Command
{
    protected $signature = 'webmention:send
        {slug : Blog post slug whose outbound links should be mentioned}';

    protected $description = 'Send webmentions for external links in a post';

    public function handle(BlogPostRepository $posts): int
    {
        $slug = (string) $this->argument('slug');
        $post = $posts->find($slug);
        if ($post === null) {
            $this->error("No post found for slug [{$slug}].");

            return self::FAILURE;
        }

        $targets = WebmentionSender::externalTargets($post);
        if ($targets === []) {
            $this->info('No external targets.');

            return self::SUCCESS;
        }

        $sent = 0;
        foreach ($targets as $target) {
            $result = WebmentionSender::send($post->canonicalUrl(), $target);
            if ($result['ok'] ?? false) {
                $sent++;
                $this->line('Mentioned '.$target);
            } else {
                $this->comment('Skip '.$target.' ('.($result['error'] ?? $result['status'] ?? 'failed').')');
            }
        }

        $this->info("Sent {$sent} webmention(s).");

        return self::SUCCESS;
    }
}
