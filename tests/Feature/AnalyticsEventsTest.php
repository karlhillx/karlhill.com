<?php

beforeEach(function () {
    config([
        'site.booking.url' => 'https://calendly.com/example',
        'site.booking.label' => 'Book a conversation',
        'site.booking.embed_src' => 'https://calendly.com/example?embed_domain=example.test&embed_type=Inline',
    ]);
});

it('tags the footer email with its placement', function () {
    $about = $this->get('/about')->assertOk()->getContent();

    expect($about)->not->toContain('data-analytics-event="booking_cta_clicked"')
        ->and($about)->toContain('data-analytics-location="footer"')
        ->and($about)->toContain('data-analytics-event="email_clicked"');

    $home = $this->get('/')->assertOk()->getContent();

    expect($home)->toContain('data-analytics-location="footer-home"')
        ->and($home)->not->toContain('data-analytics-event="booking_cta_clicked"')
        ->and($home)->not->toContain('data-analytics-target="kit"');
});

it('tags case study cards with the project slug', function () {
    $work = $this->get('/work')->assertOk()->getContent();

    expect($work)->toContain('data-analytics-event="case_study_opened"')
        ->and($work)->toContain('data-analytics-project="laads-daac"');
});

it('tags resume downloads with placement', function () {
    $resume = $this->get('/resume')->assertOk()->getContent();

    expect($resume)->toContain('data-analytics-location="resume-hero"')
        ->and($resume)->toContain('data-analytics-event="resume_downloaded"');
});

it('does not link or embed a scheduler', function () {
    $now = $this->get('/')->assertOk()->getContent();

    expect($now)->not->toContain('booking-embed__frame')
        ->and($now)->not->toContain('id="book"')
        ->and($now)->not->toContain('data-analytics-event="booking_cta_clicked"')
        ->and($now)->toContain('data-analytics-location="footer-home"')
        ->and($now)->not->toContain('https://calendly.com/example');

    expect(preg_match('~<iframe[^>]*\ssrc=~s', $now))->toBe(0);
});
