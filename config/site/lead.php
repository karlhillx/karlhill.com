<?php

return [
    'eyebrow' => 'Forward this page',
    'title' => 'How I run delivery',
    'lede' => 'The public substitute for unpublished Jacobs architecture. Definition of Done, the PR rubric, how integration risk becomes visible, and how those standards spread — portable enough to attach to a req.',
    'updated' => 'September 6, 2026',
    'why' => 'Program names, customers, environment topology, and tools stay unpublished. What I can share is the operating system: the evidence that means “ready,” the review that teaches, and the coaching that keeps that bar from living in one person’s head. NASA case studies are the public proof of platforms. This page is how I run the current chapter.',
    'sections' => [
        [
            'id' => 'done',
            'title' => 'Definition of Done',
            'intro' => 'A change is not done when it compiles on a laptop. It is done when the evidence below exists without reconstructing it in a meeting.',
            'items' => [
                'The change has an owner, a reason, and a boundary — what it does not do is written down.',
                'Automated checks that the team already agreed on are green: tests, formatting, lockfiles, packaging, security scans that belong on this path.',
                'Failure modes are named. If this breaks in a constrained environment, we know how we would see it.',
                'Interfaces and delivery assumptions that this change depends on have been exercised, not deferred to “integration week.”',
                'Release notes a teammate could use exist. Versioning means something.',
            ],
        ],
        [
            'id' => 'reviews',
            'title' => 'Pull request rubric',
            'intro' => 'Reviews are mentoring tools, not rubber stamps and not gatekeeper theater. I ask the same questions so the bar does not depend on who is on the review.',
            'items' => [
                [
                    'title' => 'Resilience',
                    'body' => 'What happens when the happy path is not the path? Timeouts, retries, and partial failure are visible in the diff, not in tribal knowledge.',
                ],
                [
                    'title' => 'Evidence',
                    'body' => 'What would convince a skeptical teammate this is ready to move? Tests, fixtures, or a trace — not “works on my machine.”',
                ],
                [
                    'title' => 'Ownership',
                    'body' => 'Who gets paged, and is that obvious from the change? Hidden coupling is a review comment, not a surprise later.',
                ],
                [
                    'title' => 'Teachability',
                    'body' => 'Could a new engineer reconstruct the decision from the PR and the notes? If not, the knowledge is still concentrated.',
                ],
            ],
        ],
        [
            'id' => 'risk',
            'title' => 'Integration risk, made visible',
            'intro' => 'Late-stage integration drift is the expensive failure mode: assumptions that were invisible in one environment become costly at a release boundary. I treat that as a product problem.',
            'items' => [
                'Name the boundary early — which environment, which contract, which team has to say yes — so “ready” is not a meeting at the end.',
                'Validate interfaces continuously instead of saving integration for the last week of a sprint.',
                'Keep a shared picture of what is blocked, by whom, and what evidence would unblock it. Risk that only I can see is not managed.',
                'Prefer smaller promotions with evidence over large moves that have to be unwound.',
            ],
        ],
        [
            'id' => 'coaching',
            'title' => 'How the bar spreads',
            'intro' => 'Standards that live in one Staff engineer do not survive leave, load, or a new teammate. Coaching is how the operating system outlasts me.',
            'items' => [
                '1:1s hold career growth, feedback, and delivery risk in the same conversation — blockers show up early enough to act.',
                'PRs are where the rubric is taught. I write the comment I wish I had received, then I expect the next PR to use it.',
                'Tradeoffs are made visible: what we ship, what we defer, what we refuse — so the team can protect focus without politics.',
                'Success is predictability and ownership across the team, not how much I personally touch.',
            ],
        ],
    ],
    'not' => 'This is not a Jacobs system architecture, a tool list, or an operations manual. The NASA studies on this site are the platforms I can show. Attach this page when the question is how I run delivery under constraint.',
];
