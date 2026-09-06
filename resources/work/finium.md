---
updated: '2026-09-06'
lede: 'An enterprise managed security services platform that scaled client operations across a national carrier network.'
role: 'Core developer — built the multi-tenant provisioning, monitoring, and incident-response services.'
leadership:
  mode: 'Core platform owner growing shared engineering ownership'
  team: '2–4 platform engineers supporting multi-tenant security operations for a Fortune 500 carrier — scaled client engagements 10×'
  unblocked: 'Automated provisioning and incident orchestration so growth was not gated on tribal knowledge.'
  decision: 'Invested in multi-tenant platform foundations early — the trade paid off when client engagements scaled 10×.'
problem:
  - 'Multi-tenant security operations required manual provisioning, monitoring, and incident coordination.'
  - 'Growth was constrained by operational bottlenecks in client onboarding and response workflows.'
approach:
  - 'Built Java/SQL Server services automating provisioning, monitoring, and incident response orchestration.'
  - 'Unified multi-tenant client operations for a Fortune 500 carrier environment.'
outcome:
  - 'Drove a 10× increase in client engagements.'
  - 'Contributed to a $105M acquisition by MCI/Verizon.'
metrics:
  - value: 10×
    label: 'Client engagement growth'
  - value: $105M
    label: 'Acquisition value'
---

## Context

Finium was a managed security services platform: a carrier sold monitored firewalls, intrusion detection, and incident response to enterprise clients, and the platform was the software that made delivering those services at scale possible. The customer was a Fortune 500 carrier running a national network; the clients were the enterprises whose perimeters were being watched.

Managed security is an operations business wearing a software costume. Every new client meant provisioning devices and monitoring rules, every alert meant a triage-and-response workflow, and every one of those steps was, when I joined, substantially manual. The security operations center knew how to do the work. The constraint was that the work did not scale: onboarding a client took the people who also handled incidents, and incident handling depended on knowledge that lived in specific analysts' heads.

I was a core developer on the platform, owning the services for provisioning, monitoring, and incident-response orchestration.

## What We Built

The platform was implemented in Java with SQL Server as the operational store, structured as multi-tenant services so that one deployment served every client with strict separation between them.

### 1. Multi-tenant foundations first

The first architectural decision was also the most consequential: build tenancy into the data model and service layer from the start rather than running per-client instances or bolting isolation on later. Every record, rule, and alert carried its tenant; every query and every operator view was scoped by it. This was more work up front for a client base that was still small. It was the reason the same platform could later carry ten times as many engagements without a redesign.

### 2. Automated provisioning

Onboarding a client — devices, monitoring policy, escalation contacts, reporting — became a defined workflow driven by the platform rather than a checklist executed by hand. Provisioning that had consumed skilled analyst time was reduced to configuration and verification, and the results were consistent across clients because the same code did the work each time.

### 3. Monitoring and incident-response orchestration

Alerts from client devices flowed into a common monitoring layer where they were normalized, correlated against tenant policy, and turned into incidents with an explicit lifecycle: detection, triage, escalation, resolution, reporting. Orchestration encoded the response playbooks so that the right steps happened in the right order with the right notifications, and the record of what was done was produced as a byproduct of doing it. Analysts spent their time on judgment calls, not on coordination.

### 4. Shared ownership of the platform

With a team of two to four platform engineers, no component could belong to one person. Part of the work was deliberately spreading ownership — through shared conventions, reviewable code, and testing discipline — so that the platform did not recreate at the engineering level the tribal-knowledge problem it was solving at the operations level.

## Trade-offs

- **Platform investment vs. immediate client work.** Building multi-tenant foundations delayed some client-visible features. Had growth not materialized the investment would have looked premature; when engagements scaled 10×, it was the thing that made scaling possible without a rewrite.
- **Encoded playbooks vs. analyst discretion.** Orchestrating response workflows in software risked being too rigid for novel incidents. We kept playbooks as defaults with explicit, logged deviation rather than hard constraints, which preserved judgment while making the common case fast and consistent.
- **One shared platform vs. per-client isolation.** Separate instances would have been simpler to reason about for isolation but impossible to operate at the target client count. Rigorous tenant scoping in a shared platform was the harder and correct choice.

## Outcome

Client engagements grew 10× on a platform and team that did not grow proportionally, because provisioning and incident coordination were no longer gated on tribal knowledge. The platform and the business built on it contributed to a $105M acquisition by MCI/Verizon. Two decades later the specific technologies have aged, but the pattern — invest in multi-tenant foundations early, automate the operational path, and spread ownership so the system outlives any individual — is the one I have carried into every platform since.
