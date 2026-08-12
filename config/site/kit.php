<?php

return [
    'eyebrow' => 'Recruiter kit',
    'lede' => 'Hire packet for aerospace and mission software leadership — resume PDF, bio, and the links that matter.',
    'bio' => 'Eight years shipping NASA Earth science platforms under real operational pressure. Now leading cloud-native mission software delivery in aerospace at Jacobs — planning, DevSecOps, and release discipline across constrained environments. Targeting Engineering Manager and Staff+ platform leadership roles in aerospace, defense, and federal mission software.',
    'highlights' => [
        'Staff Aerospace Software Engineer at Jacobs — mission simulation & telemetry platforms',
        'Prior: Lead Software Engineer, SSAI / NASA Goddard — Earth science platforms & flood mapping at scale',
        'Washington, DC metro · open to hybrid / remote-friendly mission software teams',
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
