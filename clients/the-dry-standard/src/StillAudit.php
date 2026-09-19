<?php

namespace DryStandard;

final class StillAudit
{
    public const SOURCES = ['editorial', 'producer', 'importer'];

    private const MIN_SIDE = 500;

    /**
     * Retailer storefronts and marketplaces. Producer and importer domains are allowed.
     *
     * @var list<string>
     */
    private const RETAILER_MARKERS = [
        'total wine',
        'totalwine',
        'internetwines',
        'nabeerclub',
        'supervin',
        'vinello',
        'thezeroproof',
        'the zero proof',
        'roombox',
        'spizarnia',
        'clearsips',
        'minusmoonshine',
        'beclink',
        'nonalcoholicwines',
        'gueuledejoie',
        'empire wine',
        'empirewine',
        'schatziwines',
    ];

    /**
     * @param  array<string, string>  $hashesBySlug
     * @return array{errors: list<string>, warnings: list<string>, metrics: array<string, float|int|string>}
     */
    public function inspect(Review $review, array $hashesBySlug = [], bool $forPublish = false): array
    {
        $errors = [];
        $warnings = [];
        $metrics = [];

        $absolute = $this->absolutePath($review);
        if ($absolute === null) {
            return [
                'errors' => ['still file is missing'],
                'warnings' => [],
                'metrics' => $metrics,
            ];
        }

        $credit = mb_strtolower(trim($review->imageCredit ?? ''));
        $source = mb_strtolower(trim($review->imageSource ?? ''));
        $sourceUrl = mb_strtolower(trim($review->imageSourceUrl ?? ''));
        $haystack = $credit.' '.$sourceUrl;

        if ($this->isRetailer($haystack)) {
            $errors[] = 'still is credited to a retailer; use a producer, importer, or editorial photograph';
        }

        if ($source === '') {
            $message = 'image_source is missing (editorial, producer, or importer)';
            if ($forPublish) {
                $errors[] = $message;
            } else {
                $warnings[] = $message;
            }
        } elseif (! in_array($source, self::SOURCES, true)) {
            $errors[] = 'image_source must be editorial, producer, or importer — not a vendor scrape';
        }

        if (in_array($source, ['producer', 'importer'], true) && ($review->imageSourceUrl ?? '') === '') {
            $errors[] = 'producer/importer stills require image_source_url pointing at the original asset page';
        }

        if (($review->imageSkuConfirmed ?? '') !== 'yes') {
            $message = 'image_sku_confirmed is not yes — look at the label and confirm it is this SKU';
            if ($forPublish) {
                $errors[] = $message;
            } else {
                $warnings[] = $message;
            }
        }

        $hash = md5_file($absolute) ?: '';
        $metrics['hash'] = $hash;
        foreach ($hashesBySlug as $otherSlug => $otherHash) {
            if ($otherSlug !== $review->slug && $otherHash === $hash && $hash !== '') {
                $errors[] = 'still is identical to media/reviews/'.$otherSlug.'.jpg (placeholder or copy-paste)';
                break;
            }
        }

        $image = @imagecreatefromjpeg($absolute);
        if ($image === false) {
            $errors[] = 'still is not a readable JPEG';

            return ['errors' => $errors, 'warnings' => $warnings, 'metrics' => $metrics];
        }

        $width = imagesx($image);
        $height = imagesy($image);
        $ratio = $height > 0 ? $width / $height : 0;
        $metrics['width'] = $width;
        $metrics['height'] = $height;
        $metrics['ratio'] = round($ratio, 3);

        if (min($width, $height) < self::MIN_SIDE) {
            $errors[] = 'still is too small (need at least '.self::MIN_SIDE.'px on the short side)';
        }

        if (abs($ratio - 0.75) > 0.12) {
            $warnings[] = 'still is not 3:4; crop or letterbox onto paper';
        }

        $corners = $this->cornerLuma($image, $width, $height);
        $cornerStd = $this->stddev($corners);
        $metrics['corner_std'] = round($cornerStd, 1);
        $darkShare = $this->darkStripShare($image, $width, $height);
        $metrics['dark_strip'] = round($darkShare, 2);
        $labelColors = $this->labelColorCount($image, $width, $height);
        $metrics['label_colors'] = $labelColors;

        if ($darkShare >= 0.25) {
            $errors[] = 'studio black (or other dark void) behind the bottle; flatten onto paper';
        }

        if ($cornerStd >= 32) {
            $errors[] = 'lifestyle or multi-object scene, not a single SKU on paper';
        }

        if ($labelColors < 70) {
            $warnings[] = 'label area looks sparse — possible unlabeled mockup or cropped logo';
        }

        return ['errors' => $errors, 'warnings' => $warnings, 'metrics' => $metrics];
    }

