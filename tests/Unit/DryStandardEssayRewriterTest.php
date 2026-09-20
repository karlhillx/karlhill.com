<?php

use DryStandard\EssayRewriter;
use DryStandard\Review;

it('detects known AI filler in review essays', function () {
    $rewriter = new EssayRewriter;
    expect($rewriter->needsRewrite('Structural authenticity is the open question: body versus gesture.'))->toBeTrue();
    expect($rewriter->needsRewrite('Clean essay with no filler phrases.'))->toBeFalse();
});

it('rebuilds an essay from frontmatter without inventing notes or a duplicate heading', function () {
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
    expect($result['body'])->toContain('lemon and wet stone');
    expect($result['body'])->toContain('Vacuum distillation');
    expect($result['body'])->not->toContain('## The wine');
});

it('sanitizes contaminated nose fields and flags them for repair', function () {
    $matter = [
        'title' => 'Dr. Lo Alcohol-Removed Riesling',
        'slug' => 'dr-lo-alcohol-removed-riesling',
        'brand' => 'Dr. Lo',
        'product' => 'Alcohol-Removed Riesling',
        'category' => 'wine',
        'country' => 'Germany',
        'region' => 'Mosel',
        'abv' => '<0.5%',
        'production_type' => 'dealcoholized',
        'verified' => 'yes',
        'dealcoholization_method' => 'Vacuum distillation',
        'base_beverage' => 'Mosel Riesling',
        'status' => 'draft',
        'review_date' => '2026-09-20',
        'nose' => 'lime zest, green apple, and a cool slate line. It smells like Mosel Riesling first. On the palate, off-dry and bright. The finish is crisp citrus pith.',
        'palate' => 'Off-dry and bright. Citrus and stone fruit over a lighter body than Dr. L.',
        'finish' => 'Crisp citrus pith.',
        'verdict' => 'Mosel Riesling through a vacuum still.',
        'serve' => 'Well chilled.',
    ];
    $body = "## The wine\n\nDuplicated heading body.\n\nOn the palate, Off-dry. On the palate, Off-dry again.";
    $review = Review::fromMatter($matter, $body, '/tmp/dr-lo.md');
    $rewriter = new EssayRewriter;

    expect($rewriter->needsRepair($review))->toBeTrue();

    $result = $rewriter->rewrite($review);
    expect($result['nose'])->toBe('lime zest, green apple, and a cool slate line. It smells like Mosel Riesling first.');
    expect($result['body'])->not->toContain('## The wine');
    expect(substr_count(strtolower($result['body']), 'on the palate'))->toBe(1);
});

it('keeps intact editorial essays when only the heading or nose field is dirty', function () {
    $matter = [
        'title' => 'Valckenberg Zero Riesling',
        'slug' => 'valckenberg-zero-riesling',
        'brand' => 'Valckenberg',
        'product' => 'Zero Riesling',
        'category' => 'wine',
        'country' => 'Germany',
        'region' => 'Mosel',
        'abv' => '0.0%',
        'production_type' => 'dealcoholized',
        'verified' => 'yes',
        'status' => 'draft',
        'review_date' => '2026-09-20',
        'nose' => 'pineapple and lemon. The palate is crystal-clear. The finish is short.',
        'palate' => 'Crystal-clear fruit.',
        'finish' => 'Short.',
    ];
    $body = "## The wine\n\nHand-written essay stays.\n\nSecond paragraph stays.";
    $path = sys_get_temp_dir().'/valckenberg-zero-riesling-test.md';
    $front = "title: Valckenberg Zero Riesling\nslug: valckenberg-zero-riesling\nbrand: Valckenberg\nproduct: Zero Riesling\ncategory: wine\ncountry: Germany\nregion: Mosel\nabv: '0.0%'\nproduction_type: dealcoholized\nverified: 'yes'\nstatus: draft\nreview_date: '2026-09-20'\nnose: 'pineapple and lemon. The palate is crystal-clear. The finish is short.'\npalate: 'Crystal-clear fruit.'\nfinish: Short.";
    file_put_contents($path, "---\n{$front}\n---\n\n{$body}\n");
    $review = Review::fromMatter($matter, $body, $path);
    $rewriter = new EssayRewriter;

    expect($rewriter->applyToFile($path, $review))->toBeTrue();
    $written = file_get_contents($path);
    expect($written)->toContain('Hand-written essay stays.');
    expect($written)->not->toContain('## The wine');
    expect($written)->toContain("nose: pineapple and lemon.");
    @unlink($path);
});
