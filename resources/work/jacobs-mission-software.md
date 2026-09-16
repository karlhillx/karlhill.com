---
updated: '2026-09-16'
lede: Hands-on engineering and technical delivery on a simulation program — roughly 20 repositories, three environments, a team of about 10, and partner and vendor teams. Six engineers onboarded and coached. A portable messaging layer is in use; ownership is shared.
role: Staff Aerospace Software Engineer — implementation, technical delivery, and coaching.
leadership:
  mode: Hands-on technical leadership
  team: About 10 engineers, plus program stakeholders, partner teams, and vendors
  unblocked: Onboarding, technical feedback, and turning integration problems into tickets while the change is still cheap.
  decision: Treat weak tests and integration risk as engineering work, not process leftovers.
  note: Formal personnel management remains with management.
problem:
- Independently developed services need compatible interfaces and repeatable integration.
- Delivery conventions varied by repository, which made reviews, testing, and releases harder to trust.
- Work spans roughly 20 repositories, three environments, and multiple Jacobs, partner, and vendor teams.
decisions:
- Put CI/CD, two-approval review, testing, type-checking, security, coverage, and release practices on a shared baseline.
- Separate application messaging from the broker behind a common interface and adapters.
- Treat tests that do not exercise behavior, and late integration, as defects in the engineering system.
outcome:
- Established at least 80% repository test coverage, two-approval pull-request governance, and automated quality gates across the repositories in scope. Releases are safer and more predictable.
- Shared delivery gates are the adopted baseline across those repositories.
- A portable messaging layer is in use so broker choice can stay in configuration. Ownership is shared.
- Stronger unit-test expectations are defined and applied in review. They are not a finished program-wide rewrite.
- Six engineers onboarded and coached while the same practices were reinforced in review.
metrics:
- value: ~10
  label: Engineers on the team
- value: ~20
  label: Repositories in scope
- value: ≥80%
  label: Repository test coverage
status:
- label: Delivery gates
  state: Adopted
  detail: Shared CI/CD, two-approval review, testing, type-checking, security, coverage, and release baseline in use across the repositories in scope.
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
diagram:
  title: Engineering delivery system
  caption: Local checks run on the workstation; CI provides the authoritative repository gate. Downstream validation covers cross-repository and environment-level behavior. Simplified, unclassified delivery view—not a program architecture.
  zones:
    - label: Local development
      stages:
        - label: Code
        - label: Pre-commit
          guard: true
          lines:
            - format · lint · imports · types · secrets
        - label: Commit / push
          compact: true
    - label: Repository validation
      fork:
        stem:
          label: Pull request
        branches:
          - label: Review
            lines:
              - 2+ approvals
          - label: CI pipeline
            lines:
              - unit tests · coverage · SAST
              - dependency audit · build
        join:
          label: Merge gate
          lines:
            - review + CI pass
    - label: Change intelligence
      boxed: true
      steps:
        - Change detection
        - Delta tagging
        - Cross-repo impact
    - label: System validation
      steps:
        - Integration tests
        - E2E tests
        - Environment validation
        - Release
  loop: Validation feedback
---

Program-specific architecture and operational details are not included here.

Read the status labels as the adoption record: delivery gates are the baseline, messaging is in use with shared ownership, the unit-test standard is in review, and cross-team delivery is ongoing work.

A concrete defect: some tests reported coverage without failing when the behavior was wrong, including filters whose no-op path never triggered a failure. Remaining test work is quality — isolation, representative data, and failure cases on changed code — not another coverage number.
