<?php

namespace DryStandard;

final class StillPipeline
{
    private const PAPER = [243, 239, 230];

    private const TARGET_WIDTH = 900;

    private const TARGET_HEIGHT = 1200;

    /** @var list<int> */
    private const DERIVATIVE_WIDTHS = [400, 800];

    public function __construct(private readonly Paths $paths) {}

    /**
     * Flatten a producer/editorial packshot onto paper and write a 900×1200 JPEG.
     */
    public function ingest(string $sourcePath, string $destinationJpeg): bool
    {
        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagejpeg')) {
            return false;
        }

        $loaded = $this->load($sourcePath);
        if ($loaded === false) {
            return false;
        }

        $canvas = $this->compositeOnPaper($loaded);
        $canvas = $this->scaleDown($canvas, 1800);
        $this->flattenDarkBackground($canvas);
        $this->flattenLightBackground($canvas);
        $canvas = $this->letterbox($canvas, imagesx($canvas), imagesy($canvas));

        $directory = dirname($destinationJpeg);
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            return false;
        }

        return imagejpeg($canvas, $destinationJpeg, 90);
    }

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

            $converted += $this->writeWidthSet($source, $jpeg);
        }

        return $converted;
    }

    /**
     * Build WebP (and width variants) next to a JPEG if they are missing or stale.
     */
    public function ensureDerivatives(string $jpegPath): void
    {
        if (! is_file($jpegPath) || ! function_exists('imagecreatefromjpeg') || ! function_exists('imagewebp')) {
            return;
        }

        $webp = preg_replace('/\.jpe?g$/i', '.webp', $jpegPath) ?: $jpegPath.'.webp';
        $needsFull = ! is_file($webp) || filemtime($webp) < filemtime($jpegPath);
        $needsWidths = false;

        foreach (self::DERIVATIVE_WIDTHS as $width) {
            $variant = $this->widthPath($jpegPath, $width, 'webp');
            if (! is_file($variant) || filemtime($variant) < filemtime($jpegPath)) {
                $needsWidths = true;
                break;
            }
        }

        if (! $needsFull && ! $needsWidths) {
            return;
        }

        $source = @imagecreatefromjpeg($jpegPath);
        if ($source === false) {
            return;
        }

        $this->flattenLightBackground($source);

        if ($needsFull) {
            $this->writeWebp($source, $webp);
        }

        if ($needsWidths) {
            $this->writeWidthSet($source, $jpegPath);
        }
    }

    /**
     * Serve a generated still (WebP or width variant) from the JPEG master.
     */
    public function serve(string $relative): ?string
    {
        $relative = ltrim(str_replace('\\', '/', $relative), '/');
        $directory = $this->paths->path('media/reviews');
        $target = $directory.DIRECTORY_SEPARATOR.basename($relative);

        if (is_file($target)) {
            return $target;
        }

        if (! preg_match('/^(?<slug>[a-z0-9-]+?)(?:-(?<width>400|800))?\.webp$/i', basename($relative), $matches)) {
            return null;
        }

        $jpeg = $directory.DIRECTORY_SEPARATOR.$matches['slug'].'.jpg';
        if (! is_file($jpeg)) {
            return null;
        }

        $this->ensureDerivatives($jpeg);

        return is_file($target) ? $target : null;
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

    private function writeWidthSet(\GdImage $source, string $jpegPath): int
    {
        $written = 0;
        $width = imagesx($source);
        $height = imagesy($source);

        foreach (self::DERIVATIVE_WIDTHS as $targetWidth) {
            $path = $this->widthPath($jpegPath, $targetWidth, 'webp');
            if (is_file($path) && filemtime($path) >= filemtime($jpegPath)) {
                continue;
            }

            $targetHeight = max(1, (int) round($targetWidth * ($height / max($width, 1))));
            $scaled = imagecreatetruecolor($targetWidth, $targetHeight);
            imagecopyresampled($scaled, $source, 0, 0, 0, 0, $targetWidth, $targetHeight, $width, $height);

            if (imagewebp($scaled, $path, 78)) {
                $written++;
            }
        }

        return $written;
    }

    private function widthPath(string $jpegPath, int $width, string $extension): string
    {
        return (string) preg_replace('/\.jpe?g$/i', '-'.$width.'.'.$extension, $jpegPath);
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
            $this->flood($image, $x, $y, $paper, fn (int $x, int $y): bool => $this->isNearWhite($image, $x, $y));
        }

        return true;
    }

    /**
     * Sweep a uniform near-black studio into paper. Green/amber glass stays;
     * only pixels connected to the corners are replaced.
     */
    private function flattenDarkBackground(\GdImage $image): bool
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $seeds = [
            [2, 2],
            [$width - 3, 2],
            [2, $height - 3],
            [$width - 3, $height - 3],
        ];

        $blackCorners = 0;
        foreach ($seeds as [$x, $y]) {
            if ($this->isNearBlack($image, $x, $y)) {
                $blackCorners++;
            }
        }

        if ($blackCorners < 4) {
            return false;
        }

        [$pr, $pg, $pb] = self::PAPER;
        $paper = imagecolorallocate($image, $pr, $pg, $pb);

        foreach ($seeds as [$x, $y]) {
            $this->flood($image, $x, $y, $paper, fn (int $x, int $y): bool => $this->isNearBlack($image, $x, $y));
        }

        return true;
    }

    private function isNearWhite(\GdImage $image, int $x, int $y): bool
    {
        [$r, $g, $b, $luma] = $this->pixel($image, $x, $y);

        return $luma > 248 && abs($r - $g) < 10 && abs($g - $b) < 10;
    }

    private function isNearBlack(\GdImage $image, int $x, int $y): bool
    {
        [$r, $g, $b, $luma] = $this->pixel($image, $x, $y);

        return $luma < 22 && max($r, $g, $b) < 32;
    }

    /**
     * @return array{0: int, 1: int, 2: int, 3: float}
     */
    private function pixel(\GdImage $image, int $x, int $y): array
    {
        $rgb = imagecolorat($image, $x, $y);
        $r = ($rgb >> 16) & 255;
        $g = ($rgb >> 8) & 255;
        $b = $rgb & 255;
        $luma = 0.299 * $r + 0.587 * $g + 0.114 * $b;

        return [$r, $g, $b, $luma];
    }

    /**
     * Scanline flood fill from a corner. Painted paper is outside the match
     * predicates, so visited pixels do not need a separate mask.
     *
     * @param  callable(int, int): bool  $matches
     */
    private function flood(\GdImage $image, int $sx, int $sy, int $paper, callable $matches): void
    {
        if (! $matches($sx, $sy)) {
            return;
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $stack = [[$sx, $sy]];

        while ($stack !== []) {
            [$x, $y] = array_pop($stack);

            while ($x > 0 && $matches($x - 1, $y)) {
                $x--;
            }

            $spanUp = false;
            $spanDown = false;

            while ($x < $width && $matches($x, $y)) {
                imagesetpixel($image, $x, $y, $paper);

                if ($y > 0) {
                    if ($matches($x, $y - 1)) {
                        if (! $spanUp) {
                            $stack[] = [$x, $y - 1];
                            $spanUp = true;
                        }
                    } else {
                        $spanUp = false;
                    }
                }

                if ($y < $height - 1) {
                    if ($matches($x, $y + 1)) {
                        if (! $spanDown) {
                            $stack[] = [$x, $y + 1];
                            $spanDown = true;
                        }
                    } else {
                        $spanDown = false;
                    }
                }

                $x++;
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

    private function load(string $path): \GdImage|false
    {
        if (! is_file($path)) {
            return false;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));

        return match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
    }

    private function compositeOnPaper(\GdImage $source): \GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);
        $canvas = imagecreatetruecolor($width, $height);
        [$r, $g, $b] = self::PAPER;
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, $r, $g, $b));
        imagealphablending($canvas, true);
        imagecopy($canvas, $source, 0, 0, 0, 0, $width, $height);

        return $canvas;
    }

    private function scaleDown(\GdImage $source, int $maxSide): \GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);
        $long = max($width, $height);
        if ($long <= $maxSide) {
            return $source;
        }

        $scale = $maxSide / $long;
        $drawW = max(1, (int) round($width * $scale));
        $drawH = max(1, (int) round($height * $scale));
        $scaled = imagecreatetruecolor($drawW, $drawH);
        imagecopyresampled($scaled, $source, 0, 0, 0, 0, $drawW, $drawH, $width, $height);

        return $scaled;
    }
}
