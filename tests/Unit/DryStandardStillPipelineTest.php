<?php

use DryStandard\Paths;
use DryStandard\StillPipeline;

it('ingests a black-studio packshot onto paper', function () {
    $source = sys_get_temp_dir().DIRECTORY_SEPARATOR.'tds-ingest-src.png';
    $destination = sys_get_temp_dir().DIRECTORY_SEPARATOR.'tds-ingest-out.jpg';

    $image = imagecreatetruecolor(400, 600);
    imagefill($image, 0, 0, imagecolorallocate($image, 0, 0, 0));
    imagefilledrectangle($image, 150, 80, 250, 520, imagecolorallocate($image, 40, 140, 60));
    imagepng($image, $source);

    try {
        expect((new StillPipeline(Paths::default()))->ingest($source, $destination))->toBeTrue();

        $out = imagecreatefromjpeg($destination);
        expect(imagesx($out))->toBe(900);
        expect(imagesy($out))->toBe(1200);

        $rgb = imagecolorat($out, 8, 8);
        $r = ($rgb >> 16) & 255;
        $g = ($rgb >> 8) & 255;
        $b = $rgb & 255;

        expect($r)->toBeGreaterThan(220)
            ->and($g)->toBeGreaterThan(220)
            ->and($b)->toBeGreaterThan(210);
    } finally {
        @unlink($source);
        @unlink($destination);
    }
});

it('ingests a black-studio packshot onto white when requested', function () {
    $source = sys_get_temp_dir().DIRECTORY_SEPARATOR.'tds-ingest-white-src.png';
    $destination = sys_get_temp_dir().DIRECTORY_SEPARATOR.'tds-ingest-white-out.jpg';

    $image = imagecreatetruecolor(400, 600);
    imagefill($image, 0, 0, imagecolorallocate($image, 0, 0, 0));
    imagefilledrectangle($image, 150, 80, 250, 520, imagecolorallocate($image, 40, 140, 60));
    imagepng($image, $source);

    try {
        expect((new StillPipeline(Paths::default()))->ingest($source, $destination, 'white'))->toBeTrue();

        $out = imagecreatefromjpeg($destination);
        $rgb = imagecolorat($out, 8, 8);
        $r = ($rgb >> 16) & 255;
        $g = ($rgb >> 8) & 255;
        $b = $rgb & 255;

        expect($r)->toBe(255)
            ->and($g)->toBe(255)
            ->and($b)->toBe(255);
    } finally {
        @unlink($source);
        @unlink($destination);
    }
});

it('normalizes a small bottle on a striped packshot to a consistent height', function () {
    $source = sys_get_temp_dir().DIRECTORY_SEPARATOR.'tds-normalize-src.png';
    $destination = sys_get_temp_dir().DIRECTORY_SEPARATOR.'tds-normalize-out.jpg';

    $image = imagecreatetruecolor(900, 900);
    $white = imagecolorallocate($image, 245, 245, 245);
    $stripe = imagecolorallocate($image, 210, 210, 214);
    for ($y = 0; $y < 900; $y++) {
        for ($x = 0; $x < 900; $x++) {
            imagesetpixel($image, $x, $y, (($x + $y) % 24 < 12) ? $white : $stripe);
        }
    }
    // Small centered bottle (~45% of frame height).
    imagefilledrectangle($image, 400, 250, 500, 650, imagecolorallocate($image, 20, 60, 40));
    imagefilledrectangle($image, 410, 480, 490, 620, imagecolorallocate($image, 250, 250, 250));
    imagepng($image, $source);

    try {
        expect((new StillPipeline(Paths::default()))->normalize($source, $destination, 'white'))->toBeTrue();

        $out = imagecreatefromjpeg($destination);
        expect(imagesx($out))->toBe(900);
        expect(imagesy($out))->toBe(1200);

        $corner = imagecolorat($out, 8, 8);
        expect(($corner >> 16) & 255)->toBe(255)
            ->and(($corner >> 8) & 255)->toBe(255)
            ->and($corner & 255)->toBe(255);

        $minY = 1200;
        $maxY = 0;
        for ($y = 0; $y < 1200; $y += 2) {
            for ($x = 200; $x < 700; $x += 2) {
                $rgb = imagecolorat($out, $x, $y);
                $r = ($rgb >> 16) & 255;
                $g = ($rgb >> 8) & 255;
                $b = $rgb & 255;
                $luma = 0.299 * $r + 0.587 * $g + 0.114 * $b;
                $chroma = max(abs($r - $g), abs($g - $b), abs($r - $b));
                if ($luma < 200 || $chroma > 22) {
                    $minY = min($minY, $y);
                    $maxY = max($maxY, $y);
                }
            }
        }

        $subjectShare = ($maxY - $minY + 1) / 1200;
        expect($subjectShare)->toBeGreaterThan(0.80)
            ->and($subjectShare)->toBeLessThan(0.98);
    } finally {
        @unlink($source);
        @unlink($destination);
    }
});
