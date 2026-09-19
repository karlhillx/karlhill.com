<?php

use DryStandard\Paths;
use DryStandard\Review;
use DryStandard\StillAudit;
use Spatie\YamlFrontMatter\YamlFrontMatter;

function dryStandardStillReview(string $slug): Review
{
    $file = Paths::default()->content('reviews'.DIRECTORY_SEPARATOR.$slug.'.md');
    $document = YamlFrontMatter::parseFile($file);

    return Review::fromMatter($document->matter(), $document->body(), $file);
}

function dryStandardStillHashes(): array
{
    return (new StillAudit)->hashes(Paths::default());
}

it('accepts a replaced producer still for publish', function () {
    $result = (new StillAudit)->inspect(
        dryStandardStillReview('chloe-pinot-grigio'),
        dryStandardStillHashes(),
        forPublish: true,
    );

    expect($result['errors'])->toBe([]);
});

it('accepts the Leitz gold-standard still for publish', function () {
    $result = (new StillAudit)->inspect(
        dryStandardStillReview('leitz-eins-zwei-zero-riesling'),
        dryStandardStillHashes(),
        forPublish: true,
    );

    expect($result['errors'])->toBe([]);
});

it('rejects retailer credits', function () {
    $result = (new StillAudit)->inspect(
        dryStandardStillReview('penns-best-lager'),
        dryStandardStillHashes(),
    );

    expect($result['errors'])->toContain('still is credited to a retailer; use a producer, importer, or editorial photograph');
});

it('rejects byte-identical stills reused across SKUs', function () {
    $hashes = dryStandardStillHashes();
    $chloe = md5_file(Paths::default()->path('media/reviews/chloe-pinot-grigio.jpg'));
    $stRegis = md5_file(Paths::default()->path('media/reviews/st-regis-non-alcoholic-rose.jpg'));

    expect($chloe)->not->toBe($stRegis);
    expect((new StillAudit)->inspect(dryStandardStillReview('chloe-pinot-grigio'), $hashes)['errors'])->not->toContain(
        'still is identical to media/reviews/st-regis-non-alcoholic-rose.jpg (placeholder or copy-paste)',
    );
});

it('rejects dark studio packshots', function () {
    $result = (new StillAudit)->inspect(
        dryStandardStillReview('magic-box-vanish-riesling'),
        dryStandardStillHashes(),
    );

    expect($result['errors'])->toContain('studio black (or other dark void) behind the bottle; flatten onto paper');
});

it('rejects lifestyle tablescapes', function () {
    $relative = 'media/reviews/__lifestyle-audit.jpg';
    $path = Paths::default()->path($relative);
    $image = imagecreatetruecolor(600, 800);
    imagefilledrectangle($image, 0, 0, 299, 399, imagecolorallocate($image, 10, 90, 20));
    imagefilledrectangle($image, 300, 0, 599, 399, imagecolorallocate($image, 200, 30, 30));
    imagefilledrectangle($image, 0, 400, 299, 799, imagecolorallocate($image, 20, 20, 180));
    imagefilledrectangle($image, 300, 400, 599, 799, imagecolorallocate($image, 240, 240, 40));
    imagejpeg($image, $path, 90);

    $file = Paths::default()->content('reviews'.DIRECTORY_SEPARATOR.'chloe-pinot-grigio.md');
    $document = YamlFrontMatter::parseFile($file);
    $matter = $document->matter();
    $matter['slug'] = '__lifestyle-audit';
    $matter['image'] = $relative;
    $matter['image_source'] = 'editorial';
    $matter['image_sku_confirmed'] = 'yes';
    $review = Review::fromMatter($matter, $document->body(), $file);

    try {
        $result = (new StillAudit)->inspect($review);
        expect($result['errors'])->toContain('lifestyle or multi-object scene, not a single SKU on paper');
    } finally {
        @unlink($path);
    }
});

it('rejects a close-up of part of the bottle', function () {
    $relative = 'media/reviews/__fragment-audit.jpg';
    $path = Paths::default()->path($relative);
    $image = imagecreatetruecolor(600, 800);
    imagefill($image, 0, 0, imagecolorallocate($image, 255, 255, 255));
    imagefilledrectangle($image, 20, 0, 579, 799, imagecolorallocate($image, 196, 92, 48));
    imagejpeg($image, $path, 90);

    $file = Paths::default()->content('reviews'.DIRECTORY_SEPARATOR.'chloe-pinot-grigio.md');
    $document = YamlFrontMatter::parseFile($file);
    $matter = $document->matter();
    $matter['slug'] = '__fragment-audit';
    $matter['image'] = $relative;
    $matter['image_source'] = 'editorial';
    $matter['image_sku_confirmed'] = 'yes';
    $review = Review::fromMatter($matter, $document->body(), $file);

    try {
        $result = (new StillAudit)->inspect($review);
        expect($result['errors'])->toContain('still is a close-up of part of the bottle; use a full-bottle packshot with the whole label visible');
    } finally {
        @unlink($path);
    }
});

it('warns on an honest empty frame', function () {
    $result = (new StillAudit)->inspect(
        dryStandardStillReview('leitz-sparkling-blanc-de-blancs'),
        dryStandardStillHashes(),
    );

    expect($result['errors'])->toBe([]);
    expect($result['warnings'])->toContain('empty frame — no confirmed producer or editorial still');
});

it('warns when a still looks unlabeled', function () {
    $result = (new StillAudit)->inspect(
        dryStandardStillReview('dc-brau-pale-ale'),
        dryStandardStillHashes(),
    );

    expect($result['warnings'])->toContain('label area looks sparse — possible unlabeled mockup or cropped logo');
});
