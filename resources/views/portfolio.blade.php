<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full font-sans antialiased">
<head>
    @include('partials.head')
</head>
<body class="font-sans bg-white">
<div class="flex flex-col min-h-screen">
    @include('components.header')

    <main class="flex-grow pt-32">
        <section id="recent-work">
            <main>
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
            </main>

            <div class="relative bg-gray-50 pb-20 pt-20 pl-5 pr-5">
                <div class="absolute inset-0">
                    <div class="bg-white h-1/3 sm:h-2/3"></div>
                </div>
                <div class="relative max-w-7xl mx-auto">
                    <div class="text-center">
                        <div>
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor"
                                     viewBox="0 0 48 48" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <h2 class="text-3xl tracking-tight font-bold text-gray-900 sm:text-4xl sm:tracking-tight">
                            Portfolio</h2>
                        <p class="mt-3 max-w-2xl mx-auto text-xl text-gray-500 sm:mt-4">
                            A brief, curated list of projects I've worked on over the years in professional settings.
                        </p>
                    </div>
                    <div class="mt-12 max-w-lg mx-auto grid gap-5 lg:grid-cols-3 lg:max-w-none">
                        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                            <div class="flex-shrink-0">
                                <img class="h-48 w-full object-cover"
                                     src="/img/ss-esccor.png"
                                     alt="">
                            </div>
                            <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-indigo-600">
                                        <a href="#" class="hover:underline"> Application </a>
                                    </p>
                                    <a href="#" class="block mt-2">
                                        <p class="text-xl font-semibold text-gray-900">
                                            <a href="https://www.earthdata.nasa.gov/esds" target="_blank">
                                                Earth Science Communications Content Registry (ESCCOR)
                                            </a>
                                        </p>
                                        <p class="mt-3 text-base text-gray-500">
                                            ESCCOR is a cutting-edge platform revolutionizing Earth science data
                                            management.
                                            This system integrates advanced taxonomy, AI-powered indexing, and a
                                            high-performance catalog, accessible through a user-friendly portal. ESCCOR
                                            enhances
                                            NASA's Earth Sciences Division's reporting, product access, and cross-team
                                            collaboration, positioning NASA at the forefront of Earth science data
                                            management.
                                            My work significantly improved NASA's handling of vast Earth science
                                            information,
                                            benefiting multiple NASA groups and advancing the agency's mission.
                                        </p>
                                    </a>

                                    <div class="mt-4">
                                        <div
                                            class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">
                                            <a href="/img/ss-esccor.png" target="_blank">
                                                VIEW SCREENSHOT
                                            </a>
                                        </div>
                                    </div>
                                    <hr class="border-slate-100 border-t mt-4">
                                </div>
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <a href="#">
                                            <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                        </a>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="#" class="hover:underline"> Laravel, Apache Nutch,
                                                ElasticSearch </a>
                                        </p>
                                        <div class="flex space-x-1 text-sm text-gray-500">
                                            <time datetime="2020-03-16"> NASA Goddard Space Flight Center</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                            <picture>
                                <source srcset="/img/webp/small-earth-observatory.webp" type="image/webp">
                                <img class="h-48 w-full object-cover" src="/img/small-earth-observatory.png"
                                     alt="Earth Observatory">
                            </picture>

                            <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-indigo-600">
                                        <a href="#" class="hover:underline"> Website & Backend Administration </a>
                                    </p>
                                    <a href="#" class="block mt-2">
                                        <p class="text-xl font-semibold text-gray-900"><a
                                                href="https://earthobservatory.nasa.gov/"
                                                alt="" target="_blank">Earth
                                                Observatory</a></p>
                                        <p class="mt-3 text-base text-gray-500">
                                            I helped develop NASA's Earth Observatory website and its advanced back-end
                                            system, creating a digital gateway to NASA's environmental research. This
                                            platform showcases stunning imagery, compelling stories, and crucial
                                            discoveries about Earth's ecosystems and climate from NASA's satellite
                                            missions and field research. Using cutting-edge web technologies, we crafted
                                            an immersive interface that brings complex scientific data to life for a
                                            global audience, supporting NASA's mission to foster public understanding of
                                            our planet's intricate systems and environmental changes. The site serves as
                                            an invaluable resource for scientists, educators, policymakers, and the
                                            public alike.
                                        </p>
                                    </a>
                                    <div class="mt-4">
                                        <div
                                            class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">

                                            <a href="/img/screencapture-earthobservatory-nasa-gov.png" target="_blank">
                                                VIEW SCREENSHOT
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <hr class="border-slate-100 border-t mt-4">
                                <div class="mt-6 flex items-center">
                                    <div class="flex-shrink-0">
                                        <a href="#">
                                            <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                        </a>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="#" class="hover:underline"> Laravel, Bootstrap, Laravel Mix </a>
                                        </p>
                                        <div class="flex space-x-1 text-sm text-gray-500">
                                            <time datetime="2020-03-10"> NASA Goddard Space Flight Center</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                            <div class="flex-shrink-0">
                                <img class="h-48 w-full object-cover" src="/img/ss-direct-readout2.png" alt="">
                            </div>
                            <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-indigo-600">
                                        <a href="#" class="hover:underline"> Website Case Study </a>
                                    </p>
                                    <a href="#" class="block mt-2">
                                        <p class="text-xl font-semibold text-gray-900"><a
                                                href="https://directreadout.sci.gsfc.nasa.gov/" alt=""
                                                target="_blank">Direct
                                                Readout Laboratory</a></p>
                                        <p class="mt-3 text-base text-gray-500">
                                            The Direct Readout Laboratory (DRL) plays a vital role in connecting NASA's
                                            Earth observation missions with data users worldwide. I designed a concept
                                            website for the DRL, envisioning a modern and dynamic platform built with
                                            cutting-edge web technologies like Laravel, Tailwind CSS, and Vite. This
                                            responsive design would effectively showcase the DRL's innovative work in
                                            facilitating real-time data transmission from Earth-observing satellites
                                            directly to ground stations across the globe. The website's intuitive
                                            interface would provide users with seamless access to mission-critical
                                            information, valuable tools, and essential resources, like data
                                            visualization tools and educational materials, enhancing environmental
                                            monitoring capabilities and supporting vital scientific research on a global
                                            scale. By empowering users with timely and accessible data, the DRL website
                                            aims to strengthen international collaboration and contribute to a deeper
                                            understanding of our changing planet.
                                        <div class="mt-4">
                                            <div
                                                class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">

                                                <a href="/img/ss-direct-readout.png" alt="" target="_blank">
                                                    VIEW SCREENSHOT
                                                </a>
                                            </div>
                                        </div>
                                </div>
                                <hr class="border-slate-100 border-t mt-4">
                                <div class="mt-6 flex items-center">
                                    <div class="flex-shrink-0">
                                        <a href="#">
                                            <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                        </a>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="#" class="hover:underline"> Laravel, Tailwind, Vite</a>
                                        </p>
                                        <div class="flex space-x-1 text-sm text-gray-500">
                                            <time datetime="2020-02-12"> NASA Goddard Space Flight Center</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="portfolio2">
            <div class="relative bg-gray-50 pb-20 border border-gray-200 pl-5 pr-5">
                <div class="absolute inset-0">
                    <div class="bg-white h-1/3 sm:h-2/3"></div>
                </div>
                <div class="relative max-w-7xl mx-auto">
                    <div class="mt-12 max-w-lg mx-auto grid gap-5 lg:grid-cols-3 lg:max-w-none">
                        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                            <div class="flex-shrink-0">

                                <img class="h-48 w-full object-cover"
                                     src="/img/ss-mci-verizon.png"
                                     alt="">

                            </div>
                            <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-indigo-600">
                                        <a href="#" class="hover:underline"> Application & Backend Administration</a>
                                    </p>
                                    <a href="#" class="block mt-2">
                                        <p class="text-xl font-semibold text-gray-900"> Verizon Business
                                            Finium™</p>
                                        <p class="mt-3 text-base text-gray-500">
                                            Verizon Business' Finium™ platform revolutionizes cybersecurity by
                                            integrating
                                            threat intelligence, vulnerability data, and security events in a
                                            centralized
                                            web
                                            console. Operating from a disaster-resilient Security Operations Center,
                                            this
                                            cutting-edge system empowers analysts and business leaders to proactively
                                            manage
                                            security as a strategic asset. My work on Finium™ contributed to
                                            transforming
                                            enterprise cybersecurity, making it an accessible, integral part of business
                                            operations in our complex digital landscape.
                                        </p>
                                    </a>


                                    <div class="mt-4">
                                        <div
                                            class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">
                                            <a href="/img/ss-mci-verizon.png" target="_blank">
                                                VIEW SCREENSHOT
                                            </a>
                                        </div>
                                    </div>

                                    <hr class="border-slate-100 border-t mt-4">
                                </div>
                                <div class="mt-6 flex items-center">
                                    <div class="flex-shrink-0">
                                        <a href="#">
                                            <img class="h-10 w-10 rounded-full" src="/img/logo-verizon.png" alt="">
                                        </a>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="#" class="hover:underline"> JAVA, JSF, Struts </a>
                                        </p>
                                        <div class="flex space-x-1 text-sm text-gray-500">
                                            <time datetime="2020-03-16"> Verizon Business</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                            <div class="flex-shrink-0">
                                <img class="h-48 object-cover object-left-top" src="/img/ss-informeddna.png" alt="">
                            </div>
                            <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-indigo-600">
                                        <a class="hover:underline"> Application </a>
                                    </p>
                                    <a href="#" class="block mt-2">
                                        <p class="text-xl font-semibold text-gray-900"><a
                                                href="https://idnaportal.com/"
                                                alt="" target="_blank">IDNAPortal</a></p>
                                        <p class="mt-3 text-base text-gray-500">
                                            InformedDNA leads the applied genomics field, optimizing clinical decisions
                                            with
                                            cutting-edge genomics expertise. Their IDNAPortal, which I developed, is a
                                            sophisticated platform that analyzes and interprets genomic data. It
                                            provides
                                            healthcare providers with actionable insights from years of clinical and
                                            financial
                                            data, enabling personalized care decisions. This innovative tool streamlines
                                            complex
                                            genomic information, enhancing treatment strategies in precision medicine.
                                        </p>
                                    </a>

                                    <div class="mt-4">
                                        <div
                                            class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">

                                            <a href="/img/ss-informeddna.png" target="_blank">
                                                VIEW SCREENSHOT
                                            </a>
                                        </div>
                                    </div>
                                    <hr class="border-slate-100 border-t mt-4">
                                </div>
                                <div class="mt-4 flex items-center">
                                    <div class="flex-shrink-0">
                                        <a href="#">
                                            <img class="h-10 w-10 rounded-full" src="/img/logo-informeddna.png" alt="">
                                        </a>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="#" class="hover:underline"> Laravel, Bootstrap, SugarCRM </a>
                                        </p>
                                        <div class="flex space-x-1 text-sm text-gray-500">
                                            <time datetime="2020-03-10">InformedDNA</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col rounded-lg shadow-lg overflow-hidden">
                            <div class="flex-shrink-0">

                                <img class="h-48 w-full object-cover" src="/img/ss-dante.png" alt="">

                            </div>
                            <div class="flex-1 bg-white p-6 flex flex-col justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-indigo-600">
                                        <a href="#" class="hover:underline">Tool for Building Enterprise
                                            Applications</a>
                                    </p>
                                    <span class="block mt-2">
                                        <p class="text-xl font-semibold text-gray-900"><a
                                                href="https://www.danteinc.com/" alt=""
                                                target="_blank">Taylor MDA</a></p>
                                        <p class="mt-3 text-base text-gray-500">
                                            At Dante Inc., I contributed to Taylor MDA, an advanced Eclipse-based UML modeling tool for multi-tiered, distributed systems. It streamlines complex system design and maximizes code generation from UML models. Taylor MDA features pre-designed templates for JEE applications, integrating JPA/EJB3 and JSF/Seam/Facelets, accelerating enterprise application development through innovative model-driven architecture techniques. My work enhanced its capabilities and user experience.
                                        </p>
<div class="mt-4">
    <div
        class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">

                                                <a href="img/ss-dante.png" target="_blank">
                                                    VIEW SCREENSHOT
                                                </a>
                                            </div>
</div>


                                <hr class="border-slate-100 border-t mt-4">
                                <div class="mt-6 flex items-center">
                                    <div class="flex-shrink-0">
                                        <a href="#">
                                            <img class="h-10 w-10 rounded-full" src="/img/logo-dante-small.png" alt="">
                                        </a>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">
                                            <a href="#" class="hover:underline"> JAVA, JSP, RichFaces</a>
                                        </p>
                                        <div class="flex space-x-1 text-sm text-gray-500">
                                            <time datetime="2020-02-12"> Dante Inc.</time>
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    @include('components.footer')
</div>
@include('components.back-to-top')
@include('components.scripts')
@include('components.schema')
</body>
</html>
