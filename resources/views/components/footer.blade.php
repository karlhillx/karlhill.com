<footer class="bg-gradient-to-b from-zinc-900 to-black border-t border-zinc-800/50">
    <div class="max-w-9xl mx-auto py-8 sm:py-10 px-4 sm:px-6 md:py-12 md:flex md:items-center md:justify-between lg:px-8">
        <div class="flex justify-center md:order-2">
            <div class="bottom-social-icons flex flex-wrap justify-center gap-3 sm:gap-4">
                <a class="linkedin group transition-colors duration-300 p-2 hover:bg-gray-800 rounded-lg"
                   aria-label="Visit Karl Hill's LinkedIn profile"
                   target="_blank" href="https://linkedin.com/in/khill">
                    <i class="fab fa-linkedin text-white text-lg sm:text-xl"></i>
                </a>
                <a class="github group transition-colors duration-300 p-2 hover:bg-gray-800 rounded-lg"
                   aria-label="Visit Karl Hill's GitHub profile"
                   target="_blank"
                   href="https://github.com/karlhillx/">
                    <i class="fab fa-github text-white text-lg sm:text-xl"></i>
                </a>
                <a class="medium group transition-colors duration-300 p-2 hover:bg-gray-800 rounded-lg"
                   aria-label="Visit Karl Hill's Medium profile"
                   target="_blank" href="https://karlhillx.medium.com/">
                    <i class="fab fa-medium text-white text-lg sm:text-xl"></i>
                </a>
                <a class="twitter group transition-colors duration-300 p-2 hover:bg-gray-800 rounded-lg"
                   aria-label="Visit Karl Hill's Twitter profile"
                   target="_blank" href="https://twitter.com/karl_hill/">
                    <i class="fab fa-x-twitter text-white text-lg sm:text-xl"></i>
                </a>
                <a class="discogs group transition-colors duration-300 p-2 hover:bg-gray-800 rounded-lg"
                   aria-label="Visit Karl Hill's Discogs profile"
                   target="_blank" href="https://www.discogs.com/artist/1286669-Karl-Hill">
                    <i class="fas fa-compact-disc text-white text-lg sm:text-xl"></i>
                </a>
                <a class="orcid group transition-colors duration-300 p-2 hover:bg-gray-800 rounded-lg"
                   aria-label="Visit Karl Hill's ORCID profile"
                   target="_blank"
                   href="https://orcid.org/0009-0002-6847-3368">
                    <i class="fab fa-orcid text-white text-lg sm:text-xl"></i>
                </a>
            </div>
        </div>
        <div class="mt-6 md:mt-0 md:order-1">
            <p class="text-sm text-gray-400 text-center sm:text-left leading-relaxed">
                © {{ date('Y') }} Karl Hill. Built with <span class="text-gray-300 font-medium">Laravel v{{ app()->version() }}</span>.<br class="sm:hidden">
                <span class="hidden sm:inline"> </span>
                Content (unless noted) licensed under <a
                    href="https://creativecommons.org/licenses/by/4.0/" 
                    target="_blank" 
                    rel="noopener noreferrer"
                    class="hover:text-gray-300 underline decoration-gray-600 hover:decoration-gray-400 transition-colors">CC BY 4.0</a>.
            </p>
        </div>
    </div>
</footer>