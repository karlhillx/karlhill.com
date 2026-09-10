<?php

return [
    // Contact details used on /resume (screen + print). Keep phone/ZIP out of the
    // public person profile so they only appear on the CV.
    'phone' => '(202) 599-1442',
    // The phone number always goes into the generated PDF (an applicant hands
    // that over deliberately). On the public /resume page it is opt-in, so a
    // crawlable URL doesn't expose a direct line to scrapers and robocallers.
    'phone_on_web' => false,
    'postal' => '',
    // PDF splits on the first "|": lead line, then the rest.
    'tagline' => 'Software Engineering | Technical Leadership | Mission Software | Agile Delivery',
    // Intentionally empty: leadership evidence lives in Jacobs experience bullets
    // so page 1 is Summary → Experience without duplicating the same four claims.
    'impact' => [],
    'expertise' => [
        'Software Engineering',
        'Technical Direction & Leadership',
        'Distributed Systems & Integration',
        'CI/CD & Developer Tooling',
        'Engineer Development & Coaching',
        'Agile Delivery Leadership',
        'Integration & Release Readiness',
    ],
    'tooling' => [
        [
            'name' => 'bb-run',
            'url' => 'https://github.com/karlhillx/bb-run',
            'note' => 'Run Bitbucket Pipelines locally.',
        ],
        [
            'name' => 'pipeguard',
            'url' => 'https://github.com/karlhillx/pipeguard',
            'note' => 'Policy checks for CI/CD standards and deployment safety.',
        ],
    ],
];
