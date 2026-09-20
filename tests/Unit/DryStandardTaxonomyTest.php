<?php

use DryStandard\ArchiveQuery;
use DryStandard\Review;
use DryStandard\Taxonomy;
use DryStandard\Workspace;

beforeEach(function () {
    Taxonomy::reset();
});

it('maps Leitz house names onto one slug', function () {
    expect(Taxonomy::brandSlug('Leitz'))->toBe('leitz')
        ->and(Taxonomy::brandSlug('Weingut Leitz'))->toBe('leitz')
        ->and(Taxonomy::brandSlug('Weingut Josef Leitz'))->toBe('leitz')
        ->and(Taxonomy::brandName('Weingut Josef Leitz'))->toBe('Leitz')
        ->and(Taxonomy::canonicalBrandSlug('weingut-leitz'))->toBe('leitz')
        ->and(Taxonomy::canonicalBrandSlug('p-j-valckenberg'))->toBe('valckenberg');
});

it('keeps Noughty as a Thomson & Scott product line', function () {
    expect(Taxonomy::brandSlug('Thomson & Scott'))->toBe('thomson-scott')
        ->and(Taxonomy::canonicalBrandSlug('noughty'))->toBeNull();
});

it('uses a closed style vocabulary and prefers sparkling rose', function () {
    expect(Taxonomy::styleSlug('Dealcoholized Riesling'))->toBe('riesling')
        ->and(Taxonomy::styleSlug('Sparkling rosé'))->toBe('sparkling-rose')
        ->and(Taxonomy::styleSlug('Hazy IPA'))->toBe('ipa')
        ->and(Taxonomy::hasStyle('other'))->toBeFalse();
});

it('marks formulated alternatives as method not-applicable', function () {
    $review = Review::fromMatter([
        'title' => 'Lyre\'s Italian Orange',
        'slug' => 'lyres-italian-orange',
        'brand' => 'Lyre\'s',
        'product' => 'Italian Orange',
        'category' => 'cocktails',
        'production_type' => 'alternative',
        'verified' => 'yes',
        'status' => 'published',
        'rating' => 82,
        'summary' => 'A mixer.',
        'verdict' => 'Fine.',
        'review_date' => '2026-09-18',
    ], 'Body', '/tmp/lyres.md');

    expect($review->methodFacetKey())->toBe('not-applicable')
        ->and($review->methodCardLabel())->toBe('Formulated alternative')
        ->and($review->verifiedLabel())->toBe('Evidence status: Documented');
});

it('strips a redundant brand from card titles and keeps Guinness 0.0', function () {
    $heineken = Review::fromMatter([
        'title' => 'Heineken 0.0',
        'slug' => 'heineken-0-0',
        'brand' => 'Heineken',
        'product' => '0.0',
        'category' => 'beer',
        'status' => 'published',
        'rating' => 53,
        'summary' => 'Empty.',
        'verdict' => 'Empty.',
        'review_date' => '2026-09-18',
    ], 'Body', '/tmp/heineken.md');
    $guinness = Review::fromMatter([
        'title' => 'Guinness 0.0',
        'slug' => 'guinness-0-0',
        'brand' => 'Guinness',
        'product' => 'Guinness 0.0',
        'category' => 'beer',
        'status' => 'published',
        'rating' => 91,
        'summary' => 'Stout.',
        'verdict' => 'Stout.',
        'review_date' => '2026-09-18',
    ], 'Body', '/tmp/guinness.md');

    expect($heineken->cardTitle())->toBe('0.0')
        ->and($guinness->cardTitle())->toBe('Guinness 0.0');
});

it('matches SQL archive filters to PHP filters', function () {
    $this->artisan('dry-standard:build')->assertSuccessful();

    $workspace = Workspace::default();
    $query = ArchiveQuery::from([
        'q' => 'leitz',
        'production' => 'dealcoholized',
        'country' => 'germany',
    ]);

    $sql = $workspace->reviews()->archive($query)->pluck('slug')->values()->all();
    $php = $query->apply($workspace->reviews()->listing())->pluck('slug')->values()->all();

    expect($sql)->toBe($php)->not->toBeEmpty();
});
