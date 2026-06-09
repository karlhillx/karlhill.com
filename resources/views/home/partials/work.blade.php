<section id="work" class="py-28 px-6 border-t border-neutral-800">
    <div class="max-w-6xl mx-auto">
        <x-site.section-heading number="03" label="Selected Work" />
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach(config('site.projects') as $project)
                <x-site.work-card
                    :title="$project['title']"
                    :meta="$project['meta']"
                    :description="$project['description']"
                    :image="$project['image']"
                    :tags="$project['tags']"
                    :logo="$project['logo']"
                />
            @endforeach
        </div>
    </div>
</section>
