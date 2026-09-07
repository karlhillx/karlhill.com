<?php

return [
    'headline' => 'Karl Hill',
    // Keywords stay in meta / JSON-LD — not a second headline on the first screen.
    'subtitle' => 'Aerospace Mission Software · Platform Engineering · DevSecOps · Technical Leadership',
    // Primary first-screen sentence: the hire ask, not a philosophy line.
    'positioning' => 'Seeking Engineering Manager roles in mission software — also open to Staff/Principal IC when the mandate is platform delivery and standards.',
    // First-screen proof: the NASA → Jacobs arc, not keyword soup.
    'arc' => [
        [
            'label' => 'NASA Goddard',
            'meta' => '2017–2025',
            'href' => '/work#chapters',
        ],
        [
            'label' => 'Jacobs National Security',
            'meta' => '2025–present',
            'href' => '/work/jacobs-mission-software',
        ],
    ],
    // Primary CTA is booking-aware in home/partials/hero.blade.php (Book → /now#book).
];
