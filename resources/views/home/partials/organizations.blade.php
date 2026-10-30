@php($facts = config('site.facts'))

<section id="organizations" class="record" aria-labelledby="organizations-title">
    <div class="site-shell">
        <h2 id="organizations-title" class="eyebrow">Organizations</h2>
        <ol class="record__list">
            <li class="record__item">
                <p class="record__period">{{ $facts['period'] }}</p>
                <h3>Jacobs · {{ $facts['unit'] }}</h3>
                <p>Staff Aerospace Software Engineer at {{ $facts['employer'] }}. Mission software, platform engineering, and cross-program technical leadership, including cross-team SAFe Agile. Core program: {{ $facts['repos'] }} Python repositories, {{ $facts['environments'] }} environments, and {{ $facts['team'] }} engineers.</p>
                <a href="/work/jacobs-mission-software" class="portfolio-text-link">Mission software case study <span aria-hidden="true">→</span></a>
            </li>
            <li class="record__item">
                <p class="record__period">{{ $facts['nasa_period'] }}</p>
                <h3>NASA Goddard</h3>
                <p>Lead Software Engineer with SSAI. Public Earth science systems for flood mapping, satellite-data access, and science publishing.</p>
                <a href="/work/flood-mapping-system" class="portfolio-text-link">Flood Mapping System <span aria-hidden="true">→</span></a>
            </li>
        </ol>
    </div>
</section>
