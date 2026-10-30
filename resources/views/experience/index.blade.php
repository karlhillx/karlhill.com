@extends('layouts.site', ['meta' => $meta])

@push('head')
    <x-site.json-ld :data="\App\Support\PersonJsonLd::forNamedPage('experience', '/experience')" />
@endpush

@section('content')
    <x-site.page-hero :breadcrumbs="[
        ['label' => 'Home', 'url' => '/'],
        ['label' => 'Experience'],
    ]">
        <x-slot:title>Experience</x-slot:title>
        <p class="site-page-hero__lede text-neutral-300 max-w-2xl">{{ config('site.experience.intro') }}</p>
    </x-site.page-hero>

    @include('about.partials.arc')
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
