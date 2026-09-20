<?php

use DryStandard\ArchiveQuery;
use DryStandard\ComparableSnapshot;
use DryStandard\Registry;
use DryStandard\Review;
use DryStandard\ReviewValidator;
use DryStandard\Sensory;
use DryStandard\SiteConfig;

it('loads descriptor aliases from yaml', function () {
    Sensory::reset();

    expect(Sensory::resolveDescriptor('granny smith'))->toBe('green_apple')
        ->and(Sensory::resolveDescriptor('cacao'))->toBe('cocoa')
        ->and(Sensory::resolveDescriptor('blood orange'))->toBe('blood_orange')
        ->and(Sensory::resolveDescriptor('watermelon'))->toBe('watermelon');
});

it('normalizes provenance confidence aliases', function () {
    expect(Sensory::normalizeConfidence('bottle_verified'))->toBe('label_verified')
        ->and(Sensory::normalizeConfidence('producer_verified'))->toBe('manufacturer_verified')
        ->and(Sensory::isAllowedConfidence('independently_corroborated'))->toBeTrue();
});

it('normalizes abv labels without inventing facts', function () {
    expect(Registry::normalizeAbv('0.3%', 0.3))->toBe([
        'label' => '0.3%',
        'numeric' => 0.3,
        'qualifier' => 'exact',
    ])
        ->and(Registry::normalizeAbv('<0.1%', 0.1))->toBe([
            'label' => '<0.1%',
            'numeric' => 0.1,
            'qualifier' => 'less_than',
        ])
        ->and(Registry::normalizeAbv('<0.5%', 0.5))->toBe([
            'label' => '<0.5%',
            'numeric' => 0.5,
            'qualifier' => 'less_than',
        ])
        ->and(Registry::normalizeAbv('0.5%', 0.5))->toBe([
            'label' => '0.5%',
            'numeric' => 0.5,
            'qualifier' => 'exact',
        ])
        ->and(Registry::normalizeAbv('0.0%', 0.0))->toBe([
            'label' => '0.0%',
            'numeric' => 0.0,
            'qualifier' => 'exact',
        ])
        ->and(Registry::normalizeAbv('Not published', null))->toBe([
            'label' => 'Not published',
            'numeric' => null,
            'qualifier' => 'unpublished',
        ])
        ->and(Registry::abvQualifier('0.33%'))->toBe('exact')
        ->and(Registry::abvQualifier('<0.5%'))->toBe('less_than');
});

it('maps empty dealcoholization methods to unpublished facet', function () {
    $review = Review::fromMatter([
        'title' => 'Test',
        'slug' => 'test-method',
        'brand' => 'Test',
        'product' => 'Wine',
        'category' => 'wine',
        'production_type' => 'dealcoholized',
        'verified' => 'yes',
        'abv' => '0.0%',
        'summary' => 'A',
        'verdict' => 'B',
        'status' => 'draft',
        'review_date' => '2026-09-18',
        'sources' => [['title' => 'P', 'url' => 'https://example.com', 'claims' => ['abv', 'dealcoholized']]],
    ], '', '/tmp/test-method.md');

    expect($review->methodFacetKey())->toBe('unpublished');
});

it('expands search text with abv and descriptors', function () {
    $review = Review::fromMatter([
        'title' => 'Coffee Stout',
        'slug' => 'coffee-stout',
        'brand' => 'Test',
        'product' => 'Stout',
        'category' => 'beer',
        'production_type' => 'dealcoholized',
        'verified' => 'yes',
        'abv' => '0.0%',
        'dealcoholization_method' => 'Cold filtration',
        'sensory' => [['descriptor' => 'coffee', 'locations' => ['palate']]],
        'summary' => 'Dark.',
        'verdict' => 'Good.',
        'status' => 'draft',
        'review_date' => '2026-09-18',
        'sources' => [['title' => 'P', 'url' => 'https://example.com', 'claims' => ['abv', 'method', 'dealcoholized']]],
    ], '', '/tmp/coffee-stout.md');

    $search = $review->searchText();

    expect($search)->toContain('0.0%')
        ->and($search)->toContain('coffee')
        ->and($search)->toContain('membrane');
});

it('filters by acidity and descriptor via archive query', function () {
    $review = Review::fromMatter([
        'title' => 'Bright Wine',
        'slug' => 'bright-wine',
        'brand' => 'Test',
        'product' => 'Riesling',
        'category' => 'wine',
        'production_type' => 'dealcoholized',
        'verified' => 'yes',
        'abv' => '0.0%',
        'structure_scales' => ['acidity' => 3, 'sweetness' => 2],
        'sensory' => [['descriptor' => 'green_apple', 'locations' => ['nose']]],
        'summary' => 'A',
        'verdict' => 'B',
        'status' => 'published',
        'review_date' => '2026-09-18',
        'sources' => [['title' => 'P', 'url' => 'https://example.com', 'claims' => ['abv', 'dealcoholized']]],
    ], '', '/tmp/bright-wine.md');

    $match = ArchiveQuery::from(['acidity' => '3', 'descriptor' => 'green_apple']);
    $miss = ArchiveQuery::from(['acidity' => '0', 'descriptor' => 'coffee']);

    expect($match->matches($review))->toBeTrue()
        ->and($miss->matches($review))->toBeFalse();
});

