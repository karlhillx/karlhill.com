<?php

return [
    'eyebrow' => 'Recruiter kit',
    'lede' => 'Everything a hiring manager needs in one place — resume PDF, short bio, and the links that matter.',
    'bio' => 'Software engineering leader building secure, cloud-native platforms for aerospace, NASA, and mission-critical environments — from disaster-response systems to aerospace delivery at Jacobs. Targeting Engineering Manager and Staff+ leadership roles.',
    'highlights' => [
        'Staff Software Engineer at Jacobs (aerospace mission simulation & telemetry)',
        'Prior: SSAI / NASA Goddard — Earth science platforms & flood mapping at scale',
        'Washington, DC metro · hybrid / remote-friendly',
    ],
    /*
     | Canonical outbound links for the kit (and its print leave-behind).
     | `path` is site-relative; `social` resolves from config('site.social');
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
            'label' => 'Case study — NASA Earth Observatory',
            'path' => '/work/nasa-earth-observatory',
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
