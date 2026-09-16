---
updated: '2026-09-15'
lede: Software for ingesting, processing, and distributing NASA direct-readout satellite products. The portal is public.
role: Lead Software Engineer — satellite-data processing and distribution software.
leadership:
  mode: Software development for scientific data workflows
  team: Engineering and science-operations partners
  unblocked: One-off processing paths that were hard to keep consistent as products changed.
  decision: Organize the work around defined inputs, outputs, and product tiers instead of a separate path per product.
problem:
- Data from multiple instruments needs consistent ingest, processing, and distribution.
- Changing products and dependencies make one-off processing paths hard to maintain.
decisions:
- Write software for ingestion, reformatting, and distribution as one workflow.
- Organize processing around defined inputs, outputs, and product tiers.
- Run the applications on Linux and NGINX.
outcome:
- The public portal is the artifact — direct-readout products and downstream access. This page does not publish throughput or adoption metrics.
- Processing is organized around product boundaries so a new product is not a new one-off path.
- Distribution software on Linux and NGINX supports access to those products. Existing science-operations interfaces stayed in place.
metrics: []
---

Software for NASA's Direct Readout Laboratory. The work covered ingest, reformatting, and distribution so satellite products could move through one maintained path instead of a separate process for each product.

The portal is public. Open it. This page does not describe unpublished processing internals.

## Product boundaries

Incoming data, processing steps, and the products handed to downstream users needed clearer boundaries. The software had to accommodate different instruments and changing dependencies without turning each product into its own pipeline.

Organizing those paths around defined inputs, outputs, and product tiers made the system easier to reason about as requirements changed. That is how the work was structured. It is not a measured before/after claim.

## Distribution

Distribution software ran on Linux and NGINX. The job was consistent access to products and a cleaner handoff between the software that processed the data and the systems that consumed it.

PHP was part of the application stack. Science-operations interfaces around the portal stayed in place.
