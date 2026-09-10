---
updated: '2026-09-10'
lede: Find Data search, ordering, and near-real-time access for NASA LAADS DAAC. Delivery runs through GitLab CI/CD and Kubernetes.
role: Lead Software Engineer — web applications and delivery workflows.
leadership:
  mode: Technical leadership and web-system development
  team: LAADS engineering, science operations, and user-support colleagues
  unblocked: Search and ordering workflows, supported by a more repeatable release process
  decision: Modernize the user experience and delivery workflow while retaining the archive interfaces.
problem:
- Researchers need a clear path from product selection to finding and ordering files.
- Web modernization must account for the archive services and access workflows underneath.
decisions:
- Develop a guided search-and-order workflow organized around product, time, location, files, and review.
- Work across the LAADS portal and near-real-time access tools, not only one page.
- Use GitLab CI/CD, Docker, Helm, and Kubernetes for builds and deployments alongside existing Perl services.
outcome:
- An updated search-and-order experience for satellite-data users.
- A more consistent build and deployment workflow for the web systems.
metrics: []
platform:
  caption: Public Find Data path. Archive services stay in place underneath.
  stages:
  - step: 01 · Product
    title: Choose collections
    body: Search starts with the satellite products and standard collections.
    stack: Find Data
  - step: 02 · Filter
    title: Time and location
    body: Temporal and spatial filters narrow the matching granules.
    stack: Catalog
  - step: 03 · Files
    title: Select granules
    body: Matching files are listed for review before an order is placed.
    stack: Archive
  - step: 04 · Order
    title: Review and order
    body: The request is checked and submitted against existing access workflows.
    stack: Ordering
---

LAADS DAAC work covered the public portal, Find Data search and ordering, near-real-time access, and the software delivery process supporting them. Find Data is public: open it and walk the same path.

## A usable path to the data

Find Data organizes a search into five steps: products, time, location, files, and review and order. That experience connected the user interface to the underlying catalog, archive, and access workflows.

That distinction matters. A clearer interface still has to work with the services people already depend on. Modernization could not stop at the page design or ignore existing data-access paths.

The work also extended across the LAADS portal and its near-real-time tools. These were connected parts of the same data-access experience.

## Modernizing how the software shipped

The underlying environment included established Perl services. Alongside application development, builds and deployments moved into GitLab CI/CD, Docker, Helm, and Kubernetes.

The emphasis was repeatable delivery while retaining the interfaces to the archive: incremental modernization of the applications and delivery processes around an established system.
