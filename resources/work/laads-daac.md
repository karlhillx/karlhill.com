---
updated: '2026-09-06'
lede: 'NASA’s Level-1 and Atmosphere archive — Find Data search and order, the public portal, and LANCE near-real-time access — modernized so science users can get MODIS and VIIRS products without operator heroics.'
role: 'Lead Software Engineer — owned Find Data and the LAADS web systems, and moved delivery onto GitLab CI/CD and Kubernetes.'
leadership:
  mode: 'Lead Software Engineer for operational science-data web systems'
  team: 'MODAPS / LAADS engineers, science operations, and DAAC user-support partners in Greenbelt'
  unblocked: 'Took search, order, and near-real-time access off tribal Perl workflows so a satellite pass became a product a researcher could find and download.'
  decision: 'Kept the archive and order contracts stable while replacing the Find Data experience and putting releases on GitLab and Kubernetes — modernization without breaking decades of holdings.'
problem:
  - 'Science users needed a trustworthy path from “which product?” to an order — not a set of expert-only search forms that only operators could complete.'
  - 'Archive, near-real-time, and portal tools had grown as separate Perl systems with fragile handoffs and release processes that depended on who was on call.'
  - 'MODIS and VIIRS holdings kept growing; delivery had to become repeatable (GitLab, containers, Kubernetes) without interrupting 24/7 distribution.'
decisions:
  - 'Rebuild Find Data as a guided product → time → location → files → review & order flow so non-operators could complete a search without a support ticket.'
  - 'Treat the public portal, Find Data, and LANCE NRT as one operational surface — same login, same archive contracts, fewer one-off tools.'
  - 'Move builds and deploys onto GitLab CI/CD, Docker, Helm, and Kubernetes so a release was a pipeline, not a workstation ritual — while the Perl archive services stayed the system of record.'
outcome:
  - 'Find Data is the search-and-order path for LAADS holdings — product catalogs, spatial-temporal filters, and order review in one wizard.'
  - 'The LAADS portal and LANCE NRT site give researchers archive and near-real-time access without a second set of tribal tools.'
  - 'Kubernetes and GitLab delivery made web and NRT releases repeatable — the same habit I now use on mission software at Jacobs.'
metrics:
  - value: 'Find Data'
    label: 'Search & order'
  - value: 'MODIS & VIIRS'
    label: 'Instrument record'
platform:
  caption: 'GitLab CI → Helm → Kubernetes'
  stages:
    - step: '01 · Source'
      title: 'Find Data & NRT'
      body: 'Portal, search-and-order, and near-real-time changes land in one delivery path — Perl archive contracts stay put.'
      stack: 'GitLab'
    - step: '02 · CI'
      title: 'Tests & artifacts'
      body: 'A change gets tests, an artifact, and an approval path instead of a copy to a host.'
      stack: 'GitLab CI'
    - step: '03 · Package'
      title: 'Docker & Helm'
      body: 'Web and NRT tiers are the same in integration as in production.'
      stack: 'Docker · Helm'
    - step: '04 · Deploy'
      title: 'Kubernetes rollout'
      body: 'A release is a rollout, not a snowflake workstation ritual.'
      stack: 'Kubernetes'
---

## What LAADS Has to Do

NASA’s [Level-1 and Atmosphere Archive and Distribution System (LAADS) DAAC](https://www.earthdata.nasa.gov/centers/laads-daac) is the public door onto a long instrument record: calibrated radiances and atmosphere products from MODIS on Terra and Aqua, and from VIIRS on Suomi NPP and the JPSS satellites. Clouds, aerosols, water vapor, land surface, and Level-0 / Level-1 packets — decades of granules that researchers and applied users have to *find*, then *order*, then *download*.

The DAAC does not succeed because a scientist already knows a filename. It succeeds when a user who has a region, a time window, and a product family can walk that path without an operator sitting next to them.

I spent more of the Goddard years on these systems than on Earth Observatory. The work was the websites, the search-and-order product, the near-real-time sibling, and the delivery machinery underneath.

## Find Data

[Find Data](https://ladsweb.modaps.eosdis.nasa.gov/search/) is the product that had to carry that path. The older search surfaces assumed you already spoke collection names. The modernization was a five-step wizard that matches how people actually ask for satellite data:

1. **Products** — browse by sensor and collection (MODIS, VIIRS, Sentinel-3, airborne), not by folklore filenames.
2. **Time** — a window the archive can evaluate against granule coverage.
3. **Location** — a region instead of an implicit “whole globe.”
4. **Files** — the matching granules, with size and count visible before anyone commits.
5. **Review & order** — confirm, then download under Earthdata identity.

That sounds like UI. It is also an operational contract: the wizard has to talk to the same archive, the same order limits, the same authentication, and the same bulk-download paths that scripts and `wget` tokens already used. The front of the house could change. The holdings could not.

## Portal, NRT, and the Rest of the Group

Find Data sits on the [LAADS portal](https://ladsweb.modaps.eosdis.nasa.gov/) — the public site for archive access, View Data, alerts, and the path into tools. The near-real-time counterpart is [LANCE NRT](https://nrt3.modaps.eosdis.nasa.gov/): MODIS and VIIRS products on a latency clock, not a climate-record clock, with the same Earthdata login and download story.

Around those two sites were the other DAAC tools the group runs — filename search, image viewer, saved searches, past orders, cloud access, and the support utilities that keep a production archive operable. I worked across that surface, not as a one-page redesign.

## How It Shipped

The heritage stack was Perl services that already knew how to archive and distribute. Replacing that in one rewrite would have been a science outage dressed up as a migration. The durable move was to keep those contracts and change *how we shipped*:

- **GitLab CI/CD** so a change to Find Data or NRT had tests, an artifact, and an approval path instead of a copy to a host.
- **Docker and Helm** so the web and NRT tiers were the same in integration as in production.
- **Kubernetes** so a release was a rollout, not a snowflake.

That is the same delivery shape I use now on mission software — constrained environments, repeatable pipelines, fewer heroics. LAADS is where I practiced it on systems the public can still open.

## Outcome

Researchers still search LAADS by product, time, and place. Orders still come out of the archive. NRT still exists for people who cannot wait on the climate record. What changed is that those paths are products with a release process, not a set of Perl scripts that only the on-call engineer could run. The instrument record stayed intact. The door into it got easier to operate.
