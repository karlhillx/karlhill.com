@extends('layouts.app')

@section('main-classes', 'pt-32')

@section('content')
    <x-hero class="animate-fade-in"/>

    <x-gallery class="lazy-load"/>

    <x-portfolio-header/>

    <x-portfolio-section1/>

    <x-portfolio-section2/>
@endsection
