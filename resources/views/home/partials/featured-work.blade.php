@php
    $featured = \App\Support\ProjectCatalog::featured();
@endphp

@include('partials.work', [
    'projects' => $featured,
    'sectionNumber' => '01',
    'heading' => 'Selected Work',
    'showViewAll' => true,
])
