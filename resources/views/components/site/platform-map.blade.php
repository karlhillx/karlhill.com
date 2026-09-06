@props([
    'stages' => [],
    'caption' => null,
    'eyebrow' => 'Platform',
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
    <div {{ $attributes->class('platform-map')->merge(['id' => 'platform']) }}>
        @if(filled($eyebrow))
            <p class="platform-map__eyebrow">{{ $eyebrow }}</p>
        @endif

        <ol class="platform-map__stages" data-count="{{ $count }}">
            @foreach($stages as $stage)
                @php
                    $stack = array_values(array_filter(array_map(
                        'trim',
                        preg_split('/\s*·\s*/u', (string) ($stage['stack'] ?? '')) ?: [],
                    )));
                @endphp
                <li class="platform-map__stage">
                    <p class="platform-map__step">{{ $stage['step'] }}</p>
                    <p class="platform-map__title">{{ $stage['title'] }}</p>
                    @if(filled($stage['body'] ?? null))
                        <p class="platform-map__body">{{ $stage['body'] }}</p>
                    @endif
                    @if($stack !== [])
                        <p class="platform-map__stack">
                            @foreach($stack as $i => $item)
                                @if($i > 0)<span class="platform-map__stack-sep" aria-hidden="true">·</span>@endif
                                <span>{{ $item }}</span>
                            @endforeach
                        </p>
                    @endif
                </li>
            @endforeach
        </ol>

        @if(filled($caption))
            <p class="platform-map__caption">{{ $caption }}</p>
        @endif
    </div>
@endif
