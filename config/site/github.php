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
            'name' => 'testrisk',
            'description' => 'Rank the highest-value Python test gaps from coverage, AST, and git.',
            'url' => 'https://github.com/karlhillx/testrisk',
            'stars' => 1,
            'language' => 'Python',
            'topics' => [
                'cli',
                'coverage',
                'testing',
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
    ],
];
