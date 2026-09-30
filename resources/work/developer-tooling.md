---
updated: '2026-09-30'
lede: Three independent developer tools for a common engineering problem — make feedback faster, test priorities clearer, and delivery policy easier to inspect.
role: Independent author and maintainer — tool design, implementation, tests, and documentation.
problem:
- Pipeline failures are expensive to discover only after pushing a commit.
- Delivery policies and environment assumptions are difficult to maintain when they live only in documentation.
- Coverage alone does not tell engineers which test gaps deserve attention first.
decisions:
- Build focused tools with explicit inputs and outputs rather than one all-purpose developer platform.
- Use Python for local pipeline workflows and test-risk analysis, and Go for pipeline policy validation.
- Keep usage, implementation, and tests available in public repositories so the work can be inspected.
outcome:
- Three public tools covering local CI, test-risk analysis, and policy checks.
- Source code and usage documentation provide technical proof beyond a portfolio description.
- These are independent projects. No employer adoption, performance benchmark, or usage count is claimed here.
metrics: []
---

The common thread is the feedback loop around software. A pipeline definition, test report, or deployment policy is useful only when engineers can exercise it and understand the result. These projects approach that problem at different layers.

## bb-run: local feedback

[bb-run](https://github.com/karlhillx/bb-run) runs Bitbucket Pipelines locally from an existing pipeline file. It moves a portion of the commit-push-fail loop onto the workstation. Local execution is a development aid, not a replacement for authoritative CI.

## testrisk: test priorities

[testrisk](https://github.com/karlhillx/testrisk) combines coverage, code structure, and git churn to help prioritize Python test gaps. A risk ranking directs attention; it is not a substitute for checking behavior.

## pipeguard: delivery policy

[pipeguard](https://github.com/karlhillx/pipeguard) checks pipeline definitions against policy. It makes delivery constraints inspectable alongside the configuration they govern. Its repository documents the supported rules and invocation.

These tools belong together as developer-platform engineering, but they are not presented as a single integrated stack. Each has its own boundary, documentation, and implementation.

## What to inspect

Start with the README and supported inputs, then follow the implementation and tests. Look for how errors are reported, how configuration is represented, and which assumptions the tests actually exercise. Public source is the proof; star counts and unverified benchmark claims are not.
