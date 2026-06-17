@extends('layouts.site', ['meta' => $meta])

@push('head')
    @include('home.partials.structured-data')
    <x-site.speculation-rules :rules="\App\Support\SpeculationRules::forHomepage($latestPost)" />
@endpush

@section('content')
    @include('home.partials.hero')
    @include('home.partials.latest-writing')
    @include('home.partials.why')
    @include('home.partials.featured-work')
@endsection

@section('page_footer')
    <x-site.footer variant="home" section="03" />
    <nav id="section-minimap" class="section-minimap" aria-label="Section navigation"></nav>
@endsection
