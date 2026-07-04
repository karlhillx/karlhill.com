@props([
    'variant' => 'default',
    'section' => null,
])

@php
    $person = config('site.person');
    $footer = config('site.footer');
    $isHome = $variant === 'home';
@endphp

<footer @if($isHome) id="contact" @endif class="relative z-10 border-t border-neutral-800 {{ $isHome ? 'py-24' : 'py-16' }} px-6">
    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-12">
            <div class="max-w-xl" @if($isHome) data-reveal @endif>
                @if($isHome && $section)
                    <h2 class="font-mono text-accent text-xs tracking-widest uppercase mb-6">{{ $section }} — Contact</h2>
                @else
                    <p class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Get in Touch</p>
                @endif
                <p class="font-display {{ $isHome ? 'text-[clamp(3rem,8vw,6rem)] mb-6' : 'text-[clamp(2rem,5vw,3.5rem)] mb-4' }} leading-none tracking-wide">
                    {!! nl2br(e($footer['headline'])) !!}
                </p>
                <p class="text-neutral-400 text-sm leading-relaxed max-w-sm">
                    {{ $footer['body'] }}
                </p>

                @if($isHome)
                    @if(session('status') === 'contact-sent')
                        <p role="status" class="mt-8 max-w-md border border-accent/40 bg-accent/10 text-accent font-mono text-xs uppercase tracking-widest px-4 py-3">
                            Thanks — your message is on its way. I'll reply from {{ $person['email'] }}.
                        </p>
                    @elseif(session('status') === 'contact-failed')
                        <p role="alert" class="mt-8 max-w-md border border-red-500/40 bg-red-500/10 text-red-300 font-mono text-xs uppercase tracking-widest px-4 py-3 normal-case">
                            Something went wrong sending your message. Please email me directly at
                            <a href="mailto:{{ $person['email'] }}" class="underline hover:text-red-200">{{ $person['email'] }}</a>.
                        </p>
                    @endif

                    <form id="contact-form" method="POST" action="{{ route('contact.store') }}" class="mt-8 space-y-4 max-w-md" aria-label="Send a message">
                        @csrf

                        {{-- Honeypot: hidden from people, irresistible to bots. --}}
                        <div class="absolute -left-[9999px] w-px h-px overflow-hidden" aria-hidden="true">
                            <label>Company <input type="text" name="company" tabindex="-1" autocomplete="off"></label>
                        </div>

                        <div class="grid sm:grid-cols-2 gap-4">
                            <div>
                                <label for="contact-name" class="sr-only">Your name</label>
                                <input id="contact-name" name="name" type="text" required maxlength="120"
                                       value="{{ old('name') }}" placeholder="Your name" autocomplete="name"
                                       @if($errors->has('name')) aria-invalid="true" aria-describedby="contact-name-error" @endif
                                       @class([
                                           'w-full bg-neutral-900/50 border text-neutral-200 placeholder-neutral-600 px-4 py-3 text-sm outline-none transition-colors focus:border-accent',
                                           'border-red-500/60' => $errors->has('name'),
                                           'border-neutral-800' => ! $errors->has('name'),
                                       ])>
                                @error('name')<p id="contact-name-error" class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p>@enderror
                            </div>
                            <div>
                                <label for="contact-email" class="sr-only">Your email</label>
                                <input id="contact-email" name="email" type="email" required maxlength="190"
                                       value="{{ old('email') }}" placeholder="you@company.com" autocomplete="email"
                                       @if($errors->has('email')) aria-invalid="true" aria-describedby="contact-email-error" @endif
                                       @class([
                                           'w-full bg-neutral-900/50 border text-neutral-200 placeholder-neutral-600 px-4 py-3 text-sm outline-none transition-colors focus:border-accent',
                                           'border-red-500/60' => $errors->has('email'),
                                           'border-neutral-800' => ! $errors->has('email'),
                                       ])>
                                @error('email')<p id="contact-email-error" class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div>
                            <label for="contact-message" class="sr-only">Message</label>
                            <textarea id="contact-message" name="message" required minlength="10" maxlength="4000" rows="4"
                                      placeholder="What are you building, and how can I help?"
                                      @if($errors->has('message')) aria-invalid="true" aria-describedby="contact-message-error" @endif
                                      @class([
                                          'contact-textarea w-full bg-neutral-900/50 border text-neutral-200 placeholder-neutral-600 px-4 py-3 text-sm outline-none transition-colors focus:border-accent resize-y',
                                          'border-red-500/60' => $errors->has('message'),
                                          'border-neutral-800' => ! $errors->has('message'),
                                      ])>{{ old('message') }}</textarea>
                            @error('message')<p id="contact-message-error" class="mt-1 font-mono text-[11px] text-red-400">{{ $message }}</p>@enderror
                        </div>
                        <button type="submit"
                                class="btn-sweep inline-flex items-center gap-2 border border-accent/50 text-accent font-mono text-xs uppercase tracking-widest px-6 py-3">
                            Send message <span aria-hidden="true">→</span>
                        </button>
                    </form>
                @endif
            </div>
            <div class="flex flex-col gap-4 {{ $isHome ? 'lg:pt-16' : '' }} shrink-0" @if($isHome) data-reveal @endif>
                @if($isHome)
                    <p class="font-mono text-[10px] text-neutral-400 uppercase tracking-widest">Prefer to reach me directly?</p>
                @endif
                <div class="flex items-center gap-3">
                    <a href="mailto:{{ $person['email'] }}"
                       class="flex items-center gap-4 font-mono text-sm text-neutral-400 hover:text-accent transition-colors group">
                        <span class="text-accent text-base arrow-nudge" aria-hidden="true">→</span>
                        {{ $person['email'] }}
                    </a>
                    <button type="button" data-copy-text="{{ $person['email'] }}" aria-label="Copy email address"
                            class="relative text-neutral-500 hover:text-accent transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V5a2 2 0 012-2h9a2 2 0 012 2v9a2 2 0 01-2 2h-2M5 8h9a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2v-9a2 2 0 012-2z"/>
                        </svg>
                        <span data-copy-feedback role="status" aria-live="polite"
                              class="pointer-events-none absolute bottom-full left-1/2 -translate-x-1/2 mb-2 inline-flex items-center gap-1.5 whitespace-nowrap rounded-md border border-accent/30 bg-accent/10 px-2.5 py-1 font-mono text-[10px] text-accent uppercase tracking-widest opacity-0 transition-opacity duration-200 shadow-lg shadow-black/40">
                            <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                            </svg>
                            Copied to clipboard
                        </span>
                    </button>
                </div>
                <a href="{{ $footer['resume'] }}" target="_blank" rel="noopener noreferrer"
                   class="btn-sweep inline-flex items-center gap-3 border border-neutral-700 text-neutral-300 font-semibold px-6 py-3 text-xs uppercase tracking-widest w-fit">
                    <svg class="w-3.5 h-3.5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Resume
                </a>

                <x-site.social-links />
            </div>
            <nav class="shrink-0" aria-label="Site">
                <p class="font-mono text-accent text-xs tracking-widest uppercase mb-4">Explore</p>
                <ul class="space-y-3 font-mono text-sm">
                    <li><a href="/work" class="text-neutral-400 hover:text-accent transition-colors">Work</a></li>
                    <li><a href="/about" class="text-neutral-400 hover:text-accent transition-colors">About</a></li>
                    <li><a href="/blog" class="text-neutral-400 hover:text-accent transition-colors">Writing</a></li>
                </ul>
            </nav>
        </div>
        <div @class([
            'pt-8 border-t border-neutral-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4',
            'mt-20' => $isHome,
            'mt-12' => ! $isHome,
        ])>
            <p class="font-display {{ $isHome ? 'text-3xl' : 'text-2xl' }} tracking-widest text-neutral-500">{{ $person['name'] }}</p>
            <p class="font-mono text-xs text-neutral-400">{{ $person['location'] }} &nbsp;·&nbsp; {{ $person['job_title'] }} &nbsp;·&nbsp; 25+ Years</p>
        </div>
        <p class="mt-6 font-mono text-[10px] uppercase tracking-widest text-neutral-700 hover:text-neutral-500 transition-colors duration-300 sm:text-right">
            Built with Laravel {{ \App\Support\Stack::laravelVersion() }} &middot; Tailwind CSS {{ \App\Support\Stack::tailwindVersion() ?? '4' }}
        </p>
    </div>
</footer>
