<?php

it('renders a self-contained maintenance message without deployment assets', function () {
    $html = view('errors.503')->render();

    expect($html)
        ->toContain('A brief pause while I deploy.')
        ->toContain('Please try again shortly.')
        ->toContain('Try the homepage again')
        ->toContain('class="status-dot" aria-hidden="true"')
        ->toContain('@media (prefers-reduced-motion: no-preference)')
        ->toContain('animation: status-pulse 2.8s')
        ->not->toContain('/build/')
        ->not->toContain('<script')
        ->not->toContain('<link');
});
