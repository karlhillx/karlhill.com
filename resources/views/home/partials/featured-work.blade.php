@php
    $featured = \App\Support\ProjectCatalog::featured();
@endphp

@include('partials.work', [
    'projects' => $featured,
    'sectionNumber' => '01',
    'heading' => 'Selected Work',
    'showViewAll' => true,
    'proof' => 'Jacobs is current. Public NASA systems:',
    'proofLinks' => [
        [
            'label' => 'Flood map',
            'href' => 'https://floodmapping.gsfc.nasa.gov/',
        ],
        [
            'label' => 'Find Data',
            'href' => 'https://ladsweb.modaps.eosdis.nasa.gov/search/',
        ],
        [
            'label' => 'Paper',
            'href' => '/research/global-flood-mapping',
            'external' => false,
        ],
    ],
])
