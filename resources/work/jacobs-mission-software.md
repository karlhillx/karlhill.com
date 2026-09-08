---
updated: '2026-09-08'
lede: 'Staff leadership for ~10 engineers across ~20 repositories — the work is sequencing delivery under constraint, developing engineers, and making the hard call when program urgency outruns readiness.'
role: 'Staff Aerospace Software Engineer — technical leadership, team execution, and engineer development at Jacobs National Security.'
leadership:
  mode: 'Day-to-day execution, engineer development, and the call when urgency outruns readiness'
  team: 'Roughly 10 engineers across ~20 repositories, plus program stakeholders and external partner teams'
  unblocked: 'Onboarded and coached roughly six engineers; introduced clearer development plans for junior engineers'
  decision: 'Held a sprint commitment when a partner environment was not ready — sequenced prep work instead of starting implementation that would only produce rework.'
  note: 'Staff IC title — formal personnel decisions remain with management.'
problem:
  - 'Mission software spans many repositories and deployment environments — execution has to stay sequenced when dependencies and readiness shift under program pressure.'
  - 'Engineers need shared standards and coaching so quality, testing, and release expectations are not tribal knowledge.'
  - 'Program leadership sets priorities; engineering leadership has to turn those into deliverable work and flag risk early — including when the honest answer is “not this sprint.”'
decisions:
  - 'When urgency outruns readiness, refuse premature implementation; sequence interface contracts, fixtures, and local stubs so the team is ready the moment the environment is.'
  - 'Develop engineers through onboarding, code review, and ongoing feedback — with deliberate plans for juniors so growth is not an afterthought to the hottest ticket.'
  - 'Drive shared practices for review, testing, CI/CD, security checks, repository structure, and release readiness across the repos.'
outcome:
  - 'What I own: day-to-day technical delivery, engineer development, and cross-team coordination for integration and release readiness.'
  - 'What changed: clearer coaching and development plans; execution that can absorb dependency pressure without thrashing the team into rework.'
  - 'Program names, customers, and mission data stay unpublished.'
metrics:
  - value: '~10'
    label: 'Engineers on the team'
  - value: '~20'
    label: 'Repositories in scope'
platform:
  caption: 'Schematic · Program names, customers, and mission data are unpublished.'
  stages:
    - step: '01 · Plan'
      title: 'Sequence the work'
      body: 'Turn program needs into sprint priorities, dependency order, and clear ownership across repositories.'
      stack: 'Agile · Planning'
    - step: '02 · Build'
      title: 'Coach & standardize'
      body: 'Code review, testing expectations, CI/CD, and security checks applied consistently across the team.'
      stack: 'PR · CI/CD'
    - step: '03 · Ship'
      title: 'Integration & readiness'
      body: 'Coordinate across teams and partner environments so releases move when evidence says they are ready.'
      stack: 'Release'
---

I lead day-to-day engineering execution for roughly 10 engineers across approximately 20 repositories and multiple deployment environments. The job is planning and sequencing work, coaching engineers, establishing standards, and coordinating integration and release readiness — under constraints that do not show up in a public architecture diagram.

## A hard call

A program push landed mid-cycle: pull a cross-team integration into the current sprint. On paper the feature was clear. In practice the partner environment was not ready, the interface contract was still moving, and starting “real” implementation would have meant juniors writing against sand that would shift twice before merge.

The easy Staff move is to say yes — look responsive, absorb the thrash later. I held the sprint commitment. We sequenced prep instead: freeze the interface assumptions we could own, stand up fixtures and local stubs, and document the readiness gap for program stakeholders in language they could act on. Implementation started when the environment evidence said it could stick.

What changed was not a heroic late night. The team kept velocity on work that would survive contact with the partner system. Two junior engineers stayed on a coherent learning path instead of becoming the people who rewrote the same integration three times. Program leadership got an earlier, clearer tradeoff — slip the commit, or fund the readiness work — instead of a surprise red build at the end of the sprint.

That is the judgment I am practicing now, and the judgment I want formal people-management scope to amplify: protect the team’s attention, make risk visible early, and refuse work that only produces rework.

## Developing engineers

I have onboarded and coached roughly six engineers on the codebase, the development workflow, and expectations for testing, code quality, and release readiness. Through code review and ongoing feedback I explain the reasoning behind engineering decisions and help people apply those practices in their own work.

For junior engineers I have introduced more deliberate development plans so learning and progression have clearer direction — especially when program pressure would otherwise turn every week into only the hottest ticket.

## Leading team execution

I lead Scrum/Agile execution and help turn mission and program needs into sequenced engineering work: shaping sprint priorities, coordinating dependencies, removing blockers, and adjusting when integration or operational issues emerge.

Program and product leadership set overall priorities. I guide the engineering execution needed to deliver against them and surface tradeoffs when scope, dependencies, or readiness put delivery at risk — including the call above.

## Coordinating across teams

I coordinate with engineering, program stakeholders, and external partners on integration, environment readiness, releases, and technical dependencies. Translating between program requirements and implementation needs is most of the job when the critical path sits between teams.

## Building shared engineering standards

Across approximately 20 repositories I drive practices for code review, automated testing, CI/CD, security checks, repository structure, and release readiness. Coaching and documented expectations help engineers apply a consistent standard so quality is not whoever happened to review the PR.

Program-specific details are omitted.
