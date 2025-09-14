<!-- NASA Projects Section -->
<section class="relative bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 py-16 px-5 transition-colors duration-300 reveal-section">
    <div x-data="{
        selectedImage: null,
        activeCard: null,
        openModal(imageSrc) {
            this.selectedImage = imageSrc;
        },
        closeModal() {
            this.selectedImage = null;
        },
        setActiveCard(index) {
            this.activeCard = index;
        },
        resetActiveCard() {
            this.activeCard = null;
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
                class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-90 p-4 backdrop-blur-sm"
                @click.self="closeModal"
                @keydown.escape.window="closeModal"
            >
                <div
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-90"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-90"
                    class="relative max-w-5xl max-h-[90vh] overflow-auto"
                >
                    <img
                        :src="selectedImage"
                        class="max-w-full max-h-full object-contain rounded-lg shadow-2xl border-4 border-white/10"
                        @click.stop
                    >
                    <button
                        @click="closeModal"
                        class="absolute top-4 right-4 bg-black/50 text-white rounded-full w-10 h-10 flex items-center justify-center text-2xl hover:bg-black/80 hover:text-white transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white"
                        aria-label="Close modal"
                    >
                        ×
                    </button>
                    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 bg-black/50 text-white text-sm px-4 py-2 rounded-full">
                        Click anywhere outside to close
                    </div>
                </div>
            </div>
        </template>
        <div class="relative max-w-7xl mx-auto">
            <div class="max-w-lg mx-auto grid gap-8 lg:grid-cols-3 lg:max-w-none opacity-0 translate-y-4 transition-all duration-700"
                 x-data="{}"
                 x-init="setTimeout(() => $el.classList.remove('opacity-0', 'translate-y-4'), 100)">

                <!-- ESCCOR Project Card -->
                <div class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white dark:bg-gray-800 relative"
                     @mouseenter="setActiveCard(1)"
                     @mouseleave="resetActiveCard()"
                     :class="{'ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-gray-900': activeCard === 1}">
                    <div class="flex-shrink-0 overflow-hidden">
                        <img class="h-48 w-full object-cover transition-all duration-500"
                             loading="lazy"
                             src="/img/ss-esccor.png"
                             :class="{'scale-110 brightness-110': activeCard === 1}"
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
                                <p class="mt-3 text-base text-gray-500 dark:text-gray-400 link-underline">
                                    ESCCOR is a cutting-edge platform revolutionizing Earth science data management. This system integrates advanced taxonomy, AI-powered indexing, and a high-performance catalog, accessible through a user-friendly portal. ESCCOR enhances NASA's Earth Sciences Division's reporting, product access, and cross-team collaboration, positioning NASA at the forefront of Earth science data management. My work significantly improved NASA's handling of vast science information, benefiting multiple NASA groups and advancing the agency's mission. Together, these innovations make critical data more discoverable, usable, and impactful than ever before.
                                </p>
                            </a>

                            <div class="mt-4">
                                <div
                                    class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded-md border uppercase px-4 py-2 inline-block hover:text-white hover:bg-blue-500 hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md"
                                    :class="{'bg-blue-500 text-white border-blue-500 -translate-y-1 shadow-md': activeCard === 1}">
                                    <a
                                        href="#"
                                        @click.prevent="openModal('/img/ss-esccor.png')"
                                        class="flex items-center gap-2"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
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
                <div class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white dark:bg-gray-800 relative"
                     @mouseenter="setActiveCard(2)"
                     @mouseleave="resetActiveCard()"
                     :class="{'ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-gray-900': activeCard === 2}">
                    <div class="flex-shrink-0 overflow-hidden">
                        <picture>
                            <source srcset="/img/webp/small-earth-observatory.webp" type="image/webp">
                            <img class="h-48 w-full object-cover transition-all duration-500"
                                loading="lazy"
                                src="/img/small-earth-observatory.png"
                                :class="{'scale-110 brightness-110': activeCard === 2}"
                                alt="Earth Observatory Screenshot">
                        </picture>
                    </div>
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
                                <p class="mt-3 text-base text-gray-500 dark:text-gray-400 link-underline">
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
                                    class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded-md border uppercase px-4 py-2 inline-block hover:text-white hover:bg-blue-500 hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md"
                                    :class="{'bg-blue-500 text-white border-blue-500 -translate-y-1 shadow-md': activeCard === 2}">
                                    <a
                                        href="#"
                                        @click.prevent="openModal('/img/screencapture-earthobservatory-nasa-gov.png')"
                                        class="flex items-center gap-2"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
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
                <div class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white dark:bg-gray-800 relative"
                     @mouseenter="setActiveCard(3)"
                     @mouseleave="resetActiveCard()"
                     :class="{'ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-gray-900': activeCard === 3}">
                    <div class="flex-shrink-0 overflow-hidden">
                        <img class="h-48 w-full object-cover transition-all duration-500"
                             loading="lazy"
                             src="/img/ss-direct-readout2.png"
                             :class="{'scale-110 brightness-110': activeCard === 3}"
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
                                <p class="mt-3 text-base text-gray-500 dark:text-gray-400 link-underline">
                                    The Direct Readout Laboratory (DRL) bridges NASA’s Earth-observing missions with users worldwide. I created a concept website that reimagines DRL’s presence as a modern, responsive platform built with Laravel, Tailwind CSS, and Vite. The design highlights DRL’s role in enabling real-time satellite data transmission to ground stations across the globe.

                                    With an intuitive interface and accessible tools—such as data visualizations and educational resources—the site empowers users to explore mission data, support environmental monitoring, and strengthen global scientific collaboration. This concept shows how thoughtful design can turn complex science into an engaging, user-friendly experience.
                                </p>
                                <div class="mt-4">
                                    <div
                                        class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded-md border uppercase px-4 py-2 inline-block hover:text-white hover:bg-blue-500 hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md"
                                        :class="{'bg-blue-500 text-white border-blue-500 -translate-y-1 shadow-md': activeCard === 3}">
                                        <a
                                            href="#"
                                            @click.prevent="openModal('/img/ss-direct-readout.png')"
                                            class="flex items-center gap-2"
                                        >
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
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
