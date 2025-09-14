@extends('layouts.app')

@section('main-classes', 'pt-2')

@section('content')
    <x-hero class="animate-fade-in"/>

    <x-gallery class="lazy-load"/>

    <x-portfolio-header/>

    <x-portfolio-section1/>

    <x-portfolio-section2/>
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
                    document.querySelectorAll('.reveal-section').forEach(el => el.classList.add('in-view'));
                }

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
