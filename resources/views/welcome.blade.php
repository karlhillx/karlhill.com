@extends('layouts.app')

@section('dark-mode', true)

@section('body-bg', 'bg-maroon-dream dark:bg-maroon-dream text-gray-900 dark:text-white')

@section('content')

    <section class="reveal-section">
        <x-landing/>
    </section>

    <section class="reveal-section">
        <x-feature/>
    </section>

    <section class="reveal-section">
        <x-core-competencies/>
    </section>

    <section class="reveal-section">
        <x-skills/>
    </section>

    <section class="reveal-section">
        <x-dynamic-skills/>
    </section>

    <section class="reveal-section">
        <x-map/>
    </section>

    <script>
        (function () {
            try {
                const prefersReduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                if (!prefersReduced && 'IntersectionObserver' in window) {
                    const io = new IntersectionObserver((entries, obs) => {
                        for (const e of entries) {
                            if (e.isIntersecting) {
                                e.target.classList.add('in-view');
                                obs.unobserve(e.target);
                            }
                        }
                    }, { root: null, rootMargin: '0px 0px -10% 0px', threshold: 0.15 });

                    document.querySelectorAll('.reveal-section').forEach(el => io.observe(el));
                } else {
                    // If reduced motion is preferred or IO unsupported, show content immediately.
                    document.querySelectorAll('.reveal-section').forEach(el => el.classList.add('in-view'));
                }

                // Subtle page transition: fade out on navigation
                const enablePageFade = (e) => {
                    const a = e.currentTarget;
                    const url = a.getAttribute('href');
                    if (!url || url.startsWith('#') || a.target === '_blank' || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;
                    e.preventDefault();
                    document.body.classList.add('leaving');
                    window.setTimeout(() => { window.location.href = url; }, 120);
                };
                document.addEventListener('DOMContentLoaded', () => {
                    document.body.classList.remove('leaving');
                });
                document.querySelectorAll('a[href]').forEach(a => a.addEventListener('click', enablePageFade));

            } catch (_) { /* no-op */ }
        })();
    </script>
@endsection
