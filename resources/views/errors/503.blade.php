<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Updating the site · Karl Hill</title>
    <style>
        :root { color-scheme: light dark; }
        body { margin: 0; padding: 2rem; min-height: 100vh; box-sizing: border-box; display: grid; place-items: center; font: 400 1rem/1.6 system-ui, sans-serif; background: light-dark(#faf9f7, #141414); color: light-dark(#202020, #f4f1eb); }
        main { max-width: 32rem; }
        .eyebrow { display: flex; align-items: center; gap: .75rem; font-size: .8rem; letter-spacing: .15em; text-transform: uppercase; }
        .status-dot { width: .5rem; height: .5rem; flex-shrink: 0; border-radius: 50%; background: #c98626; }
        @media (prefers-reduced-motion: no-preference) {
            .status-dot { animation: status-pulse 2.8s ease-in-out infinite; }
            @keyframes status-pulse { 0%, 100% { opacity: 1; } 50% { opacity: .4; } }
        }
        h1 { font-size: clamp(2rem, 6vw, 3rem); line-height: 1.15; }
        a { display: inline-block; margin-top: 1rem; padding: .75rem 1.25rem; background: #c98626; color: #141414; font-weight: 600; text-decoration: none; }
        a:focus-visible { outline: 3px solid currentColor; outline-offset: 4px; }
    </style>
</head>
<body>
    <main>
        <p class="eyebrow"><span class="status-dot" aria-hidden="true"></span>Karl Hill · Site update</p>
        <h1>A brief pause while I deploy.</h1>
        <p>The site is temporarily offline while an update is being installed. Please try again shortly.</p>
        <a href="/">Try the homepage again</a>
    </main>
</body>
</html>
