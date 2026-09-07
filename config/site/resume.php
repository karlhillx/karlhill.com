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
    // Banner: engineer who leads — not a management rebrand.
    // PDF splits on the first "|": lead line, then the rest.
    'tagline' => 'Aerospace & Mission Software | Software Engineering | Technical Leadership | Team Execution',
    // Intentionally empty: leadership evidence lives in Jacobs experience bullets
    // so page 1 is Summary → Experience without duplicating the same four claims.
    'impact' => [],
    'expertise' => [
        'Software Engineering',
        'Technical Leadership',
        'CI/CD & DevSecOps',
        'Distributed Systems & Messaging',
        'Engineer Development & Coaching',
        'Team Execution & Agile Delivery',
        'Mission Software Delivery',
    ],
    'tooling' => [
        [
            'name' => 'bb-run',
            'url' => 'https://github.com/karlhillx/bb-run',
            'note' => 'Local Bitbucket Pipelines runner — CI you can execute on a laptop.',
        ],
        [
            'name' => 'pipeguard',
            'url' => 'https://github.com/karlhillx/pipeguard',
            'note' => 'Policy-as-code checks for CI/CD standards and deployment safety.',
        ],
    ],
];
