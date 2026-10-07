---
title: 'Integration Is Not a Phase'
slug: integration-is-not-a-phase
date: 2026-10-07
excerpt: 'Independently successful services can still fail at system delivery. Interfaces, environment assumptions, and release evidence need attention throughout development.'
hero_image: img/blog/science-data-automation.jpg
tags:
  - engineering
  - integration
  - architecture
  - leadership
---

A working component is not a working release.

Two services can pass their own tests and still disagree about a message, a configuration value, a timeout, or the order in which changes reach an environment. Neither repository's green pipeline resolves the disagreement.

Treating integration as a final phase leaves those decisions until they are most expensive to change.

## Find the boundaries before the blockers

For each dependency, identify what crosses the boundary: data, behavior, ownership, deployment order, and failure expectations.

Who produces the message? Who consumes it? What happens when a field is absent, a message is repeated, or a consumer is temporarily unavailable? Which versions can operate together?

Record the assumptions where both teams can inspect them. An interface decision needs an owner and a review path, not just a diagram.

## Test the contract, then test the system

Contract tests help expose disagreements early. They do not replace integration tests.

A mock can faithfully represent the wrong assumption. Test representative payloads and failure cases against the agreed contract, then exercise actual components together with the relevant configuration.

Keep repository evidence and system evidence distinct. That makes a failure easier to locate and prevents one kind of passing check from being mistaken for another.

## Make sequencing part of the design

An interface change includes a rollout problem.

Prefer compatible changes that let producers and consumers move independently. When compatibility is impossible, identify deployment order, coordination, rollback constraints, and the evidence needed before promotion.

A ticket saying "update the other service" is not enough. Name the dependency, its owner, and the condition that unblocks the next change.

## Use failures to improve the engineering system

An integration defect is also information about the workflow.

Did two teams interpret the same contract differently? Did an environment carry undocumented configuration? Did a test exercise only the happy path? Did a release artifact omit the version information needed to reproduce the problem?

Fix the defect, then improve the boundary that allowed it to remain invisible. The improvement might be a test, a shared schema, a release check, or simply a clearer ownership decision.

Integration becomes more reliable when it is ordinary engineering work throughout delivery, rather than a final meeting where teams discover what they assumed.
