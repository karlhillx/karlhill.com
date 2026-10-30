@props([
    'project',
    'compact' => false,
])

<x-site.project-visual.screenshot :project="$project" :compact="$compact" />
