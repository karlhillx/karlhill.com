<?php

return [
    'language_colors' => [
        'JavaScript' => '#f1e05a',
        'TypeScript' => '#3178c6',
        'Python' => '#3572A5',
        'PHP' => '#4F5D95',
        'Java' => '#b07219',
        'HTML' => '#e34c26',
        'CSS' => '#563d7c',
        'Shell' => '#89e051',
        'Go' => '#00ADD8',
        'Ruby' => '#701516',
        'Blade' => '#f7523f',
        'Rust' => '#dea584',
    ],
    'fallback_repos' => [
        [
            'name' => 'bb-run',
            'description' => 'Run Bitbucket Pipelines locally from your existing pipeline file.',
            'url' => 'https://github.com/karlhillx/bb-run',
            'stars' => 1,
            'language' => 'Python',
            'topics' => [
                'bitbucket-pipelines',
                'devops',
            ],
        ],
        [
            'name' => 'sim-rs',
            'description' => 'Satellite orbit and telemetry simulation in Rust.',
            'url' => 'https://github.com/karlhillx/sim-rs',
            'stars' => 0,
            'language' => 'Rust',
            'topics' => [
                'rust',
                'simulation',
                'aerospace',
            ],
        ],
        [
            'name' => 'pipeguard',
            'description' => 'Check Bitbucket Pipelines definitions against CI/CD and deployment policies.',
            'url' => 'https://github.com/karlhillx/pipeguard',
            'stars' => 0,
            'language' => 'Go',
            'topics' => [
                'ci-cd',
                'policy-as-code',
            ],
        ],
        [
            'name' => 'driftlens',
            'description' => 'Compare environment configuration files and flag differences.',
            'url' => 'https://github.com/karlhillx/driftlens',
            'stars' => 0,
            'language' => 'Python',
            'topics' => [
                'observability',
                'configuration',
            ],
        ],
        [
            'name' => 'drift-rs',
            'description' => 'A Rust data sink for telemetry and simulation workloads.',
            'url' => 'https://github.com/karlhillx/drift-rs',
            'stars' => 0,
            'language' => 'Rust',
            'topics' => [
                'rust',
                'telemetry',
                'aerospace',
            ],
        ],
    ],
];
