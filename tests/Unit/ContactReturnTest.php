<?php

use App\Support\ContactReturn;

it('allows public pages including article and case-study paths', function (string $candidate, string $expected) {
    expect(ContactReturn::path($candidate))->toBe($expected);
})->with([
    ['/', '/'],
    ['/now', '/now'],
    ['/about', '/about'],
    ['/resume', '/resume'],
    ['/kit', '/kit'],
    ['/work', '/work'],
    ['/work/', '/work'],
    ['/work/tag/laravel', '/work/tag/laravel'],
    ['/work/nasa-earth-observatory', '/work/nasa-earth-observatory'],
    ['/blog', '/blog'],
    ['/blog/staff-to-em-first-90-days', '/blog/staff-to-em-first-90-days'],
    ['/blog/tag/leadership', '/blog/tag/leadership'],
    ['https://evil.example/blog/staff-to-em-first-90-days', '/blog/staff-to-em-first-90-days'],
]);

it('rejects paths outside the public site', function (string $candidate) {
    expect(ContactReturn::path($candidate))->toBe('/');
})->with([
    '/clients/keithhillmusic.com',
    '/csrf-token',
    '/__a11y/contact-errors',
    '/../etc/passwd',
    '//example.com',
    '',
]);
