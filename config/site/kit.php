<?php

$person = require __DIR__.'/person.php';

return [
    'eyebrow' => 'For recruiters & hiring managers',
    'lede' => 'What Karl is open to, selected evidence, and a resume to forward.',
    // Identity only. Current-role outcomes live in the Jacobs case study.
    'glance' => [
        $person['bio'],
    ],
    'scope' => [
        [
            'label' => 'Engineering',
            'body' => 'Python services, shared interfaces, messaging, tests, CI/CD, and release engineering.',
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
            'path' => '/research/global-flood-mapping',
        ],
        [
            'label' => 'Release governance',
            'path' => '/blog/release-governance',
            'meta' => 'Writing',
        ],
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
     | on screen, omitted in print. Case studies, the DOI, and the
     | release-governance essay live under evidence — do not repeat them here.
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
