<?php

namespace App\Http\Controllers;

use App\Support\BlogPost;
use App\Support\BlogPostRepository;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class OgImageController extends Controller
{
    private const WIDTH = 1200;

    private const HEIGHT = 630;

    public function __construct(
        protected readonly BlogPostRepository $posts,
    ) {}

    public function blog(string $slug): Response
    {
        $post = $this->posts->findOrFail($slug);

        // If GD or the bundled font is unavailable, fail soft to the static card
        // rather than throwing a 500 on a social crawler.
        if (! function_exists('imagettftext') || ! is_file($this->fontPath())) {
            return redirect()->to(rtrim(config('app.url', 'https://karlhill.com'), '/').'/img/og-home.jpg');
        }

        // Cache the rendered bytes keyed by content mtime so edits bust the cache.
        $png = Cache::remember(
            "og.blog.{$slug}.".$post->modifiedAt()->timestamp,
            now()->addDays(30),
            fn (): string => $this->render($post),
        );

        return response($png, 200, [
            'Content-Type' => 'image/png',
            'Content-Length' => (string) strlen($png),
        ]);
    }

    protected function render(BlogPost $post): string
    {
        $w = self::WIDTH;
        $h = self::HEIGHT;
        $font = $this->fontPath();
        $pad = 80;

        $im = imagecreatetruecolor($w, $h);

        $bg = imagecolorallocate($im, 8, 8, 8);
        $accent = imagecolorallocate($im, 249, 115, 22);
        $white = imagecolorallocate($im, 245, 245, 245);
        $muted = imagecolorallocate($im, 150, 150, 150);
        $line = imagecolorallocate($im, 38, 38, 38);

        imagefilledrectangle($im, 0, 0, $w, $h, $bg);

        // Faint inner frame + accent tick to echo the site's card language.
        imagerectangle($im, $pad - 40, $pad - 40, $w - ($pad - 40), $h - ($pad - 40), $line);
        imagefilledrectangle($im, $pad, 104, $pad + 64, 110, $accent);

        // Eyebrow.
        imagettftext($im, 26, 0, $pad, 160, $accent, $font, 'KARL HILL — WRITING');

        // Title: pick the largest size that fits within four lines.
        $maxWidth = $w - ($pad * 2);
        [$size, $lines] = $this->fitTitle($post->title, $font, $maxWidth, 4);
        $lineHeight = (int) round($size * 1.14);

        // Vertically center the title block in the space between eyebrow and footer.
        $blockHeight = count($lines) * $lineHeight;
        $areaTop = 210;
        $areaBottom = $h - 150;
        $y = (int) round($areaTop + (($areaBottom - $areaTop) - $blockHeight) / 2) + $size;
        $y = max($areaTop + $size, $y);

        foreach ($lines as $ln) {
            imagettftext($im, $size, 0, $pad, $y, $white, $font, $ln);
            $y += $lineHeight;
        }

        // Footer meta (date · read time) and domain, aligned to the frame.
        $meta = strtoupper($post->publishedAt->format('M j, Y')).'   ·   '.$post->readMinutes.' MIN READ';
        imagettftext($im, 24, 0, $pad, $h - 84, $muted, $font, $meta);

        $domain = 'KARLHILL.COM';
        $bbox = imagettfbbox(24, 0, $font, $domain);
        $domainWidth = $bbox[2] - $bbox[0];
        imagettftext($im, 24, 0, $w - $pad - $domainWidth, $h - 84, $accent, $font, $domain);

        ob_start();
        imagepng($im);

        return (string) ob_get_clean();
    }

    /**
     * Find the largest font size (from a candidate ramp) whose wrapped title
     * fits within $maxLines; truncate the final line if even the smallest ramp
     * overflows.
     *
     * @return array{0:int,1:array<int,string>}
     */
    protected function fitTitle(string $title, string $font, int $maxWidth, int $maxLines): array
    {
        $title = trim(preg_replace('/\s+/', ' ', $title) ?? $title);

        foreach ([92, 80, 70, 60] as $size) {
            $lines = $this->wrap($title, $font, $size, $maxWidth);
            if (count($lines) <= $maxLines) {
                return [$size, $lines];
            }
        }

        $lines = $this->wrap($title, $font, 60, $maxWidth);
        $lines = array_slice($lines, 0, $maxLines);
        $last = count($lines) - 1;
        $lines[$last] = rtrim($lines[$last]).'…';

        return [60, $lines];
    }

    /**
     * Greedy word-wrap using FreeType metrics.
     *
     * @return array<int,string>
     */
    protected function wrap(string $text, string $font, int $size, int $maxWidth): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $current = '';

        foreach ($words as $word) {
            $trial = $current === '' ? $word : $current.' '.$word;
            $bbox = imagettfbbox($size, 0, $font, $trial);
            $width = $bbox[2] - $bbox[0];

            if ($width > $maxWidth && $current !== '') {
                $lines[] = $current;
                $current = $word;
            } else {
                $current = $trial;
            }
        }

        if ($current !== '') {
            $lines[] = $current;
        }

        return $lines === [] ? [''] : $lines;
    }

    protected function fontPath(): string
    {
        return resource_path('fonts/BebasNeue-Regular.ttf');
    }
}
