@extends('layouts.site', ['meta' => $meta])

@section('content')
    <section class="site-section" aria-label="Contact validation fixture">
        <div class="site-shell">
            <h1 class="font-display text-4xl tracking-wide text-white mb-4">Contact errors</h1>
            <p class="text-neutral-400 text-sm leading-relaxed max-w-xl mb-2">
                CI fixture — invalid fields are already in an error state.
            </p>
            <x-site.contact-form id-prefix="a11y" :return-to="url('/')" />
        </div>
    </section>
@endsection

{{-- Non-empty so @hasSection('page_footer') is true and the site footer (second form) is omitted. --}}
@section('page_footer')
    <div class="sr-only">End of accessibility fixture</div>
@endsection
