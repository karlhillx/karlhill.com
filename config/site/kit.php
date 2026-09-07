<?php

return [
    'eyebrow' => 'For recruiters & hiring managers',
    'lede' => 'A leave-behind: PDF, short bio, and the links a hiring manager needs. Evidence is on Work and the resume; booking is one click away.',
    'highlights' => [
        'Primary ask: Engineering Manager in mission software (Staff/Principal IC when the mandate is platforms and standards).',
        'Proof: Jacobs National Security (current) + public NASA Goddard platforms.',
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
