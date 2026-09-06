---
updated: '2026-09-06'
lede: 'Mission-focused software delivery — published as the shape of the work, not the programs themselves.'
role: 'Staff Aerospace Software Engineer — delivery leadership and engineering standards in constrained environments.'
leadership:
  mode: 'Staff IC with delivery ownership — coaching, release discipline, and stakeholder translation'
  team: 'Cross-functional engineering, integration, and mission partners'
  unblocked: 'Made release readiness and integration risk visible early enough that the team could act without late-stage heroics.'
  decision: 'Treat constrained delivery as a product problem: shared standards and traceability beat tribal knowledge.'
problem:
  - 'Mission software crosses environments with different delivery constraints — late integration drift is expensive and hard to unwind.'
  - 'Security, traceability, and release evidence are not optional, and they cannot live in one person’s head.'
  - 'Program specifics cannot be published, so the public record has to describe the operating system, not the mission.'
decisions:
  - 'Lead delivery through shared quality checks and governance so “ready” is evidence, not a meeting.'
  - 'Coach the team on PR discipline, Definition of Done, and ownership so standards outlast any one engineer.'
  - 'Keep the public case study at the level of constraints and practices — no program names, customers, system designs, tools, or screenshots.'
outcome:
  - 'A delivery system that can move across constrained environments without relying on heroics.'
  - 'Engineering standards that make integration risk visible before it becomes a surprise.'
  - 'A public description of current work that is accurate without being operationally specific.'
metrics:
  - value: Constrained
    label: 'Aerospace mission software'
  - value: Unpublished
    label: 'Program details'
platform:
  caption: 'Schematic · Program names, customers, and mission data are unpublished.'
  stages:
    - step: '01 · Ingest'
      title: 'Simulation & Telemetry'
      body: 'Cloud-native streaming pipelines ingesting synthetic flight data and operational sensor feeds.'
      stack: 'Python · AWS'
    - step: '02 · Pipeline'
      title: 'DevSecOps & Gates'
      body: 'Deterministic CI/CD, PR coaching, multi-repo governance, and immutable artifact verification.'
      stack: 'Kubernetes · CI/CD'
    - step: '03 · Release'
      title: 'Multi-Environment Ship'
      body: 'Continuous readiness across isolated and connected baselines without late-stage heroics.'
      stack: 'High-Assurance'
---

At Jacobs, I help teams deliver mission-focused software in constrained environments. My work spans platform delivery, engineering standards, and release readiness.

This page intentionally stays at the level of engineering practice. It does not publish program names, customers, system designs, environment topology, tools, screenshots, or operating procedures. What I can share is the work: make delivery repeatable, raise the engineering bar through coaching and standards, and surface integration risk early.

The NASA case studies on this site are the public proof of how I build platforms. This one is the current chapter — same habits, tighter constraints.

## Delivery Context

In high-assurance environments, software often crosses boundaries with different access, verification, and release requirements. Changes that appear sound in one environment may not be ready to promote to another.

The recurring failure mode is **late-stage integration drift**:
- Assumptions that are invisible during development become costly at a release boundary.
- Release evidence and ownership become fragmented across teams.
- Integration turns into reactive triage instead of planned engineering work.

My approach treats delivery as a first-class product problem: quality, traceability, and readiness need to be visible throughout the work—not reconstructed at the end.

## How I Work

I focus on practices that make teams more reliable without concentrating critical knowledge in one person:

1. **Make readiness observable:** Define and automate the evidence that establishes whether a change is ready to move forward.
2. **Design for repeatability:** Reduce manual handoffs and undocumented assumptions so teams can make progress predictably.
3. **Create shared standards:** Establish lightweight review, testing, and delivery expectations that teams can use consistently.

## Engineering Governance

Technical depth alone does not scale high-assurance work. As a Staff engineer with delivery ownership, my primary impact is elevating the team's working habits:

- **Code Reviews That Teach:** Pull requests are not rubber stamps or gatekeeper bottlenecks; they are mentoring tools. Clear rubrics ensure PRs address architectural resilience, error handling, and test coverage before merging.
- **Explicit Definition of Done:** Features are not complete when code compiles locally. A task is done when the agreed quality and release checks are satisfied.
- **De-risking Integration Early:** Instead of deferring integration to the end of a sprint, teams validate interfaces and delivery assumptions continuously.

When systems fail in constrained environments, recovery is expensive. By building repeatable delivery practices and coaching teams to own standards, we make mission-focused software more dependable without publishing operational detail.
