@props(['project', 'compact' => false])

@php($summary = $project['summary'] ?? [])

<div class="project-visual project-visual--record {{ $compact ? 'project-visual--compact' : '' }}">
    <p class="eyebrow">{{ $project['meta'] ?? 'On the record' }}</p>
    <p class="record__impact">{{ $summary['impact'] ?? $project['description'] }}</p>
    @if(filled($summary['note'] ?? null))
        <p class="record__note">{{ $summary['note'] }}</p>
    @endif
</div>
