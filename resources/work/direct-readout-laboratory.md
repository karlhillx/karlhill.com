---
updated: '2026-09-06'
lede: Software for processing and distributing satellite data products.
role: Lead developer — satellite-data processing and distribution software.
leadership:
  mode: Software development for scientific data workflows
  team: Engineering and science-operations partners
  unblocked: Inconsistent processing and distribution workflows
  decision: Use clearer product boundaries and distribution conventions to make the software easier to
    maintain.
problem:
- Data from multiple instruments needs consistent processing and distribution workflows.
- Changing products and dependencies make one-off processing paths difficult to maintain.
approach:
- Develop software for data ingestion, reformatting, and distribution.
- Organize processing around defined inputs, outputs, and product tiers.
- Support the applications on Linux and NGINX infrastructure.
outcome:
- Software supporting NASA direct-readout data processing and distribution.
- More consistent handoffs between processing stages and downstream users.
metrics: []
platform:
  caption: High-level data workflow.
  stages:
  - step: 01 · Input
    title: Receive data
    body: Bring incoming data into the processing workflow.
    stack: Linux
  - step: 02 · Process
    title: Prepare products
    body: Reformat and organize the data for downstream use.
    stack: Data processing
  - step: 03 · Distribute
    title: Provide access
    body: Support consistent product access and distribution.
    stack: NGINX
---

Software for NASA's Direct Readout Laboratory focused on satellite-data ingestion, processing, and distribution.

## Making the workflow maintainable

The software had to accommodate different data products and processing dependencies. Ingestion and reformatting workflows needed clearer boundaries between the input data, processing steps, and products made available to downstream users.

Standardizing those paths made the software easier to reason about and maintain as the data and processing requirements changed.

## Supporting distribution

The work also included distribution software on Linux and NGINX infrastructure. The focus was consistent access to products and cleaner handoffs between the software processing the data and the systems consuming it.
