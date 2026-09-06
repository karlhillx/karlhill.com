---
lede: 'Cloud-native mission simulation and telemetry — published as the shape of the work, not the programs themselves.'
role: 'Staff Aerospace Software Engineer — platform delivery, DevSecOps, and engineering standards across constrained multi-environment baselines.'
leadership:
  mode: 'Staff IC with delivery ownership — coaching, release discipline, and stakeholder translation'
  team: 'Cross-functional engineering, integration, and mission partners'
  unblocked: 'Made release readiness and integration risk visible early enough that the team could act without late-stage heroics.'
  decision: 'Treat constrained environments as a product problem: standards, pipelines, and traceability beat tribal knowledge.'
problem:
  - 'Mission software spans isolated and integrated environments — late baseline drift is expensive and hard to unwind.'
  - 'Security, traceability, and release evidence are not optional, and they cannot live in one person’s head.'
  - 'Program specifics cannot be published, so the public record has to describe the operating system, not the mission.'
decisions:
  - 'Lead delivery through CI/CD, quality gates, and multi-repo governance so “ready” is evidence, not a meeting.'
  - 'Coach the team on PR discipline, Definition of Done, and ownership so standards outlast any one engineer.'
  - 'Keep the public case study at the level of constraints and practices — no program names, customers, architectures, or screenshots.'
outcome:
  - 'A delivery system that can move across constrained environments without relying on heroics.'
  - 'Engineering standards that make integration risk visible before it becomes a surprise.'
  - 'A public description of current work that is accurate without being operationally specific.'
metrics:
  - value: Constrained
    label: 'Aerospace mission software'
  - value: Unpublished
    label: 'Program details'
---

At Jacobs I own cloud-native mission simulation and telemetry — platform delivery, DevSecOps, and release readiness across constrained environments.

This page is intentionally incomplete. I do not publish program names, customers, architectures, screenshots, or operational details. What I can say is the job: own delivery, raise the engineering bar through coaching and standards, and keep release readiness honest when integration risk is high.

The NASA case studies on this site are the public proof of how I build platforms. This one is the current chapter — same habits, tighter constraints.

## Constrained Delivery Context

In aerospace and national security environments, software does not live in a single uniform cloud cluster. It spans segmented networks, isolated testing baselines, and air-gapped operational environments.

The perennial failure mode of mission software is **late-stage baseline drift**:
- Software develops rapidly in low-side or connected environments with unfettered access to external package registries.
- Months later, deployment into isolated testing or mission enclaves fails because transitive dependencies, dynamic network assumptions, or unsigned binary artifacts cannot cross the boundary.
- Integration turns into a chaotic triage of hotfixes, manual overrides, and high-stress meetings.

My approach treats **delivery across boundaries as a first-class product problem**. If a service cannot be deterministically built, scanned, packaged, and verified in CI, it does not exist.

## Platform Architecture

Rather than relying on manual deployment runbooks, we structured the platform around immutable artifacts, continuous provenance, and automated gate verification:

