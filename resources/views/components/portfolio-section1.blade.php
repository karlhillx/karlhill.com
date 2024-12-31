<!-- NASA Projects Section -->
<section class="relative bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 py-16 px-5 transition-colors duration-300">
    <div x-data="{
        selectedImage: null,
        openModal(imageSrc) {
            this.selectedImage = imageSrc;
        },
        closeModal() {
            this.selectedImage = null;
        }
    }">
        <!-- Image Modal -->
        <template x-if="selectedImage">
            <div
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-75 p-4"
                @click.self="closeModal"
            >
                <div
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                    class="max-w-5xl max-h-[90vh] overflow-auto"
                >
                    <img
                        :src="selectedImage"
                        class="max-w-full max-h-full object-contain rounded-lg shadow-2xl"
                        @click.stop
                    >
                    <button
                        @click="closeModal"
                        class="absolute top-4 right-4 text-white text-4xl hover:text-gray-300"
                    >
                        ×
                    </button>
                </div>
            </div>
        </template>
        <div class="relative max-w-7xl mx-auto">
            <div class="max-w-lg mx-auto grid gap-8 lg:grid-cols-3 lg:max-w-none opacity-0 translate-y-4 transition-all duration-700"
                 x-data="{}"
                 x-init="setTimeout(() => $el.classList.remove('opacity-0', 'translate-y-4'), 100)">

                <!-- ESCCOR Project Card -->
                <div class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white dark:bg-gray-800">
                    <div class="flex-shrink-0">
                        <img class="h-48 w-full object-cover transition-opacity duration-300"
                             loading="lazy"
                             src="/img/ss-esccor.png"
                             alt="ESCCOR Project Screenshot">
                    </div>
                    <div class="flex-1 p-6 flex flex-col justify-between dark:bg-gray-800">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                <a href="#" class="hover:underline transition-colors">Application</a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    <a href="https://www.earthdata.nasa.gov/esds" target="_blank">
                                        Earth Science Communications Content Registry (ESCCOR)
                                    </a>
                                </p>
                                <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
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
                                    <a
                                        href="#"
                                        @click.prevent="openModal('/img/ss-esccor.png')"
                                    >
                                        VIEW SCREENSHOT
                                    </a>
                                </div>
                            </div>
                            <hr class="border-slate-100 dark:border-slate-700 border-t mt-4">
                        </div>
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    <a href="#" class="hover:underline"> Laravel, Apache Nutch,
                                        ElasticSearch </a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500 dark:text-gray-400">
                                    <time datetime="2020-03-16"> NASA Goddard Space Flight Center</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Earth Observatory Project Card -->
                <div class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white dark:bg-gray-800">
                    <picture>
                        <source srcset="/img/webp/small-earth-observatory.webp" type="image/webp">
                        <img class="h-48 w-full object-cover transition-opacity duration-300"
                             loading="lazy"
                             src="/img/small-earth-observatory.png"
                             alt="Earth Observatory Screenshot">
                    </picture>
                    <div class="flex-1 p-6 flex flex-col justify-between dark:bg-gray-800">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                <a href="#" class="hover:underline transition-colors">Website & Backend Administration</a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900 dark:text-white"><a
                                        href="https://earthobservatory.nasa.gov/"
                                        alt="" target="_blank">Earth
                                        Observatory</a></p>
                                <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
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
                                    <a
                                        href="#"
                                        @click.prevent="openModal('/img/screencapture-earthobservatory-nasa-gov.png')"
                                    >
                                        VIEW SCREENSHOT
                                    </a>
                                </div>
                            </div>
                        </div>
                        <hr class="border-slate-100 dark:border-slate-700 border-t mt-4">
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    <a href="#" class="hover:underline"> Laravel, Bootstrap, Laravel Mix </a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500 dark:text-gray-400">
                                    <time datetime="2020-03-10"> NASA Goddard Space Flight Center</time>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Direct Readout Laboratory Project Card -->
                <div class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white dark:bg-gray-800">
                    <div class="flex-shrink-0">
                        <img class="h-48 w-full object-cover transition-opacity duration-300"
                             loading="lazy"
                             src="/img/ss-direct-readout2.png"
                             alt="Direct Readout Laboratory Screenshot">
                    </div>
                    <div class="flex-1 p-6 flex flex-col justify-between dark:bg-gray-800">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                                <a href="#" class="hover:underline transition-colors">Website Case Study</a>
                            </p>
                            <a href="#" class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900 dark:text-white"><a
                                        href="https://directreadout.sci.gsfc.nasa.gov/" alt=""
                                        target="_blank">Direct
                                        Readout Laboratory</a></p>
                                <p class="mt-3 text-base text-gray-500 dark:text-gray-400">
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
                                </p>
                                <div class="mt-4">
                                    <div
                                        class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600">
                                        <a
                                            href="#"
                                            @click.prevent="openModal('/img/ss-direct-readout.png')"
                                        >
                                            VIEW SCREENSHOT
                                        </a>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <hr class="border-slate-100 dark:border-slate-700 border-t mt-4">
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-nasa.png" alt="">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    <a href="#" class="hover:underline"> Laravel, Tailwind, Vite</a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500 dark:text-gray-400">
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
