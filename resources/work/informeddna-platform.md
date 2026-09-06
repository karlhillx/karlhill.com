---
updated: '2026-09-06'
lede: 'A clinical genomics workflow platform that unified case management, counseling routing, and billing across distributed care teams.'
role: 'Platform architect — designed and delivered the case-management system end-to-end.'
leadership:
  mode: 'Platform architect in a small delivery circle'
  team: '2–4 engineers, partnering closely with product and clinical operations'
  unblocked: 'Replaced fragmented manual workflows so care teams were not waiting on ad-hoc engineering for routine cases.'
  decision: 'Prioritized governed case management and auditability over feature sprawl — reliability was the product.'
problem:
  - 'Fragmented operational processes created manual overhead across case intake, routing, and billing.'
  - 'Distributed care teams lacked a governed system with auditability and role-based access.'
approach:
  - 'Architected a Laravel platform integrating case management, counseling workflows, and billing reconciliation.'
  - 'Automated documentation pipelines and enforced role-based access with full audit trails.'
outcome:
  - 'Cut per-case operational overhead by $30K annually.'
  - 'Improved coordination across distributed genetic counseling and care teams.'
metrics:
  - value: $30K
    label: 'Annual savings per case type'
---

## The Operational Problem

InformedDNA provides genetic counseling and clinical genomics services to patients, providers, and health plans. Every case moves through a recognizable lifecycle: intake, assignment to a qualified counselor, the counseling encounter itself, documentation, and billing. The work is clinical; the coordination around it is operational.

When I joined, that coordination was spread across disconnected tools and manual steps. Intake data was re-keyed. Routing a case to the right counselor depended on someone knowing who was licensed where and available when. Documentation was assembled by hand. Billing reconciliation happened after the fact, from records that did not always agree with each other. Distributed care teams had no single governed view of a case, and questions about who had touched a record and when could not be answered with confidence.

As platform architect, my mandate was to replace that with one system of record for the case lifecycle — designed for auditability and role-based access from the first migration, not retrofitted.

## Architecture

The platform was built on Laravel with MySQL, exposing RESTful APIs for integration with adjacent systems. Four capabilities carried the operational load.

### 1. Case management as the spine

A case was modeled as a first-class entity with an explicit state machine: intake, pending assignment, scheduled, in counseling, documentation, billing, closed. Every other feature hung off that spine. The state model was deliberately narrow — a small number of well-defined transitions — because ambiguity in case state was the root cause of most of the manual reconciliation the team had been doing.

### 2. Counseling routing

Assignment rules encoded what had lived in people's heads: counselor licensure by jurisdiction, specialty, availability, and caseload. Routing became a system function with a visible rationale, and exceptions were handled by explicit override with a recorded reason rather than a side conversation.

### 3. Billing reconciliation

Billing was tied to case state and to the documented encounter, so the record that drove an invoice was the same record the care team worked from. Reconciliation shifted from a periodic cleanup exercise to a continuous check that flagged mismatches when they occurred.

### 4. Role-based access and full audit trails

Clinical data demands governance. Access was enforced by role at the application layer — counselors, coordinators, billing staff, and administrators each saw and could change only what their role required. Every material change to a case was recorded with actor, timestamp, and before/after state. Automated documentation pipelines generated the required case documents from structured data, removing a hand-assembly step that was both slow and error-prone.

## Trade-offs

- **A narrow state model vs. flexibility.** Product occasionally wanted a case to be in two places at once. We held the line on unambiguous state, because every relaxation would have re-introduced the reconciliation work the platform was meant to eliminate.
- **Auditability from day one vs. shipping faster.** Building the audit trail and role model before the first feature was slower initially. It was also the reason the platform could be trusted with clinical operations and why later features did not require security retrofits.
- **Governed platform vs. feature sprawl.** In a 2–4 person team, every feature has a maintenance cost. We optimized for a small, reliable core that operations staff could depend on over a broad surface that would decay.

## Outcome

The platform cut per-case operational overhead by $30K annually and gave distributed genetic counseling and care teams a single, governed view of their work. Routine cases stopped waiting on ad-hoc engineering. And because auditability and access control were structural rather than bolted on, the system could grow with the organization's clinical and compliance obligations rather than against them.
