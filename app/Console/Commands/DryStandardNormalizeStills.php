<?php

namespace App\Console\Commands;

use DryStandard\StillPipeline;
use DryStandard\Workspace;
use Illuminate\Console\Command;

class DryStandardNormalizeStills extends Command
{
    protected $signature = 'dry-standard:normalize-stills
        {slug? : Review slug whose media/reviews/{slug}.jpg should be normalized}
        {--all : Normalize every JPEG under media/reviews}
        {--white : Pure white background (default)}
        {--paper : Site paper tone instead of white}
        {--from= : Optional source image path (requires slug)}';

    protected $description = 'Crop bottles to a consistent size and place them on a white (or paper) 3:4 still.';

    public function handle(): int
    {
        $workspace = Workspace::default();
        $paths = $workspace->paths;
        $pipeline = new StillPipeline($paths);
        $background = $this->option('paper') ? 'paper' : 'white';
        $directory = $paths->path('media/reviews');
        $jobs = [];

        $from = $this->option('from');
        $slug = $this->argument('slug');

        if (is_string($from) && $from !== '') {
            if (! is_string($slug) || $slug === '') {
                $this->error('Pass a slug when using --from.');

                return self::FAILURE;
            }
            if (! is_file($from)) {
                $this->error("Source not found: {$from}");

                return self::FAILURE;
            }
            $jobs[] = [$slug, $from, $directory.DIRECTORY_SEPARATOR.$slug.'.jpg'];
        } elseif ($this->option('all')) {
            foreach (glob($directory.DIRECTORY_SEPARATOR.'*.jpg') ?: [] as $jpeg) {
                $name = basename($jpeg, '.jpg');
                if (str_starts_with($name, '__')) {
                    continue;
                }
                $jobs[] = [$name, $jpeg, $jpeg];
            }
        } elseif (is_string($slug) && $slug !== '') {
            $jpeg = $directory.DIRECTORY_SEPARATOR.$slug.'.jpg';
            if (! is_file($jpeg)) {
                $this->error("Still not found: media/reviews/{$slug}.jpg");

                return self::FAILURE;
            }
            $jobs[] = [$slug, $jpeg, $jpeg];
        } else {
            $this->error('Pass a slug, or --all.');

            return self::FAILURE;
        }

        $ok = 0;
        $failed = 0;

        foreach ($jobs as [$name, $source, $destination]) {
            $tmp = $destination.'.normalize-tmp.jpg';
            if (! $pipeline->normalize($source, $tmp, $background)) {
                $this->error("FAIL {$name}");
                $failed++;
                @unlink($tmp);

                continue;
            }

            if (! rename($tmp, $destination)) {
                @unlink($tmp);
                $this->error("FAIL {$name} (write)");
                $failed++;

                continue;
            }

            $pipeline->ensureDerivatives($destination);
            $this->line("OK {$name}");
            $ok++;
        }

        $this->line("Normalized {$ok}".($failed > 0 ? ", failed {$failed}" : ''));

        return $failed === 0 ? self::SUCCESS : self::FAILURE;
    }
}
