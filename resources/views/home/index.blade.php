@extends('layouts.site', ['meta' => $meta])

@push('head')
    @include('home.partials.structured-data')
    <x-site.speculation-rules :rules="\App\Support\SpeculationRules::forHomepage($latestPosts)" />
@endpush

@section('content')
    @include('home.partials.hero')
    @include('home.partials.featured-work')
    @include('home.partials.latest-writing')
    @include('home.partials.why')
    @include('home.partials.metrics')
@endsection

@section('page_footer')
    <x-site.footer variant="home" section="05" />
@endsection
