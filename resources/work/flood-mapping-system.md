---
updated: '2026-09-10'
lede: Python and Docker software that processes and distributes satellite-derived flood maps on AWS. The live map is public.
role: Lead software developer — design, processing, and delivery on AWS.
leadership:
  mode: Software development and technical leadership
  team: Engineering and Earth science partners at NASA Goddard
  unblocked: Manual steps between processing imagery and making products available
  decision: Build repeatable processing and delivery workflows rather than rely on separate manual runs.
problem:
- Flood-mapping workflows need to connect satellite imagery, processing, and product access.
- Manual handoffs make that path harder to repeat and maintain.
decisions:
- Develop processing and delivery software in Python.
- Use Docker and AWS to support repeatable deployment and execution.
- Automate the path between processing inputs and distributing flood products.
outcome:
- An AWS-based workflow for processing and distributing satellite-derived flood maps.
- Software supporting Earth science research and flood-response use cases.
metrics: []
platform:
  caption: Public processing and delivery path. Open the live map.
  stages:
  - step: 01 · Input
    title: Satellite imagery
    body: Imagery is the input for water and flood products.
    stack: Satellite data
  - step: 02 · Process
    title: Python workflows
    body: Processing runs as packaged Python workflows, not a one-off script.
    stack: Python · Docker
  - step: 03 · Deliver
    title: Product access
    body: AWS-based services make the derived maps available.
    stack: AWS
---

Software for an AWS-based flood-mapping system at NASA Goddard, supporting Earth science partners. The work connected satellite imagery, processing, and distribution so users could obtain satellite-derived flood products through an automated service.

## Processing and delivery

Python, Docker, and AWS were the tools around that workflow. Processing automation, packaging, deployment, and product access had to fit together so the team could repeat the path.

The practical problem was larger than running a processing script successfully. Inputs, dependencies, and outputs had to stay in one maintained workflow. Automating those connections reduced reliance on separate manual steps between imagery and a usable product.

The live map is public. It is the artifact for this work: repeatable processing and delivery, not a private pipeline description.

## Related research

Co-author of *A web-based high-resolution global water and flood mapping platform*. The paper describes the Global Water and Flood Mapping System, a NASA-supported experimental portal, and its scientific evaluation.

[Read the paper](https://doi.org/10.1144/gh2025-7).
