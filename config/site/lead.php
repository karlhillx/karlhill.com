<?php

return [
    'eyebrow' => 'Forward this page',
    'title' => 'Engineering delivery',
    'lede' => 'A change is ready when another engineer can review it, rebuild it, and see the evidence. Those expectations live in tests, CI, review, and coaching.',
    'updated' => 'September 10, 2026',
    'why' => 'The written bar is below. It has to survive beyond one person.',
    'sections' => [
        [
            'id' => 'done',
            'title' => 'Definition of Done',
            'intro' => 'A change is not done when it compiles on a laptop. It also needs evidence that another engineer can review and use.',
            'items' => [
                'The purpose, scope, and owner are clear.',
                'Tests cover the behavior that changed, including relevant failure cases.',
                'Required quality, packaging, dependency, and security checks pass.',
                'Interfaces and deployment assumptions have been checked, with remaining risks recorded.',
                'Versioning, release notes, and supporting documentation are ready for the next person.',
            ],
        ],
        [
            'id' => 'reviews',
            'title' => 'Pull request rubric',
            'intro' => 'Reviews should improve the change and help the author understand why.',
            'items' => [
                [
                    'title' => 'Correctness',
                    'body' => 'Does the implementation solve the intended problem, including boundary and failure cases?',
                ],
                [
                    'title' => 'Evidence',
                    'body' => 'Do the tests check meaningful behavior, rather than only exercising the code?',
                ],
                [
                    'title' => 'Maintainability',
                    'body' => 'Are the interfaces, dependencies, and error handling clear enough for another engineer to work with?',
                ],
                [
                    'title' => 'Context',
                    'body' => 'Does the change explain the important decisions and their effect on other parts of the system?',
                ],
            ],
        ],
        [
            'id' => 'risk',
            'title' => 'Make integration risk visible',
            'intro' => 'A working component is not the same as a working release. Boundaries between services, environments, and teams get particular attention.',
            'items' => [
                'Identify dependencies and interface assumptions before they block implementation.',
                'Exercise integration paths throughout development, not only at the end.',
                'Record blockers, ownership, and the evidence needed to move forward.',
                'Keep changes small enough to test, explain, and recover when something goes wrong.',
            ],
        ],
        [
            'id' => 'coaching',
            'title' => 'Make the practices shared',
            'intro' => 'A standard is useful only when the team can understand and apply it.',
            'items' => [
                'Put repeatable checks into tooling rather than relying on reminders.',
                'Use reviews to explain the reasoning, not just enforce a rule.',
                'Include the development workflow and release expectations in onboarding.',
                'Adjust practices when experience shows they are adding work without improving delivery.',
            ],
        ],
    ],
    'links' => [
        [
            'label' => 'Current work →',
            'href' => '/work/jacobs-mission-software',
            'emphasis' => true,
        ],
        [
            'label' => 'NASA projects',
            'href' => '/work',
        ],
        [
            'label' => 'Recruiter kit',
            'href' => '/kit',
        ],
    ],
];
