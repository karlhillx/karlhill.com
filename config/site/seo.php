<?php

return [
    // Homepage <title> disambiguates in search. The H1 stays the name.
    // Interior pages use "{Page} — Karl Hill". Employer and programs stay
    // in the description and JSON-LD, not in interior titles.
    'home' => [
        'title' => 'Karl Hill · Staff Aerospace Software Engineer',
        'description' => 'Karl Hill, Staff Aerospace Software Engineer. National security, aerospace, and defense mission software, NASA Goddard, and independent products.',
        'og_description' => 'Staff Aerospace Software Engineer at Jacobs. NASA Goddard, mission software, and products built end to end.',
    ],
    'blog_index' => [
        'title' => 'Writing — Karl Hill',
        'description' => 'Essays by Karl Hill on software engineering, technical leadership, testing, integration, and the work of delivering reliable software.',
        'og_description' => 'Practical notes on engineering teams, software delivery, and the systems around the code.',
    ],
    'work' => [
        'title' => 'Projects',
        'description' => 'Engineering projects: national security and aerospace mission software, NASA Earth science systems, open source, and The Dry Standard.',
        'og_description' => 'Case studies across mission software, NASA public systems, open-source tools, and an independent product.',
    ],
    'about' => [
        'title' => 'About — Karl Hill',
        'description' => 'Karl Hill (Karl M. Hill): Staff Aerospace Software Engineer at Jacobs. Cross-program technical leadership, hands-on engineering, and NASA research.',
        'og_description' => 'Karl Hill: Staff Aerospace Software Engineer at Jacobs, NASA flood-mapping co-author, and musician and songwriter. Washington, DC.',
    ],
    'privacy' => [
        'title' => 'Privacy — Karl Hill',
        'description' => 'How karlhill.com handles email and analytics. No ads, no account system, no selling of visitor data.',
        'og_description' => 'Email and analytics on karlhill.com — concise and specific.',
    ],
    'experience' => [
        'title' => 'Experience',
        'description' => 'Karl Hill’s engineering career: Jacobs National Security, NASA Goddard, and earlier enterprise systems.',
        'og_description' => 'Career progression across national security, aerospace, NASA, and enterprise software.',
    ],
    'open_source' => [
        'title' => 'Open Source',
        'description' => 'Open-source tools by Karl Hill: local pipeline runs, test-gap ranking, and policy checks for CI.',
        'og_description' => 'bb-run, testrisk, and pipeguard — small tools for feedback, test risk, and pipeline policy.',
    ],
    'resume' => [
        'title' => 'Resume — Karl Hill',
        'description' => 'Karl Hill: Staff Aerospace Software Engineer. 25+ years of mission software, cross-program technical leadership, platform engineering, and NASA systems.',
        'og_description' => 'Karl Hill resume: software engineering, NASA flood mapping, technical skills, education, and credentials.',
    ],
    'research' => [
        'title' => 'NASA Global Water and Flood Mapping Research',
        'description' => 'Karl Hill: NASA Global Water and Flood Mapping research. GeoHorizons 2026 co-author. Paper evaluation: >90% accuracy, ~3.5% false positives.',
        'og_description' => 'Karl Hill: NASA Global Water and Flood Mapping System. GeoHorizons co-author, Software (Equal). Live GWFMS map and the engineering behind it.',
    ],
];
