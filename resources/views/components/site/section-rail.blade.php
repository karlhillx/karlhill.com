@props([
    'sections' => [],
])

@if(count($sections) > 0)
    <nav id="section-rail" class="section-rail md:hidden" aria-label="On this page">
        <div class="section-rail-inner">
            @foreach($sections as $section)
                <a href="{{ $section['href'] }}"
                   data-rail-section="{{ $section['id'] }}"
                   @if($loop->first) aria-current="true" @endif>
                    {{ $section['label'] }}
                </a>
            @endforeach
        </div>
    </nav>
@endif
