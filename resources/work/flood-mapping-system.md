---
updated: '2026-09-06'
lede: 'Near real-time flood inundation mapping from satellite data — built so disaster responders get trustworthy products in hours, without overnight engineering heroics.'
role: 'Architect & lead developer — designed and automated the end-to-end geospatial pipeline on AWS.'
leadership:
  mode: 'Technical lead for a mission-critical geospatial pipeline'
  team: '~4 engineers and science partners, plus emergency-management users from sensor acquisition through global dissemination'
  unblocked: 'Removed manual handoffs that forced late-night heroics during active disaster events.'
  decision: 'Prioritized automation and fault tolerance over ad-hoc speed — latency dropped because the team system was reliable under urgency.'
problem:
  - 'Manual processing steps sat on the critical path — flood products lagged during active global disaster events when hours mattered.'
  - 'Sensor acquisition → product generation → dissemination crossed teams and environments with fragile handoffs.'
  - 'Emergency-management users needed repeatable, trustworthy maps — not one-off runs that only the on-call engineer could reproduce.'
decisions:
  - 'Automate end-to-end from raw sensor ingestion through geospatial product generation — remove humans from the latency path under urgency.'
  - 'Containerize processing stages so the same pipeline is deployable and recoverable across environments, not a snowflake workstation.'
  - 'Integrate outputs with emergency-management and research distribution networks so “done” means disseminated, not merely generated.'
outcome:
  - 'Near real-time flood inundation maps during active disaster events worldwide — latency measured in hours, not overnight queues.'
  - 'Fewer manual handoffs under urgency — consistency came from the pipeline, not who was awake.'
  - 'Supported peer-reviewed research on global water and flood mapping (GeoHorizons).'
metrics:
  - value: 'Hours, not overnight'
    label: 'Product latency'
  - value: 'End-to-end, sensor ingest to dissemination'
    label: 'Automated pipeline'
platform:
  caption: 'Commit → gates → same pipeline in every environment'
  stages:
    - step: '01 · Source'
      title: 'Containerized stages'
      body: 'Each imagery transformation is a containerized task runner, not a snowflake workstation.'
      stack: 'Git · Docker'
    - step: '02 · Verify'
      title: 'Flood regression gates'
      body: 'Pipeline changes must pass automated tests against known flood extents before they ship.'
      stack: 'CI'
    - step: '03 · Deploy'
      title: 'Same pipeline, every env'
      body: 'The processing chain is deployable and recoverable across environments, not a local hero path.'
      stack: 'AWS · Containers'
    - step: '04 · Operate'
      title: 'Ingest to dissemination'
      body: 'Sensor packets become OGC products for responders without overnight engineering heroics.'
      stack: 'Event-driven'
---

## Operational Context

When a cyclone makes landfall or an inland reservoir breaches, emergency management organizations — including FEMA, the Red Cross, UN-SPIDER, and regional civil protection agencies — require verified surface water extents immediately. Every four-hour operational briefing window without actionable flood extents is a window where evacuation routing and relief logistics operate blind.

Historically, satellite-derived flood mapping was hindered by manual interventions:
1. Operators waited for polar-orbiting downlinks to land in file directories.
2. Scientists ran desktop GIS scripts on dedicated workstations with bespoke projection libraries.
3. Quality checks required visual cloud-mask inspection before an engineer manually uploaded GeoTIFFs to distribution servers.

During major activations, this workflow required around-the-clock staffing and sleepless heroics. If a processing script threw an unhandled exception at 2:00 AM on tile 47 of 120, the entire batch stalled until morning.

My mandate as architect and lead developer was clear: **eliminate human intervention from the critical latency path**. The system had to ingest raw sensor packets, calibrate imagery, detect surface water at >90% statistical accuracy, and syndicate OGC-compliant GIS products to responder endpoints automatically within hours of satellite downlink.

## System Architecture

The platform was architected on AWS as an event-driven, decoupled processing pipeline. Rather than maintaining heavy, long-running monolithic servers, each stage of imagery transformation was isolated into containerized task runners managed by deterministic work queues.

