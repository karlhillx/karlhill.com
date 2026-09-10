@php
    $id = (string) $stage['id'];
    $inputId = 'ds-'.$id;
    $descId = $inputId.'-desc';
    $tools = collect($stage['tools'] ?? [])
        ->filter(fn ($tool) => is_string($tool) && $tool !== '')
        ->values();
    $satellites = collect($stage['satellites'] ?? [])
        ->filter(fn ($item) => is_array($item) && filled($item['label'] ?? null))
        ->values();
    $description = trim($stage['summary'].($tools->isNotEmpty() ? ' '.$tools->implode(', ').'.' : ''));
@endphp

<label class="delivery-map__node delivery-map__node--{{ $variant }} delivery-map__node--{{ $id }}"
       data-stage="{{ $id }}"
       data-analytics-event="delivery_stage_selected"
       data-analytics-location="home-system"
       data-analytics-stage="{{ $id }}">
    <input class="sr-only"
           type="radio"
           name="delivery-stage"
           id="{{ $inputId }}"
           value="{{ $id }}"
           aria-describedby="{{ $descId }}"
           @checked($id === $default)>
    <span class="delivery-map__name">{{ $stage['label'] }}</span>
    @if($satellites->isNotEmpty())
        <span class="delivery-map__tags" aria-hidden="true">
            @foreach($satellites as $satellite)
                <span>{{ $satellite['label'] }}</span>
            @endforeach
        </span>
    @endif
    <span id="{{ $descId }}" class="sr-only">{{ $description }}</span>
</label>
