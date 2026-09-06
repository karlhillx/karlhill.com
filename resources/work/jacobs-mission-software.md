---
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

<figure class="my-8 overflow-hidden rounded-lg border border-neutral-800 bg-neutral-900/70 p-4 sm:p-6 backdrop-blur-sm">
  <div class="overflow-x-auto">
    <svg viewBox="0 0 780 170" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full min-w-[640px] h-auto" role="img" aria-label="Representative delivery lifecycle: build, verify, and release">
      <rect x="20" y="35" width="210" height="100" rx="8" fill="#1f1f1f" stroke="#404040" stroke-width="1.5" />
      <text x="125" y="78" fill="#ffffff" font-family="system-ui, sans-serif" font-size="18" font-weight="600" text-anchor="middle">Build</text>
      <text x="125" y="105" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="12" text-anchor="middle">Make work repeatable</text>
      <path d="M230 85 H278" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" />
      <polygon points="278,80 290,85 278,90" fill="#38bdf8" />
      <rect x="290" y="35" width="210" height="100" rx="8" fill="#1f1f1f" stroke="#404040" stroke-width="1.5" />
      <text x="395" y="78" fill="#ffffff" font-family="system-ui, sans-serif" font-size="18" font-weight="600" text-anchor="middle">Verify</text>
      <text x="395" y="105" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="12" text-anchor="middle">Make readiness visible</text>
      <path d="M500 85 H548" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" />
      <polygon points="548,80 560,85 548,90" fill="#38bdf8" />
      <rect x="560" y="35" width="200" height="100" rx="8" fill="#1f1f1f" stroke="#404040" stroke-width="1.5" />
      <text x="660" y="78" fill="#ffffff" font-family="system-ui, sans-serif" font-size="18" font-weight="600" text-anchor="middle">Release</text>
      <text x="660" y="105" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="12" text-anchor="middle">Learn and improve</text>
    </svg>
  </div>
  <figcaption class="mt-3 text-center font-mono text-caption text-neutral-400 uppercase tracking-widest">
    Representative delivery lifecycle — not a Jacobs system architecture
  </figcaption>
</figure>

1. **Make readiness observable:** Define and automate the evidence that establishes whether a change is ready to move forward.
2. **Design for repeatability:** Reduce manual handoffs and undocumented assumptions so teams can make progress predictably.
3. **Create shared standards:** Establish lightweight review, testing, and delivery expectations that teams can use consistently.

## Engineering Governance

Technical depth alone does not scale high-assurance work. As a Staff engineer with delivery ownership, my primary impact is elevating the team's working habits:

- **Code Reviews That Teach:** Pull requests are not rubber stamps or gatekeeper bottlenecks; they are mentoring tools. Clear rubrics ensure PRs address architectural resilience, error handling, and test coverage before merging.
- **Explicit Definition of Done:** Features are not complete when code compiles locally. A task is done when the agreed quality and release checks are satisfied.
- **De-risking Integration Early:** Instead of deferring integration to the end of a sprint, teams validate interfaces and delivery assumptions continuously.

When systems fail in constrained environments, recovery is expensive. By building repeatable delivery practices and coaching teams to own standards, we make mission-focused software more dependable without publishing operational detail.
