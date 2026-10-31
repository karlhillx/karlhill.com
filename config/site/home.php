<?php

$facts = require __DIR__.'/facts.php';

return [
    'stories' => [
        'jacobs-mission-software',
        'flood-mapping-system',
        'the-dry-standard',
    ],
    'nasa_supporting' => [
        'laads-daac',
        'nasa-earth-observatory',
    ],
    'tools' => 'developer-tooling',
    'context' => [
        ['label' => 'Current', 'value' => 'Jacobs / '.$facts['unit']],
        ['label' => 'Previously', 'value' => 'NASA Goddard via SSAI, '.$facts['nasa_period']],
        ['label' => 'Independent', 'value' => 'The Dry Standard'],
        ['label' => 'Based in', 'value' => $facts['location']],
    ],
    'how_i_work' => [
        [
            'title' => 'Architecture and integration',
            'body' => 'Shared interfaces and integration choices made in review, while the change is still cheap to correct. Mission software has to stay compatible across repositories and environments.',
            'href' => '/work/jacobs-mission-software',
            'label' => 'Mission software case study',
        ],
        [
            'title' => 'Delivery practices and developer experience',
            'body' => 'Tests, coverage, type checks, and security gates belong in the pipeline. Independent tools make local CI, test priorities, and pipeline policy inspectable.',
            'href' => '/work/developer-tooling',
            'label' => 'Developer tooling case study',
        ],
        [
            'title' => 'Technical guidance and engineer development',
            'body' => 'Sequencing work, reviewing the same code, and coaching the engineers who ship it. Personnel authority stays with management.',
            'href' => '/work/jacobs-mission-software#leadership',
            'label' => 'How that work is scoped',
        ],
    ],
];
