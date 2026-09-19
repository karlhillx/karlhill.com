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
        dryStandardStillReview('be-free-chardonnay'),
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
    $result = (new StillAudit)->inspect(
        dryStandardStillReview('biagio-cru-rose-all-day'),
        dryStandardStillHashes(),
    );

    expect($result['errors'])->toContain('lifestyle or multi-object scene, not a single SKU on paper');
});

it('warns when a still looks unlabeled', function () {
    $result = (new StillAudit)->inspect(
        dryStandardStillReview('dc-brau-pale-ale'),
        dryStandardStillHashes(),
    );

    expect($result['warnings'])->toContain('label area looks sparse — possible unlabeled mockup or cropped logo');
});
