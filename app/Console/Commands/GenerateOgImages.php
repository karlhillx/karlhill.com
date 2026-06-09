<?php

namespace App\Console\Commands;

use App\Support\BlogPostRepository;
use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class GenerateOgImages extends Command
{
    protected $signature = 'og:generate
        {slug? : Generate a blog post card only}
        {--home : Regenerate the homepage OG card only}';

    protected $description = 'Generate 1200×630 Open Graph images via scripts/generate-og-images.py';

    public function handle(BlogPostRepository $posts): int
    {
        $script = base_path('scripts/generate-og-images.py');
        if (! is_file($script)) {
            $this->error('Missing scripts/generate-og-images.py');

            return self::FAILURE;
        }

        $slug = $this->argument('slug');
        $homeOnly = (bool) $this->option('home');

        if ($slug !== null) {
            return $this->runBlogPost($script, $posts, (string) $slug);
        }

        if (! $homeOnly) {
            $this->runProcess(['python3', $script]);
            foreach ($posts->all() as $post) {
                $this->runBlogPost($script, $posts, $post->slug, quiet: true);
            }
            $this->info('Generated homepage and blog OG images.');

            return self::SUCCESS;
        }

        $this->runProcess(['python3', $script]);
        $this->info('Generated homepage OG image.');

        return self::SUCCESS;
    }

    protected function runBlogPost(string $script, BlogPostRepository $posts, string $slug, bool $quiet = false): int
    {
        $post = $posts->find($slug);
        if ($post === null) {
            $this->error("Unknown post slug: {$slug}");

            return self::FAILURE;
        }

        if ($post->heroImage === null || $post->heroImage === '') {
            $this->warn("Post {$slug} has no hero_image; skipped.");

            return self::SUCCESS;
        }

        $this->runProcess([
            'python3',
            $script,
            '--blog',
            $post->slug,
            $post->title,
            $post->heroImage,
        ]);

        if (! $quiet) {
            $this->info("Generated OG image for {$slug}.");
        }

        return self::SUCCESS;
    }

    /**
     * @param  array<int, string>  $command
     */
    protected function runProcess(array $command): void
    {
        $process = new Process($command, base_path());
        $process->setTimeout(120);
        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            throw new \RuntimeException(trim($process->getErrorOutput()) ?: 'OG image generation failed.');
        }
    }
}
