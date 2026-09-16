@props([
    'diagram' => [],
])

@php
    $title = trim((string) ($diagram['title'] ?? ''));
    $feedback = trim((string) ($diagram['loop'] ?? ''));
    $normalizeStages = static function (mixed $stages): \Illuminate\Support\Collection {
        return collect($stages ?? [])
            ->filter(fn ($stage) => is_array($stage) && filled($stage['label'] ?? null))
            ->map(function (array $stage) {
                $stage['lines'] = collect($stage['lines'] ?? [])
                    ->filter(fn ($line) => is_string($line) && trim($line) !== '')
                    ->map(fn ($line) => trim($line))
                    ->values();
                $stage['steps'] = collect($stage['steps'] ?? [])
                    ->filter(fn ($step) => is_string($step) && trim($step) !== '')
                    ->map(fn ($step) => trim($step))
                    ->values();

                return $stage;
            })
            ->values();
    };
    $normalizeSteps = static function (mixed $steps): \Illuminate\Support\Collection {
        return collect($steps ?? [])
            ->filter(fn ($step) => is_string($step) && trim($step) !== '')
            ->map(fn ($step) => trim($step))
            ->values();
    };
    $normalizeFork = static function (mixed $fork) use ($normalizeStages): ?array {
        if (! is_array($fork)) {
            return null;
        }

        $asStage = static fn (mixed $stage) => $normalizeStages([$stage])->first();
        $stem = $asStage($fork['stem'] ?? null);
        $join = $asStage($fork['join'] ?? null);
        $branches = $normalizeStages($fork['branches'] ?? []);

        if ($stem === null || $join === null || $branches->count() < 2) {
            return null;
        }

        return compact('stem', 'branches', 'join');
    };
    $zones = collect($diagram['zones'] ?? [])
        ->filter(fn ($zone) => is_array($zone) && filled($zone['label'] ?? null))
        ->map(function (array $zone) use ($normalizeStages, $normalizeSteps, $normalizeFork) {
            return [
                'label' => trim((string) $zone['label']),
                'boxed' => ! empty($zone['boxed']),
                'fork' => $normalizeFork($zone['fork'] ?? null),
                'stages' => $normalizeStages($zone['stages'] ?? []),
                'steps' => $normalizeSteps($zone['steps'] ?? []),
            ];
        })
        ->filter(fn (array $zone) => $zone['fork'] !== null || $zone['stages']->isNotEmpty() || $zone['steps']->isNotEmpty())
        ->values();

    if ($zones->isEmpty() && ! empty($diagram['stages'])) {
        $zones = collect([[
            'label' => $title,
            'boxed' => false,
            'stages' => $normalizeStages($diagram['stages']),
            'steps' => collect(),
        ]])->filter(fn (array $zone) => $zone['stages']->isNotEmpty());
    }
@endphp

@if($title !== '' && $zones->isNotEmpty())
    <div {{ $attributes->class('case-study-flow') }} role="group" aria-label="{{ $title }}">
        <div class="case-study-logo-plate__grid" aria-hidden="true"></div>
        <div class="case-study-logo-plate__glow" aria-hidden="true"></div>

        <p class="case-study-flow__kicker">{{ $title }}</p>

        <div class="case-study-flow__board">
            @if($feedback !== '')
                <p class="case-study-flow__return">
                    <span>{{ $feedback }}</span>
                </p>
            @endif

            <ol class="case-study-flow__spine">
                @foreach($zones as $zone)
                    @php
                        $zoneStages = $zone['stages'];
                        $zoneSteps = $zone['steps'];
                        $isLocal = $zoneStages->contains(
                            fn (array $stage) => ! empty($stage['guard']) || ! empty($stage['compact'])
                        );
                    @endphp
                    <li class="case-study-flow__zone">
                        <p class="case-study-flow__zone-label">{{ $zone['label'] }}</p>

                        @if($zone['fork'])
                            @php $fork = $zone['fork']; @endphp
                            @include('components.site.partials.case-study-flow-card', ['stage' => $fork['stem']])
                            <span class="case-study-flow__join case-study-flow__join--short" aria-hidden="true"></span>
                            <div class="case-study-flow__fork-arms" role="group" aria-label="Parallel checks">
                                @foreach($fork['branches'] as $stage)
                                    @include('components.site.partials.case-study-flow-card', ['stage' => $stage])
                                @endforeach
                            </div>
                            <span class="case-study-flow__join case-study-flow__join--short" aria-hidden="true"></span>
                            @include('components.site.partials.case-study-flow-card', ['stage' => $fork['join']])
                        @elseif($isLocal)
                            @foreach($zoneStages as $i => $stage)
                                @if($i > 0)
                                    <span class="case-study-flow__join case-study-flow__join--short" aria-hidden="true"></span>
                                @endif
                                @include('components.site.partials.case-study-flow-card', ['stage' => $stage])
                            @endforeach
                        @elseif($zone['boxed'] && $zoneSteps->isNotEmpty())
                            <div class="case-study-flow__chain">
                                @foreach($zoneSteps as $i => $step)
                                    @if($i > 0)
                                        <span class="case-study-flow__chevron" aria-hidden="true">→</span>
                                    @endif
                                    <div class="case-study-flow__card case-study-flow__card--sm">
                                        <p class="case-study-flow__label">{{ $step }}</p>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($zoneStages->isNotEmpty())
                            @foreach($zoneStages as $stage)
                                @unless($loop->first)
                                    <span class="case-study-flow__join" aria-hidden="true"></span>
                                @endunless
                                @include('components.site.partials.case-study-flow-card', ['stage' => $stage])
                            @endforeach
                        @else
                            <p class="case-study-flow__path case-study-flow__path--zone">
                                @foreach($zoneSteps as $i => $step)
                                    @if($i > 0)
                                        <span class="case-study-flow__chevron" aria-hidden="true">→</span>
                                    @endif
                                    <span>{{ $step }}</span>
                                @endforeach
                            </p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>
    </div>
@endif
