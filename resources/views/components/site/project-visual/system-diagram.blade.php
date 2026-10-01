@props(['project', 'compact' => false])

<div class="project-visual project-visual--diagram {{ $compact ? 'project-visual--compact' : '' }}" aria-hidden="true">
    <div class="project-visual__chrome">
        <div class="flex items-center gap-2">
            <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs uppercase tracking-wider font-mono font-medium" style="color: var(--text);">System Topology</span>
        </div>
        <span class="text-xs font-mono hidden sm:inline" style="color: var(--muted-2);">Unclassified Architecture</span>
    </div>
    <div class="project-visual__stage project-visual__stage--diagram">
        <svg viewBox="0 0 540 200" class="w-full h-full" fill="none" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="diag-grid" width="20" height="20" patternUnits="userSpaceOnUse">
                    <circle cx="2" cy="2" r="0.75" fill="var(--border)" />
                </pattern>
                <marker id="arrow" viewBox="0 0 6 6" refX="5" refY="3" markerWidth="4" markerHeight="4" orient="auto-start-reverse">
                    <path d="M 0 0 L 6 3 L 0 6 z" fill="var(--accent)" />
                </marker>
            </defs>
            <rect width="540" height="200" fill="url(#diag-grid)" opacity="0.6" />

            <!-- Left: Repositories -->
            <g class="diagram-nodes-source">
                <rect x="18" y="22" width="105" height="34" rx="3" fill="var(--panel)" stroke="var(--border-strong)" stroke-width="1.2" />
                <text x="28" y="43" font-family="var(--font-mono)" font-size="10.5" fill="var(--text)" font-weight="500">service-a.git</text>

                <rect x="18" y="68" width="105" height="34" rx="3" fill="var(--panel)" stroke="var(--border-strong)" stroke-width="1.2" />
                <text x="28" y="89" font-family="var(--font-mono)" font-size="10.5" fill="var(--text)" font-weight="500">service-b.git</text>

                <rect x="18" y="114" width="105" height="34" rx="3" fill="var(--panel)" stroke="var(--border-strong)" stroke-width="1.2" />
                <text x="28" y="135" font-family="var(--font-mono)" font-size="10.5" fill="var(--text)" font-weight="500">sim-core.git</text>

                <text x="24" y="172" font-family="var(--font-mono)" font-size="9.5" fill="var(--accent)" letter-spacing="0.05em">~20 REPOSITORIES</text>
            </g>

            <!-- Connectors: Repos to DevSecOps -->
            <path d="M 123 39 C 145 39, 145 85, 168 85" stroke="var(--border-strong)" stroke-width="1.2" />
            <path d="M 123 85 L 168 85" stroke="var(--border-strong)" stroke-width="1.2" />
            <path d="M 123 131 C 145 131, 145 85, 168 85" stroke="var(--border-strong)" stroke-width="1.2" />

            <!-- Center-Left: DevSecOps Gate -->
            <g class="diagram-gate">
                <rect x="170" y="32" width="128" height="106" rx="5" fill="var(--bg)" stroke="var(--accent)" stroke-width="1.5" />
                <rect x="170" y="32" width="128" height="24" rx="4" fill="var(--panel)" />
                <text x="182" y="48" font-family="var(--font-mono)" font-size="9.5" font-weight="600" fill="var(--accent)" letter-spacing="0.04em">DEVSECOPS GATE</text>
                
                <text x="182" y="74" font-family="var(--font-mono)" font-size="9" fill="var(--text)">✔ Policy-as-Code</text>
                <text x="182" y="93" font-family="var(--font-mono)" font-size="9" fill="var(--text)">✔ CI Baseline 90%+</text>
                <text x="182" y="112" font-family="var(--font-mono)" font-size="9" fill="var(--text)">✔ Hermetic Builds</text>
                <text x="182" y="127" font-family="var(--font-mono)" font-size="8" fill="var(--muted)">Automated verification</text>
            </g>

            <!-- Connector: Gate to Messaging -->
            <path d="M 298 85 L 328 85" stroke="var(--accent)" stroke-width="1.5" marker-end="url(#arrow)" />

            <!-- Center-Right: Portable Messaging Bus -->
            <g class="diagram-messaging">
                <rect x="332" y="44" width="98" height="82" rx="5" fill="var(--panel)" stroke="var(--border-strong)" stroke-width="1.2" />
                <text x="344" y="66" font-family="var(--font-mono)" font-size="9.5" font-weight="600" fill="var(--text)">MESSAGING</text>
                <text x="344" y="82" font-family="var(--font-mono)" font-size="8.5" fill="var(--muted)">Portable Bus</text>
                <line x1="344" y1="92" x2="416" y2="92" stroke="var(--border)" stroke-width="1" />
                <text x="344" y="107" font-family="var(--font-mono)" font-size="8.5" fill="var(--accent)">Pub/Sub Fabric</text>
            </g>

            <!-- Connectors: Messaging to Environments -->
            <path d="M 430 70 C 445 70, 445 42, 458 42" stroke="var(--border-strong)" stroke-width="1.2" marker-end="url(#arrow)" />
            <path d="M 430 85 L 458 85" stroke="var(--border-strong)" stroke-width="1.2" marker-end="url(#arrow)" />
            <path d="M 430 100 C 445 100, 445 128, 458 128" stroke="var(--border-strong)" stroke-width="1.2" marker-end="url(#arrow)" />

            <!-- Right: Environments -->
            <g class="diagram-targets">
                <rect x="462" y="27" width="64" height="28" rx="3" fill="var(--bg)" stroke="var(--border-strong)" stroke-width="1" />
                <text x="470" y="45" font-family="var(--font-mono)" font-size="9" fill="var(--text)">Dev Envs</text>

                <rect x="462" y="71" width="64" height="28" rx="3" fill="var(--bg)" stroke="var(--border-strong)" stroke-width="1" />
                <text x="470" y="89" font-family="var(--font-mono)" font-size="9" fill="var(--text)">Staging</text>

                <rect x="462" y="115" width="64" height="28" rx="3" fill="var(--bg)" stroke="var(--accent)" stroke-width="1.2" />
                <text x="468" y="133" font-family="var(--font-mono)" font-size="9" fill="var(--accent)" font-weight="600">Sim Target</text>
                
                <text x="462" y="162" font-family="var(--font-mono)" font-size="8" fill="var(--muted)">3 ENVIRONMENTS</text>
            </g>
        </svg>
    </div>
</div>
