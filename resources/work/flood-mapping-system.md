---
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
  - value: Hours
    label: 'Not overnight latency'
  - value: E2E
    label: 'Automated pipeline'
---

# Flood Mapping System

Case study narrative for [Flood Mapping System](/work/flood-mapping-system). Structured fields live in the YAML front matter; edit those to update the page.
