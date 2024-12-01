<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full font-sans antialiased">
<head>
    @include('partials.head')
</head>
<body class="font-sans bg-white">
<div class="flex flex-col min-h-screen">
    @include('components.header')

    <main class="flex-grow pt-32">

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

    </main>
    @include('components.footer')
</div>
@include('components.back-to-top')
@include('components.scripts')
@include('components.schema')
</body>
</html>
