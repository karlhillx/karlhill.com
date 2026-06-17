@php
    $featured = collect(config('site.projects'))->where('featured', true)->take(3)->values();
@endphp

@include('home.partials.work', [
    'projects' => $featured,
    'sectionNumber' => '02',
    'heading' => 'Selected Work',
    'showViewAll' => true,
])
