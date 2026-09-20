<?php

namespace DryStandard;

final class StillPipeline
{
    private const PAPER = [243, 239, 230];

    private const WHITE = [255, 255, 255];

    private const TARGET_WIDTH = 900;

    private const TARGET_HEIGHT = 1200;

    /** Bottle/can height as a fraction of the 3:4 frame. */
    private const SUBJECT_HEIGHT_RATIO = 0.94;

    /** Max bottle/can width as a fraction of the frame. */
    private const SUBJECT_WIDTH_RATIO = 0.72;

    /** Padding around the detected subject before scaling. */
    private const SUBJECT_PAD_RATIO = 0.02;

    /** @var list<int> */
    private const DERIVATIVE_WIDTHS = [400, 800];

    /** @var array{0: int, 1: int, 2: int} */
    private array $fill;

    public function __construct(private readonly Paths $paths)
    {
        $this->fill = self::PAPER;
    }

    /**
     * Flatten a producer/editorial packshot onto paper/white, normalize bottle
     * scale, and write a 900×1200 JPEG.
     *
     * @param  'paper'|'white'  $background
     */
    public function ingest(string $sourcePath, string $destinationJpeg, string $background = 'paper'): bool
    {
        if (! function_exists('imagecreatetruecolor') || ! function_exists('imagejpeg')) {
            return false;
        }

        $this->fill = $background === 'white' ? self::WHITE : self::PAPER;

        $loaded = $this->load($sourcePath);
        if ($loaded === false) {
            return false;
        }

        $canvas = $this->compositeOnFill($loaded);
        $canvas = $this->scaleDown($canvas, 1800);
        // Dark studios only — never flood light neutrals (white labels/foil
        // connect to white packshot backdrops and get erased).
        $this->flattenDarkBackground($canvas);
        $canvas = $this->normalizeSubject($canvas);

        $directory = dirname($destinationJpeg);
        if (! is_dir($directory) && ! mkdir($directory, 0755, true) && ! is_dir($directory)) {
            return false;
        }

        $ok = imagejpeg($canvas, $destinationJpeg, 90);
        $this->fill = self::PAPER;

        return $ok;
    }

    /**
     * Re-normalize an existing still (crop to bottle, consistent scale, fill bg).
     *
     * @param  'paper'|'white'  $background
     */
    public function normalize(string $sourcePath, string $destinationJpeg, string $background = 'white'): bool
    {
        return $this->ingest($sourcePath, $destinationJpeg, $background);
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
     * Pure white packshots are left white so JPEG and WebP stay in sync.
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

        $alreadyWhite = 0;
        foreach ($seeds as [$x, $y]) {
            [$r, $g, $b] = $this->pixel($image, $x, $y);
            if ($r >= 254 && $g >= 254 && $b >= 254) {
                $alreadyWhite++;
            }
        }
        if ($alreadyWhite === 4) {
            return true;
        }

        // Already on the site paper tone — leave it.
        if ($this->fill === self::PAPER) {
            $alreadyPaper = 0;
            foreach ($seeds as [$x, $y]) {
                [$r, $g, $b] = $this->pixel($image, $x, $y);
                if (abs($r - self::PAPER[0]) < 8 && abs($g - self::PAPER[1]) < 8 && abs($b - self::PAPER[2]) < 8) {
                    $alreadyPaper++;
                }
            }
            if ($alreadyPaper === 4) {
                return true;
            }
        }

        [$pr, $pg, $pb] = $this->fill;
        $fill = imagecolorallocate($image, $pr, $pg, $pb);

        foreach ($seeds as [$x, $y]) {
            $this->flood($image, $x, $y, $fill, fn (int $x, int $y): bool => $this->isNearWhite($image, $x, $y));
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

        [$pr, $pg, $pb] = $this->fill;
        $fill = imagecolorallocate($image, $pr, $pg, $pb);

        foreach ($seeds as [$x, $y]) {
            $this->flood($image, $x, $y, $fill, fn (int $x, int $y): bool => $this->isNearBlack($image, $x, $y));
        }

        return true;
    }

    private function isNearWhite(\GdImage $image, int $x, int $y): bool
    {
        [$r, $g, $b, $luma] = $this->pixel($image, $x, $y);

        // Strict: only near-pure white/cream studio, not pale label stock.
        return $luma > 248 && abs($r - $g) < 10 && abs($g - $b) < 10;
    }

    /**
     * Crop to the bottle/can and place it at a consistent size on the fill.
     */
    private function normalizeSubject(\GdImage $source): \GdImage
    {
        $bounds = $this->subjectBounds($source);
        if ($bounds === null) {
            return $this->letterbox($source, imagesx($source), imagesy($source));
        }

        [$x0, $y0, $x1, $y1] = $bounds;
        $subjectW = max(1, $x1 - $x0 + 1);
        $subjectH = max(1, $y1 - $y0 + 1);

        $padX = (int) round($subjectW * self::SUBJECT_PAD_RATIO);
        $padY = (int) round($subjectH * self::SUBJECT_PAD_RATIO);
        $x0 = max(0, $x0 - $padX);
        $y0 = max(0, $y0 - $padY);
        $x1 = min(imagesx($source) - 1, $x1 + $padX);
        $y1 = min(imagesy($source) - 1, $y1 + $padY);
        $subjectW = max(1, $x1 - $x0 + 1);
        $subjectH = max(1, $y1 - $y0 + 1);

        $scale = min(
            (self::TARGET_WIDTH * self::SUBJECT_WIDTH_RATIO) / $subjectW,
            (self::TARGET_HEIGHT * self::SUBJECT_HEIGHT_RATIO) / $subjectH,
        );
        $drawW = max(1, (int) round($subjectW * $scale));
        $drawH = max(1, (int) round($subjectH * $scale));

        $canvas = imagecreatetruecolor(self::TARGET_WIDTH, self::TARGET_HEIGHT);
        [$r, $g, $b] = $this->fill;
        imagefill($canvas, 0, 0, imagecolorallocate($canvas, $r, $g, $b));

        $dstX = (int) round((self::TARGET_WIDTH - $drawW) / 2);
        $dstY = (int) round((self::TARGET_HEIGHT - $drawH) / 2);
        imagecopyresampled($canvas, $source, $dstX, $dstY, $x0, $y0, $drawW, $drawH, $subjectW, $subjectH);

        return $canvas;
    }

    /**
     * Box around the bottle/can. Light neutrals are ignored for detection so
     * patterned backdrops drop out of the crop; label pixels are never rewritten.
     *
     * @return array{0: int, 1: int, 2: int, 3: int}|null
     */
    private function subjectBounds(\GdImage $image): ?array
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $minX = $width;
        $minY = $height;
        $maxX = -1;
        $maxY = -1;
        $step = max(1, (int) floor(min($width, $height) / 500));

        for ($y = 0; $y < $height; $y += $step) {
            for ($x = 0; $x < $width; $x += $step) {
                if ($this->isBackdropPixel($image, $x, $y)) {
                    continue;
                }

                $minX = min($minX, $x);
                $minY = min($minY, $y);
                $maxX = max($maxX, $x);
                $maxY = max($maxY, $y);
            }
        }

        if ($maxX < $minX || $maxY < $minY) {
            return null;
        }

        $pad = $step * 2;
        $rx0 = max(0, $minX - $pad);
        $ry0 = max(0, $minY - $pad);
        $rx1 = min($width - 1, $maxX + $pad);
        $ry1 = min($height - 1, $maxY + $pad);
        $minX = $width;
        $minY = $height;
        $maxX = -1;
        $maxY = -1;

        for ($y = $ry0; $y <= $ry1; $y++) {
            for ($x = $rx0; $x <= $rx1; $x++) {
                if ($this->isBackdropPixel($image, $x, $y)) {
                    continue;
                }

                $minX = min($minX, $x);
                $minY = min($minY, $y);
                $maxX = max($maxX, $x);
                $maxY = max($maxY, $y);
            }
        }

        if ($maxX < $minX || $maxY < $minY) {
            return null;
        }

        $boxH = $maxY - $minY + 1;
        if ($boxH < (int) round($height * 0.12)) {
            return null;
        }

        // Keep white foil / label edges that sit outside ink/glass seeds.
        $grow = max(10, (int) round(min($width, $height) * 0.015));

        return [
            max(0, $minX - $grow),
            max(0, $minY - $grow),
            min($width - 1, $maxX + $grow),
            min($height - 1, $maxY + $grow),
        ];
    }

