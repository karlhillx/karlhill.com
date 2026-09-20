<?php

use DryStandard\EssayRewriter;
use DryStandard\Review;

it('detects known AI filler in review essays', function () {
    $rewriter = new EssayRewriter;
    expect($rewriter->needsRewrite('Structural authenticity is the open question: body versus gesture.'))->toBeTrue();
    expect($rewriter->needsRewrite('Clean essay with no filler phrases.'))->toBeFalse();
});

it('rebuilds an essay from frontmatter without inventing notes', function () {
    $matter = [
        'title' => 'Test Bottle',
        'slug' => 'test-bottle',
        'brand' => 'Test',
        'product' => 'Bottle',
        'category' => 'wine',
        'country' => 'France',
        'abv' => '<0.5%',
        'production_type' => 'dealcoholized',
        'verified' => 'yes',
        'dealcoholization_method' => 'Vacuum distillation',
        'base_beverage' => 'Sauvignon Blanc',
        'status' => 'draft',
        'review_date' => '2026-09-20',
        'nose' => 'Lemon and wet stone.',
        'palate' => 'Bright acid, light body.',
        'finish' => 'Short and clean.',
        'mouthfeel' => 'Lean and crisp.',
        'verdict' => 'Honest light white.',
        'serve' => 'Well chilled.',
    ];
    $body = "Junk.\n\nOn the palate, bright acid, light body. That is the mouthfeel story as well: whatever body, fizz, grip, or softness the sip already has.\n\nStructural authenticity is the open question: body, bitterness, dryness, or heat versus a thin gesture at wine. Serve it cold and judge the glass.";
    $review = Review::fromMatter($matter, $body, '/tmp/test-bottle.md');
    $result = (new EssayRewriter)->rewrite($review);

    expect($result['changed'])->toBeTrue();
    expect($result['body'])->not->toContain('Structural authenticity is the open question');
    expect($result['body'])->toContain('Lemon and wet stone');
    expect($result['body'])->toContain('Vacuum distillation');
    expect($result['body'])->toContain('## The wine');
});
