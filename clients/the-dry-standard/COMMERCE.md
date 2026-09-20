# Commerce & outbound links — phase policy

**Phase:** staged preview under karlhill.com (pre–own-domain, pre–affiliate).
**Status:** active as of 20 Sep 2026.
**Owner:** Karl Hill (editor).

## Decision

**Keep “Where to buy.”** Do not remove the section.

**This phase, outbound purchase links are producer-only** (brand site, brand DTC, importer/producer shop that is the product’s own commercial home).

**Retailers, marketplaces, and NA specialists may be named in plain text** (`availability` and similar) **but must not carry an `href` yet** — no Total Wine, Amazon, The Zero Proof, Boisson, etc. as clickable purchase links until a commercial relationship exists.

Producer / brand links stay fine. They are provenance and utility, not our monetization surface.

## Why (this phase)

1. **Editorial independence first.** Scores and classification must not look like a storefront. Linking every shelf we mention trains readers (and partners) that outbound retail is free traffic.
2. **Option value.** Named-but-unlinked availability is honest help for readers *and* a clean opt-in for future collaborators: “we already cite you; a tracked link is the partnership.” Giving the link away now weakens that conversation.
3. **Disclosure debt.** The schema already supports `relationship: citation | affiliate | paid` and a separate `affiliate_url`. Shipping unlabeled retailer deep links before those relationships exist creates cleanup and FTC-shaped mess later.
4. **Removing the section is worse.** Readers still ask where to get the bottle. Dropping “Where to buy” hides useful facts and removes the future affiliate surface entirely.

## What to put where

| Intent | Field | Linked? |
| --- | --- | --- |
| Brand / producer official product or shop URL | `purchase_links[]` with `relationship: citation` | **Yes** |
| “Sold at Total Wine, Amazon, The Zero Proof…” | `availability` (prose) | **No** |
| Future tracked retailer / affiliate URL | `purchase_links[]` + `relationship: affiliate` or `paid`; store partner URL in `affiliate_url` if the public href must stay clean | **Only after agreement** |
| Evidence that a fact came from a retailer sheet | `sources[]` (claims), not “Where to buy” | Yes, as a **source** citation — different job |

Sources that prove ABV or method are not the same as purchase CTAs. Keep them in Sources & verification.

## Public copy

Default buy blurb (renderer):

> Purchase links go to the producer when we have one. Retailers named in the text are availability notes only — not paid placement.

Do not imply affiliate or partnership until `affiliate_relationship: present` (or paid) is set and disclosed.

## Future iterations

### Phase B — partnered retail (post opt-in)

- Add retailer `purchase_links` only for partners with a written understanding (affiliate, paid, or reciprocal).
- Set `relationship` accurately; turn on `affiliate_relationship: present` when any affiliate href ships.
- Prefer `affiliate_url` for the monetized URL if the visible link must remain a clean citation.
- Disclose on the review (existing disclosure path) and keep scores independent of who pays for a click.

### Phase C — own domain / scale

- Revisit marketplace defaults (Amazon, Total Wine) only with network terms and disclosure.
- Optional: geo or region filters on buy links; still no silent monetization.
- Industry kit (`/industry/`) can pitch “featured availability” as a product once Phase B exists.

### Explicit non-goals this phase

- No affiliate IDs in the wild “just to test.”
- No removing producer links to be “fair” to retailers.
- No pay-for-score or pay-for-classification.

## Enforcement

- **Agents / editors:** follow this file when drafting or revising `purchase_links` and `availability`. See also `AGENTS.md` and `SITE.md`.
- **Code:** renderer may later hard-block non-producer hrefs; until then, editorial discipline is the gate. Prefer fixing data over relying on a filter.
- **Review when:** first signed retail/affiliate agreement, or domain cutover (`CUTOVER.md`) — whichever comes first.