    private function isBackdropPixel(\GdImage $image, int $x, int $y): bool
    {
        [$r, $g, $b, $luma] = $this->pixel($image, $x, $y);
        [$fr, $fg, $fb] = $this->fill;

        if (abs($r - $fr) < 14 && abs($g - $fg) < 14 && abs($b - $fb) < 14) {
            return true;
        }

        $chroma = max(abs($r - $g), abs($g - $b), abs($r - $b));

        // Paper, white, and light gray studio stripes — detection only.
        return $luma >= 200 && $chroma <= 22;
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
     * Scanline flood fill from a corner. Tracks visited pixels so the fill
     * color may itself satisfy $matches (e.g. white onto near-white).
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
        $visited = array_fill(0, $width * $height, false);
        $stack = [[$sx, $sy]];

        while ($stack !== []) {
            [$x, $y] = array_pop($stack);
            $idx = $y * $width + $x;
            if ($visited[$idx] || ! $matches($x, $y)) {
                continue;
            }

            while ($x > 0 && ! $visited[$y * $width + ($x - 1)] && $matches($x - 1, $y)) {
                $x--;
            }

            $spanUp = false;
            $spanDown = false;

            while ($x < $width && ! $visited[$y * $width + $x] && $matches($x, $y)) {
                $visited[$y * $width + $x] = true;
                imagesetpixel($image, $x, $y, $paper);

                if ($y > 0) {
                    $up = ($y - 1) * $width + $x;
                    if (! $visited[$up] && $matches($x, $y - 1)) {
                        if (! $spanUp) {
                            $stack[] = [$x, $y - 1];
                            $spanUp = true;
                        }
                    } else {
                        $spanUp = false;
                    }
                }

                if ($y < $height - 1) {
                    $down = ($y + 1) * $width + $x;
                    if (! $visited[$down] && $matches($x, $y + 1)) {
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
        [$r, $g, $b] = $this->fill;
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
        $loaded = match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($path),
            'png' => @imagecreatefrompng($path),
            'webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };

        if ($loaded !== false) {
            return $loaded;
        }

        // Cursor/chat assets sometimes keep a .png name on a JPEG payload.
        $info = @getimagesize($path);
        $mime = is_array($info) ? (string) ($info['mime'] ?? '') : '';

        return match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($path) : false,
            default => false,
        };
    }

    private function compositeOnFill(\GdImage $source): \GdImage
    {
        $width = imagesx($source);
        $height = imagesy($source);
        $canvas = imagecreatetruecolor($width, $height);
        [$r, $g, $b] = $this->fill;
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
