@extends('layouts.main')

@section('dark-mode', true)

@section('body-bg', 'bg-maroon-dream dark:bg-maroon-dream text-gray-900 dark:text-white')

@section('content')
    <x-landing/>
    <x-feature/>
    <x-skills/>
    <x-core-competencies/>
    <x-map/>
@endsection</html>
