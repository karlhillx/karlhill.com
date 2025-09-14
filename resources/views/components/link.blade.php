@props(['href', 'external' => false])
<a href="{{ $href }}" {{ $external ? 'target="_blank"' : '' }} {{ $attributes->merge(['class' => 'text-sky-500 font-semibold dark:text-sky-400 hover:text-sky-600 dark:hover:text-sky-300 transition duration-300']) }}>{{ trim($slot) }}</a>
