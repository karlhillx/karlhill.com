<!-- Enterprise Projects Section -->
<section
    class="relative bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 py-16 px-5 transition-colors duration-300">
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
                    class="max-w-5xl max-h-[90vh] overflow-contain"
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
        <div
            class="max-w-lg mx-auto grid gap-8 lg:grid-cols-3 lg:max-w-none opacity-0 translate-y-4 transition-all duration-700"
            x-data="{}"
            x-init="setTimeout(() => $el.classList.remove('opacity-0', 'translate-y-4'), 100)">

            <!-- Verizon Business Finium Card -->
            <div
                class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white dark:bg-gray-800">
                <div class="flex-shrink-0">
                    <img class="h-48 w-full object-cover transition-opacity duration-300"
                         loading="lazy"
                         src="/img/ss-mci-verizon.png"
                         alt="Verizon Business Finium Screenshot">
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between dark:bg-gray-800">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                            <a href="#" class="hover:underline transition-colors">Application & Backend
                                Administration</a>
                        </p>
                        <div class="block mt-2">
                            <p class="text-xl font-semibold text-gray-900 dark:text-white">Verizon Business Finium™</p>
                            <p class="mt-3 text-base text-gray-500 dark:text-gray-300">
                                Verizon Business' Finium™ platform revolutionizes cybersecurity by integrating threat
                                intelligence, vulnerability data, and security events in a centralized web console.
                                Operating from a disaster-resilient Security Operations Center, this cutting-edge system
                                empowers analysts and business leaders to proactively manage security as a strategic
                                asset. My work on Finium™ contributed to transforming enterprise cybersecurity, making
                                it an accessible, integral part of business operations in our complex digital landscape.
                            </p>
                        </div>
                        <div class="mt-4">
                            <div
                                class="font-bold tracking-tight text-sm text-blue-400 dark:text-blue-300 border-blue-400 dark:border-blue-300 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600 transition-colors">
                                <a
                                    href="#"
                                    @click.prevent="openModal('/img/ss-mci-verizon.png')"
                                >
                                    VIEW SCREENSHOT
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr class="border-slate-100 dark:border-gray-700 border-t mt-4">
                    <div class="mt-6 flex items-center">
                        <div class="flex-shrink-0">
                            <a href="#">
                                <img class="h-10 w-10 rounded-full" src="/img/logo-verizon.png" alt="">
                            </a>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <a href="#" class="hover:underline"> JAVA, JSF, Struts </a>
                            </p>
                            <div class="flex space-x-1 text-sm text-gray-500 dark:text-gray-400">
                                <time datetime="2020-03-16"> Verizon Business</time>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- IDNAPortal Project Card -->
            <div
                class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white dark:bg-gray-800">
                <div class="flex-shrink-0">
                    <img class="h-48 object-cover object-left-top transition-opacity duration-300"
                         loading="lazy"
                         src="/img/ss-informeddna.png"
                         alt="IDNAPortal Screenshot">
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between dark:bg-gray-800">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                            <a class="hover:underline transition-colors"> Application </a>
                        </p>
                        <a href="#" class="block mt-2">
                            <p class="text-xl font-semibold text-gray-900 dark:text-white"><a
                                    href="https://idnaportal.com/"
                                    alt="" target="_blank">IDNAPortal</a></p>
                            <p class="mt-3 text-base text-gray-500 dark:text-gray-300">
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
                                class="font-bold tracking-tight text-sm text-blue-400 dark:text-blue-300 border-blue-400 dark:border-blue-300 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600 transition-colors">
                                <a
                                    href="#"
                                    @click.prevent="openModal('/img/ss-informeddna.png')"
                                >
                                    VIEW SCREENSHOT
                                </a>
                            </div>
                        </div>
                    </div>
                    <hr class="border-slate-100 dark:border-gray-700 border-t mt-4">
                    <div class="mt-4 flex items-center">
                        <div class="flex-shrink-0">
                            <a href="#">
                                <img class="h-10 w-10 rounded-full" src="/img/logo-informeddna.png"
                                     alt="InformedDNA Logo">
                            </a>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">
                                <a href="#" class="hover:underline"> Laravel, Bootstrap, SugarCRM </a>
                            </p>
                            <div class="flex space-x-1 text-sm text-gray-500 dark:text-gray-400">
                                <time datetime="2020-03-10">InformedDNA</time>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Taylor MDA Project Card -->
            <div
                class="flex flex-col rounded-xl shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl bg-white dark:bg-gray-800">
                <div class="flex-shrink-0">
                    <img class="h-48 w-full object-cover transition-opacity duration-300"
                         loading="lazy"
                         src="/img/ss-dante.png"
                         alt="Taylor MDA Screenshot">
                </div>
                <div class="flex-1 p-6 flex flex-col justify-between dark:bg-gray-800">
                    <div class="flex-1">
                        <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">
                            <a href="#" class="hover:underline transition-colors">Tool for Building Enterprise
                                Applications</a>
                        </p>
                        <span class="block mt-2">
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">
                                    <a href="https://www.danteinc.com/" target="_blank">Taylor MDA</a>
                                </p>
                                <p class="mt-3 text-base text-gray-500 dark:text-gray-300">
                                    At Dante Inc., I contributed to Taylor MDA, an advanced Eclipse-based UML modeling tool for multi-tiered, distributed systems. It streamlines complex system design and maximizes code generation from UML models. Taylor MDA features pre-designed templates for JEE applications, integrating JPA/EJB3 and JSF/Seam/Facelets, accelerating enterprise application development through innovative model-driven architecture techniques. My work enhanced its capabilities and user experience.
                                </p>
                            </span>
                        <div class="mt-4">
                            <div
                                class="font-bold tracking-tight text-sm text-blue-400 dark:text-blue-300 border-blue-400 dark:border-blue-300 rounded border uppercase px-4 py-1 inline-block hover:text-blue-600 hover:border-blue-600 transition-colors">
                                <a
                                    href="#"
                                    @click.prevent="openModal('/img/ss-dante.png')"
                                >
                                    VIEW SCREENSHOT
                                </a>
                            </div>
                        </div>
                        <hr class="border-slate-100 dark:border-gray-700 border-t mt-4">
                        <div class="mt-6 flex items-center">
                            <div class="flex-shrink-0">
                                <a href="#">
                                    <img class="h-10 w-10 rounded-full" src="/img/logo-dante-small.png"
                                         alt="Dante Inc. Logo">
                                </a>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-gray-900 dark:text-white">
                                    <a href="#" class="hover:underline"> JAVA, JSP, RichFaces</a>
                                </p>
                                <div class="flex space-x-1 text-sm text-gray-500 dark:text-gray-400">
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
