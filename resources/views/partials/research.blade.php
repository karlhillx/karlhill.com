@php($research = config('site.research'))

<x-site.section id="research" :number="$sectionNumber ?? '02'" label="Research">
        <article class="overflow-hidden border border-neutral-800 bg-neutral-900/30" data-reveal>
            @if(! empty($research['image']))
                <figure class="border-b border-neutral-800 bg-[#fff]">
                    <x-site.responsive-image
                        :src="$research['image']"
                        :alt="$research['image_alt'] ?? $research['title']"
                        sizes="(min-width: 1024px) 960px, 100vw"
                        width="940"
                        height="788"
                        loading="lazy"
                        img-class="w-full h-auto"
                    />
                </figure>
            @endif

            <div class="grid lg:grid-cols-[260px_1fr] gap-8 lg:gap-12 p-6 sm:p-8 md:p-10">
                <div>
                    <p class="font-mono text-xs text-accent uppercase tracking-widest mb-3">{{ $research['label'] }}</p>
                    <p class="font-display text-4xl text-neutral-500 leading-none">{{ $research['publication'] }}</p>
                    <p class="font-mono text-xs text-neutral-500 mt-4">{{ $research['published'] }}</p>
                </div>

                <div>
                    <h3 class="font-sans font-semibold text-xl sm:text-2xl md:text-[1.75rem] tracking-tight text-neutral-100 leading-snug mb-5 text-balance">
                        {{ $research['title'] }}
                    </h3>
                    <p class="text-neutral-400 text-sm leading-relaxed max-w-3xl mb-6">
                        {{ $research['summary'] }}
                    </p>
                    <p class="text-neutral-500 text-sm leading-relaxed max-w-3xl mb-8">
                        {{ $research['citation'] }}
                        <span class="text-neutral-500">{{ $research['journal'] }}</span>
                    </p>
                    <x-site.button variant="secondary" :href="$research['doi']" target="_blank" rel="noopener noreferrer" data-no-ext>
                        {{ $research['doi_label'] }}
                        <span aria-hidden="true">↗</span>
                    </x-site.button>
                </div>
            </div>
        </article>
</x-site.section>
