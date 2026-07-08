@if($latestPosts->isNotEmpty())
    @php($featured = $latestPosts->first())
    @php($morePosts = $latestPosts->skip(1))

    <x-site.section id="writing" section-label="Latest Writing" border="soft" aria-label="Latest writing">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-16" data-reveal>
            <x-site.section-heading number="03" label="Latest Writing" class="mb-0" />
            <a href="/blog"
               class="font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors shrink-0">
                All writing <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
            </a>
        </div>

        <a href="{{ $featured->url() }}"
           class="surface-card group block px-7 py-8 md:px-10 md:py-10"
           data-reveal>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-5 font-mono text-[10px] uppercase tracking-widest text-neutral-500">
                <time datetime="{{ $featured->isoDate() }}">{{ $featured->publishedAt->format('M j, Y') }}</time>
                <span class="text-neutral-700" aria-hidden="true">·</span>
                <span>{{ $featured->readMinutes }} min read</span>
                @foreach($featured->tags as $tag)
                    <a href="{{ route('blog.tag', $tag) }}"
                       class="surface-chip px-2 py-0.5 text-neutral-600 hover:border-accent hover:text-accent transition-colors"
                       onclick="event.stopPropagation()">{{ $tag }}</a>
                @endforeach
            </div>
            <h3 class="font-display text-3xl md:text-4xl tracking-wide text-neutral-100 group-hover:text-accent transition-colors leading-tight mb-4">
                {{ $featured->title }}
            </h3>
            <p class="text-neutral-400 leading-relaxed max-w-2xl mb-6">{{ $featured->excerpt }}</p>
            <span class="font-mono text-xs text-accent uppercase tracking-widest">
                Read the post <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
            </span>
        </a>

        @if($morePosts->isNotEmpty())
            <ul class="mt-3 divide-y divide-neutral-800/50">
                @foreach($morePosts as $post)
                    <li data-reveal style="transition-delay: {{ $loop->index * 60 }}ms">
                        <a href="{{ $post->url() }}"
                           class="group flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 py-6 px-2 -mx-2 hover:bg-neutral-900/30 transition-colors">
                            <div class="min-w-0">
                                <h4 class="font-display text-xl tracking-wide text-neutral-200 group-hover:text-accent transition-colors leading-tight">
                                    {{ $post->title }}
                                </h4>
                                <p class="text-neutral-500 text-sm leading-relaxed mt-1 line-clamp-1">{{ $post->excerpt }}</p>
                            </div>
                            <div class="flex items-center gap-4 shrink-0 font-mono text-[10px] uppercase tracking-widest text-neutral-500">
                                <time datetime="{{ $post->isoDate() }}">{{ $post->publishedAt->format('M j, Y') }}</time>
                                <span class="text-neutral-700" aria-hidden="true">·</span>
                                <span>{{ $post->readMinutes }} min</span>
                                <span class="text-accent opacity-60 group-hover:opacity-100 transition-opacity pointer-coarse:opacity-100">
                                    Read <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
                                </span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </x-site.section>
@endif
