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

            if ($this->writeWebp($jpeg, $webp)) {
                $converted++;
            }
        }

        return $converted;
    }

    private function writeWebp(string $jpeg, string $webp): bool
    {
        $source = @imagecreatefromjpeg($jpeg);
        if ($source === false) {
            return false;
        }

        $width = imagesx($source);
        $height = imagesy($source);
        $ratio = $height > 0 ? $width / $height : 1;
        $canvas = $source;

        if (abs($ratio - 0.75) > 0.08) {
            $padded = $this->letterbox($source, $width, $height);
            imagedestroy($source);
            $canvas = $padded;
            $width = imagesx($canvas);
            $height = imagesy($canvas);
        }

        if ($width > self::TARGET_WIDTH) {
            $scaledHeight = (int) round(self::TARGET_WIDTH * ($height / $width));
            $scaled = imagecreatetruecolor(self::TARGET_WIDTH, $scaledHeight);
            imagecopyresampled($scaled, $canvas, 0, 0, 0, 0, self::TARGET_WIDTH, $scaledHeight, $width, $height);
            imagedestroy($canvas);
            $canvas = $scaled;
        }

        $ok = imagewebp($canvas, $webp, 78);
        imagedestroy($canvas);

        return $ok;
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
