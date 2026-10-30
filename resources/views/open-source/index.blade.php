@extends('layouts.site', ['meta' => $meta])

@section('content')
    <x-site.page-hero :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Open Source'],
    ]">
        <x-slot:title>Open Source</x-slot:title>
        <p class="site-page-hero__lede text-neutral-300 max-w-2xl">{{ config('site.work.collections.tooling.intro') }}</p>
        <div class="site-page-hero__actions">
            <x-site.button variant="primary" href="/work/developer-tooling">Read the case study</x-site.button>
            <x-site.button variant="secondary" href="https://github.com/karlhillx" target="_blank" rel="noopener noreferrer">GitHub</x-site.button>
        </div>
    </x-site.page-hero>

    <x-site.section id="repositories" section-label="Repositories" border="none">
        <x-site.tooling-list />
    </x-site.section>
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
