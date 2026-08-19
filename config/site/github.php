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
            'description' => 'Run Bitbucket Pipelines locally — execute your bitbucket-pipelines.yml faithfully in Docker or on your host, with parallel steps, fail-fast, and artifacts.',
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
            'description' => 'High-performance satellite orbit and telemetry simulation engine. Built with Rust and Tokio for scale-testing mission control pipelines and high-throughput data sinks.',
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
            'description' => 'Policy-as-code validator for Bitbucket Pipelines. Enforce CI/CD standards, deployment safety, and organizational consistency across repositories.',
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
            'description' => 'Observability for configuration drift. Detect, classify, and score risky environment differences across .env, YAML, and JSON with policy-as-code.',
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
            'description' => 'High-performance telemetry and simulation data sink for aerospace operations. Built in Rust for memory safety, ultra-low latency, and mission-critical reliability.',
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
