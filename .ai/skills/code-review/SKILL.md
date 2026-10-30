---
name: code-review
description: Review a karlhill.com diff for architecture, disclosure, tests, and regressions. Use when asked for a code review or before merging.
---

# Code review

## When to use

Someone asks for a review, or a change is about to merge. Read the diff. Do not review from the summary alone.

## Steps

1. Read `AGENTS.md`, `.ai/rules/architecture.md`, and `.ai/rules/security.md`.
2. Read the diff. List files and the behavior each one changes.
3. Split findings:
   - **Evidence** — a line, a failing command, or a rendered page you saw.
   - **Assumption** — a risk you did not verify. Label it as such.
4. Check disclosure. New numbers, program names, and repository links must already be public and qualified.
5. Check scope. A drive-by font, palette, or dependency does not belong in an unrelated change.
6. Check tests. Name the Pest, Pint, or Playwright command that covers the change. If none ran, say so.
7. Check production risk. Anything that would push `main` or dispatch Deploy needs an explicit human request.

## Report

Lead with defects that change behavior or leak nonpublic detail. Then test gaps. Omit style nits that Pint already enforces.

Do not approve a merge or a deploy in the review. The human does that.
