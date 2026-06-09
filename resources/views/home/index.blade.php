@extends('layouts.site', ['meta' => $meta])

@push('head')
    @include('home.partials.structured-data')
@endpush

@section('content')
    @include('home.partials.hero')
    @include('home.partials.latest-writing')
    @include('home.partials.why')
    @include('home.partials.stats')
    @include('home.partials.experience')
    @include('home.partials.work')
    @include('home.partials.research')
    @include('home.partials.stack')
    @include('home.partials.open-source')
    @include('home.partials.certs')
@endsection

@section('page_footer')
    <x-site.footer variant="home" section="08" />
    <nav id="section-minimap" aria-label="Section navigation"></nav>
@endsection
