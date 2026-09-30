<x-site.section id="background" section-label="Background" border="soft">
    <div class="portfolio-background">
        <div>
            <p class="portfolio-eyebrow">The through line</p>
            <h2>Thirty years.<br>Still close to the code.</h2>
            <p>Enterprise and healthcare systems. Eight years at NASA Goddard. Aerospace technical leadership at Jacobs. Independent tools and products alongside it.</p>
            <p>The common problem: turning complex domains into software people can use, teams can maintain, and organizations can trust.</p>
            <div class="flex flex-wrap gap-5 mt-5">
                <a href="/about" class="portfolio-text-link">About Karl</a>
                <a href="/resume" class="portfolio-text-link">Career &amp; credentials</a>
                <a href="/kit" class="portfolio-text-link" data-analytics-event="recruiter_link_opened"
                   data-analytics-location="home-background" data-analytics-target="kit">Recruiter kit</a>
            </div>
        </div>
        <div>
            <p class="portfolio-eyebrow">Engineering notes</p>
            <h2>Writing from the work.</h2>
            @foreach($latestPosts as $post)
                <a href="/blog/{{ $post->slug }}" class="portfolio-writing-link">{{ $post->title }} <span aria-hidden="true">→</span></a>
            @endforeach
            <a href="/blog" class="portfolio-text-link">All writing <span aria-hidden="true">→</span></a>
        </div>
    </div>
</x-site.section>
