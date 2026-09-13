@props([
    'scope' => [],
    'rows' => [],
    'heading' => 'Scope',
    'headingId' => null,
])

@php
    $resolved = [];
    foreach ($rows as $key => $value) {
        if (is_array($value) && filled($value['body'] ?? null)) {
            $resolved[(string) ($value['label'] ?? $key)] = $value['body'];
        } elseif (is_string($key) && filled($value)) {
            $resolved[$key] = $value;
        }
    }
    if ($resolved === []) {
        $resolved = array_filter([
            'Owns' => $scope['owned'] ?? null,
            'Influences' => $scope['influence'] ?? null,
            'Reserved' => $scope['reserved'] ?? null,
        ], fn ($value) => filled($value));
    }
@endphp

@if($resolved !== [])
    <div {{ $attributes->class('job-scope') }}>
        @if(filled($heading))
            <h2 @if(filled($headingId)) id="{{ $headingId }}" @endif class="job-scope__heading">{{ $heading }}</h2>
        @endif
        <dl class="job-scope__list">
            @foreach($resolved as $label => $body)
                <div class="job-scope__row">
                    <dt class="job-scope__label">{{ $label }}</dt>
                    <dd class="job-scope__body">{{ $body }}</dd>
                </div>
            @endforeach
        </dl>
    </div>
@endif
