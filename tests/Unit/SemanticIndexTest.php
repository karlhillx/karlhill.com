<?php

use App\Support\SemanticIndex;

it('builds a normalized sparse vector from prose', function () {
    $vector = SemanticIndex::vector('Release governance is a decision. Governance beats heroics. Release.');

    expect($vector)->toBeArray()->not->toBeEmpty()
        ->and(array_key_exists('governance', $vector) || array_key_exists('release', $vector))->toBeTrue();

    $norm = 0.0;
    foreach ($vector as $weight) {
        $norm += $weight * $weight;
    }
    expect($norm)->toEqualWithDelta(1.0, 0.02);
});

it('ranks related documents above unrelated ones', function () {
    $query = SemanticIndex::vector('flood mapping satellite disaster response');
    $flood = SemanticIndex::vector('Near real-time flood mapping from satellite sensors during disasters');
    $scrum = SemanticIndex::vector('Certified ScrumMaster professional development workshop');

    expect(SemanticIndex::cosine($query, $flood))
        ->toBeGreaterThan(SemanticIndex::cosine($query, $scrum));
});

it('command index embeds term vectors', function () {
    $json = $this->get('/api/commands.json')->assertOk()->json();

    expect($json['posts'])->toBeArray()->not->toBeEmpty()
        ->and($json['posts'][0])->toHaveKey('terms');
});
