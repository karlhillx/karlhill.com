@props([
    'diagram' => [],
])

@php
    $title = trim((string) ($diagram['title'] ?? ''));
    $source = trim((string) ($diagram['source'] ?? ''));
    $feedback = trim((string) ($diagram['loop'] ?? ''));
    $stages = collect($diagram['stages'] ?? [])
        ->filter(fn ($stage) => is_array($stage) && filled($stage['label'] ?? null))
        ->values();
    $practices = collect($diagram['practices'] ?? [])
        ->filter(fn ($item) => is_string($item) && trim($item) !== '')
        ->map(fn ($item) => trim($item))
        ->values();
    $practiceRows = $practices->chunk(3);
@endphp

@if($title !== '' && $stages->isNotEmpty())
    <div {{ $attributes->class('case-study-flow') }} role="group" aria-label="{{ $title }}">
        <div class="case-study-flow__grid" aria-hidden="true"></div>
        <div class="case-study-flow__glow" aria-hidden="true"></div>

        <p class="case-study-flow__kicker">{{ $title }}</p>

        @if($source !== '')
            <p class="case-study-flow__source">{{ $source }}</p>
        @endif

        <ol class="case-study-flow__spine">
            @foreach($stages as $stage)
                @php
                    $lines = collect($stage['lines'] ?? [])
                        ->filter(fn ($line) => is_string($line) && trim($line) !== '')
                        ->map(fn ($line) => trim($line))
                        ->values();
                    $steps = collect($stage['steps'] ?? [])
                        ->filter(fn ($step) => is_string($step) && trim($step) !== '')
                        ->map(fn ($step) => trim($step))
                        ->values();
                    $wide = ! empty($stage['wide']) || $steps->isNotEmpty();
                @endphp
                <li @class(['case-study-flow__stage', 'case-study-flow__stage--path' => $wide])>
                    <span class="case-study-flow__join" aria-hidden="true"></span>
                    <div class="case-study-flow__card">
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
                </li>
            @endforeach
        </ol>

        @if($feedback !== '')
            <p class="case-study-flow__loop">
                <span class="case-study-flow__loop-rule" aria-hidden="true"></span>
                <span>{{ $feedback }}</span>
                <span class="case-study-flow__loop-rule" aria-hidden="true"></span>
            </p>
        @endif

        @if($practiceRows->isNotEmpty())
            <div class="case-study-flow__practices">
                @foreach($practiceRows as $row)
                    <p>{{ $row->implode(' · ') }}</p>
                @endforeach
            </div>
        @endif
    </div>
@endif
