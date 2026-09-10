---
updated: '2026-09-06'
lede: Search, metadata, and access workflows for Earth science data.
role: Lead developer — catalog, search, and data-access software.
leadership:
  mode: Technical development of a shared data catalog
  team: Engineering, data-management, and research partners
  unblocked: Manual registration and disconnected discovery and access workflows
  decision: Establish consistent metadata before building search and access behavior around it.
problem:
- Researchers need to discover data across different holdings and metadata conventions.
- Discovery, access rules, and ordering need to work together.
approach:
- Develop a shared metadata model and catalog workflows.
- Use MySQL and Elasticsearch to support storage and discovery.
- Implement granule-level access controls and automate content registration.
outcome:
- A shared catalog connecting search, access, and ordering workflows.
- An automated registry process that reduced recurring manual registration work.
metrics: []
platform:
  caption: High-level catalog workflow.
  stages:
  - step: 01 · Register
    title: Dataset metadata
    body: Bring dataset information into a consistent model.
    stack: MySQL
  - step: 02 · Discover
    title: Search the catalog
    body: Make holdings searchable through the discovery layer.
    stack: Elasticsearch
  - step: 03 · Access
    title: Apply access rules
    body: Connect search results to eligible access and ordering.
    stack: Catalog workflows
---

Catalog and search software for ESSCOR brought together Earth science metadata, discovery, access rules, and ordering workflows.

## Search built on consistent metadata

The platform combined MySQL metadata storage with Elasticsearch search. A shared model made the holdings easier to describe and query, rather than making each collection a separate search problem.

Granule-level access controls connected discovery to the appropriate data-access workflow.

## Automating registration

The content-registry workflow automated recurring steps involved in registering datasets and making them discoverable. The practical improvement was less manual coordination between new data arriving and researchers being able to find it.

The work combined data modeling, application development, search integration, and workflow automation.
