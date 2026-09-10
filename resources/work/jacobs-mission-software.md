---
updated: '2026-09-10'
lede: Python services, shared interfaces, messaging, and CI/CD across about 20 repositories. Technical delivery and mentoring on a team of about 10.
role: Staff Aerospace Software Engineer — software development, technical delivery, and coaching.
leadership:
  mode: Hands-on technical leadership
  team: About 10 engineers, working with program stakeholders and partner teams
  unblocked: Onboarding, technical feedback, and clearer development practices
  decision: Make integration dependencies and readiness visible when planning the work.
problem:
- Independently developed services need compatible interfaces and repeatable integration.
- Work spans approximately 20 repositories and multiple deployment environments.
- Engineers need clear priorities, development practices, and support as their responsibilities grow.
decisions:
- Build Python application and integration code alongside shared developer tooling.
- Put tests, review, and quality checks on the change instead of treating them as a late-stage ritual.
- Coordinate engineering work with program priorities and cross-team dependencies.
outcome:
- Shared interfaces, tooling, and development practices for a multi-repository software effort.
- Approximately six engineers onboarded and coached through reviews and technical feedback.
- Ongoing technical coordination for integration and release readiness.
metrics:
- value: ~10
  label: Engineers on the team
- value: ~20
  label: Repositories in scope
platform:
  caption: A high-level view of the engineering system, not a program architecture.
  stages:
  - step: 01 · Interfaces
    title: Shared contracts
    body: Independently developed services need compatible interfaces so the work can integrate.
    stack: Python · APIs
  - step: 02 · Checks
    title: Tests and review
    body: Automated tests, review, and quality checks run with the change.
    stack: pytest · CI
  - step: 03 · Integrate
    title: Messaging and packaging
    body: Interface, messaging, and dependency changes are treated as integration work.
    stack: Messaging · Packaging
  - step: 04 · Release
    title: Release readiness
    body: A version moves forward when tests, reviews, and documented assumptions are in place.
    stack: CI/CD · Release
---

Aerospace mission software at Jacobs, across about 20 repositories and multiple environments. The work is application code and the engineering system around it: shared interfaces, tests, CI, packaging, and release readiness.

## Hands-on engineering

Python services, shared interfaces, messaging integration, and service orchestration sit alongside CI/CD, automated tests, security checks, repository standards, dependency management, and release automation.

An interface change can affect several services. A dependency or packaging change can affect how another team builds and runs the software. Those effects are part of the implementation, not a later surprise.

Messaging work includes common client interfaces and adapters so services are not tightly coupled to a single broker. RabbitMQ and ActiveMQ are both in scope for that abstraction.

Quality work lives next to the code: pytest for behavior, formatting and linting, type checking, and security and dependency checks in CI. The aim is evidence on the change.

## Technical delivery

Agile planning and execution, sequencing, and dependency coordination with program stakeholders and partner teams. Program leadership sets the broader priorities. The job is to turn them into scoped work and make technical risks visible while the software is still being written.

Integration and release readiness are part of that work. A version moves forward when tests, reviews, and documented assumptions are in place.

## Developing engineers

About six engineers onboarded and coached through code reviews, technical feedback, and development guidance. For junior engineers, that includes more structured growth plans and the reasoning behind the practices.

The goal is independent work and sound decisions, not a checklist.

Program-specific architecture and operational details are not included here.
