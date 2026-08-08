@if($latestPosts->isNotEmpty())
    <x-site.section id="writing" section-label="Latest Writing" border="soft" aria-label="Latest writing">
        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-5 site-heading-space" data-reveal>
            <x-site.section-heading number="02" label="Latest Writing" class="!mb-0" />
            <a href="/blog"
               class="font-mono text-xs text-neutral-500 hover:text-accent uppercase tracking-widest transition-colors shrink-0">
                All writing <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
            </a>
        </div>

        <ul class="divide-y divide-neutral-800/70 site-bleed">
            @foreach($latestPosts as $post)
                <li class="group" data-reveal @if(! $loop->first) style="transition-delay: {{ ($loop->index - 1) * 60 }}ms" @endif>
                    <div class="site-list-row grid md:grid-cols-[200px_1fr] gap-6 md:gap-12 relative transition-colors hover:bg-neutral-900/30">
                        <div class="relative z-10 flex flex-col gap-2">
                            <time datetime="{{ $post->isoDate() }}"
                                  class="font-mono text-xs text-neutral-400 uppercase tracking-widest">
                                {{ $post->publishedAt->format('M j, Y') }}
                            </time>
                            <span class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest">
                                {{ $post->readMinutes }} min read
                            </span>
                        </div>
                        <div class="relative z-10 min-w-0">
                            <h3 @class([
                                'font-display tracking-wide text-neutral-100 group-hover:text-accent transition-colors leading-tight mb-4 text-balance',
                                'text-2xl sm:text-3xl md:text-4xl' => $loop->first,
                                'text-xl sm:text-2xl md:text-3xl' => ! $loop->first,
                            ])>
                                <a href="{{ $post->url() }}"
                                   class="inline-block after:absolute after:inset-0 after:content-['']">
                                    {{ $post->title }}
                                </a>
                            </h3>
                            <p @class([
                                'text-neutral-400 leading-relaxed mb-6 max-w-2xl',
                                'text-base' => $loop->first,
                                'text-sm line-clamp-2' => ! $loop->first,
                            ])>{{ $post->excerpt }}</p>
                            <div class="relative z-20 flex flex-wrap items-center gap-4">
                                @foreach($post->tags as $tag)
                                    <a href="{{ route('blog.tag', $tag) }}"
                                       class="surface-chip font-mono text-[10px] text-neutral-400 uppercase tracking-widest px-2 py-1 hover:border-accent hover:text-accent transition-colors">
                                        {{ $tag }}
                                    </a>
                                @endforeach
                                <span class="blog-read-cta font-mono text-xs text-accent ml-auto opacity-60 group-hover:opacity-100 transition-opacity pointer-coarse:opacity-100">
                                    Read <span class="arrow-nudge inline-block" aria-hidden="true">→</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>
    </x-site.section>
@endif
