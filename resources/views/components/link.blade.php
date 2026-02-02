@props(['href', 'external' => false])
<a href="{{ $href }}" {{ $external ? 'target="_blank"' : '' }} {{ $attributes->merge(['class' => 'text-sky-500 font-semibold hover:text-sky-600 transition duration-300']) }}>{{ trim($slot) }}</a>