<figure class="my-8 overflow-hidden rounded-lg border border-neutral-800 bg-neutral-900/70 p-4 sm:p-6 backdrop-blur-sm">
  <div class="overflow-x-auto">
    <svg viewBox="0 0 820 260" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full min-w-[700px] h-auto" role="img" aria-label="End-to-end automated satellite flood processing pipeline">
      <!-- Ingestion Column -->
      <rect x="10" y="20" width="140" height="210" rx="8" fill="#141414" stroke="#2e2e2e" stroke-width="1.5" />
      <text x="80" y="48" fill="#e5e5e5" font-family="system-ui, sans-serif" font-size="13" font-weight="600" text-anchor="middle">01 · INGESTION</text>
      <rect x="25" y="65" width="110" height="42" rx="4" fill="#1f1f1f" stroke="#38bdf8" stroke-width="1" />
      <text x="80" y="83" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Sensor Stream</text>
      <text x="80" y="97" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">MODIS / VIIRS / Planet</text>
      <rect x="25" y="120" width="110" height="42" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="80" y="138" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Event Trigger</text>
      <text x="80" y="152" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">S3 Put → SNS Notification</text>
      <rect x="25" y="175" width="110" height="38" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="80" y="198" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="10" text-anchor="middle">Backpressure Queue</text>
      <!-- Arrow 1 to 2 -->
      <path d="M150 125 H175" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" />
      <polygon points="175,120 185,125 175,130" fill="#38bdf8" />
      <!-- Processing Column -->
      <rect x="185" y="20" width="170" height="210" rx="8" fill="#141414" stroke="#2e2e2e" stroke-width="1.5" />
      <text x="270" y="48" fill="#e5e5e5" font-family="system-ui, sans-serif" font-size="13" font-weight="600" text-anchor="middle">02 · CALIBRATION</text>
      <rect x="200" y="65" width="140" height="42" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="270" y="83" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Radiometric Correction</text>
      <text x="270" y="97" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">Sun angle &amp; atmospheric</text>
      <rect x="200" y="120" width="140" height="42" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="270" y="138" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Terrain Orthorectify</text>
      <text x="270" y="152" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">SRTM / DEM alignment</text>
      <rect x="200" y="175" width="140" height="38" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="270" y="198" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="10" text-anchor="middle">Cloud &amp; Shadow Mask</text>
      <!-- Arrow 2 to 3 -->
      <path d="M355 125 H380" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" />
      <polygon points="380,120 390,125 380,130" fill="#38bdf8" />
      <!-- Analytics Column -->
      <rect x="390" y="20" width="170" height="210" rx="8" fill="#141414" stroke="#2e2e2e" stroke-width="1.5" />
      <text x="475" y="48" fill="#e5e5e5" font-family="system-ui, sans-serif" font-size="13" font-weight="600" text-anchor="middle">03 · DETECTION</text>
      <rect x="405" y="65" width="140" height="42" rx="4" fill="#1f1f1f" stroke="#22c55e" stroke-width="1" />
      <text x="475" y="83" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Water Detection Core</text>
      <text x="475" y="97" fill="#22c55e" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">&gt;90% Validation Accuracy</text>
      <rect x="405" y="120" width="140" height="42" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="475" y="138" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Hydro Baseline Diff</text>
      <text x="475" y="152" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">Normal water vs flood extent</text>
      <rect x="405" y="175" width="140" height="38" rx="4" fill="#1f1f1f" stroke="#404040" stroke-width="1" />
      <text x="475" y="198" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="10" text-anchor="middle">Polygon Vectorization</text>
      <!-- Arrow 3 to 4 -->
      <path d="M560 125 H585" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" />
      <polygon points="585,120 595,125 585,130" fill="#38bdf8" />
      <!-- Dissemination Column -->
      <rect x="595" y="20" width="215" height="210" rx="8" fill="#141414" stroke="#2e2e2e" stroke-width="1.5" />
      <text x="702" y="48" fill="#e5e5e5" font-family="system-ui, sans-serif" font-size="13" font-weight="600" text-anchor="middle">04 · DISSEMINATION</text>
      <rect x="610" y="65" width="185" height="42" rx="4" fill="#1f1f1f" stroke="#a855f7" stroke-width="1" />
      <text x="702" y="83" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Cloud-Optimized GeoTIFF</text>
      <text x="702" y="97" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">S3 Tiering &amp; Range Reads</text>
      <rect x="610" y="120" width="185" height="42" rx="4" fill="#1f1f1f" stroke="#a855f7" stroke-width="1" />
      <text x="702" y="138" fill="#ffffff" font-family="monospace" font-size="11" text-anchor="middle">Web Map / Tile Server</text>
      <text x="702" y="152" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="9" text-anchor="middle">OGC WMS / WMTS API</text>
      <rect x="610" y="175" width="185" height="38" rx="4" fill="#1f1f1f" stroke="#a855f7" stroke-width="1" />
      <text x="702" y="198" fill="#a3a3a3" font-family="system-ui, sans-serif" font-size="10" text-anchor="middle">Responder Feeds (FEMA/UN)</text>
    </svg>
  </div>
  <figcaption class="mt-3 text-center font-mono text-caption text-neutral-400 uppercase tracking-widest">
    Figure 1: Automated Satellite Ingestion to Multi-Agency Dissemination Architecture
  </figcaption>
