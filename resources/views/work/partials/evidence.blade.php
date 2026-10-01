<section id="snapshot" class="case-study-brief__block scroll-mt-24" aria-labelledby="evidence-title">
    <h2 id="evidence-title" class="case-study-brief__heading">Evidence</h2>
    @if($page->gallery !== [])
        <figure class="case-study-media" data-reveal>
            <div class="case-study-frame__chrome" aria-hidden="true">
                <span class="case-study-frame__title">{{ $page->frameTitle }}</span>
            </div>
            <x-site.shot-carousel
                :slides="$page->gallery"
                :transition-name="'view-transition-name: work-img-'.$project['slug'].'; view-transition-class: card-media'"
            />
            <figcaption class="case-study-media__footer">
                <span class="case-study-media__detail">{{ $project['meta'] }}</span>
            </figcaption>
        </figure>
    @endif

    @if(! empty($study['metrics']))
        <dl class="case-study-facts" aria-label="Key facts">
            @foreach($study['metrics'] as $metric)
                <div class="case-study-facts__row">
                    <dt class="case-study-facts__label">{{ $metric['label'] }}</dt>
                    <dd class="case-study-facts__value case-study-facts__value--stat">{{ $metric['value'] }}</dd>
                </div>
            @endforeach
        </dl>
    @endif

    @if(! empty($study['status']))
        <dl class="case-study-status" aria-label="Delivery status">
            @foreach($study['status'] as $row)
                <div class="case-study-status__row">
                    <dt><span class="case-study-status__state">{{ $row['state'] }}</span> {{ $row['label'] }}</dt>
                    <dd>{{ $row['detail'] }}</dd>
                </div>
            @endforeach
        </dl>
    @endif

    @if($project['slug'] === 'flood-mapping-system' && \App\Support\SiteFeatures::webgpu())
        <figure class="webgpu-flood case-study-media mt-6" data-webgpu-flood-root hidden>
            <div class="case-study-frame__chrome" aria-hidden="true">
                <span class="case-study-frame__title">Live WebGPU field</span>
            </div>
            <canvas data-webgpu-flood class="webgpu-flood__canvas w-full aspect-[16/9]"
                    aria-label="Generative flood-extent field, animated"></canvas>
            <figcaption class="case-study-media__footer">
                <p class="case-study-media__detail">Generative illustration, not mission data.</p>
            </figcaption>
        </figure>
    @endif
</section>
