@props([
    'stages' => [],
    'caption' => null,
    'eyebrow' => 'Workflow',
])

@php
    $stages = array_values(array_filter(
        is_array($stages) ? $stages : [],
        fn ($stage) => is_array($stage)
            && filled($stage['step'] ?? null)
            && filled($stage['title'] ?? null)
            && filled($stage['body'] ?? null),
    ));
    $count = count($stages);
@endphp

@if($count >= 3)
    <section {{ $attributes->class(['platform-map', 'work-diagram'])->merge([
        'id' => 'platform',
        'aria-labelledby' => filled($eyebrow) ? 'platform-heading' : null,
    ]) }}>
        @if(filled($eyebrow))
            <h2 id="platform-heading" class="work-diagram__label">{{ $eyebrow }}</h2>
        @endif

        <div class="work-diagram__board">
            <div class="work-diagram__grid" aria-hidden="true"></div>
            <ol class="work-diagram__flow" data-count="{{ $count }}">
                @foreach($stages as $index => $stage)
                    @php
                        $stepLabel = (string) $stage['step'];
                        $stepNum = preg_match('/(\d+)/', $stepLabel, $match) === 1
                            ? str_pad($match[1], 2, '0', STR_PAD_LEFT)
                            : str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT);
                        $stepName = trim((string) preg_replace('/^\d+\s*[·.:-]\s*/u', '', $stepLabel));
                        $stack = array_values(array_filter(array_map(
                            'trim',
                            preg_split('/\s*·\s*/u', (string) ($stage['stack'] ?? '')) ?: [],
                        )));
                    @endphp
                    <li class="work-diagram__node">
                        <div class="work-diagram__node-head">
                            <span class="work-diagram__index">{{ $stepNum }}</span>
                            @if($stepName !== '')
                                <span class="work-diagram__phase">{{ $stepName }}</span>
                            @endif
                        </div>
                        <p class="work-diagram__title">{{ $stage['title'] }}</p>
                        @if(filled($stage['body'] ?? null))
                            <p class="work-diagram__body">{{ $stage['body'] }}</p>
                        @endif
                        @if($stack !== [])
                            <p class="work-diagram__stack">
                                @foreach($stack as $i => $item)
                                    @if($i > 0)<span class="work-diagram__stack-sep" aria-hidden="true">·</span>@endif
                                    <span>{{ $item }}</span>
                                @endforeach
                            </p>
                        @endif
                    </li>
                @endforeach
            </ol>
        </div>

        @if(filled($caption))
            <p class="work-diagram__caption">{{ $caption }}</p>
        @endif
    </section>
@endif
