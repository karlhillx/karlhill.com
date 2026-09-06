<?php

beforeEach(function () {
    config([
        'site.booking.url' => 'https://calendly.com/example',
        'site.booking.label' => 'Book a conversation',
        'site.booking.embed_src' => 'https://calendly.com/example?embed_domain=example.test&embed_type=Inline',
    ]);
});

it('tags booking and email CTAs in the footer with their placement', function () {
    $about = $this->get('/about')->assertOk()->getContent();

    expect($about)->toContain('data-analytics-event="booking_cta_clicked"')
        ->and($about)->toContain('data-analytics-location="footer"')
        ->and($about)->toContain('data-analytics-event="email_clicked"');

    $home = $this->get('/')->assertOk()->getContent();

    expect($home)->toContain('data-analytics-location="footer-home"')
        ->and($home)->toContain('data-analytics-location="hero"')
        ->and($home)->toContain('data-analytics-location="hero-availability"')
        ->and($home)->toContain('data-analytics-location="nav-mobile"');
});

it('tags case study cards with the project slug', function () {
    $work = $this->get('/work')->assertOk()->getContent();

    expect($work)->toContain('data-analytics-event="case_study_opened"')
        ->and($work)->toContain('data-analytics-project="laads-daac"');
});

it('tags recruiter kit links with placement and target', function () {
    $kit = $this->get('/kit')->assertOk()->getContent();

    expect($kit)->toContain('data-analytics-location="kit-actions"')
        ->and($kit)->toContain('data-analytics-location="kit-links"')
        ->and($kit)->toMatch('/data-analytics-target="[a-z0-9-]+"/');
});

it('renders the booking embed the completion listener hooks into', function () {
    $now = $this->get('/now')->assertOk()->getContent();

    expect($now)->toContain('class="booking-embed__frame"')
        ->and($now)->toContain('data-analytics-location="now-intro"')
        ->and($now)->toContain('data-analytics-location="now-embed-fallback"');
});
