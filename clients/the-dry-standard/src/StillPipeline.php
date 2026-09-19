<?php

namespace DryStandard;

final class StillPipeline
{
    private const PAPER = [243, 239, 230];

    private const TARGET_WIDTH = 900;

    private const TARGET_HEIGHT = 1200;

    public function __construct(private readonly Paths $paths) {}

    public function run(): int
    {
        if (! function_exists('imagecreatefromjpeg') || ! function_exists('imagewebp')) {
            return 0;
        }

        $directory = $this->paths->path('media/reviews');
        if (! is_dir($directory)) {
            return 0;
        }

        $converted = 0;

        foreach (glob($directory.DIRECTORY_SEPARATOR.'*.jpg') ?: [] as $jpeg) {
            $webp = preg_replace('/\.jpg$/i', '.webp', $jpeg) ?: $jpeg.'.webp';

            if (is_file($webp) && filemtime($webp) >= filemtime($jpeg)) {
                continue;
            }

            $source = @imagecreatefromjpeg($jpeg);
            if ($source === false) {
                continue;
            }

            $this->flattenLightBackground($source);

            if ($this->writeWebp($source, $webp)) {
                $converted++;
            }
        }

        return $converted;
    }

    private function writeWebp(\GdImage $source, string $webp): bool
    {
        $width = imagesx($source);
        $height = imagesy($source);
        $ratio = $height > 0 ? $width / $height : 1;
        $canvas = $source;

        if (abs($ratio - 0.75) > 0.08) {
            $canvas = $this->letterbox($source, $width, $height);
            $width = imagesx($canvas);
            $height = imagesy($canvas);
        }

        if ($width > self::TARGET_WIDTH) {
            $scaledHeight = (int) round(self::TARGET_WIDTH * ($height / $width));
            $scaled = imagecreatetruecolor(self::TARGET_WIDTH, $scaledHeight);
            imagecopyresampled($scaled, $canvas, 0, 0, 0, 0, self::TARGET_WIDTH, $scaledHeight, $width, $height);
            $canvas = $scaled;
        }

        return imagewebp($canvas, $webp, 78);
    }

    /**
     * Sweep a uniform near-white studio into paper. Skip dark studios — black
     * cans connect to black backdrops and a flood fill would erase the SKU.
     */
    private function flattenLightBackground(\GdImage $image): bool
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $seeds = [
            [2, 2],
            [$width - 3, 2],
            [2, $height - 3],
            [$width - 3, $height - 3],
        ];

        $whiteCorners = 0;
        foreach ($seeds as [$x, $y]) {
            if ($this->isNearWhite($image, $x, $y)) {
                $whiteCorners++;
            }
        }

        if ($whiteCorners < 4) {
            return false;
        }

        [$pr, $pg, $pb] = self::PAPER;
        $paper = imagecolorallocate($image, $pr, $pg, $pb);

        foreach ($seeds as [$x, $y]) {
            $this->floodNearWhite($image, $x, $y, $paper);
        }

        return true;
    }

    private function isNearWhite(\GdImage $image, int $x, int $y): bool
    {
        $rgb = imagecolorat($image, $x, $y);
        $r = ($rgb >> 16) & 255;
        $g = ($rgb >> 8) & 255;
        $b = $rgb & 255;
        $luma = 0.299 * $r + 0.587 * $g + 0.114 * $b;

        return $luma > 248 && abs($r - $g) < 10 && abs($g - $b) < 10;
    }

    private function floodNearWhite(\GdImage $image, int $sx, int $sy, int $paper): void
    {
        if (! $this->isNearWhite($image, $sx, $sy)) {
            return;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $seen = array_fill(0, $width * $height, false);
        $queue = [[$sx, $sy]];

        while ($queue !== []) {
            [$x, $y] = array_pop($queue);
            $index = $y * $width + $x;
            if ($seen[$index]) {
                continue;
            }
            $seen[$index] = true;

            if (! $this->isNearWhite($image, $x, $y)) {
                continue;
            }

            imagesetpixel($image, $x, $y, $paper);

            if ($x > 0) {
                $queue[] = [$x - 1, $y];
            }
            if ($x < $width - 1) {
                $queue[] = [$x + 1, $y];
            }
            if ($y > 0) {
                $queue[] = [$x, $y - 1];
            }
            if ($y < $height - 1) {
                $queue[] = [$x, $y + 1];
            }
        }
    }

    private function letterbox(\GdImage $source, int $width, int $height): \GdImage
    {
        $canvas = imagecreatetruecolor(self::TARGET_WIDTH, self::TARGET_HEIGHT);
        [$r, $g, $b] = self::PAPER;
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, $r, $g, $b));

        $scale = min(self::TARGET_WIDTH / max($width, 1), self::TARGET_HEIGHT / max($height, 1));
        $drawW = (int) round($width * $scale);
        $drawH = (int) round($height * $scale);
        $x = (int) round((self::TARGET_WIDTH - $drawW) / 2);
        $y = (int) round((self::TARGET_HEIGHT - $drawH) / 2);
        imagecopyresampled($canvas, $source, $x, $y, 0, 0, $drawW, $drawH, $width, $height);

        return $canvas;
    }
}
