---
updated: '2026-09-16'
lede: Python and Docker software that processes and distributes satellite-derived flood maps on AWS. The live map is the shipped artifact. A GeoHorizons paper describes the system and its scientific evaluation.
role: Lead Software Engineer — design, processing, and delivery on AWS.
leadership:
  mode: Software development and technical leadership
  team: Engineering and Earth science partners at NASA Goddard
  unblocked: Manual steps between processing imagery and making products available.
  decision: Build repeatable processing and delivery workflows rather than rely on separate manual runs.
problem:
- Flood products have to connect satellite imagery, processing, and a place users can get the result.
- Separate manual handoffs made that path harder to repeat and maintain.
decisions:
- Write the processing and delivery software in Python.
- Package and run it with Docker on AWS so the same path can be rebuilt and executed elsewhere.
- Automate the connections between inputs, processing, and product access.
outcome:
- The public map is the shipped system — satellite-derived flood products through a repeatable AWS workflow. Program-level before/after metrics are not published here.
- Python, Docker, and AWS carry processing and delivery so the team is not depending on a one-off script.
- Co-author of the GeoHorizons paper that describes the Global Water and Flood Mapping System and evaluates it scientifically.
metrics: []
---

Lead software engineering on an AWS-based flood-mapping system at NASA Goddard. The work connected satellite imagery, processing, and distribution so Earth science partners could obtain satellite-derived flood products through one maintained service instead of a chain of manual runs.

The live map is public. It is the artifact for this work: repeatable processing and delivery, not a private pipeline description.

## Processing and delivery

Python, Docker, and AWS were the tools around that workflow. Packaging, deployment, processing automation, and product access had to stay in one path so the same change could be rebuilt and run again.

The practical problem was larger than getting a script to finish. Inputs, dependencies, and outputs had to remain together. Automating those connections reduced reliance on separate manual steps between imagery and a usable product.

This page does not publish latency, coverage, or agency-adoption figures. The public map and the paper are the evidence.

## Related research

Co-author of [*A web-based high-resolution global water and flood mapping platform*](/research/global-flood-mapping), published in GeoHorizons (7 July 2026). CRediT: Software (Equal); Writing – review & editing (Equal). The paper describes the Global Water and Flood Mapping System, a NASA-supported experimental portal, and its scientific evaluation. It is not a claim of sole authorship.

[Peer-reviewed research](/research/global-flood-mapping). [Read the paper](https://doi.org/10.1144/gh2025-7).
