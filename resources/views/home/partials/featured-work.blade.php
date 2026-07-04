@php
    $featured = \App\Support\ProjectCatalog::featured();
@endphp

@include('partials.work', [
    'projects' => $featured,
    'sectionNumber' => '02',
    'heading' => 'Selected Work',
    'showViewAll' => true,
])
