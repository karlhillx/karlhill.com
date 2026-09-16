@php
    $isGuard = ! empty($stage['guard']);
    $isCompact = ! empty($stage['compact']);
    $lines = collect($stage['lines'] ?? []);
    $steps = collect($stage['steps'] ?? []);
@endphp
<div @class([
    'case-study-flow__card',
    'case-study-flow__card--guard' => $isGuard,
    'case-study-flow__card--sm' => $isCompact,
])>
    <p class="case-study-flow__label">{{ $stage['label'] }}</p>
    @foreach($lines as $line)
        <p class="case-study-flow__detail">{{ $line }}</p>
    @endforeach
    @if($steps->isNotEmpty())
        <p class="case-study-flow__path">
            @foreach($steps as $i => $step)
                @if($i > 0)
                    <span class="case-study-flow__chevron" aria-hidden="true">→</span>
                @endif
                <span>{{ $step }}</span>
            @endforeach
        </p>
    @endif
</div>
