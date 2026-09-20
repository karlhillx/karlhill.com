<?php

use DryStandard\Markdown;
use DryStandard\Referral;
use DryStandard\RetailPartners;

it('declares one site referral slug', function () {
    expect(Referral::slug())->toBe('the-dry-standard')
        ->and(Referral::SLUG)->toBe('the-dry-standard');
});

it('appends the referral slug to outbound http links', function () {
    expect(Referral::append('https://www.metrowinedc.com/'))
        ->toBe('https://www.metrowinedc.com/?ref=the-dry-standard')
        ->and(Referral::append('https://example.com/path?utm=1'))
        ->toBe('https://example.com/path?utm=1&ref=the-dry-standard')
        ->and(Referral::append('https://example.com/path?ref=already'))
        ->toBe('https://example.com/path?ref=already')
        ->and(Referral::append('/reviews/'))
        ->toBe('/reviews/')
        ->and(Referral::append('mailto:drinkdrystandard@gmail.com'))
        ->toBe('mailto:drinkdrystandard@gmail.com');
});

it('appends referral through markdown external links', function () {
    $html = Markdown::toHtml('See [Metro](https://www.metrowinedc.com/shop) and [about](../about/).');

    expect($html)
        ->toContain('href="https://www.metrowinedc.com/shop?ref=the-dry-standard"')
        ->toContain('href="../about/"')
        ->not->toContain('href="../about/?ref=');
});

it('builds partner hrefs from Referral', function () {
    expect(RetailPartners::href('Metro Wine & Spirits'))
        ->toBe('https://www.metrowinedc.com/?ref=the-dry-standard')
        ->and(RetailPartners::href('Brightwood Pizza & Bottle'))
        ->toBe('https://store.anxodc.com/?ref=the-dry-standard')
        ->and(RetailPartners::href('ANXO'))
        ->toBe('https://store.anxodc.com/?ref=the-dry-standard')
        ->and(RetailPartners::href('Total Wine'))->toBeNull();
});

it('linkifies Metro Wine & Spirits in availability prose', function () {
    $html = RetailPartners::linkifyAvailability('US retail including Metro Wine & Spirits; direct from drinkuntitled.com');

    expect($html)
        ->toContain('href="https://www.metrowinedc.com/?ref=the-dry-standard"')
        ->toContain('data-analytics-event="outbound_retail"')
        ->toContain('Metro Wine &amp; Spirits');
});

it('linkifies Brightwood Pizza and ANXO aliases', function () {
    $html = RetailPartners::linkifyAvailability('DC bottle shop: Brightwood Pizza & Bottle / ANXO Cider');

    expect($html)
        ->toContain('href="https://store.anxodc.com/?ref=the-dry-standard"')
        ->toContain('Brightwood Pizza &amp; Bottle')
        ->toContain('ANXO Cider')
        ->not->toContain('>ANXO</a>'); // longer alias wins first; bare ANXO not left to rematch inside ANXO Cider

    $listing = RetailPartners::linkifyAvailability('Brightwood Pizza & Bottle / ANXO');
    expect($listing)
        ->toContain('Brightwood Pizza &amp; Bottle')
        ->toContain('data-retailer="ANXO"')
        ->toContain('href="https://store.anxodc.com/?ref=the-dry-standard"');
});
