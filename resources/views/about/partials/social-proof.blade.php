@php
    $proof = config('site.about.social_proof');
@endphp

@if(! empty($proof['items']))
    <section aria-label="Social proof" class="site-section border-t border-neutral-800/50">
        <div class="site-shell grid md:grid-cols-[200px_1fr] gap-8 md:gap-14" data-reveal>
            <p class="font-mono text-accent text-xs tracking-widest uppercase pt-1">
                @if(! empty($sectionNumber)){{ $sectionNumber }} — @endif{{ $proof['eyebrow'] ?? 'Signal' }}
            </p>
            <ul class="max-w-2xl space-y-8">
                @foreach($proof['items'] as $item)
                    <li>
                        <p class="text-neutral-200 text-lg leading-relaxed">
                            {{ $item['quote'] }}
                        </p>
                        @if(! empty($item['attribution']))
                            <p class="mt-3 font-mono text-[10px] text-neutral-400 uppercase tracking-widest">
                                {{ $item['attribution'] }}
                            </p>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </section>
@endif
