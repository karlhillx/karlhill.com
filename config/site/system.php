<?php

/**
 * Homepage delivery diagram. Tools named here are in current use.
 * The longer catalog stays on /resume#stack — this is not a skills grid.
 */
return [
    'heading' => 'How software gets delivered',
    'lede' => 'Software other people depend on, then the checks, integration, and release around it. Select a stage.',
    'caption' => 'A delivery diagram of current practice. Not a program architecture.',
    'default' => 'verify',
    'continue' => [
        'label' => 'Written bar for reviews →',
        'href' => '/about#delivery',
    ],
    'stages' => [
        [
            'id' => 'build',
            'label' => 'Build',
            'slot' => 'build',
            'summary' => 'Packaging so the same change rebuilds elsewhere.',
            'tools' => ['uv', 'Docker', 'packaging'],
        ],
        [
            'id' => 'code',
            'label' => 'Code',
            'slot' => 'spine',
            'summary' => 'Services, libraries, and interfaces other people depend on.',
            'tools' => ['Python', 'TypeScript', 'Java'],
        ],
        [
            'id' => 'verify',
            'label' => 'Verify',
            'slot' => 'spine',
            'summary' => 'Quality, tests, and security checks on the change.',
            'tools' => [
                'Ruff',
                'mypy',
                'pytest',
                'coverage',
                'mutation testing',
                'security scanning',
            ],
            'satellites' => [
                ['id' => 'quality', 'label' => 'Quality'],
                ['id' => 'testing', 'label' => 'Testing'],
                ['id' => 'security', 'label' => 'Security'],
            ],
        ],
        [
            'id' => 'integrate',
            'label' => 'Integrate',
            'slot' => 'spine',
            'summary' => 'Contracts between services: APIs, messages, and schemas.',
            'tools' => ['APIs', 'messaging', 'schemas', 'distributed services'],
        ],
        [
            'id' => 'release',
            'label' => 'Release',
            'slot' => 'spine',
            'summary' => 'Promotion through environments until the change is operable.',
            'tools' => [
                'containers',
                'CI/CD',
                'environment promotion',
                'operational readiness',
            ],
            'satellites' => [
                ['id' => 'environment', 'label' => 'Environment'],
                ['id' => 'readiness', 'label' => 'Readiness'],
            ],
        ],
    ],
];
