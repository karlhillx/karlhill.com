@php($system = config('site.system', []))

<x-site.section id="system" section-label="Delivery" number="02" label="How software gets delivered" border="soft">
    @if(filled($system['lede'] ?? null))
        <p class="text-neutral-300 text-lg leading-relaxed max-w-2xl mb-10 sm:mb-12" data-reveal>
            {{ $system['lede'] }}
        </p>
    @endif
    <div data-reveal>
        <x-site.delivery-map :system="$system" />
    </div>
</x-site.section>
