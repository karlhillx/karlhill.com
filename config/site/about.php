<?php

$facts = require __DIR__.'/facts.php';

return [
    'lede' => [
        'Software engineer and technical leader working on aerospace mission software at Jacobs. Previously eight years on NASA Goddard Earth science systems — flood maps, satellite-data access, and public science publishing.',
        'The work connects software other people depend on with the delivery practices, standards, and coaching around it.',
    ],
    'leadership' => [
        'title' => 'Technical leadership',
        'intro' => [
            'Technical leadership stays close to the code.',
            'Current responsibilities span hands-on development, technical direction, delivery coordination, integration across teams, engineering standards, and mentoring for '.$facts['team_engineers'].'.',
        ],
        'items' => [
            [
                'title' => 'Turn priorities into engineering work',
                'body' => 'Translates program needs into scoped, sequenced work with clear dependencies, ownership, and integration paths.',
            ],
            [
                'title' => 'Review for correctness and growth',
                'body' => 'Code review covers implementation, tests, interfaces, failure modes, and maintainability — while helping engineers understand the reasoning behind the feedback.',
            ],
            [
                'title' => 'Build standards into the system',
                'body' => 'Uses CI/CD, automated testing, security checks, repository standards, and release practices to make quality repeatable rather than dependent on individual memory.',
            ],
            [
                'title' => 'Develop independent engineers',
                'body' => 'Onboarding, mentoring, technical feedback, and delegation are used to expand ownership across the team rather than concentrating it in one person.',
            ],
        ],
        'note' => 'Formal personnel management remains with management; current leadership is technical, delivery-focused, and cross-team.',
    ],
    'delivery' => [
        'title' => 'Engineering delivery',
        'intro' => [
            'Reliable delivery is an engineering problem.',
            'A change is ready when another engineer can understand it, review it, rebuild it, test it, and see the evidence that supports releasing it.',
        ],
        'principles_lede' => 'The operating principles are straightforward:',
        'principles' => [
            'Define scope, ownership, dependencies, and interface assumptions early.',
            'Test meaningful behavior, including important failure cases.',
            'Automate quality, packaging, dependency, and security checks wherever practical.',
            'Exercise integration paths throughout development rather than waiting for the end.',
            'Keep releases small enough to understand, validate, and recover.',
            'Capture enough context that the next engineer does not have to reconstruct the decision.',
        ],
        'close' => 'The goal is not process for its own sake. It is predictable software delivery without creating a human bottleneck.',
        'cta_label' => 'How I run delivery',
        'cta_href' => '/delivery',
    ],
    'career' => [
        'title' => 'Career',
        'intro' => 'The common thread has been software that matters operationally — first enterprise systems, then NASA science platforms, and now aerospace mission software.',
        'roles' => [
            [
                'title' => 'Staff Aerospace Software Engineer',
                'org' => $facts['employer'].' · '.$facts['period'],
                'summary' => 'Hands-on engineer and technical delivery leader working across '.$facts['repos'].' repositories and multiple deployment environments.',
                'highlights' => [
                    'Leads day-to-day engineering delivery for a team of '.$facts['team'].', coordinating dependencies, integration work, and release readiness.',
                    'Develops Python mission software, shared interfaces, distributed messaging, and service orchestration.',
                    'Puts CI/CD, automated testing, security checks, repository standards, and release automation into the delivery path.',
                    'Works across organizational boundaries to surface technical risk early and follow issues through to a decision.',
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
    'numbers' => [
        'heading' => 'Experience in numbers',
        'items' => [
            [
                'display' => '20+',
                'label' => 'Years building software',
                'to' => 20,
                'prefix' => '',
                'suffix' => '+',
            ],
            [
                'display' => $facts['team_display'],
                'label' => 'Engineers on the current team',
                'to' => $facts['team_to'],
                'prefix' => '~',
                'suffix' => '',
            ],
            [
                'display' => $facts['onboarded_display'],
                'label' => 'Engineers onboarded and coached',
                'to' => $facts['onboarded_to'],
                'prefix' => '~',
                'suffix' => '',
            ],
            [
                'display' => $facts['repos_display'],
                'label' => 'Repositories across the current environment',
                'to' => $facts['repos_to'],
                'prefix' => '~',
                'suffix' => '',
            ],
            [
                'display' => $facts['visitors_display'],
                'label' => 'Monthly visitors · Earth Observatory',
                'to' => $facts['visitors_to'],
                'prefix' => '',
                'suffix' => 'M',
            ],
        ],
    ],
    'beyond' => [
        'When not writing software, solving engineering problems, or working with a team, the other work is music: songwriter and musician across post-punk, indie rock, hardcore, and alternative. Independent label work has also supported underground and alternative artists in Washington, DC and beyond.',
        'Recording and performance credits are available on Discogs.',
    ],
];
