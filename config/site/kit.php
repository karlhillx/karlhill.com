<?php

return [
    'eyebrow' => 'For recruiters & hiring managers',
    'lede' => 'A leave-behind: PDF, short bio, and canonical links. Status lives on Now. Evidence lives on the resume.',
    'highlights' => [],
    /*
     | Canonical outbound links for the kit (and its print leave-behind).
     | `path` is site-relative; `url` is an absolute outbound href;
     | `social` resolves from config('site.social');
     | `type` => pdf uses the footer resume path.
     */
    'links' => [
        [
            'label' => 'Resume PDF',
            'type' => 'pdf',
            'meta' => 'Download',
            'download' => true,
        ],
        [
            'label' => 'Live resume (HTML)',
            'path' => '/resume',
            'meta' => '/resume',
        ],
        [
            'label' => 'Now — focus & booking',
            'path' => '/now',
            'meta' => '/now',
        ],
        [
            'label' => 'How I run delivery',
            'path' => '/lead',
            'meta' => 'Packet',
        ],
        [
            'label' => null, // filled from booking.label at render time
            'type' => 'booking',
            'path' => '/now#book',
            'meta' => '#book',
        ],
        [
            'label' => 'LinkedIn',
            'social' => 'linkedin',
            'meta' => 'Profile',
            'external' => true,
        ],
        [
            'label' => 'GitHub',
            'social' => 'github',
            'meta' => 'Code',
            'external' => true,
        ],
        [
            'label' => 'bb-run',
            'url' => 'https://github.com/karlhillx/bb-run',
            'meta' => 'Python',
            'external' => true,
        ],
        [
            'label' => 'Current work — Aerospace mission software',
            'path' => '/work/jacobs-mission-software',
            'meta' => 'Current',
        ],
        [
            'label' => 'Case study — LAADS DAAC',
            'path' => '/work/laads-daac',
            'meta' => 'Flagship',
        ],
        [
            'label' => 'Case study — Flood Mapping System',
            'path' => '/work/flood-mapping-system',
            'meta' => 'Flagship',
        ],
        [
            'label' => null, // person email
            'type' => 'email',
            'meta' => 'Email',
        ],
    ],
];
