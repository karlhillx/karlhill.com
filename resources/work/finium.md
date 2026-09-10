---
updated: '2026-09-06'
lede: Java and SQL Server services for multi-tenant managed-security operations.
role: Core developer — provisioning, monitoring, and incident-workflow services.
leadership:
  mode: Hands-on development of shared platform services
  team: Platform engineers and security-operations colleagues
  unblocked: Manual coordination across provisioning and incident workflows
  decision: Build common services around multi-tenant operations rather than separate workflows for each
    client.
problem:
- Managed-security operations involve provisioning, monitoring, and incident workflows across multiple
  clients.
- Manual coordination makes those workflows harder to operate consistently.
approach:
- Build shared services in Java with SQL Server.
- Support multi-tenant provisioning, monitoring, and incident workflows.
- Contribute to shared testing and code-quality practices.
outcome:
- Software connecting recurring managed-security operations in a multi-tenant platform.
- Automated services for work previously dependent on manual coordination.
metrics: []
platform:
  caption: High-level managed-security workflow.
  stages:
  - step: 01 · Provision
    title: Client setup
    body: Support repeatable provisioning workflows.
    stack: Java
  - step: 02 · Monitor
    title: Operational data
    body: Bring monitoring information into the platform.
    stack: SQL Server
  - step: 03 · Respond
    title: Incident workflows
    body: Coordinate incident-related steps through shared services.
    stack: Workflow automation
---

Core developer on Finium, a managed-security platform built with Java and SQL Server. The work included services for provisioning, monitoring, and incident workflows.

## Software around the operations

The engineering problem was multi-tenant security operations: supporting different clients through a common platform while keeping their data and workflows appropriately separated.

Services automated recurring operational steps and connected information that would otherwise require manual coordination. The value was in making those workflows part of the software, not merely adding another interface over the same manual process.

## Shared development practices

Testing, code quality, and shared engineering practices sat alongside the application work. The platform needed to be maintainable by the team, not dependent on an individual developer's knowledge.
