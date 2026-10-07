<?php

$facts = require __DIR__.'/facts.php';

return [
    'lede' => [
        'Staff Aerospace Software Engineer at Jacobs combining hands-on engineering with cross-program technical leadership across multiple aerospace mission-software efforts. Previously eight years on NASA Goddard Earth science systems — flood maps, satellite-data access, and public science publishing.',
        'The work connects software other people depend on with the delivery practices, standards, and coaching around it.',
    ],
    'career' => [
        'title' => 'Career',
        'intro' => 'The common thread has been software that matters operationally — first enterprise systems, then NASA science platforms, and now aerospace mission software.',
        'roles' => [
            [
                'title' => 'Staff Aerospace Software Engineer',
                'org' => $facts['employer'].' · '.$facts['period'],
                'summary' => 'Hands-on engineering, shared interfaces, platform practices, and technical delivery across internal and partner teams. The core program spans '.$facts['repos'].' Python repositories and multiple deployment environments.',
                'highlights' => [
                    [
                        'text' => 'Cross-program integration strategy, architecture and design reviews, engineering standards, delivery coordination, and engineer development. Outcomes live in the current-work case study.',
                        'href' => '/work/jacobs-mission-software',
                        'link' => 'Read the case study',
                    ],
                ],
            ],
            [
                'title' => 'Lead Software Engineer',
                'org' => 'SSAI / NASA Goddard Space Flight Center · '.$facts['nasa_period'],
                'summary' => 'Earth science systems for flood mapping, satellite-data access, and public science publishing. The map, Find Data, and Earth Observatory are public.',
                'highlights' => [
                    [
                        'text' => 'Flood-mapping system on AWS for satellite-derived products. The live map is public.',
                        'href' => 'https://floodmapping.gsfc.nasa.gov/',
                        'link' => 'Open the map',
                    ],
                    [
                        'text' => 'Find Data search, ordering, and near-real-time access. Web delivery through GitLab CI/CD and Kubernetes alongside existing archive services.',
                        'href' => 'https://ladsweb.modaps.eosdis.nasa.gov/search/',
                        'link' => 'Open Find Data',
                    ],
                    [
                        'text' => 'Earth Observatory publishing platform — editorial workflows, imagery, and the public site.',
                        'href' => 'https://earthobservatory.nasa.gov/',
                        'link' => 'Open Earth Observatory',
                    ],
                ],
            ],
        ],
        'earlier' => [
            'title' => 'Earlier engineering work',
            'body' => 'Before NASA, built case-management, CRM, travel, and enterprise software across healthcare, consulting, and commercial environments. At Sabre that included new PHP applications and features on existing ones.',
        ],
        'cta_note' => 'The full history, technologies, education, and certifications are available on the resume.',
        'cta_label' => 'Full resume',
        'cta_href' => '/resume',
    ],
    'approach' => [
        'Implementation, reviews, and coaching belong in the same week. I work on Python services, shared interfaces, messaging, and CI/CD while helping teams turn program priorities into executable software.',
        'My scope is increasingly cross-program: technical direction, engineering systems, integration, architecture, delivery, and engineer development across team boundaries.',
    ],
    'beyond' => [
        'When not writing software, solving engineering problems, or working with a team, the other work is music: songwriter and musician across post-punk, indie rock, hardcore, and alternative. Independent label work has also supported underground and alternative artists in Washington, DC and beyond.',
        'Recording and performance credits are available on Discogs.',
    ],
];
