---
updated: '2026-09-15'
lede: Find Data search, ordering, and near-real-time access for NASA LAADS DAAC. The wizard is public. Web-system delivery runs through GitLab CI/CD and Kubernetes alongside existing Perl services.
role: Lead Software Engineer — web applications and delivery workflows.
leadership:
  mode: Technical leadership and web-system development
  team: LAADS engineering, science operations, and user-support colleagues
  unblocked: Search and ordering workflows, supported by a more repeatable release process.
  decision: Modernize the user experience and delivery workflow while retaining the archive interfaces.
problem:
- Researchers need a clear path from choosing a product to finding and ordering files.
- Web work has to keep working with the catalog, archive, and access services already in use.
decisions:
- Build a guided search-and-order workflow organized around product, time, location, files, and review.
- Treat the LAADS portal and near-real-time access tools as one data-access experience, not a single page.
- Move builds and deployments to GitLab CI/CD, Docker, Helm, and Kubernetes without replacing the archive interfaces.
outcome:
- Find Data is live — product, time, location, files, review and order. That is the public artifact.
- Web-system delivery is more repeatable through GitLab CI/CD, Docker, Helm, and Kubernetes. Existing Perl services remained in place.
- Portal and near-real-time access work sat in the same delivery path as Find Data. This page does not publish query-time or user-count metrics.
metrics: []
---

LAADS DAAC work covered the public portal, Find Data search and ordering, near-real-time access, and the delivery process around those web systems. Find Data is public: open it and walk the same path.

The underlying archive interfaces stayed in place. Modernization was the applications and how they shipped, not a rewrite of the archive.

## Find Data

Find Data organizes a search into five steps: products, time, location, files, and review and order. That experience connects the user interface to the catalog, archive, and access workflows people already depend on.

A clearer interface still has to complete an order against those services. The work could not stop at the page design or ignore existing data-access paths.

The same delivery path included the LAADS portal and its near-real-time tools. They were connected parts of one access experience, not a standalone microsite.

## Delivery around existing services

The environment included established Perl services. Alongside application development, builds and deployments moved into GitLab CI/CD, Docker, Helm, and Kubernetes.

The emphasis was repeatable delivery while retaining the interfaces to the archive. Incremental modernization of the web systems — not a claim that every service behind LAADS was replaced.
