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
    'tagline' => 'Aerospace & National Security Software | Engineering Systems · Technical Leadership',
    // Intentionally empty: leadership evidence lives in Jacobs experience bullets.
    'impact' => [],
    'expertise' => [
        'Software Engineering & Technical Leadership',
        'Mission Software & Distributed Systems',
        'Engineering Systems & Developer Experience',
        'CI/CD, Testing & Release Engineering',
        'Systems Integration & Cross-Team Delivery',
        'Mentoring, Agile Delivery & Engineering Standards',
    ],
    'tooling' => [
        [
            'name' => 'bb-run',
            'url' => 'https://github.com/karlhillx/bb-run',
            'note' => 'Run Bitbucket Pipelines locally.',
        ],
        [
            'name' => 'testrisk',
            'url' => 'https://github.com/karlhillx/testrisk',
            'note' => 'Rank the highest-value Python test gaps from coverage, AST, and git.',
        ],
    ],
];
