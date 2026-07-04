@extends('layouts.site', ['meta' => $meta])

@push('head')
<x-site.speculation-rules :rules="\App\Support\SpeculationRules::forWorkIndex()" />
@endpush

@section('content')
    @php
        $breadcrumbs = [
            ['label' => 'Home', 'url' => '/'],
        ];
        if ($activeTag) {
            $breadcrumbs[] = ['label' => 'Work', 'url' => '/work'];
            $breadcrumbs[] = ['label' => $activeTag];
        } else {
            $breadcrumbs[] = ['label' => 'Work'];
        }
    @endphp

    <x-site.page-hero eyebrow="Portfolio" :breadcrumbs="$breadcrumbs">
        <x-slot:title>Selected Work</x-slot:title>

        <p class="text-neutral-400 text-base leading-relaxed max-w-2xl">
            Mission-critical platforms across NASA Earth science, disaster response, clinical genomics, and enterprise security.
        </p>
    </x-site.page-hero>

    @if($allTags->isNotEmpty())
        <section class="px-6 pb-4 border-t border-neutral-800">
            <div class="max-w-6xl mx-auto pt-10">
                <x-site.tag-filter
                    :all-url="route('work')"
                    :tags="$allTags"
                    :active-tag="$activeTag"
                    :url-for="fn ($tag) => route('work.tag', \App\Support\ProjectCatalog::tagSlug($tag))"
                />
            </div>
        </section>
    @endif

    @include('partials.work', [
        'projects' => $projects,
        'sectionNumber' => '01',
        'heading' => $activeTag ? "Projects · {$activeTag}" : 'Projects',
    ])

    @include('partials.open-source', ['sectionNumber' => '02'])
@endsection

@section('page_footer')
    <x-site.footer />
@endsection
