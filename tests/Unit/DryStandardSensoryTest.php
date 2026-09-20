<?php

use DryStandard\Sensory;

it('maps legacy profile chips onto structure scales', function () {
    $scales = Sensory::normalizeStructureScales([], [
        'Off-dry',
        'Light body',
        'Modest bitterness',
        'Short finish',
    ]);

    expect($scales['sweetness'])->toBe(2)
        ->and($scales['body'])->toBe(1)
        ->and($scales['bitterness'])->toBe(1)
        ->and($scales['finish_length'])->toBe(0);
});

it('resolves free-text tastes to canonical descriptors', function () {
    $sensory = Sensory::normalizeSensoryList([
        'roasted barley',
        'coffee grounds',
        'cocoa nib',
        'which is the compliment',
    ]);

    expect($sensory)->toHaveCount(3)
        ->and(collect($sensory)->pluck('descriptor')->all())->toBe([
            'roasted_barley',
            'coffee',
            'cocoa_nib',
        ]);
});

it('derives wine color from style vocabulary', function () {
    expect(Sensory::wineColor('riesling'))->toBe('white')
        ->and(Sensory::wineColor('sparkling-rose'))->toBe('sparkling-rose')
        ->and(Sensory::wineColor('pinot-noir'))->toBe('red');
});
