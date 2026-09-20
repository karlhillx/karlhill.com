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
