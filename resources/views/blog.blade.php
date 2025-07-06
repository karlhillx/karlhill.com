@extends('layouts.app')

@section('main-classes', 'pt-32')

@section('content')
    <x-hero class="animate-fade-in"/>

    <div class="container mx-auto px-4 py-12">
        <x-blog/>
    </div>
@endsection
