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
            'description' => 'Run Bitbucket Pipelines locally from your existing pipeline file before pushing.',
            'problem' => 'Eliminates slow commit-push-fail cycles by executing containerized pipeline steps on local workstations.',
            'url' => 'https://github.com/karlhillx/bb-run',
            'stars' => 1,
            'language' => 'Python',
            'category' => 'CI/CD & Developer Tooling',
            'topics' => [
                'bitbucket-pipelines',
                'devops',
                'cli',
            ],
        ],
        [
            'name' => 'testrisk',
            'description' => 'Rank the highest-value Python test gaps from coverage, AST, and git churn.',
            'problem' => 'Identifies untested code risks by combining AST complexity analysis with git mutation history.',
            'url' => 'https://github.com/karlhillx/testrisk',
            'stars' => 1,
            'language' => 'Python',
            'category' => 'Quality & Testing',
            'topics' => [
                'cli',
                'coverage',
                'testing',
            ],
        ],
        [
            'name' => 'pipeguard',
            'description' => 'Policy-as-code validation and rule enforcement for Bitbucket Pipelines.',
            'problem' => 'Enforces governance, secret safety, and deployment constraints against pipeline YAML definitions before execution.',
            'url' => 'https://github.com/karlhillx/pipeguard',
            'stars' => 0,
            'language' => 'Go',
            'category' => 'Policy-as-Code & Security',
            'topics' => [
                'ci-cd',
                'policy-as-code',
                'governance',
            ],
        ],
    ],
];
