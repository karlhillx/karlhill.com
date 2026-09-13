---
updated: '2026-09-12'
lede: Technical delivery and hands-on software engineering across a simulation program spanning roughly 20 repositories, three operating environments, and multiple teams across Jacobs and vendors.
role: Staff Aerospace Software Engineer — software development, technical delivery, and coaching.
leadership:
  mode: Hands-on technical leadership
  team: About 10 engineers, working with program stakeholders, partner teams, and vendors
  unblocked: Onboarding, technical feedback, and shared delivery practices
  decision: Surface integration dependencies and readiness while the work is still being planned.
problem:
- Independently developed services need compatible interfaces and repeatable integration.
- Delivery conventions varied by repository, which made reviews, testing, and releases harder to trust.
- Work spans roughly 20 repositories, three operating environments, and multiple Jacobs and vendor teams.
decisions:
- Standardize CI/CD, review, testing, type-checking, security, and release practices across the program.
- Separate application messaging from the broker behind a shared adapter layer.
- Treat weak tests and cross-team integration risk as engineering problems, not process afterthoughts.
outcome:
- Common engineering gates replaced inconsistent project-level conventions across the program’s repositories.
- Shared asynchronous messaging lets broker choice stay in configuration instead of application rewrites.
- Stronger automated-test standards and earlier cross-team coordination when delivery is at risk.
- Six engineers onboarded and coached while reinforcing shared development practices.
metrics:
- value: ~10
  label: Engineers on the team
- value: ~20
  label: Repositories in scope
platform:
  caption: A high-level view of the engineering system, not a program architecture.
  stages:
  - step: 01 · Standards
    title: Delivery gates
    body: Common CI/CD, review, testing, type-checking, security, and release practices across repositories.
    stack: CI/CD · Review
  - step: 02 · Messaging
    title: Portable adapters
    body: Shared async messaging separates application code from the underlying broker.
    stack: Messaging · Config
  - step: 03 · Tests
    title: Meaningful coverage
    body: Isolation, failure conditions, and changed-code standards raise confidence in automated tests.
    stack: pytest · CI
  - step: 04 · Integrate
    title: Cross-team delivery
    body: Integration problems become actionable work across team and vendor boundaries.
    stack: Tickets · Dependencies
---

Technical delivery and hands-on software engineering across a simulation program spanning roughly 20 repositories, three operating environments, and multiple teams across Jacobs and vendors.

## Standardized the software delivery system

Established common CI/CD, review, testing, type-checking, security, and release practices across the program’s repositories, replacing inconsistent project-level conventions with repeatable engineering gates.

## Made messaging infrastructure portable

Architected a shared asynchronous messaging layer used by multiple services. Its adapter model separates application code from the underlying broker, allowing message queue deployments to be selected through configuration rather than application rewrites.

## Raised confidence in automated testing

Identified tests that reported coverage without meaningfully exercising behavior and drove stronger standards around isolation, failure conditions, coverage of changed code, and test organization.

## Reduced cross-team delivery friction

Work spans both team and vendor delivery processes: translating integration problems into actionable work, creating and implementing tickets across team boundaries, coordinating dependencies, and bringing engineers together early when technical issues threaten delivery.

## Built team capability alongside the software

Onboarded and coached six engineers while reinforcing shared development, review, testing, and delivery practices across the program.

Program-specific architecture and operational details are not included here.
