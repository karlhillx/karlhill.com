<?php

return [
    'eyebrow' => 'For recruiters & hiring managers',
    'lede' => 'A concise view of current scope, selected work, and career direction.',
    'glance' => [
        'Staff Aerospace Software Engineer at Jacobs working across Python mission software, distributed messaging, shared interfaces, CI/CD, and release engineering across roughly 20 repositories and multiple environments.',
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
            'label' => 'Current aerospace mission software and engineering systems',
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
            'label' => 'Engineering delivery and software process work',
            'path' => '/delivery',
        ],
        [
            'label' => 'Open-source developer tooling',
            'path' => '/work#open-source',
        ],
    ],
    'direction' => [
        'Growing toward broader ownership of architecture, engineering strategy, delivery, and team development.',
        'Engineering management is a natural next step where the role remains technically credible and close to software delivery. Principal-level technical leadership remains an equally strong path.',
    ],
    'contact_lede' => 'Schedule a conversation, view the resume, or explore selected work.',
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
            'meta' => 'Book',
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
            'label' => 'Aerospace mission software',
            'path' => '/work/jacobs-mission-software',
            'meta' => 'Current',
            'group' => 'primary',
        ],
        [
            'label' => 'Flood maps',
            'url' => 'https://floodmapping.gsfc.nasa.gov/',
            'meta' => 'Live',
            'group' => 'primary',
        ],
        [
            'label' => 'LAADS Find Data',
            'url' => 'https://ladsweb.modaps.eosdis.nasa.gov/search/',
            'meta' => 'Live',
            'group' => 'primary',
        ],
        [
            'label' => 'GeoHorizons paper',
            'url' => 'https://doi.org/10.1144/gh2025-7',
            'meta' => 'DOI',
            'group' => 'primary',
        ],
        [
            'label' => null,
            'type' => 'email',
            'meta' => 'Email',
            'group' => 'primary',
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
            'label' => 'How software gets delivered',
            'path' => '/#system',
            'meta' => 'Diagram',
            'group' => 'more',
        ],
        [
            'label' => 'Engineering delivery',
            'path' => '/delivery',
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
            'label' => 'Earth Observatory',
            'url' => 'https://earthobservatory.nasa.gov/',
            'meta' => 'Live',
            'group' => 'more',
        ],
        [
            'label' => 'Earth Observatory study',
            'path' => '/work/nasa-earth-observatory',
            'meta' => 'Case study',
            'group' => 'more',
        ],
        [
            'label' => 'Flood Mapping System',
            'path' => '/work/flood-mapping-system',
            'meta' => 'Case study',
            'group' => 'more',
        ],
        [
            'label' => 'LAADS DAAC',
            'path' => '/work/laads-daac',
            'meta' => 'Case study',
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
