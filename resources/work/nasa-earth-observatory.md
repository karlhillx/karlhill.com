---
lede: 'A flagship NASA science communication platform serving 1.5M+ monthly visitors — rebuilt so editors ship without waiting on engineering, and the stack stays maintainable for the next decade.'
role: 'Lead engineer — owned the platform re-architecture and publishing pipeline, and set the frontend performance and accessibility standards.'
leadership:
  mode: 'Tech lead / sticky IC across platform engineering and editorial partners'
  team: '~4 engineers and content partners on a flagship site serving 1.5M+ monthly visitors'
  unblocked: 'Moved story production off one-off engineering work so editors could ship without waiting on custom builds.'
  decision: 'Traded short-term feature velocity for a shared publishing model — fewer heroics per story, higher long-term throughput.'
problem:
  - 'Routine stories still needed custom engineering — brittle, one-off publishing patterns made editorial velocity a queue, not a system.'
  - 'Traffic and imagery volume exposed performance, accessibility, and SEO debt that could not be patched story-by-story.'
  - 'Distributed content teams lacked a shared workflow — engineering had become the bottleneck for science communication at scale.'
decisions:
  - 'Standardize on repeatable story templates and a shared publishing model instead of per-story builds — accept slower net-new features to unlock editorial self-service.'
  - 'Treat large imagery, metadata consistency, and non-engineer workflows as first-class pipeline concerns, not afterthoughts bolted onto the CMS.'
  - 'Gate releases on frontend performance, accessibility, and search discoverability so public science traffic and WCAG expectations stay non-negotiable.'
outcome:
  - 'Editors ship routine stories without waiting on custom engineering — throughput became a product of the system, not heroics.'
  - 'High-traffic public science audience got a stronger performance and accessibility baseline.'
  - 'Left a maintainable foundation for ongoing Earth science communication instead of another round of one-off platform debt.'
metrics:
  - value: 1.5M+
    label: 'Monthly visitors'
  - value: Self-serve
    label: 'Editorial publishing'
---

## Editorial Velocity & Scale

NASA's Earth Observatory is one of the agency's highest-traffic public education platforms, delivering satellite imagery, climate data, and explanatory science journalism to over 1.5 million visitors every month.

Over years of organic growth, however, the publishing workflow had become a critical engineering bottleneck:
- Every new editorial format, custom interactive visualization, or major data story required ad-hoc software engineering support.
- Ultra-high-resolution satellite images (often 100MB+ TIFF files from MODIS, Landsat, and VIIRS) were manually cropped and exported, leading to inconsistent compression, bloated page weights, and degraded mobile performance.
- Search discoverability and accessibility compliance (Section 508 / WCAG) were managed reactively rather than enforced systematically at publication time.

Editorial velocity had turned into an engineering queue. My goal as lead engineer was to decouple content production from developer intervention by building a self-service publishing architecture engineered for long-term maintainability.

## Publishing Architecture

We restructured the platform into a decoupled publishing system with an automated asset transformation engine:

1. **Self-Service Editorial Templates:** Instead of bespoke layouts per article, we developed a modular, component-driven story publishing model. Editorial staff could compose rich narrative layouts, image comparisons (before-and-after flood or wildfire overlays), and data callouts without writing a line of code or filing an engineering ticket.
2. **Automated Imagery Pipeline:** Satellite images uploaded by science writers are automatically ingested into an asynchronous image processing pipeline. The pipeline generates responsive responsive AVIF and WebP image pyramids, extracts spatial metadata, and pre-generates lightweight low-quality image placeholders (LQIP) to ensure zero layout shift (CLS).
3. **Edge Caching & Resilience:** Serving 1.5M+ monthly visitors across global networks required aggressive edge caching with deterministic cache tags. When breaking disaster imagery or viral astronomical events drove sudden 10x traffic spikes, origin server load remained virtually flat while edge nodes served cached, pre-compressed assets.

## Accessibility & Performance

Public science platforms have a civic obligation to be accessible to everyone, across low-bandwidth connections, mobile devices, and assistive technologies:

- **Strict Accessibility Compliance:** Accessibility was integrated into CI/CD quality gates. Semantic HTML, keyboard navigability, high-contrast typography, and automated alternate-text requirements ensured compliance with federal Section 508 and WCAG standards.
- **Frontend Budget Discipline:** By eliminating third-party script bloat, optimizing font delivery, and relying on lean, modern web standards, First Contentful Paint (FCP) and Largest Contentful Paint (LCP) dropped dramatically across mobile devices.
- **Durable Architecture:** By resisting the temptation to rewrite the frontend in a fast-moving, short-lived SPA framework, we delivered a platform that remained stable, fast, and easy for new developers to maintain years into the future.