    /**
     * @return array<string, string>
     */
    public function hashes(Paths $paths): array
    {
        $directory = $paths->path('media/reviews');
        $hashes = [];

        foreach (glob($directory.DIRECTORY_SEPARATOR.'*.jpg') ?: [] as $jpeg) {
            $slug = basename($jpeg, '.jpg');
            $hash = md5_file($jpeg);
            if (is_string($hash)) {
                $hashes[$slug] = $hash;
            }
        }

        return $hashes;
    }

    private function absolutePath(Review $review): ?string
    {
        $root = Paths::default()->path();
        $candidates = array_values(array_filter([
            $review->image,
            'media/reviews/'.$review->slug.'.jpg',
            $review->imageSrc(),
        ]));

        foreach ($candidates as $relative) {
            $relative = ltrim(str_replace('\\', '/', (string) $relative), '/');
            if (! preg_match('/\.(jpe?g|png)$/i', $relative)) {
                continue;
            }

            $absolute = $root.DIRECTORY_SEPARATOR.str_replace('/', DIRECTORY_SEPARATOR, $relative);
            if (is_file($absolute)) {
                return $absolute;
            }
        }

        return null;
    }

    private function isRetailer(string $haystack): bool
    {
        foreach (self::RETAILER_MARKERS as $marker) {
            if (str_contains($haystack, $marker)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return list<float>
     */
    private function cornerLuma(\GdImage $image, int $width, int $height): array
    {
        $points = [
            [8, 8],
            [$width - 9, 8],
            [8, $height - 9],
            [$width - 9, $height - 9],
        ];

        $values = [];
        foreach ($points as [$x, $y]) {
            $values[] = $this->luma($image, $x, $y);
        }

        return $values;
    }

    private function darkStripShare(\GdImage $image, int $width, int $height): float
    {
        $dark = 0;
        $total = 0;
        $x = max(1, (int) round($width * 0.07));

        for ($y = 0; $y < $height; $y += 4) {
            if ($this->luma($image, $x, $y) < 40) {
                $dark++;
            }
            $total++;
        }

        return $total === 0 ? 0.0 : $dark / $total;
    }

    private function labelColorCount(\GdImage $image, int $width, int $height): int
    {
        $x0 = (int) round($width * 0.38);
        $x1 = (int) round($width * 0.62);
        $y0 = (int) round($height * 0.42);
        $y1 = (int) round($height * 0.72);
        $set = [];

        for ($y = $y0; $y < $y1; $y += 2) {
            for ($x = $x0; $x < $x1; $x += 2) {
                $rgb = imagecolorat($image, $x, $y);
                $key = (($rgb >> 20) & 15).'.'.(($rgb >> 12) & 15).'.'.(($rgb >> 4) & 15);
                $set[$key] = true;
            }
        }

        return count($set);
    }

    private function luma(\GdImage $image, int $x, int $y): float
    {
        $rgb = imagecolorat($image, $x, $y);
        $r = ($rgb >> 16) & 255;
        $g = ($rgb >> 8) & 255;
        $b = $rgb & 255;

        return 0.299 * $r + 0.587 * $g + 0.114 * $b;
    }

    /**
     * @param  list<float>  $values
     */
    private function stddev(array $values): float
    {
        $count = count($values);
        if ($count === 0) {
            return 0.0;
        }

        $mean = array_sum($values) / $count;
        $variance = 0.0;
        foreach ($values as $value) {
            $variance += ($value - $mean) ** 2;
        }

        return sqrt($variance / $count);
    }
}
