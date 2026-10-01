@props([
    'project',
    'compact' => false,
])

@php
    $type = $project['artifact_type'] ?? match($project['portfolio_group'] ?? null) {
        'mission' => 'diagram',
        'tooling' => 'terminal',
        default => 'screenshot',
    };
@endphp

@if($type === 'diagram')
    <x-site.project-visual.system-diagram :project="$project" :compact="$compact" />
@elseif($type === 'terminal')
    <x-site.project-visual.terminal :project="$project" :compact="$compact" />
@else
    <x-site.project-visual.screenshot :project="$project" :compact="$compact" />
@endif
