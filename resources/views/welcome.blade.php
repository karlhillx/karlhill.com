@extends('layouts.app')

@section('dark-mode', true)

@section('body-bg', 'bg-white dark:bg-zinc-900 text-gray-900 dark:text-white')

@section('content')

    <section class="reveal-section">
        <x-landing/>
    </section>

    <section class="reveal-section">
        <x-feature/>
    </section>

    <section class="reveal-section">
        <x-core-competencies/>
    </section>

    <section class="reveal-section">
        <x-career-trajectory/>
    </section>

    <section class="reveal-section">
        <x-impact-leadership/>
    </section>

    <section class="reveal-section">
        <x-skills/>
    </section>

    <section class="reveal-section">
        <x-dynamic-skills/>
    </section>

    <section class="reveal-section">
        <x-map/>
    </section>
@endsection
