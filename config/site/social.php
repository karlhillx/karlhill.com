<?php

return [
    0 => [
        'label' => 'LinkedIn',
        'url' => 'https://www.linkedin.com/in/khill/',
        'icon' => 'linkedin',
    ],
    1 => [
        'label' => 'GitHub',
        'url' => 'https://github.com/karlhillx',
        'icon' => 'github',
    ],
    2 => [
        'label' => 'X / Twitter',
        'url' => 'https://twitter.com/karl_hill/',
        'icon' => 'twitter',
    ],
    3 => [
        'label' => 'ORCID',
        'url' => 'https://orcid.org/0009-0002-6847-3368',
        'icon' => 'orcid',
    ],
    4 => [
        'label' => 'ResearchGate',
        'url' => 'https://www.researchgate.net/profile/Karl-Hill-2',
        'icon' => 'researchgate',
    ],
    5 => [
        'label' => 'Google Scholar',
        'url' => 'https://scholar.google.com/citations?user=ykw3hstDPLcC',
        'icon' => 'scholar',
    ],
    6 => [
        'label' => 'Discogs',
        'url' => 'https://www.discogs.com/artist/1286669-Karl-Hill?superFilter=Credits&sort=year,desc',
        'icon' => 'discogs',
        // Canonical artist page for JSON-LD; the href above keeps the credits view.
        'same_as' => 'https://www.discogs.com/artist/1286669-Karl-Hill',
    ],
    // Entity sameAs only — not footer icons. Gravatar and about.me confirmed as
    // this Karl Hill. Crunchbase slug supplied.
    7 => [
        'label' => 'Gravatar',
        'url' => 'https://gravatar.com/karlhillx',
        'icon' => 'gravatar',
        'footer' => false,
    ],
    8 => [
        'label' => 'Crunchbase',
        'url' => 'https://www.crunchbase.com/person/karl-hill-09bb',
        'icon' => 'crunchbase',
        'footer' => false,
    ],
    9 => [
        'label' => 'About.me',
        'url' => 'https://about.me/karlhill/',
        'icon' => 'aboutme',
        'footer' => false,
    ],
];
