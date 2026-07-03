@props(['items' => []])

<ul {{ $attributes->merge(['class' => 'space-y-4 text-neutral-400 text-sm leading-relaxed self-start']) }}>
    @foreach($items as $item)
        <li class="flex gap-4">
            <span class="text-accent shrink-0 mt-0.5 arrow-nudge" aria-hidden="true">→</span>
            <span class="min-w-0">{!! $item !!}</span>
        </li>
    @endforeach
</ul>
