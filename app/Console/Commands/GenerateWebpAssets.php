<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class GenerateWebpAssets extends Command
{
    protected $signature = 'assets:webp';

    protected $description = 'Generate WebP variants for raster images under public/img';

    public function handle(): int
    {
        $script = base_path('scripts/generate-webp.py');
        if (! is_file($script)) {
            $this->error('Missing scripts/generate-webp.py');

            return self::FAILURE;
        }

        $process = new Process(['python3', $script], base_path());
        $process->setTimeout(300);
        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            $this->error(trim($process->getErrorOutput()) ?: 'WebP generation failed.');

            return self::FAILURE;
        }

        $this->info('WebP assets generated.');

        return self::SUCCESS;
    }
}
