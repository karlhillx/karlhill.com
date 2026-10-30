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
            'description' => 'Run bitbucket-pipelines.yml locally in Docker or on the host, including parallel steps, services, caches, and artifacts.',
            'problem' => 'Catch pipeline failures on the workstation. Host mode runs script-only steps without Docker; service sidecars still need it.',
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
            'description' => 'Rank Python functions that need better tests from coverage.py, uncovered branches, complexity, and changed lines.',
            'problem' => 'A coverage percentage does not say which gap to test next. The default command advises; optional flags can fail a run.',
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
            'description' => 'Check Bitbucket Pipelines YAML for required steps, forbidden image tags, manual production triggers, and an allowlist of pipes.',
            'problem' => 'Policy that lives only in a document is easy to skip. PipeGuard reports ERROR, WARNING, or INFO against the pipeline file. It does not run the pipeline.',
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
