@extends('layouts.site', ['meta' => $meta])

@push('head')
    @include('home.partials.structured-data')
    <x-site.speculation-rules :rules="\App\Support\SpeculationRules::forHomepage($featuredPosts)" />
@endpush

@section('content')
    @include('home.partials.hero')
    @include('home.partials.featured-work')
    @include('home.partials.system')
    @include('home.partials.writing')
@endsection

@section('page_footer')
    <x-site.footer variant="home" />
@endsection
