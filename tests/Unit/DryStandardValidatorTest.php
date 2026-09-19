<?php

use DryStandard\Review;
use DryStandard\ReviewValidator;
use DryStandard\SiteConfig;

function dryStandardConfig(): SiteConfig
{
    return new SiteConfig([
        'categories' => [
            'wine' => 'Wine',
            'beer' => 'Beer',
            'spirits' => 'Spirits',
            'cocktails' => 'Cocktails',
            'cider' => 'Cider',
        ],
    ]);
}

function dryStandardReview(array $overrides = []): Review
{
    return Review::fromMatter([
        'title' => 'Test Wine',
        'slug' => 'test-wine',
        'brand' => 'Test',
        'product' => 'Wine',
        'category' => 'wine',
        'abv' => '0.0%',
        'dealcoholized' => 'yes',
        'dealcoholization_method' => 'Vacuum distillation',
        'summary' => 'A test.',
        'verdict' => 'Fine.',
        'nose' => 'Citrus.',
        'palate' => 'Bright.',
        'finish' => 'Short.',
        'status' => 'validated',
        'review_date' => '2026-09-18',
        'rating' => 80,
        'sources' => [
            [
                'title' => 'Producer',
                'url' => 'https://example.com/wine',
                'claims' => ['abv', 'method', 'dealcoholized'],
            ],
        ],
        ...$overrides,
    ], 'Overview', '/tmp/test-wine.md');
}

it('accepts a fully sourced dealcoholized review', function () {
    $errors = (new ReviewValidator)->errors(dryStandardReview(), dryStandardConfig(), forPublish: true);

    expect($errors)->toBe([]);
});

it('rejects an ABV claim without a source', function () {
    $review = dryStandardReview([
        'sources' => [
            [
                'title' => 'Producer',
                'url' => 'https://example.com/wine',
                'claims' => ['method', 'dealcoholized'],
            ],
        ],
    ]);

    $errors = (new ReviewValidator)->errors($review, dryStandardConfig());

    expect($errors)->toContain('ABV is set but no source claims abv');
});

it('rejects a product above the 0.5 percent ceiling', function () {
    $review = dryStandardReview(['abv_numeric' => 4.2]);

    $errors = (new ReviewValidator)->errors($review, dryStandardConfig());

    expect($errors)->toContain('abv_numeric exceeds the 0.5% editorial ceiling; do not publish over-limit products');
});

it('rejects a missing image file when one is declared', function () {
    $review = dryStandardReview(['image' => 'media/reviews/does-not-exist.jpg']);

    $errors = (new ReviewValidator)->errors($review, dryStandardConfig());

    expect($errors)->toContain('image file is missing: media/reviews/does-not-exist.jpg');
});

it('rejects dates in slugs', function () {
    $review = dryStandardReview(['slug' => 'test-wine-2026-09-18']);

    $errors = (new ReviewValidator)->errors($review, dryStandardConfig());

    expect($errors)->toContain('slug must not include dates');
});

it('maps a formulated spirit to alternative rather than a failed dealcoholized test', function () {
    $review = dryStandardReview([
        'dealcoholized' => 'no',
        'dealcoholized_note' => 'Formulated as a zero-proof alternative',
    ]);

    expect($review->productionType)->toBe('alternative')
        ->and($review->verified)->toBe('yes')
        ->and($review->productionTypeShortLabel())->toBe('Alternative')
        ->and($review->productionTypeLabel())->not->toContain('Dealcoholized: No');
});

it('keeps naturally low alcohol separate from alternative', function () {
    $review = dryStandardReview([
        'production_type' => 'naturally-low-alcohol',
        'verified' => 'yes',
    ]);

    expect($review->productionType)->toBe('naturally-low-alcohol')
        ->and($review->productionTypeShortLabel())->toBe('Naturally low alcohol');
});