<figure class="my-8 overflow-hidden rounded-lg border border-neutral-800 bg-neutral-900/70 p-4 sm:p-6 backdrop-blur-sm">
  <div class="overflow-x-auto">
    <svg viewBox="0 0 820 250" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full min-w-[700px] h-auto" role="img" aria-label="High-assurance multi-environment delivery pipeline">
      <!-- Connected Dev Environment -->
      <rect x="10" y="20" width="230" height="200" rx="8" fill="#141414" stroke="#2e2e2e" stroke-width="1.5" />
      <text x="125" y="48" fill="#e5e5e5" font-family="system-ui, sans-serif" font-size="13" font-weight="600" text-anchor="middle">CONNECTED / DEV BASELINE</text>
      <rect x="25" y="65" width="200" height="40" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="125" y="83" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Multi-Repo Source Control</text>
      <text x="125" y="96" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">Branch protection &amp; review gates</text>
      <rect x="25" y="115" width="200" height="40" rx="4" fill="#1f1f1f" stroke="#38bdf8" stroke-width="1" />
      <text x="125" y="133" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Deterministic CI / Build</text>
      <text x="125" y="146" fill="#38bdf8" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">Hermetic containers &amp; pinned deps</text>
      <rect x="25" y="165" width="200" height="38" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="125" y="188" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="10" text-anchor="middle">Unit &amp; Contract Tests</text>
      <!-- Arrow 1 to 2 -->
      <path d="M240 120 H275" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" />
      <polygon points="275,115 285,120 275,125" fill="#38bdf8" />
      <!-- Governance & Verification -->
      <rect x="285" y="20" width="240" height="200" rx="8" fill="#141414" stroke="#2e2e2e" stroke-width="1.5" />
      <text x="405" y="48" fill="#e5e5e5" font-family="system-ui, sans-serif" font-size="13" font-weight="600" text-anchor="middle">VERIFICATION &amp; EVIDENCE</text>
      <rect x="305" y="65" width="200" height="40" rx="4" fill="#1f1f1f" stroke="#a855f7" stroke-width="1" />
      <text x="405" y="83" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">DevSecOps Policy Gates</text>
      <text x="405" y="96" fill="#a855f7" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">Static analysis &amp; container CVE scans</text>
      <rect x="305" y="115" width="200" height="40" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="405" y="133" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Provenance &amp; SBOM</text>
      <text x="405" y="146" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">Cryptographic signing &amp; bill of materials</text>
      <rect x="305" y="165" width="200" height="38" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="405" y="188" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="10" text-anchor="middle">Automated Release Evidence</text>
      <!-- Arrow 2 to 3 -->
      <path d="M525 120 H560" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" />
      <polygon points="560,115 570,120 560,125" fill="#38bdf8" />
      <!-- Constrained Enclave -->
      <rect x="570" y="20" width="240" height="200" rx="8" fill="#141414" stroke="#2e2e2e" stroke-width="1.5" />
      <text x="690" y="48" fill="#e5e5e5" font-family="system-ui, sans-serif" font-size="13" font-weight="600" text-anchor="middle">CONSTRAINED / AIR-GAP ENCLAVE</text>
      <rect x="590" y="65" width="200" height="40" rx="4" fill="#1f1f1f" stroke="#22c55e" stroke-width="1" />
      <text x="690" y="83" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Air-Gapped Registry Drop</text>
      <text x="690" y="96" fill="#22c55e" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">Signature &amp; hash verification</text>
      <rect x="590" y="115" width="200" height="40" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="690" y="133" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Kubernetes Mission Mesh</text>
      <text x="690" y="146" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">Hermetic telemetry &amp; simulation</text>
      <rect x="590" y="165" width="200" height="38" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="690" y="188" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="10" text-anchor="middle">Zero-Drift Execution</text>
    </svg>
  </div>
  <figcaption class="mt-3 text-center font-mono text-caption text-neutral-400 uppercase tracking-widest">
    Figure 1: High-Assurance Boundary-Crossing Release Architecture
  </figcaption>
</figure>

### Core Delivery Pillars

1. **Hermetic Builds & Immutable Artifacts:** Dependencies are vendor-cached and pinned by cryptographic digest. Images are built once, signed, and promoted across environments without re-compilation or external fetches.
2. **Policy-as-Code & Automated Evidence:** Compliance and security requirements (vulnerability scanning, static analysis, secrets detection) are validated inline during CI. "Release readiness" is produced as an automated evidence manifest rather than negotiated in meetings.
3. **Multi-Repo Governance:** Enforcing standardized linting, testing harnesses, and CI templates across microservices ensures that engineers rotate across simulation and telemetry components without encountering tribal setup quirks.

## Engineering Governance

Technical depth alone does not scale high-assurance platforms. As a Staff engineer with delivery ownership, my primary impact is elevating the team's operational habits:

- **Code Reviews That Teach:** Pull requests are not rubber stamps or gatekeeper bottlenecks; they are mentoring tools. Clear rubrics ensure PRs address architectural resilience, error handling, and test coverage before merging.
- **Explicit Definition of Done:** Features are not complete when code compiles locally. A task is done when automated tests pass, telemetry is instrumented, security scans are clean, and deployment runbooks are updated.
- **De-risking Integration Early:** Instead of deferring multi-service integration testing to the end of a sprint, contract testing and automated environment stubs validate inter-service interfaces continuously.

When systems fail in constrained environments, they fail expensive. By building repeatable delivery platforms and coaching teams to own standards, we ensure mission software performs reliably when failure is not an option.
