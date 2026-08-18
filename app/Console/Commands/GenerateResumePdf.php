<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class GenerateResumePdf extends Command
{
    protected $signature = 'resume:pdf';

    protected $description = 'Generate public/files/Karl-Hill-Resume.pdf via Puppeteer (classic 2-page layout)';

    public function handle(): int
    {
        $script = base_path('scripts/generate-resume-pdf.mjs');
        if (! is_file($script)) {
            $this->error('Missing scripts/generate-resume-pdf.mjs');

            return self::FAILURE;
        }

        $social = collect(config('site.social'));
        $html = view('resume.pdf', [
            'person' => config('site.person'),
            'resume' => config('site.resume'),
            'experience' => config('site.experience'),
            'education' => config('site.education', []),
            'certifications' => config('site.certifications', []),
            'stack' => config('site.stack', []),
            'linkedin' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'linkedin'),
            'github' => $social->first(fn (array $link) => ($link['icon'] ?? '') === 'github'),
        ])->render();

        $htmlPath = storage_path('app/resume-print.html');
        $pdfPath = public_path('files/Karl-Hill-Resume.pdf');
        $legacyPath = public_path('files/karlhill-resume.pdf');

        File::ensureDirectoryExists(dirname($htmlPath));
        File::ensureDirectoryExists(dirname($pdfPath));
        File::put($htmlPath, $html);

        if (is_file($legacyPath)) {
            File::delete($legacyPath);
        }

        $env = array_merge($_ENV, $_SERVER, [
            'PUPPETEER_CACHE_DIR' => getenv('PUPPETEER_CACHE_DIR')
                ?: (rtrim((string) getenv('HOME'), '/').'/.cache/puppeteer'),
        ]);

        $process = new Process(['node', $script, $htmlPath, $pdfPath], base_path(), $env);
        $process->setTimeout(120);
        $process->run(function (string $type, string $buffer): void {
            $this->output->write($buffer);
        });

        if (! $process->isSuccessful()) {
            $this->error(trim($process->getErrorOutput()) ?: 'Resume PDF generation failed.');

            return self::FAILURE;
        }

        if (! is_file($pdfPath)) {
            $this->error('PDF was not written.');

            return self::FAILURE;
        }

        $this->call('credentials:generate');

        $this->info('Generated '.str_replace(base_path().'/', '', $pdfPath).' ('.number_format(filesize($pdfPath) / 1024, 1).' KB)');

        return self::SUCCESS;
    }
}
