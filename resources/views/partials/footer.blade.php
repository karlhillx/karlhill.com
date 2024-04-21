<!-- Footer -->
<footer class="bg-black">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8">
        <div class="flex justify-center space-x-6 md:order-2">
            <div class="bottom-social-icons -align-right">
                <a class="linkedin hover:bg-blue-600" target="_blank" href="https://linkedin.com/in/khill ">
                    <i class="fab fa-linkedin"></i>
                </a>
                <a class="github hover:bg-gray-900" target="_blank" href="https://github.com/karlhillx/">
                    <i class="fab fa-github"></i>
                </a>
                <a class="medium hover:bg-green-400" target="_blank" href="https://karlhillx.medium.com/">
                    <i class="fab fa-medium"></i>
                </a>
                <a class="twitter hover:bg-blue-400" target="_blank" href="https://twitter.com/karl_hill/">
                    <i class="fab fa-x-twitter"></i>
                </a>
                <a class="discogs hover:bg-gray-400" target="_blank"
                   href="https://www.discogs.com/artist/1286669-Karl-Hill">
                    <i class="fas fa-compact-disc"></i>
                </a>
            </div>
        </div>
        <div class="mt-8 md:mt-0 md:order-1">
            <p class="text-sm text-gray-400 text-center sm:text-left">
                © {{ date('Y') }} Karl Hill. Laravel Build v{{ app()->version() }}.<br>
                Unless otherwise indicated, content is licensed under the <a href="https://creativecommons.org/licenses/by/4.0/" target="_blank">Creative Commons Attribution
                    4.0 International</a>.
            </p>
        </div>
    </div>
</footer>
