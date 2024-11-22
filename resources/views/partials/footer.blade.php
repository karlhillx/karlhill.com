<footer class="bg-black">
    <div class="max-w-9xl mx-auto py-8 px-4 sm:px-6 sm:py-12 md:flex md:items-center md:justify-between lg:px-8">
        <div class="flex justify-center space-x-6 md:order-2">
            <div class="bottom-social-icons flex gap-2">
                <a class="linkedin group transition-colors duration-300"
                   aria-label="Visit Karl Hill's LinkedIn profile"
                   target="_blank" href="https://linkedin.com/in/khill">
                    <i class="fab fa-linkedin text-white"></i>
                </a>
                <a class="github group transition-colors duration-300 hover:text-gray-400 hover:border hover:border-gray-700 hover:rounded-lg p-2"
                   aria-label="Visit Karl Hill's GitHub profile"
                   target="_blank"
                   href="https://github.com/karlhillx/">
                    <i class="fab fa-github text-white"></i>
                </a>
                <a class="medium group transition-colors duration-300"
                   aria-label="Visit Karl Hill's Medium profile"
                   target="_blank" href="https://karlhillx.medium.com/">
                    <i class="fab fa-medium text-white"></i>
                </a>
                <a class="twitter group transition-colors duration-300"
                   aria-label="Visit Karl Hill's Twitter profile"
                   target="_blank" href="https://twitter.com/karl_hill/">
                    <i class="fab fa-x-twitter text-white"></i>
                </a>
                <a class="discogs group transition-colors duration-300"
                   aria-label="Visit Karl Hill's Discogs profile"
                   target="_blank" href="https://www.discogs.com/artist/1286669-Karl-Hill">
                    <i class="fas fa-compact-disc text-white"></i>
                </a>
            </div>
        </div>
        <div class="mt-8 md:mt-0 md:order-1">
            <p class="text-sm text-gray-400 text-center sm:text-left">
                © {{ date('Y') }} Karl Hill. Laravel Build v{{ app()->version() }}.<br>
                Unless otherwise indicated, content is licensed under the <a
                    href="https://creativecommons.org/licenses/by/4.0/" target="_blank">Creative Commons Attribution 4.0
                    International</a>.
            </p>
        </div>
    </div>
</footer>
