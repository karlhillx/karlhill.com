<?php

return [
    'eyebrow' => 'For recruiters & hiring managers',
    'lede' => 'Current engineering system, public NASA software, and resume.',
    // Glance bio is person.bio: work-first, no LinkedIn opener.
    'highlights' => [
        'Flood maps, LAADS Find Data, Earth Observatory, and the GeoHorizons paper are public.',
        'At Jacobs the work spans about 20 repositories: interfaces, tests, CI, and release. About six engineers have been onboarded and coached.',
        'Engineering Manager is the next container for this scope. Principal-level technical work remains a parallel path.',
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
            'label' => 'About Karl',
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