</figure>

### Pipeline Stages

1. **Autonomous Ingestion & Event Demuxing:** As downlinked raw files arrive in staging S3 buckets from NASA ground stations and commercial providers, Amazon SNS broadcasts granular tile events to SQS queues. This decoupled message broker absorbs burst downlinks without dropping work or overwhelming downstream processors.
2. **Containerized Geospatial Transformation:** Stateless Python worker containers running GDAL, Rasterio, and NumPy scale on demand. Workers pull tasks, apply radiometric correction, orthorectify sensor angles against digital elevation models (SRTM), and generate calibrated multi-spectral reflectance arrays.
3. **Dynamic Water Indexing & Baseline Differencing:** The algorithms isolate surface water signatures using spectral band ratios (such as NDWI and MNDWI) paired with automated cloud and terrain-shadow masking. The detected water masks are then compared against permanent water baselines (Global Surface Water / HydroLAKES) to distinguish normal river channels from active flood inundation.
4. **Vectorization & Cloud-Optimized Product Delivery:** The resulting raster inundation masks are translated into both Cloud-Optimized GeoTIFFs (COG) for remote GIS range reads and GeoJSON/Shapefile vector polygons for tactical emergency management platforms.

## Engineering Trade-offs

Designing a platform that runs without human intervention during natural disasters required choosing boring, resilient architectural primitives over fragile complexity:

### 1. Isolated Task Containers vs. Monolithic Workflow Engine
- **Trade-off:** We avoided large, centralized orchestration engines that maintain complex state machines. Instead, each granule transformation is a single idempotent container execution governed by SQS visibility timeouts.
- **Why it matters:** If an uncalibrated satellite granule contains corrupted metadata or atypical optical artifacts, only that specific granule fails and routes to a dead-letter queue (DLQ) with attached telemetry. The remainder of the orbital pass continues processing uninterrupted.

### 2. Cloud-Optimized GeoTIFFs (COGs) vs. Dynamic Tile Slicing
- **Trade-off:** Generating pre-rendered map tile pyramids for every zoom level at scale creates astronomical file counts and storage churn.
- **Why it matters:** Standardizing on Cloud-Optimized GeoTIFFs with internal overviews allows client map viewers and responder GIS systems to fetch only the byte ranges needed for their current viewport via HTTP range requests. Storage costs dropped dramatically, and product availability was instantaneous upon file write.

### 3. Graceful Partial Delivery vs. All-or-Nothing Orbit Mosaics
- **Trade-off:** Downlinks occasionally experience optical dropout, sensor scanline gaps, or dense regional cloud cover.
- **Why it matters:** Rather than blocking an entire regional release waiting for 100% cloud-free mosaic coverage, the pipeline generates partial inundation tiles immediately, tagging each tile with a machine-readable data-quality confidence index. Responders receive an 80% clear view in hour two rather than waiting eight hours for perfection.

## Operational Discipline

Technical architecture is only half the equation; mission-critical software succeeds or fails based on operational discipline:

- **Automated Validation Gates:** Water detection algorithms were continuously benchmarked against historical ground-truth flood datasets. Any pipeline modification required automated regression testing against known flood extents to verify that water classification accuracy remained above 90%.
- **Peer-Reviewed Scientific Provenance:** The system's methodology, validation rigor, and operational throughput were published in *GeoHorizons* (The Geological Society / AGU, May 2026: *A web-based high-resolution global water and flood mapping platform*), demonstrating that operational software engineering can meet the highest standards of scientific peer review.
- **Culture of Sustainable Delivery:** By replacing manual, hero-dependent releases with a resilient, automated CI/CD and telemetry stack, the engineering and science teams transitioned from exhausting all-night emergency shifts to calm, proactive operational oversight.
