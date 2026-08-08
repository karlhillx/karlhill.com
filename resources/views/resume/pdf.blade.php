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
            --navy-ink: #f7fafc;
            --navy-muted: rgb(247 250 252 / 0.7);
            --navy-rule: rgb(255 255 255 / 0.22);
            --accent: #03385f;
            --sidebar: 2.42in;
            --gutter: 0.42in;
            --main-pad-right: calc(var(--sidebar) + 0.22in);
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
            -webkit-font-smoothing: antialiased;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        a {
            color: inherit;
            text-decoration: none;
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

        /* —— Sidebar —— */
        .sidebar {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: var(--sidebar);
            background: var(--navy);
            color: var(--navy-ink);
            padding: 0.48in 0.32in 0.4in;
        }

        .sidebar-block + .sidebar-block {
            margin-top: 0.32in;
        }

        .sidebar-title {
            font-size: 7.4pt;
            font-weight: 700;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: #fff;
            margin-bottom: 0.11in;
            padding-bottom: 0.06in;
            border-bottom: 1px solid var(--navy-rule);
        }

        .sidebar-list {
            list-style: none;
            font-size: 8pt;
            line-height: 1.48;
            color: var(--navy-ink);
        }

        .sidebar-list li + li {
            margin-top: 0.06in;
        }

        .sidebar-list a {
            color: #fff;
        }

        .sidebar-link-label {
            display: block;
            font-size: 8.2pt;
            font-weight: 600;
            color: #fff;
            letter-spacing: 0.01em;
        }

        .sidebar-link-url {
            display: block;
            margin-top: 0.02in;
            font-size: 6.8pt;
            line-height: 1.35;
            color: var(--navy-muted);
            word-break: break-all;
        }

        .expertise {
            list-style: none;
        }

        .expertise li {
            position: relative;
            font-size: 7.55pt;
            line-height: 1.3;
            padding: 0.055in 0 0.055in 0.11in;
            color: var(--navy-ink);
        }

        .expertise li::before {
            content: "";
            position: absolute;
            left: 0;
            top: 0.12in;
            width: 0.03in;
            height: 0.03in;
            border-radius: 50%;
            background: rgb(255 255 255 / 0.55);
        }

        /* —— Main column —— */
        .main {
            height: 100%;
            padding: 0.48in var(--main-pad-right) 0.36in var(--gutter);
        }

        .masthead {
            padding: 0 0 0.155in;
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
            font-size: 6.85pt;
            font-weight: 600;
            letter-spacing: 0.12em;
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
            margin-top: 0.24in;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 0.08in;
            font-size: 7.5pt;
            font-weight: 700;
            letter-spacing: 0.135em;
            text-transform: uppercase;
            color: var(--ink);
            margin-bottom: 0.1in;
        }

        .section-title::after {
            content: "";
            flex: 1 1 auto;
            height: 1px;
            background: var(--rule);
            opacity: 0.85;
            transform: translateY(-0.01in);
        }

        .summary {
            font-size: 8.35pt;
            line-height: 1.35;
            color: var(--ink-soft);
            text-wrap: pretty;
        }

        .bullets {
            list-style: none;
            margin: 0.055in 0 0;
        }

        .bullets li {
            position: relative;
            margin: 0 0 0.028in;
            padding-left: 0.13in;
            font-size: 8.1pt;
            line-height: 1.28;
            color: var(--ink-soft);
            text-wrap: pretty;
        }

        .bullets li::before {
            content: "";
            position: absolute;
            top: 0.075in;
            left: 0;
            width: 0.035in;
            height: 0.035in;
            border-radius: 50%;
            background: var(--accent);
        }

        .role {
            margin-top: 0.15in;
        }

        .role:first-child {
            margin-top: 0;
        }

        .role-header {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            gap: 0.12in;
        }

        .role-title {
            font-size: 9pt;
            font-weight: 700;
            line-height: 1.25;
            color: var(--ink);
            letter-spacing: -0.01em;
        }

        .role-meta {
            margin-top: 0.018in;
            font-size: 7.8pt;
            font-weight: 500;
            color: var(--ink-soft);
            line-height: 1.3;
        }

        .role-dates {
            flex: 0 0 auto;
            font-size: 7pt;
            font-weight: 600;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--muted);
            white-space: nowrap;
        }

        .role-company {
            margin-top: 0.018in;
            font-size: 7.8pt;
            font-weight: 500;
            color: var(--ink-soft);
        }

        /* —— Page 2 —— */
        .page-2 .accent {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            width: 0.12in;
            background: var(--navy);
        }

        .page-2 .content {
            height: 100%;
            padding: 0.48in 0.55in 0.38in var(--gutter);
        }

        .page-2-kicker {
            display: flex;
            align-items: baseline;
            justify-content: space-between;
            margin-bottom: 0.22in;
            padding: 0 0 0.1in;
            border-bottom: 2px solid var(--rule);
        }

        .page-2-kicker strong {
            font-family: "Oswald", "Arial Narrow", sans-serif;
            font-size: 14pt;
            font-weight: 500;
            letter-spacing: 0.02em;
            text-transform: uppercase;
        }

        .page-2-kicker span {
            font-size: 6.8pt;
            font-weight: 600;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--muted);
        }

        .edu-list,
        .cert-list {
            list-style: none;
        }

        .edu-list li,
        .cert-list li {
            font-size: 8.1pt;
            line-height: 1.32;
            color: var(--ink-soft);
            margin: 0 0 0.045in;
        }

        .edu-list li strong,
        .cert-list li strong {
            font-weight: 600;
            color: var(--ink);
        }

        .stack-block {
            margin-top: 0.24in;
        }

        .stack-line {
            font-size: 7.7pt;
            line-height: 1.34;
            color: var(--ink-soft);
            margin: 0 0 0.035in;
        }

        .stack-label {
            font-weight: 700;
            color: var(--ink);
        }
    </style>
