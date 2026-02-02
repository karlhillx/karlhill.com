<!-- Enterprise Projects Section -->
<section
    class="relative bg-gradient-to-b from-gray-50 to-white py-16 px-5 transition-colors duration-300 reveal-section">
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
        <div
            class="max-w-lg mx-auto grid gap-8 lg:grid-cols-3 lg:max-w-none opacity-0 translate-y-4 transition-all duration-700"
            x-data="{}"
            x-init="setTimeout(() => $el.classList.remove('opacity-0', 'translate-y-4'), 100)">

            <!-- IDNAPortal Project Card -->
            <div
                class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white relative"
                @mouseenter="setActiveCard(2)"
                @mouseleave="resetActiveCard()"
                :class="{'ring-2 ring-indigo-500 ring-offset-2 ring-offset-white': activeCard === 2}">
                <div class="flex-shrink-0 overflow-hidden">
                    <img class="h-48 object-cover object-left-top transition-all duration-500"
                         loading="lazy"
                         src="/img/ss-informeddna.png"
                         :class="{'scale-110 brightness-110': activeCard === 2}"
                         alt="IDNAPortal Screenshot">
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-indigo-600">
                            <a class="hover:underline transition-colors"> Application </a>
                        </p>
                        <a href="#" class="block mt-2">
                            <p class="text-xl font-semibold text-gray-900"><a
                                    href="https://idnaportal.com/"
                                    alt="" target="_blank">IDNAPortal</a></p>
                            <p class="mt-3 text-base text-gray-500 link-underline">
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
                                class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded-md border uppercase px-4 py-2 inline-block hover:text-white hover:bg-blue-500 hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md"
                                :class="{'bg-blue-500 text-white border-blue-500 -translate-y-1 shadow-md': activeCard === 2}">
                                <a
                                    href="#"
                                    @click.prevent="openModal('/img/ss-informeddna.png')"
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
                    <hr class="border-slate-100 border-t mt-4">
                    <div class="mt-4 flex items-center">
                        <div class="flex-shrink-0">
                            <a href="#">
                                <img class="h-10 w-10 rounded-full" src="/img/logo-informeddna.png"
                                     alt="InformedDNA Logo">
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

            <!-- Taylor MDA Project Card -->
            <div
                class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white relative"
                @mouseenter="setActiveCard(3)"
                @mouseleave="resetActiveCard()"
                :class="{'ring-2 ring-indigo-500 ring-offset-2 ring-offset-white': activeCard === 3}">
                <div class="flex-shrink-0 overflow-hidden">
                    <img class="h-48 w-full object-cover transition-all duration-500"
                         loading="lazy"
                         src="/img/ss-dante.png"
                         :class="{'scale-110 brightness-110': activeCard === 3}"
                         alt="Taylor MDA Screenshot">
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-indigo-600">
                            <a href="#" class="hover:underline transition-colors">Tool for Building Enterprise
                                Applications</a>
                        </p>
                        <span class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900">
                                    <a href="https://www.danteinc.com/" target="_blank">Taylor MDA</a>
                                </p>
                                <p class="mt-3 text-base text-gray-500 link-underline">
                                    At Dante Inc., I contributed to Taylor MDA, an advanced Eclipse-based UML modeling tool for multi-tiered, distributed systems. It streamlines complex system design and maximizes code generation from UML models. Taylor MDA features pre-designed templates for JEE applications, integrating JPA/EJB3 and JSF/Seam/Facelets, accelerating enterprise application development through innovative model-driven architecture techniques. My work enhanced its capabilities and user experience.
                                </p>
                            </span>
                        <div class="mt-4">
                            <div
                                class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded-md border uppercase px-4 py-2 inline-block hover:text-white hover:bg-blue-500 hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md"
                                :class="{'bg-blue-500 text-white border-blue-500 -translate-y-1 shadow-md': activeCard === 3}">
                                <a
                                    href="#"
                                    @click.prevent="openModal('/img/ss-dante.png')"
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
                        <hr class="border-slate-100 border-t mt-4">
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-dante-small.png"
                                         alt="Dante Inc. Logo">
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

            <!-- Verizon Business Finium Card -->
            <div
                class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white relative"
                @mouseenter="setActiveCard(1)"
                @mouseleave="resetActiveCard()"
                :class="{'ring-2 ring-indigo-500 ring-offset-2 ring-offset-white': activeCard === 1}">
                <div class="flex-shrink-0 overflow-hidden">
                    <img class="h-48 w-full object-cover transition-all duration-500"
                         loading="lazy"
                         src="/img/ss-mci-verizon.png"
                         :class="{'scale-110 brightness-110': activeCard === 1}"
                         alt="Verizon Business Finium Screenshot">
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-indigo-600">
                            <a href="#" class="hover:underline transition-colors">Application & Backend
                                Administration</a>
                        </p>
                        <div class="block mt-2">
                            <p class="text-xl font-semibold text-gray-900">Verizon Business Finium™</p>
                            <p class="mt-3 text-base text-gray-500 link-underline">
                                Verizon Business' Finium™ platform transforms cybersecurity by unifying threat intelligence, vulnerability data, and security events in a centralized web console. Built on a disaster-resilient Security Operations Center, this advanced system empowers analysts and business leaders to treat security as a strategic asset. My work on Finium™ helped reshape enterprise cybersecurity, making it more accessible and integral to business operations in today’s digital landscape.                 </p>
                        </div>
                        <div class="mt-4">
                            <div
                                class="font-bold tracking-tight text-sm text-blue-400 border-blue-400 rounded-md border uppercase px-4 py-2 inline-block hover:text-white hover:bg-blue-500 hover:border-blue-500 transition-all duration-300 transform hover:-translate-y-1 shadow-sm hover:shadow-md"
                                :class="{'bg-blue-500 text-white border-blue-500 -translate-y-1 shadow-md': activeCard === 1}">
                                <a
                                    href="#"
                                    @click.prevent="openModal('/img/ss-mci-verizon.png')"
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
                    <hr class="border-slate-100 border-t mt-4">
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

        </div>
    </div>
</div>
</section>
