@php
    use App\Support\PlainText;

    $plain = static fn (?string $html): string => PlainText::fromHtml($html);
    $bullets = static function (array $items, int $limit = 99) use ($plain): array {
        return array_slice(array_map($plain, $items), 0, $limit);
    };

    // Match classic karlhill-resume.pdf: Oswald (display) + Lato (body).
    $latoDir = base_path('node_modules/@fontsource/lato/files');
    $oswaldDir = base_path('node_modules/@fontsource/oswald/files');
    $fontUrl = static fn (string $dir, string $file): string => 'file://'.$dir.'/'.$file;

    $linkedinUrl = $linkedin['url'] ?? 'https://www.linkedin.com/in/khill/';
    $githubUrl = $github['url'] ?? 'https://github.com/karlhillx';
    $jacobs = $bullets($experience['current']['highlights'], 4);
    $nasa = $bullets($experience['roles'][0]['highlights'], 6);
    $informed = $bullets($experience['roles'][1]['highlights'], 4);
    $ticomix = $bullets($experience['roles'][2]['highlights'], 4);
    $earlier = $bullets($experience['earlier']['highlights'], 3);

    $locationLine = trim(($person['location'] ?? '').(! empty($resume['postal']) ? ' '.$resume['postal'] : ''));

    $tagline = (string) ($resume['tagline'] ?? '');
    $taglineParts = preg_split('/\s*\|\s*/', $tagline, 2) ?: [$tagline];
    $taglineLead = trim($taglineParts[0] ?? '');
    $taglineRest = trim($taglineParts[1] ?? '');

    $summaryLead = 'Software engineering leader with 20+ years building secure, cloud-native platforms';
    $summaryFull = (string) ($experience['intro'] ?? '');
    $summaryRest = str_starts_with($summaryFull, $summaryLead)
        ? substr($summaryFull, strlen($summaryLead))
        : '';

    $nowrapHtml = static function (string $text): string {
        $escaped = e($text);

        return str_replace(
            'high-assurance',
            '<span class="nowrap">high-assurance</span>',
            $escaped,
        );
    };
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $person['name'] }} — Resume</title>
    <style>
        @font-face {
            font-family: "Lato";
            font-style: normal;
            font-weight: 400;
            src: url("{{ $fontUrl($latoDir, 'lato-latin-400-normal.woff2') }}") format("woff2");
        }

        @font-face {
            font-family: "Lato";
            font-style: normal;
            font-weight: 700;
            src: url("{{ $fontUrl($latoDir, 'lato-latin-700-normal.woff2') }}") format("woff2");
        }

        @font-face {
            font-family: "Oswald";
            font-style: normal;
            font-weight: 500;
            src: url("{{ $fontUrl($oswaldDir, 'oswald-latin-500-normal.woff2') }}") format("woff2");
        }

        @page {
            size: Letter;
            margin: 0;
        }

        :root {
            --ink: #12141a;
            --ink-soft: #3a4150;
            --muted: #6b7382;
            --rule: #1a1f2a;
            /* Sampled from classic karlhill-resume.pdf sidebar */
            --navy: #03385f;
            --navy-ink: #ffffff;
            --navy-muted: rgb(255 255 255 / 0.88);
            --navy-rule: rgb(255 255 255 / 0.28);
            --accent: #03385f;
            --sidebar: 2.42in;
            --gutter: 0.42in;
            --track: 0.07em;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html, body {
            background: #fff;
            color: var(--ink);
            font-family: "Lato", Helvetica, Arial, sans-serif;
            font-weight: 400;
            font-size: 9.1pt;
            -webkit-font-smoothing: antialiased;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .nowrap {
            white-space: nowrap;
        }

        .page {
            width: 8.5in;
            height: 11in;
            page-break-after: always;
            position: relative;
            overflow: hidden;
            background: #fff;
        }

        .page:last-child {
            page-break-after: auto;
        }

        /*
         * Page 1 siblings are ONLY <main> then <aside>.
         * Main is normal flow; aside is absolutely positioned so Chrome paints the
         * entire main column into the PDF content stream before any sidebar text.
         * (CSS Grid was interleaving sidebar fragments between job headings/bullets.)
         */
        .page-1 > main {
            height: 100%;
            padding: 0.45in calc(var(--sidebar) + 0.2in) 0.34in var(--gutter);
        }

        .page-1 > aside {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: var(--sidebar);
            background: var(--navy);
            color: var(--navy-ink);
            padding: 0.45in 0.3in 0.38in;
        }

        .masthead {
            padding: 0 0 0.14in;
            border-bottom: 2px solid var(--rule);
        }

        .name {
            font-family: "Oswald", "Arial Narrow", sans-serif;
            font-size: 34pt;
            font-weight: 500;
            letter-spacing: 0.01em;
            line-height: 0.95;
            text-transform: uppercase;
            color: var(--ink);
        }

        .tagline {
            margin-top: 0.1in;
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--muted);
            line-height: 1.45;
        }

        .tagline-line {
            display: block;
            white-space: nowrap;
        }

        .tagline-lead {
            color: var(--ink);
        }

        .section {
            margin-top: 0.2in;
        }

        .section-title {
            font-size: 8.25pt;
            font-weight: 700;
            letter-spacing: var(--track);
            text-transform: uppercase;
            color: var(--ink);
            margin-bottom: 0.09in;
            padding-bottom: 0.045in;
            border-bottom: 1px solid var(--rule);
        }

        .summary {
            font-size: 9.1pt;
            line-height: 1.38;
            color: var(--ink-soft);
            text-wrap: pretty;
        }

        .summary strong {
            font-weight: 700;
            color: var(--ink);
        }

        .bullets {
            list-style: none;
            margin: 0.055in 0 0;
            padding-left: 0;
        }

        .bullets li {
            margin: 0 0 0.04in;
            padding-left: 0.15in;
            text-indent: -0.15in;
            font-size: 9.1pt;
            line-height: 1.34;
            color: var(--ink-soft);
            text-wrap: pretty;
        }

        /* Text bullet (not ::marker / absolute) — Chrome PDF drops some disc markers. */
        .bullets li::before {
            content: "•";
            color: var(--accent);
            padding-right: 0.08in;
            text-indent: 0;
        }

        article.role {
            display: block;
            margin-top: 0.14in;
            break-inside: avoid;
            page-break-inside: avoid;
        }

        article.role:first-of-type {
            margin-top: 0;
        }

        /* Table (not flex/float) keeps title→date order in the PDF content stream. */
        .role-header {
            width: 100%;
            border-collapse: collapse;
        }

        .role-header td {
            vertical-align: baseline;
            padding: 0;
        }

        .role-title {
            font-size: 9.75pt;
            font-weight: 700;
            line-height: 1.25;
            color: var(--ink);
            letter-spacing: -0.01em;
        }

        .role-dates {
            width: 1%;
            white-space: nowrap;
            text-align: right;
            padding-left: 0.12in;
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .role-meta {
            margin-top: 0.015in;
            font-size: 8.5pt;
            font-weight: 400;
            color: var(--ink-soft);
            line-height: 1.3;
        }

        .role-company {
            margin-top: 0.02in;
            font-size: 8.5pt;
            font-weight: 400;
            color: var(--ink-soft);
        }

        .sidebar-block + .sidebar-block {
            margin-top: 0.3in;
        }

        .sidebar-title {
            font-size: 8pt;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #fff;
            margin-bottom: 0.11in;
            padding-bottom: 0.06in;
            border-bottom: 1px solid var(--navy-rule);
        }

        .sidebar-list {
            list-style: none;
            font-size: 8.25pt;
            line-height: 1.45;
            color: var(--navy-ink);
        }

        .sidebar-list li + li {
            margin-top: 0.065in;
        }

        .sidebar-list a {
            color: #fff;
        }

        .sidebar-link-label {
            display: block;
            font-size: 8.5pt;
            font-weight: 700;
            color: #fff;
            letter-spacing: 0.01em;
        }

        .sidebar-link-url {
            display: block;
            margin-top: 0.02in;
            font-size: 7.5pt;
            line-height: 1.35;
            color: var(--navy-muted);
            word-break: break-all;
        }

        .expertise {
            list-style: disc;
            padding-left: 0.14in;
        }

        .expertise li {
            font-size: 8pt;
            line-height: 1.32;
            padding: 0.05in 0;
            color: #fff;
        }

        .expertise li::marker {
            color: #fff;
            font-size: 0.8em;
        }

        /* —— Page 2 —— */
        .page-2 .content {
            height: 100%;
            padding: 0.45in 0.55in 0.36in var(--gutter);
        }

        .page-2-kicker {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 0.2in;
            padding-bottom: 0.1in;
            border-bottom: 2px solid var(--rule);
        }

        .page-2-kicker td {
            vertical-align: baseline;
            padding: 0;
        }

        .page-2-kicker strong {
            font-family: "Oswald", "Arial Narrow", sans-serif;
            font-size: 15pt;
            font-weight: 500;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .page-2-kicker .page-label {
            width: 1%;
            white-space: nowrap;
            text-align: right;
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 0.07em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .edu-list,
        .cert-list {
            list-style: none;
        }

        .edu-list li,
        .cert-list li {
            font-size: 9.1pt;
            line-height: 1.35;
            color: var(--ink-soft);
            margin: 0 0 0.05in;
        }

        .edu-list li strong,
        .cert-list li strong {
            font-weight: 700;
            color: var(--ink);
        }

        .stack-block {
            margin-top: 0.2in;
        }

        .stack-line {
            font-size: 8.5pt;
            line-height: 1.38;
            color: var(--ink-soft);
            margin: 0 0 0.04in;
        }

        .stack-label {
            font-weight: 700;
            color: var(--ink);
        }
    </style>
</head>
<body>
    {{-- Page 1: grid siblings are only <main> then <aside>. Each job is one <article>. --}}
    <section class="page page-1">
        <main>
            <header class="masthead">
                <h1 class="name">{{ $person['name'] }}</h1>
                <p class="tagline">
                    <span class="tagline-line tagline-lead">{{ $taglineLead }}</span>
                    @if($taglineRest !== '')
                        <span class="tagline-line">{{ $taglineRest }}</span>
                    @endif
                </p>
            </header>

            <section class="section" aria-labelledby="summary-heading">
                <h2 id="summary-heading" class="section-title">Summary</h2>
                <p class="summary">
                    @if($summaryRest !== '')
                        <strong>{{ $summaryLead }}</strong>{{ $summaryRest }}
                    @else
                        {{ $summaryFull }}
                    @endif
                </p>
            </section>

            <section class="section" aria-labelledby="impact-heading">
                <h2 id="impact-heading" class="section-title">Selected Leadership Impact</h2>
                <ul class="bullets">
                    @foreach($resume['impact'] as $item)
                        <li>{!! $nowrapHtml($item) !!}</li>
                    @endforeach
                </ul>
            </section>

            <section class="section" aria-labelledby="experience-heading">
                <h2 id="experience-heading" class="section-title">Professional Experience</h2>

                <article class="role">
                    <table class="role-header">
                        <tr>
                            <td><h3 class="role-title">{{ $experience['current']['title'] }}</h3></td>
                            <td class="role-dates">{{ $experience['current']['period'] }}</td>
                        </tr>
                    </table>
                    <p class="role-meta">{{ $experience['current']['company'] }} · {{ $experience['current']['location'] }}</p>
                    <ul class="bullets">
                        @foreach($jacobs as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </article>

                <article class="role">
                    <table class="role-header">
                        <tr>
                            <td><h3 class="role-title">{{ $experience['roles'][0]['title'] }}</h3></td>
                            <td class="role-dates">{{ $experience['roles'][0]['period'] }}</td>
                        </tr>
                    </table>
                    <p class="role-meta">{{ $experience['roles'][0]['company'] }} · {{ $experience['roles'][0]['location'] }}</p>
                    <ul class="bullets">
                        @foreach($nasa as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </article>
            </section>
        </main>

        <aside aria-label="Contact and expertise">
            <section class="sidebar-block" aria-labelledby="details-heading">
                <h2 id="details-heading" class="sidebar-title">Details</h2>
                <ul class="sidebar-list">
                    <li>{{ $locationLine }}</li>
                    <li><a href="tel:+1{{ preg_replace('/\D+/', '', $resume['phone']) }}">{{ $resume['phone'] }}</a></li>
                    <li><a href="mailto:{{ $person['email'] }}">{{ $person['email'] }}</a></li>
                </ul>
            </section>

            <section class="sidebar-block" aria-labelledby="links-heading">
                <h2 id="links-heading" class="sidebar-title">Links</h2>
                <ul class="sidebar-list">
                    <li>
                        <a href="{{ $linkedinUrl }}">
                            <span class="sidebar-link-label">LinkedIn</span>
                            <span class="sidebar-link-url">linkedin.com/in/khill</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ $githubUrl }}">
                            <span class="sidebar-link-label">GitHub</span>
                            <span class="sidebar-link-url">github.com/karlhillx</span>
                        </a>
                    </li>
                    <li>
                        <a href="https://karlhill.com">
                            <span class="sidebar-link-label">Website</span>
                            <span class="sidebar-link-url">karlhill.com</span>
                        </a>
                    </li>
                </ul>
            </section>

            <section class="sidebar-block" aria-labelledby="expertise-heading">
                <h2 id="expertise-heading" class="sidebar-title">Areas of Expertise</h2>
                <ul class="expertise">
                    @foreach($resume['expertise'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </section>
        </aside>
    </section>

    <section class="page page-2">
        <div class="content">
            <table class="page-2-kicker">
                <tr>
                    <td><strong>{{ $person['name'] }}</strong></td>
                    <td class="page-label">Resume · 2 / 2</td>
                </tr>
            </table>

            <section class="section" style="margin-top:0" aria-labelledby="experience-2-heading">
                <h2 id="experience-2-heading" class="section-title">Professional Experience</h2>

                <article class="role">
                    <table class="role-header">
                        <tr>
                            <td><h3 class="role-title">{{ $experience['roles'][1]['title'] }}</h3></td>
                            <td class="role-dates">{{ $experience['roles'][1]['period'] }}</td>
                        </tr>
                    </table>
                    <p class="role-meta">{{ $experience['roles'][1]['company'] }} · {{ $experience['roles'][1]['location'] }}</p>
                    <ul class="bullets">
                        @foreach($informed as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </article>

                <article class="role">
                    <table class="role-header">
                        <tr>
                            <td><h3 class="role-title">{{ $experience['roles'][2]['title'] }}</h3></td>
                            <td class="role-dates">{{ $experience['roles'][2]['period'] }}</td>
                        </tr>
                    </table>
                    <p class="role-meta">{{ $experience['roles'][2]['company'] }} · {{ $experience['roles'][2]['location'] }}</p>
                    <ul class="bullets">
                        @foreach($ticomix as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </article>

                <article class="role">
                    <table class="role-header">
                        <tr>
                            <td><h3 class="role-title">{{ $experience['earlier']['title'] }}</h3></td>
                            <td class="role-dates">{{ $experience['earlier']['period'] }}</td>
                        </tr>
                    </table>
                    <p class="role-company">{{ $experience['earlier']['company'] }}</p>
                    <ul class="bullets">
                        @foreach($earlier as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </article>
            </section>

            <section class="section" aria-labelledby="education-heading">
                <h2 id="education-heading" class="section-title">Education</h2>
                <ul class="edu-list">
                    @foreach($education as $item)
                        <li><strong>{{ $item['degree'] }}</strong>, {{ $item['school'] }}</li>
                    @endforeach
                </ul>
            </section>

            <section class="section" aria-labelledby="certifications-heading">
                <h2 id="certifications-heading" class="section-title">Certifications</h2>
                <ul class="cert-list">
                    @foreach($certifications as $cert)
                        <li><strong>{{ $cert['name'] }}</strong>@if(! empty($cert['issuer']))<span>, {{ $cert['issuer'] }}</span>@endif@if(! empty($cert['status']))<span> ({{ strtolower($cert['status']) }})</span>@endif</li>
                    @endforeach
                </ul>
            </section>

            <section class="stack-block" aria-labelledby="stack-heading">
                <h2 id="stack-heading" class="section-title">Technical Leadership, Platforms &amp; Engineering Stack</h2>
                @foreach($stack as $group)
                    <p class="stack-line">
                        <span class="stack-label">{{ $group['category'] }}:</span>
                        {{ implode(', ', $group['skills']) }}
                    </p>
                @endforeach
            </section>
        </div>
    </section>
</body>
</html>
