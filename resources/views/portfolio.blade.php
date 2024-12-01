@extends('layouts.main')

@section('main-classes', 'pt-32')

@section('content')
    <x-hero/>
    <x-carousel/>
    <div class="sm:px-8 mt-24 md:mt-28">
        <div class="mx-auto max-w-7xl lg:px-8">
            <div class="relative px-4 sm:px-8 lg:px-12">
                <div class="mx-auto max-w-2xl lg:max-w-5xl">
                    <div class="mx-auto grid max-w-xl grid-cols-1 gap-y-20 lg:max-w-none lg:grid-cols-2">
                        <x-blog/>
                        <x-work/>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <x-portfolio-header/>
    <x-portfolio-section1/>
    <x-portfolio-section2/>
@endsection
