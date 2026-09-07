@php
    $featured = \App\Support\ProjectCatalog::featured();
@endphp

@include('partials.work', [
    'projects' => $featured,
    'sectionNumber' => '01',
    'heading' => 'Selected Work',
    'showViewAll' => true,
    'proof' => 'Jacobs leadership chapter · NASA platforms you can open',
])
