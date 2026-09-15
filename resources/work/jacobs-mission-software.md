---
updated: '2026-09-15'
lede: Hands-on engineering and technical delivery on a simulation program — roughly 20 repositories, three environments, a team of about 10, and partner and vendor teams. Public proof is scope and practice, not program metrics.
role: Staff Aerospace Software Engineer — implementation, technical delivery, and coaching.
leadership:
  mode: Hands-on technical leadership
  team: About 10 engineers, plus program stakeholders, partner teams, and vendors
  unblocked: Onboarding, technical feedback, and the shared delivery practices now used in review.
  decision: Treat weak tests and integration risk as engineering work, not process leftovers.
  note: Formal personnel management remains with management.
problem:
- Independently developed services need compatible interfaces and repeatable integration.
- Delivery conventions varied by repository, which made reviews, testing, and releases harder to trust.
- Work spans roughly 20 repositories, three environments, and multiple Jacobs, partner, and vendor teams.
decisions:
- Put CI/CD, review, testing, type-checking, security, and release practices on a shared baseline.
- Separate application messaging from the broker behind a common interface and adapters.
- Treat tests that do not exercise behavior, and late integration, as defects in the engineering system.
outcome:
- Delivery gates, portable messaging, and stronger tests are in use. Coverage across repositories is still uneven; program-level metrics are not published here.
- Shared delivery gates are the adopted baseline, not a claim that every repository already meets them.
- A portable messaging layer is in use so broker choice can stay in configuration. Ownership is shared.
- Stronger unit-test expectations are defined and applied in review. They are not a finished program-wide rewrite.
- Six engineers onboarded and coached while the same practices were reinforced in review.
metrics:
- value: ~10
  label: Engineers on the team
- value: ~20
  label: Repositories in scope
status:
- label: Delivery gates
  state: Adopted
  detail: Shared CI/CD, review, testing, type-checking, security, and release baseline. Coverage across repositories is still uneven.
- label: Portable messaging
  state: In use
  detail: Common interface and broker adapters. Ownership is shared; every consumer is not claimed.
- label: Unit-test standard
  state: In progress
  detail: Written and used in review. Not a repository-wide rewrite.
- label: Cross-team delivery
  state: Ongoing
  detail: Tickets, sequencing, and coordination. Not a closed initiative.
- label: Coaching
  state: Shipped
  detail: Six engineers onboarded. Personnel decisions remain with management.
platform:
  caption: A high-level view of the engineering system, not a program architecture.
  stages:
  - step: 01 · Standards
    title: Delivery gates
    body: Shared CI/CD, review, testing, type-checking, security, and release practices. The baseline is adopted; coverage across repositories is still catching up.
    stack: CI/CD · Review
  - step: 02 · Messaging
    title: Portable adapters
    body: A common messaging interface and broker adapters so applications are not rewritten when the queue changes.
    stack: Messaging · Config
  - step: 03 · Tests
    title: Meaningful tests
    body: Isolation, failure cases, and changed-code coverage. The standard is written; applying it is ongoing.
    stack: Tests · CI
  - step: 04 · Integrate
    title: Cross-team delivery
    body: Integration problems become tickets, sequenced work, and conversations while the change is still cheap to fix.
    stack: Tickets · Dependencies
---

Hands-on software engineering and technical delivery on a simulation program. The work covers implementation, standards, messaging, tests, and coordination across roughly 20 repositories, three operating environments, and a team of about 10, with partner and vendor teams in the same delivery path.

Program-specific architecture and operational details are not included here.

## Delivery gates

Repositories were not starting from the same review, test, or release conventions. The shared baseline is now CI/CD, review, type-checking, security checks, and release practice. That baseline is adopted. How completely each repository meets it is still uneven, and this page does not treat the program as finished.

## Portable messaging

Services need to exchange messages without baking a single broker into application code. The work advances a common interface and adapter layer so the queue can be selected in configuration. It is in use. It is not a sole-author product, and production coverage across every consumer is not claimed here.

## Tests that exercise behavior

Some tests reported coverage without failing when the behavior was wrong — including filters whose no-op path never triggered a failure. The response was a tighter unit-test standard: isolation, representative data, meaningful failure cases, and coverage of changed code. Those expectations are written and used in review. They are not a completed, repository-wide rewrite.

## Cross-team delivery

Delivery is not limited to one team's board. The work includes turning integration problems into tickets, implementing and delegating them, sequencing dependencies, and bringing the right engineers together while the change is still cheap. That is ongoing responsibility, not a closed initiative.

## Coaching while shipping

Six engineers were onboarded and coached through review, technical feedback, and the same delivery practices. Formal personnel decisions remain with management.
