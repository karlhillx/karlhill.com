---
updated: '2026-10-30'
lede: Three independent developer tools for a common engineering problem — make feedback faster, test priorities clearer, and delivery policy easier to inspect.
role: Independent author and maintainer — tool design, implementation, tests, and documentation.
attribution: These are independently authored projects, not employer-delivered tools. Public source, tests, and documentation are the evidence; employer adoption, usage counts, and performance benchmarks are not claimed.
problem:
- Pipeline failures are expensive to discover only after pushing a commit.
- A coverage percentage does not say which untested function deserves the next test.
- Delivery rules are easy to skip when they live only in a document.
decisions:
- Build focused tools with explicit inputs and outputs rather than one all-purpose developer platform.
- Use Python for local pipeline runs and test-risk ranking, and Go for pipeline policy checks.
- Keep the public README as the contract for supported inputs, modes, and rules.
outcome:
- Three public tools covering local CI, test-risk ranking, and pipeline policy checks.
- Source, tests, and READMEs are the technical proof. No usage or adoption figures are claimed.
metrics: []
---

The common thread is the feedback loop around software. A pipeline file, a coverage report, or a deployment rule is useful when an engineer can run it and read the result. Each tool stays inside one job.

## bb-run: local pipeline runs

[bb-run](https://github.com/karlhillx/bb-run) reads `bitbucket-pipelines.yml` and runs it in Docker or on the host. The public README covers parallel steps, fail-fast, artifacts, services, caches, and after-script. Docker is the closer match to Bitbucket. Host mode runs script-only steps without Docker; steps that declare service sidecars still need it. It is Python 3.12+ with PyYAML as the runtime dependency. A local run is a development aid, not a substitute for Bitbucket’s own pipeline.

## testrisk: which gap to test next

[testrisk](https://github.com/karlhillx/testrisk) reads `coverage.py` data, maps uncovered lines onto functions, and ranks those functions. The score draws on uncovered branches, complexity, git-changed lines, and tests that are missing or only ceremonial. The default command advises. Optional flags (`--fail-under-changed`, `--fail-on-risk`, `--fail-on-weak`) can fail a run, and `--prompt` turns the same evidence into a short task. It is Python 3.12+. A ranking points at a gap. It does not prove the behavior is correct.

## pipeguard: policy against the pipeline file

[pipeguard](https://github.com/karlhillx/pipeguard) is a Go checker for Bitbucket Pipelines YAML. The documented rules require a named step, forbid image tags such as `latest`, require `trigger: manual` for named deployment environments, and restrict Bitbucket Pipes to an allowlist. Findings are `ERROR`, `WARNING`, or `INFO`, as text or JSON. It checks the file. It does not execute the pipeline.

Each tool has its own boundary. Start with the README, then the implementation and tests.
