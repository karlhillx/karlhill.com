<?php

$facts = require __DIR__.'/facts.php';

return [
    'headline' => 'Karl Hill',
    // Keywords stay in meta / JSON-LD — not a second headline on the first screen.
    'subtitle' => 'Software engineering, technical leadership, and delivery',
    // Positioning line under the name. Kit “Open to” uses person.availability.
    'statement' => 'Mission software, engineering systems, and technical delivery.',
    'lede' => 'Hands-on engineering and technical delivery on aerospace and national security software — implementation, standards, and coordination across teams.',
    // No first-screen ask. Next-role copy lives on Kit.
    'proof' => [
        $facts['repos_chip'],
        $facts['team_chip'],
        'NASA Goddard 2017–2025',
    ],
];
