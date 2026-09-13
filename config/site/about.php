<?php

$facts = require __DIR__.'/facts.php';

return [
    'lede' => [
        'Software engineer and technical leader working on aerospace mission software at Jacobs. Previously spent eight years building and modernizing NASA Goddard Earth science systems.',
        'The work has grown from building software other people depend on to shaping the engineering systems, technical direction, and team practices that make reliable delivery possible.',
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
                    'Establishes engineering guardrails through CI/CD, automated testing, security checks, repository standards, dependency management, and release automation.',
                    'Works across organizational boundaries to surface technical risk early and drive issues through resolution.',
                ],
            ],
            [
                'title' => 'Lead Software Engineer',
                'org' => 'SSAI / NASA Goddard Space Flight Center · '.$facts['nasa_period'],
                'summary' => 'Built and modernized Earth science systems used for satellite-data access, flood mapping, and public science communication.',
                'highlights' => [
                    'Led development of an AWS-based flood-mapping system for automated processing and distribution of satellite-derived flood products.',
                    'Modernized LAADS DAAC search, ordering, archive, and near-real-time data access systems using GitLab CI/CD and Kubernetes.',
                    'Modernized NASA Earth Observatory\'s web platform, supporting an audience of approximately 1.5 million monthly visitors during that work.',
                ],
            ],
        ],
        'earlier' => [
            'title' => 'Earlier engineering work',
            'body' => 'Before NASA, built case-management, CRM, travel, and enterprise software across healthcare, consulting, and commercial environments.',
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
