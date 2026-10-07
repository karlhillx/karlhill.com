<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Updating the site · Karl Hill</title>
    <style>
        :root {
            color-scheme: light dark;
            --bg: light-dark(#f5f3ee, #141414);
            --panel: light-dark(#fdfcf9, #1b1b1a);
            --text: light-dark(#24231f, #f4f1eb);
            --muted: light-dark(#626057, #b8b4ab);
            --line: light-dark(#dedbd2, #383732);
            --accent: #c98626;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: clamp(1rem, 4vw, 3rem);
            min-height: 100vh;
            min-height: 100svh;
            display: grid;
            place-items: center;
            font: 400 1rem/1.6 system-ui, sans-serif;
            background: radial-gradient(ellipse at 80% 0%, light-dark(#e9dcc6, #302619), transparent 65%), var(--bg);
            color: var(--text);
        }
        main {
            width: 100%;
            max-width: 48rem;
            overflow: hidden;
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: 1rem;
            box-shadow: 0 1.5rem 4rem light-dark(#24231f0d, #00000026);
        }
        .masthead, .footer { display: flex; align-items: center; justify-content: space-between; gap: 1rem; flex-wrap: wrap; }
        .masthead { padding: 1.25rem clamp(1.25rem, 5vw, 3rem); border-bottom: 1px solid var(--line); }
        .brand { font-weight: 700; letter-spacing: .12em; text-transform: uppercase; }
        .eyebrow { display: flex; align-items: center; gap: .65rem; margin: 0; color: var(--muted); font: .7rem/1.5 ui-monospace, monospace; letter-spacing: .1em; text-transform: uppercase; }
        .status-dot { width: .5rem; height: .5rem; flex-shrink: 0; border-radius: 50%; background: var(--accent); }
        .content { padding: clamp(2rem, 6vw, 4rem) clamp(1.25rem, 5vw, 3rem); }
        .signal { display: flex; align-items: center; gap: .5rem; margin-bottom: 2rem; }
        .signal span { width: 1.5rem; height: .35rem; background: var(--accent); }
        .signal span:nth-child(2) { opacity: .55; }
        .signal span:nth-child(3) { opacity: .25; }
        h1 { max-width: 15ch; margin: 0 0 1.5rem; font-size: clamp(2.25rem, 6vw, 3.75rem); font-weight: 600; line-height: 1.08; letter-spacing: -.045em; text-wrap: balance; }
        .message { max-width: 42ch; margin: 0; color: var(--muted); font-size: 1.0625rem; line-height: 1.7; }
        a { display: inline-flex; align-items: center; justify-content: space-between; gap: 1.25rem; min-height: 48px; margin-top: 2rem; padding: .8rem 1.25rem; border: 1px solid transparent; border-radius: .25rem; background: var(--accent); color: #141414; font-size: .875rem; font-weight: 600; text-decoration: none; }
        a:hover { border-color: currentColor; }
        a:focus-visible { outline: 3px solid var(--text); outline-offset: 4px; }
        .footer { padding: 1rem clamp(1.25rem, 5vw, 3rem); border-top: 1px solid var(--line); color: var(--muted); font: .75rem/1.5 ui-monospace, monospace; }
        @media (prefers-reduced-motion: no-preference) {
            .status-dot { animation: status-pulse 2.8s ease-in-out infinite; }
            @keyframes status-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }
        }
    </style>
</head>
<body>
    <main aria-labelledby="maintenance-title">
        <header class="masthead">
            <span class="brand">Karl Hill</span>
            <p class="eyebrow"><span class="status-dot" aria-hidden="true"></span>Site update</p>
        </header>
        <div class="content">
            <div class="signal" aria-hidden="true"><span></span><span></span><span></span></div>
            <h1 id="maintenance-title">A brief pause while I deploy.</h1>
            <p class="message">The site is temporarily offline while an update is being installed. Please try again shortly.</p>
            <a href="/">Try the homepage again <span aria-hidden="true">→</span></a>
        </div>
        <footer class="footer">
            <span>karlhill.com</span>
            <span>Maintenance / 503</span>
        </footer>
    </main>
</body>
</html>
