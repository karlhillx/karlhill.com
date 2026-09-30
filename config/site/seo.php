<?php

return [
    // Homepage <title> disambiguates in search. The H1 stays the name.
    // Interior pages use "{Page} — Karl Hill". Employer and programs stay
    // in the description and JSON-LD, not in interior titles.
    'home' => [
        'title' => 'Karl Hill · Staff Aerospace Software Engineer',
        'description' => 'Karl Hill: 30 years building reliable software. Aerospace leadership at Jacobs, NASA public systems, open-source tools, and The Dry Standard.',
        'og_description' => 'Explore the work: Jacobs mission software, NASA platforms, developer tools, and The Dry Standard. Engineering by Karl Hill.',
    ],
    'blog_index' => [
        'title' => 'Writing — Karl Hill',
        'description' => 'Essays by Karl Hill on software engineering, technical leadership, testing, integration, and the work of delivering reliable software.',
        'og_description' => 'Practical notes on engineering teams, software delivery, and the systems around the code.',
    ],
    'work' => [
        'title' => 'Work — Karl Hill',
        'description' => 'Karl Hill’s engineering portfolio: Jacobs mission software, NASA Earth science platforms, open-source developer tools, and The Dry Standard.',
        'og_description' => 'Case studies, public systems, source code, and research. Mission software to independently built products.',
    ],
    'about' => [
        'title' => 'About — Karl Hill',
        'description' => 'Karl Hill (Karl M. Hill), Staff Aerospace Software Engineer at Jacobs. NASA Goddard Earth science 2017–2025; GeoHorizons co-author on NASA flood mapping.',
        'og_description' => 'Karl Hill: Staff Aerospace Software Engineer at Jacobs, NASA flood-mapping co-author, and musician and songwriter. Washington, DC.',
    ],
    'privacy' => [
        'title' => 'Privacy — Karl Hill',
        'description' => 'How karlhill.com handles contact messages, booking, and analytics. No ads, no account system, no selling of visitor data.',
        'og_description' => 'Contact, booking, and analytics on karlhill.com — concise and specific.',
    ],
    'resume' => [
        'title' => 'Resume — Karl Hill',
        'description' => 'Karl Hill: Staff Aerospace Software Engineer at Jacobs. NASA Earth science, GeoHorizons flood mapping, Python, technical leadership, and software delivery.',
        'og_description' => 'Karl Hill resume: software engineering, NASA flood mapping, technical skills, education, and credentials.',
    ],
    'research' => [
        'title' => 'NASA Global Water and Flood Mapping Research',
        'description' => 'Karl Hill: NASA Global Water and Flood Mapping research. GeoHorizons 2026 co-author. Paper evaluation: >90% accuracy, ~3.5% false positives.',
        'og_description' => 'Karl Hill: NASA Global Water and Flood Mapping System. GeoHorizons co-author, Software (Equal). Live GWFMS map and the engineering behind it.',
    ],
];