</head>
<body>
    <section class="page page-1">
        <aside class="sidebar">
            <div class="sidebar-block">
                <h2 class="sidebar-title">Details</h2>
                <ul class="sidebar-list">
                    <li>{{ $locationLine }}</li>
                    <li><a href="tel:+1{{ preg_replace('/\D+/', '', $resume['phone']) }}">{{ $resume['phone'] }}</a></li>
                    <li><a href="mailto:{{ $person['email'] }}">{{ $person['email'] }}</a></li>
                </ul>
            </div>

            <div class="sidebar-block">
                <h2 class="sidebar-title">Links</h2>
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
            </div>

            <div class="sidebar-block">
                <h2 class="sidebar-title">Areas of Expertise</h2>
                <ul class="expertise">
                    @foreach($resume['expertise'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </div>
        </aside>

        <div class="main">
            <header class="masthead">
                <h1 class="name">{{ $person['name'] }}</h1>
                <p class="tagline">
                    <span class="tagline-line tagline-lead">{{ $taglineLead }}</span>
                    @if($taglineRest !== '')
                        <span class="tagline-line">{{ $taglineRest }}</span>
                    @endif
                </p>
            </header>

            <section class="section">
                <h2 class="section-title">Summary</h2>
                <p class="summary">
                    @if($summaryRest !== '')
                        <strong>{{ $summaryLead }}</strong>{{ $summaryRest }}
                    @else
                        {{ $summaryFull }}
                    @endif
                </p>
            </section>

            <section class="section">
                <h2 class="section-title">Selected Leadership Impact</h2>
                <ul class="bullets">
                    @foreach($resume['impact'] as $item)
                        <li>{{ $item }}</li>
                    @endforeach
                </ul>
            </section>

            <section class="section">
                <h2 class="section-title">Professional Experience</h2>

                <div class="role">
                    <div class="role-header">
                        <p class="role-title">{{ $experience['current']['title'] }}</p>
                        <p class="role-dates">{{ $experience['current']['period'] }}</p>
                    </div>
                    <p class="role-meta">{{ $experience['current']['company'] }} · {{ $experience['current']['location'] }}</p>
                    <ul class="bullets">
                        @foreach($jacobs as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="role">
                    <div class="role-header">
                        <p class="role-title">{{ $experience['roles'][0]['title'] }}</p>
                        <p class="role-dates">{{ $experience['roles'][0]['period'] }}</p>
                    </div>
                    <p class="role-meta">{{ $experience['roles'][0]['company'] }} · {{ $experience['roles'][0]['location'] }}</p>
                    <ul class="bullets">
                        @foreach($nasa as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </section>
        </div>
    </section>

    <section class="page page-2">
        <div class="accent" aria-hidden="true"></div>
        <div class="content">
            <div class="page-2-kicker">
                <strong>{{ $person['name'] }}</strong>
                <span>Resume · 2 / 2</span>
            </div>

            <section class="section" style="margin-top:0">
                <h2 class="section-title">Professional Experience</h2>

                <div class="role">
                    <div class="role-header">
                        <p class="role-title">{{ $experience['roles'][1]['title'] }}</p>
                        <p class="role-dates">{{ $experience['roles'][1]['period'] }}</p>
                    </div>
                    <p class="role-meta">{{ $experience['roles'][1]['company'] }} · {{ $experience['roles'][1]['location'] }}</p>
                    <ul class="bullets">
                        @foreach($informed as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="role">
                    <div class="role-header">
                        <p class="role-title">{{ $experience['roles'][2]['title'] }}</p>
                        <p class="role-dates">{{ $experience['roles'][2]['period'] }}</p>
                    </div>
                    <p class="role-meta">{{ $experience['roles'][2]['company'] }} · {{ $experience['roles'][2]['location'] }}</p>
                    <ul class="bullets">
                        @foreach($ticomix as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="role">
                    <div class="role-header">
                        <p class="role-title">{{ $experience['earlier']['title'] }}</p>
                        <p class="role-dates">{{ $experience['earlier']['period'] }}</p>
                    </div>
                    <p class="role-company">{{ $experience['earlier']['company'] }}</p>
                    <ul class="bullets">
                        @foreach($earlier as $item)
                            <li>{{ $item }}</li>
                        @endforeach
                    </ul>
                </div>
            </section>

            <section class="section">
                <h2 class="section-title">Education</h2>
                <ul class="edu-list">
                    @foreach($education as $item)
                        <li><strong>{{ $item['degree'] }}</strong>, {{ $item['school'] }}</li>
                    @endforeach
                </ul>
            </section>

            <section class="section">
                <h2 class="section-title">Certifications</h2>
                <ul class="cert-list">
                    @foreach($certifications as $cert)
                        <li><strong>{{ $cert['name'] }}</strong>@if(! empty($cert['issuer']))<span>, {{ $cert['issuer'] }}</span>@endif</li>
                    @endforeach
                </ul>
            </section>

            <section class="stack-block">
                <h2 class="section-title">Technical Leadership, Platforms &amp; Engineering Stack</h2>
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
