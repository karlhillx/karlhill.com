<section aria-label="Career impact at a glance" class="px-6 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto grid grid-cols-2 lg:grid-cols-4 gap-px bg-neutral-800">
        @foreach(config('site.stats') as $stat)
            <x-site.stat
                padding="px-6 py-10"
                data-reveal
                :value="$stat['display']"
                :label="$stat['label']"
                :to="$stat['to']"
                :prefix="$stat['prefix']"
                :suffix="$stat['suffix']"
                value-class="text-5xl sm:text-6xl mb-2"
                label-class="text-neutral-400"
            />
        @endforeach
    </div>
</section>
