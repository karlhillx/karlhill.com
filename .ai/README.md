# AI-assisted development

This directory is project knowledge for coding agents. It is not a tool-specific instruction file. Cursor, Claude Code, and Copilot do not load `.ai/` on their own.

| Path | Role |
| --- | --- |
| [`../AGENTS.md`](../AGENTS.md) | Contract. Read first. |
| [`rules/index.md`](rules/index.md) | Which rule applies to which paths. |
| [`rules/`](rules/) | Architecture, design system, disclosure, verification. |
| [`skills/`](skills/) | On-demand workflows. Read the `SKILL.md` when the task matches. |

Laravel Boost is not installed. The paths match Boost’s project-rule and custom-skill conventions (`.ai/rules/*.md` with `paths` frontmatter, `.ai/skills/<name>/SKILL.md`). If Boost is added later, keep these files and let `boost:update` install package skills beside them. Do not add Boost only to publish this directory.

Generated agent files (`.mcp.json`, a Boost-managed block inside `AGENTS.md`) are not part of this setup. `AGENTS.md` and `CLAUDE.md` in this repository are hand-written and should stay committed.