it('builds a comparison snapshot from a review', function () {
    $review = Review::fromMatter([
        'title' => 'Compare Me',
        'slug' => 'compare-me',
        'brand' => 'Test',
        'product' => 'IPA',
        'category' => 'beer',
        'production_type' => 'alternative',
        'verified' => 'yes',
        'abv' => '0.0%',
        'rating' => 88,
        'price' => '$12',
        'structure_scales' => ['body' => 2],
        'summary' => 'A',
        'verdict' => 'B',
        'status' => 'published',
        'review_date' => '2026-09-18',
        'sources' => [['title' => 'P', 'url' => 'https://example.com', 'claims' => ['abv', 'dealcoholized', 'price']]],
    ], '', '/tmp/compare-me.md');

    $snap = ComparableSnapshot::fromReview($review);

    expect($snap->score)->toBe(88)
        ->and($snap->methodFacet)->toBe('not-applicable')
        ->and($snap->toArray()['structure']['body'])->toBe(2);
});

it('reclassifies retail and registry provenance kinds', function () {
    expect(Review::inferProvenanceKind('https://morewines.com/ohla-rosado/', 'Manufacturer notes'))
        ->toBe('retailer')
        ->and(Review::inferProvenanceKind('https://www.trademarkelite.com/trademark/detail', 'OHLA trademark'))
        ->toBe('research')
        ->and(Review::resolveProvenanceKind('unknown', 'https://morewines.com/ohla/', ''))
        ->toBe('retailer');
});

it('labels score bands for the public 100-point scale', function () {
    $review = Review::fromMatter([
        'title' => 'Banded',
        'slug' => 'banded',
        'brand' => 'Test',
        'product' => 'Wine',
        'category' => 'wine',
        'production_type' => 'dealcoholized',
        'verified' => 'yes',
        'abv' => '0.0%',
        'rating' => 74,
        'summary' => 'A',
        'verdict' => 'B',
        'status' => 'draft',
        'review_date' => '2026-09-18',
        'sources' => [['title' => 'P', 'url' => 'https://example.com', 'claims' => ['abv', 'dealcoholized']]],
    ], '', '/tmp/banded.md');

    expect($review->scoreBandLabel())->toBe('Recommended');
});

it('emits classification and length warnings without blocking', function () {
    $config = new SiteConfig([
        'categories' => [
            'wine' => 'Wine',
            'beer' => 'Beer',
            'spirits' => 'Spirits',
            'cocktails' => 'Cocktails',
            'cider' => 'Cider',
        ],
    ]);

    $review = Review::fromMatter([
        'title' => 'Alt',
        'slug' => 'alt-drink',
        'brand' => 'Test',
        'product' => 'Spirit',
        'category' => 'spirits',
        'production_type' => 'alternative',
        'verified' => 'yes',
        'abv' => '0.0%',
        'dealcoholization_method' => 'Spinning cone',
        'summary' => 'Short.',
        'verdict' => 'Too short.',
        'nose' => 'Brief.',
        'palate' => 'Also brief.',
        'finish' => 'Gone.',
        'status' => 'draft',
        'review_date' => '2026-09-18',
        'sources' => [['title' => 'P', 'url' => 'https://example.com', 'claims' => ['abv', 'method', 'dealcoholized', 'production_type']]],
    ], '', '/tmp/alt-drink.md');

    $validator = new ReviewValidator;
    $warnings = $validator->warnings($review);

    expect($warnings)->toContain('classification warning: production_type is alternative but dealcoholization_method is set')
        ->and($validator->errors($review, $config))->not->toContain('classification warning: production_type is alternative but dealcoholization_method is set');
});

it('suppresses highlight when it duplicates verdict', function () {
    $review = Review::fromMatter([
        'title' => 'Dup',
        'slug' => 'dup-highlight',
        'brand' => 'Test',
        'product' => 'Wine',
        'category' => 'wine',
        'production_type' => 'dealcoholized',
        'verified' => 'yes',
        'abv' => '0.0%',
        'verdict' => 'Same line used twice.',
        'highlight' => 'Same line used twice.',
        'summary' => 'A',
        'status' => 'draft',
        'review_date' => '2026-09-18',
        'sources' => [['title' => 'P', 'url' => 'https://example.com', 'claims' => ['abv', 'dealcoholized']]],
    ], '', '/tmp/dup-highlight.md');

    expect($review->distinctHighlight())->toBeNull();
});
