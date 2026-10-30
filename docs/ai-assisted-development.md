# AI-assisted development

Coding agents working in this repository start at [`AGENTS.md`](../AGENTS.md). That file is the contract: what not to publish, when a push is a production deploy, and where content lives.

Tool-specific files are short pointers:

- [`CLAUDE.md`](../CLAUDE.md) for Claude Code
- [`.cursor/rules/project.mdc`](../.cursor/rules/project.mdc) for Cursor
- [`.github/copilot-instructions.md`](../.github/copilot-instructions.md) for GitHub Copilot

Project knowledge sits in [`.ai/`](../.ai/). Those files are not loaded automatically. [`AGENTS.md`](../AGENTS.md) tells the agent to open [`.ai/rules/index.md`](../.ai/rules/index.md) and the rule that matches the files being edited.

| Rule | Applies when editing |
| --- | --- |
| [Architecture](../.ai/rules/architecture.md) | PHP, routes, config, Blade, essays, case studies |
| [Design system](../.ai/rules/design-system.md) | CSS, Blade, front-end JS |
| [Disclosure](../.ai/rules/security.md) | Public copy |
| [Verification](../.ai/rules/verification.md) | Tests, CI, package manifests |

Two skills are workflows, read only when the task matches:

- [Frontend review](../.ai/skills/frontend-review/SKILL.md) — rendered type, spacing, and layout
- [Code review](../.ai/skills/code-review/SKILL.md) — diff, disclosure, and tests

Laravel Boost is not a dependency. The `.ai/rules` and `.ai/skills` paths match Boost’s conventions so a later install can sit beside them. Human review still decides what merges and what deploys. A push to `main` runs syntax CI and then the Deploy workflow.
