---
updated: '2026-09-15'
lede: Catalog, search, and access software for Earth science holdings — metadata, discovery, and ordering in one workflow. There is no public demo.
role: Lead Software Engineer — catalog, search, and data-access software.
leadership:
  mode: Technical development of a shared data catalog
  team: Engineering, data-management, and research partners
  unblocked: Manual dataset registration and disconnected discovery and access paths.
  decision: Establish a shared metadata model before building search and access on top of it.
problem:
- Researchers need to discover data across holdings that did not share one metadata convention.
- Discovery, access rules, and ordering have to work together, not as separate tools.
decisions:
- Build a shared metadata model and catalog workflows.
- Use MySQL for metadata storage and Elasticsearch for discovery.
- Connect search results to granule-level access rules and automate dataset registration.
outcome:
- A catalog that connects search, access, and ordering around one metadata model. There is no public demo.
- An automated registration workflow so new holdings do not depend on a fully manual path into the catalog.
- This page does not publish how much registration time changed. A percentage is not claimed here.
metrics: []
platform:
  caption: High-level catalog workflow. Not a public service to open.
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

Catalog and search software for ESSCOR. The work brought Earth science metadata, discovery, access rules, and ordering into one catalog so a holding was not a separate search problem.

There is no public demo. What can be said is the system and the decisions, not a live artifact and not a time-saved figure.

## Search on a shared model

MySQL stored the metadata. Elasticsearch provided discovery. A shared model made holdings easier to describe and query instead of giving each collection its own search path.

Granule-level access rules connected a search result to the access and ordering workflow that applied. Discovery without those rules would have stopped at a list.

## Registration

A content-registry workflow automated the recurring steps that take a dataset from arrival to something the catalog can describe. The aim was less manual coordination between new data and a researcher being able to find it.

How much that reduced registration work is not published here.
