@props([
    'items' => [],
    'plain' => false,
    'class' => 'text-neutral-400 text-sm leading-relaxed',
])

<ul {{ $attributes->class(['space-y-2 list-disc pl-5', $class]) }}>
    @foreach($items as $item)
        <li>
            @if($plain)
                {{ \App\Support\PlainText::fromHtml($item) }}
            @else
                {!! $item !!}
            @endif
        </li>
    @endforeach
</ul>
