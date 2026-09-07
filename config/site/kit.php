<?php

return [
    'eyebrow' => 'For recruiters & hiring managers',
    'lede' => 'A leave-behind for Engineering Manager searches: PDF, short bio, and the links that prove current Staff leadership scope. Evidence is on Work and the resume; booking is one click away.',
    // Ask lives in person.availability (Open to). Do not restate EM vs IC here.
    'highlights' => [
        'Proof: Jacobs — held a sprint when partner readiness lagged; sequenced prep instead of rework (~10 engineers, ~20 repos).',
        'Packet: this page + resume PDF + /about#delivery for how I run delivery.',
    ],
    /*
     | Canonical outbound links for the kit (and its print leave-behind).
     | group => primary: first-pass skim. group => more: collapsed on screen,
     | always expanded in print.
     */
    'links' => [
        [
            'label' => null,
            'type' => 'booking',
            'path' => '/now#book',
            'meta' => '#book',
            'group' => 'primary',
        ],
        [
            'label' => 'Resume PDF',
            'type' => 'pdf',
            'meta' => 'Download',
            'download' => true,
            'group' => 'primary',
        ],
        [
            'label' => 'Selected work',
            'path' => '/work',
            'meta' => '/work',
            'group' => 'primary',
        ],
        [
            'label' => 'Current work — Aerospace mission software',
            'path' => '/work/jacobs-mission-software',
            'meta' => 'Current',
            'group' => 'primary',
        ],
        [
            'label' => 'Case study — LAADS DAAC',
            'path' => '/work/laads-daac',
            'meta' => 'Flagship',
            'group' => 'primary',
        ],
        [
            'label' => null,
            'type' => 'email',
            'meta' => 'Email',
            'group' => 'primary',
        ],
        [
            'label' => 'Live resume (HTML)',
            'path' => '/resume',
            'meta' => '/resume',
            'group' => 'more',
        ],
        [
            'label' => 'About — leadership & delivery',
            'path' => '/about',
            'meta' => '/about',
            'group' => 'more',
        ],
        [
            'label' => 'How I run delivery',
            'path' => '/about#delivery',
            'meta' => 'Packet',
            'group' => 'more',
        ],
        [
            'label' => 'Writing',
            'path' => '/blog',
            'meta' => '/blog',
            'group' => 'more',
        ],
        [
            'label' => 'Case study — Flood Mapping System',
            'path' => '/work/flood-mapping-system',
            'meta' => 'Flagship',
            'group' => 'more',
        ],
        [
            'label' => 'LinkedIn',
            'social' => 'linkedin',
            'meta' => 'Profile',
            'external' => true,
            'group' => 'more',
        ],
        [
            'label' => 'GitHub',
            'social' => 'github',
            'meta' => 'Code',
            'external' => true,
            'group' => 'more',
        ],
        [
            'label' => 'bb-run',
            'url' => 'https://github.com/karlhillx/bb-run',
            'meta' => 'Python',
            'external' => true,
            'group' => 'more',
        ],
    ],
];
