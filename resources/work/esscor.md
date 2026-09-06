---
updated: '2026-09-06'
lede: 'A discovery portal unifying archival and near real-time remote sensing holdings into a searchable, standards-compliant catalog.'
role: 'Lead developer — built the discovery and search platform, the metadata model, and granule-level access controls.'
leadership:
  mode: 'Lead developer for a shared science-data catalog'
  team: 'Small engineering team working with data managers, archive operators, and researchers across federal agencies and partner institutions'
  unblocked: 'Replaced an ad-hoc registry process with an automated content workflow so new datasets reached researchers without a manual curation queue.'
  decision: 'Standardized the metadata model before scaling search — a governed schema made access control and ordering tractable instead of bespoke per collection.'
problem:
  - 'Researchers struggled to discover and order data across fragmented archival and near real-time holdings.'
  - 'Metadata inconsistency slowed search, access control, and downstream ordering workflows.'
approach:
  - 'Implemented granule-level access controls and standardized metadata schemas.'
  - 'Built search and discovery on ElasticSearch with governed ordering and delivery paths.'
outcome:
  - 'Streamlined data discovery and ordering for government agencies and partner institutions.'
  - 'Reduced friction between catalog search and governed data access.'
  - 'Automated the content registry workflow, improving data collection efficiency by roughly 60% and accelerating researcher access to new datasets.'
metrics:
  - value: '~60%'
    label: 'Data collection efficiency gain'
  - value: Granule
    label: 'Level access control'
---

## The Discovery Problem

Earth science data does not live in one place. Long-term archives hold decades of calibrated records; near real-time systems produce fresh granules hours after a satellite pass; partner institutions maintain their own holdings with their own conventions. For a researcher, that fragmentation shows up as a practical question with no good answer: *what exists, can I have it, and how do I get it?*

Before ESSCOR, answering that question meant knowing which system to ask, how each one described its data, and who to contact for access. Metadata was inconsistent across collections, so even when a dataset was findable, ordering it and confirming eligibility were separate manual steps. Adding a new dataset to the catalog was a curation task that sat in a queue behind engineering.

My role as lead developer was to turn that sprawl into one searchable, standards-compliant catalog with access rules that could be enforced automatically at the level of individual granules.

## What We Built

The platform had three load-bearing pieces: a governed metadata model, a search layer built on it, and an access-control layer that made the search results actionable.

### 1. A standardized metadata model first

The temptation with a discovery portal is to start with search and bolt on structure later. We did the opposite. Every collection entering the catalog was mapped onto a common, standards-aligned schema — spatial and temporal extent, instrument and platform, processing level, access classification — stored in MySQL as the system of record. Collections that arrived with sparse or idiosyncratic metadata were normalized on ingest rather than special-cased downstream.

This was slower up front and paid for itself immediately: search facets, ordering rules, and access decisions could all be expressed once against the schema instead of once per collection.

### 2. Search and discovery on ElasticSearch

With a consistent model, the catalog was indexed into ElasticSearch for faceted, full-text, and spatiotemporal discovery. Researchers could narrow by instrument, date range, region, and processing level across archival and near real-time holdings in the same query — the unification that had been missing. The relational store remained authoritative; the index was rebuildable from it, which kept schema evolution and reindexing low-risk.

### 3. Granule-level access control

Not every user is entitled to every granule. Some holdings are open, some are restricted to agency staff, and some are governed by partner agreements. Rather than gating at the collection level (too coarse — it either over-shared or hid discoverable data) or handling restricted requests manually (too slow), access rules were attached to granules and evaluated at order time. A user could discover that data existed, see what they were eligible for, and place an order through a governed delivery path without a human intermediary.

### 4. Automated content registry

The most visible operational change was the registry workflow. New datasets and updated granules flowed into the catalog through an automated ingest-and-register process instead of a manual curation step. That change drove the roughly 60% improvement in data collection efficiency and meant researchers saw new holdings when they were produced, not when someone got to the ticket.

## Trade-offs

- **Schema rigor vs. onboarding speed.** Normalizing metadata on ingest made adding a collection more deliberate. We accepted that because every shortcut in the model became a permanent exception in search and access logic.
- **Relational source of truth vs. index-only.** Keeping MySQL authoritative and ElasticSearch derived cost a synchronization step, but it made the index disposable — a property that mattered every time the mapping changed.
- **Granule-level enforcement vs. collection-level.** Finer-grained rules meant more policy data to manage. In exchange, the same catalog could serve open researchers and restricted partners honestly, showing each exactly what they could obtain.

## What It Changed

For government agencies and partner institutions, discovery and ordering became one workflow instead of several. For the team operating the catalog, new data no longer waited on manual registration. And because access was governed by the system rather than by whoever answered the request, the catalog could be trusted as the front door to the holdings rather than one of several unofficial ones.
