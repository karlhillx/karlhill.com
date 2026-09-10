@props([
    'system' => [],
])

@php
    $stages = collect($system['stages'] ?? [])
        ->filter(fn ($stage) => is_array($stage)
            && filled($stage['id'] ?? null)
            && filled($stage['label'] ?? null)
            && filled($stage['summary'] ?? null))
        ->values();
    $default = (string) ($system['default'] ?? 'verify');
    $byId = $stages->keyBy('id');
    $spine = $stages->where('slot', 'spine')->values();
    $build = $byId->get('build');
    $continue = $system['continue'] ?? null;
@endphp

@if($stages->isNotEmpty())

<figure {{ $attributes->class('delivery-map')->merge([
    'data-delivery-map' => true,
]) }}>
    <div class="delivery-map__board" role="radiogroup" aria-label="Delivery stages">
        @if(is_array($build))
            @include('components.site.partials.delivery-node', [
                'stage' => $build,
                'default' => $default,
                'variant' => 'build',
            ])
        @endif

        @foreach($spine as $stage)
            @include('components.site.partials.delivery-node', [
                'stage' => $stage,
                'default' => $default,
                'variant' => 'spine',
            ])
        @endforeach

        @foreach($stages as $stage)
            @foreach($stage['satellites'] ?? [] as $satellite)
                @if(is_array($satellite) && filled($satellite['label'] ?? null) && filled($satellite['id'] ?? null))
                    <label class="delivery-map__sat delivery-map__sat--{{ $satellite['id'] }}"
                           data-sat-of="{{ $stage['id'] }}"
                           for="ds-{{ $stage['id'] }}">
                        <span>{{ $satellite['label'] }}</span>
                    </label>
                @endif
            @endforeach
        @endforeach
    </div>

    <div class="delivery-map__detail" aria-hidden="true">
        @foreach($stages as $stage)
            @php
                $tools = collect($stage['tools'] ?? [])
                    ->filter(fn ($tool) => is_string($tool) && $tool !== '')
                    ->values();
            @endphp
            <div class="delivery-map__panel" data-panel="{{ $stage['id'] }}">
                <p class="delivery-map__panel-kicker">{{ $stage['label'] }}</p>
                <p class="delivery-map__panel-summary">{{ $stage['summary'] }}</p>
                @if($tools->isNotEmpty())
                    <p class="delivery-map__panel-tools">
                        @foreach($tools as $i => $tool)
                            @if($i > 0)<span class="delivery-map__panel-sep" aria-hidden="true">·</span>@endif
                            <span>{{ $tool }}</span>
                        @endforeach
                    </p>
                @endif
            </div>
        @endforeach
    </div>

    @if(filled($system['caption'] ?? null) || (is_array($continue) && filled($continue['href'] ?? null)))
        <figcaption class="delivery-map__caption">
            @if(filled($system['caption'] ?? null))
                <span>{{ $system['caption'] }}</span>
            @endif
            @if(is_array($continue) && filled($continue['href'] ?? null) && filled($continue['label'] ?? null))
                <a href="{{ $continue['href'] }}" class="delivery-map__continue">{{ $continue['label'] }}</a>
            @endif
        </figcaption>
    @endif
</figure>
@endif
