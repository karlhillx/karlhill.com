@php($delivery = config('site.about.delivery', []))

@if(! empty($delivery))
    <x-site.section id="delivery" section-label="Engineering delivery" :number="$sectionNumber ?? '02'" :label="$delivery['title'] ?? 'Engineering delivery'">
        <div class="about-lede max-w-3xl" data-reveal>
            @foreach($delivery['intro'] ?? [] as $paragraph)
                <p class="opsz-scroll text-neutral-400 text-base leading-relaxed">
                    {{ $paragraph }}
                </p>
            @endforeach
        </div>

        @if(! empty($delivery['cta_href']))
            <a href="{{ $delivery['cta_href'] }}"
               class="inline-flex items-center min-h-11 mt-6 font-mono text-xs text-accent uppercase tracking-widest hover:underline underline-offset-4"
               data-reveal>
                {{ $delivery['cta_label'] ?? 'Engineering delivery' }} →
            </a>
        @endif
    </x-site.section>
@endif
