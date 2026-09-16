<?php

return [
    'eyebrow' => 'For recruiters & hiring managers',
    'lede' => 'A concise view of current scope, selected work, and career direction.',
    'glance' => [
        'Staff Aerospace Software Engineer at Jacobs. Python mission software, distributed messaging, shared interfaces, CI/CD, and release engineering across roughly 20 repositories and multiple environments. Established at least 80% repository test coverage, two-approval pull-request governance, and automated quality gates; releases are safer and more predictable.',
        'Technical scope includes hands-on implementation, engineering standards, cross-team integration, delivery coordination, and mentoring for a team of about 10 engineers.',
        'Previously Lead Software Engineer supporting NASA Goddard Earth science systems, including flood mapping, LAADS DAAC, and Earth Observatory.',
    ],
    'scope' => [
        [
            'label' => 'Engineering',
            'body' => 'Python services, distributed messaging, shared interfaces, testing, CI/CD, release engineering, and developer workflows across roughly 20 repositories.',
        ],
        [
            'label' => 'Technical leadership',
            'body' => 'Coordinates integration and delivery across teams, helps set engineering standards and technical direction, anticipates cross-team issues, and drives problems through resolution.',
        ],
        [
            'label' => 'Team development',
            'body' => 'Mentors and coaches engineers, supports onboarding, delegates technical work, and helps translate program priorities into executable software.',
        ],
        [
            'label' => 'Operating model',
            'body' => 'Staff individual contributor with broad technical and delivery influence. Formal people-management responsibility remains with management.',
        ],
    ],
    'evidence' => [
        [
            'label' => 'Engineering mission software at scale',
            'path' => '/work/jacobs-mission-software',
        ],
        [
            'label' => 'NASA Flood Mapping System',
            'path' => '/work/flood-mapping-system',
        ],
        [
            'label' => 'LAADS DAAC',
            'path' => '/work/laads-daac',
        ],
        [
            'label' => 'NASA Earth Observatory',
            'path' => '/work/nasa-earth-observatory',
        ],
        [
            'label' => 'GeoHorizons research publication',
            'url' => 'https://doi.org/10.1144/gh2025-7',
        ],
        [
            'label' => 'Staff IC to Engineering Manager: first 90 days',
            'path' => '/blog/staff-to-em-first-90-days',
            'meta' => 'Writing',
        ],
        [
            'label' => 'Release governance',
            'path' => '/blog/release-governance',
            'meta' => 'Writing',
        ],
    ],
    'direction' => [
        'Growing toward broader ownership of architecture, engineering strategy, delivery, and team development.',
        'Principal-level technical leadership is the primary path. Engineering management is a strong next step where the role stays technically credible and close to software delivery.',
    ],
    'contact_lede' => 'Schedule a conversation, view the resume, or explore selected work.',
    'ask_prompts' => [
        'What is Karl open to?',
        'What is the current work?',
        'What public evidence is there?',
    ],
    /*
     | Canonical outbound links for the kit (and its print leave-behind).
     | group => primary: first-pass skim and print. group => more: collapsed
     | on screen, omitted in print. Case studies, the DOI, and the two
     | leadership essays live under evidence — do not repeat them here.
     | The writing index stays in more.
     */
    'links' => [
        [
            'label' => 'Resume PDF',
            'type' => 'pdf',
            'meta' => 'Download',
            'download' => true,
            'group' => 'primary',
        ],
        [
            'label' => 'LinkedIn',
            'social' => 'linkedin',
            'meta' => 'Profile',
            'external' => true,
            'group' => 'primary',
        ],
        [
            'label' => null,
            'type' => 'booking',
            'path' => '/now#book',
            'meta' => 'Book',
            'group' => 'primary',
        ],
        [
            'label' => 'Engineering mission software at scale',
            'path' => '/work/jacobs-mission-software',
            'meta' => 'Current',
            'group' => 'primary',
        ],
        [
            'label' => 'Selected work',
            'path' => '/work',
            'meta' => '/work',
            'group' => 'more',
        ],
        [
            'label' => 'Resume online',
            'path' => '/resume',
            'meta' => '/resume',
            'group' => 'more',
        ],
        [
            'label' => 'About Karl Hill',
            'path' => '/about',
            'meta' => '/about',
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
            'label' => null,
            'type' => 'email',
            'meta' => 'Email',
            'group' => 'more',
        ],
        [
            'label' => 'Writing',
            'path' => '/blog',
            'meta' => '/blog',
            'group' => 'more',
        ],
        [
            'label' => 'Engineering delivery',
            'path' => '/delivery',
            'meta' => 'Packet',
            'group' => 'more',
        ],
    ],
];
