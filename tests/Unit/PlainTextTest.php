<?php

use App\Support\PlainText;

it('strips tags and decodes entities', function () {
    $html = 'Cut costs by <strong>$30K</strong> annually.';

    expect(PlainText::fromHtml($html))->toBe('Cut costs by $30K annually.');
});

it('drops trailing case study link text', function () {
    $html = 'Delivered flood products. <a href="/work/flood-mapping-system">Case study</a>';

    expect(PlainText::fromHtml($html))->toBe('Delivered flood products.');
});

it('handles empty input', function () {
    expect(PlainText::fromHtml(null))->toBe('')
        ->and(PlainText::fromHtml(''))->toBe('');
});
