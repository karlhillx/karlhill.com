---
updated: '2026-09-06'
lede: 'A scientific data hub ingesting multi-instrument satellite streams and distributing geophysical products to a global network of ground stations.'
role: 'Lead developer — designed the ingestion and reformatting architecture and operated the round-the-clock processing infrastructure.'
leadership:
  mode: 'Lead developer / operations owner for continuous science infrastructure'
  team: '~4 engineers and science operations partners, plus a global network of registered direct-broadcast ground stations'
  unblocked: 'Made instrument portfolio changes operable without rewriting tribal processing knowledge.'
  decision: 'Standardized product tiers and distribution paths so partner stations could trust the system instead of individual operators.'
problem:
  - 'Multi-instrument sensor streams required consistent reformatting from Level-0 through Level-2 products.'
  - 'Operational centers and research partners depended on predictable, near real-time delivery.'
  - 'Legacy processing paths were difficult to operate and extend as instrument portfolios evolved.'
approach:
  - 'Built ingestion and reformatting pipelines tuned for polar-orbiting satellite data volumes.'
  - 'Standardized product tiers and distribution paths for downstream operational consumers.'
  - 'Operated on Linux/NGINX infrastructure designed for continuous scientific workloads.'
outcome:
  - 'Sustained near real-time distribution to registered direct broadcast ground stations.'
  - 'Improved reliability for multi-instrument product generation and handoff.'
  - 'Supported NASA direct readout operations across a global partner network.'
metrics:
  - value: L0–L2
    label: 'Product tiers'
  - value: 24/7
    label: 'Operational ingest'
---

## What Direct Readout Means

Polar-orbiting Earth observation satellites broadcast their instrument data continuously as they pass overhead. Any ground station within line of sight — a university, a weather service, a regional disaster agency — can receive that downlink directly, without waiting for the data to route through a central archive. That is direct readout: local, immediate access to satellite observations.

The catch is that a raw downlink is not a usable product. Level-0 data is instrument packets. Turning it into Level-1 calibrated radiances and Level-2 geophysical products (sea surface temperature, fire detections, vegetation indices, cloud properties) requires processing software, calibration tables, and ancillary data that must be kept current as instruments and algorithms evolve. The Direct Readout Laboratory at NASA Goddard exists to make that processing available and dependable for a global network of registered ground stations.

I was lead developer and operations owner for the data hub at the center of that mission.

## The System

The hub ran as a continuous, round-the-clock service on Linux and NGINX, and its job broke into three concerns.

### 1. Ingestion tuned for polar-orbiting volumes

Multi-instrument streams arrive in bursts aligned with satellite passes, not as a smooth flow. The ingestion layer was built to absorb those bursts — staging incoming data, validating it, and queuing it for processing without dropping passes or stalling on a single bad file. Because the network of downstream consumers expected near real-time delivery, ingest latency was treated as an operational metric, not an afterthought.

### 2. Reformatting from Level-0 through Level-2

The processing chain took raw Level-0 packets through calibration and geolocation to Level-2 products, for multiple instruments, each with its own formats and algorithm dependencies. The legacy paths for doing this had accumulated as one-off scripts and operator knowledge; when an instrument was added or an algorithm version changed, that knowledge had to be rediscovered.

We restructured the processing into standardized product tiers with explicit inputs, outputs, and versioned dependencies. That made it possible to bring a new instrument or algorithm release into the chain as a configuration change rather than a rewrite, and to reason about what a given product was built from.

### 3. Distribution to a global partner network

Products and processing packages had to reach registered ground stations and operational centers reliably. Distribution paths were standardized so that partners downloaded from known locations with predictable structure, whether they were pulling a nightly package or polling for the latest pass. Underneath, a high-performance file and metadata platform built on Ceph improved virtual directory mapping and made large scientific datasets faster to locate and serve.

## Operating It

Continuous science infrastructure is judged on the nights nothing happens. Several practices mattered more than any individual component:

- **Standardization over heroics.** The aim of the product tiers and distribution conventions was that partner stations could trust the system rather than an individual operator. If the person who knew the workaround was unavailable, the system still worked.
- **Extensibility as a requirement.** Instrument portfolios evolve over a satellite's lifetime. Designing the processing chain to accept new instruments and algorithm versions without archaeological work was the difference between a maintainable service and a fragile one.
- **Ownership of the whole path.** Leading development and owning operations for the same system kept the feedback loop short: operational pain became a backlog item, not a ticket to another team.

## Outcome

The hub sustained near real-time distribution to registered direct broadcast ground stations worldwide, with more reliable multi-instrument product generation and cleaner handoffs to downstream consumers. For the partners on the receiving end — operational centers and research groups — the practical result was that a satellite pass over their station turned into usable science products on a predictable schedule.
