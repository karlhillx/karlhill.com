# Heartbeat

On a configured publish day, or when asked to check the desk:

1. Read `clients/the-dry-standard/AGENTS.md`.
2. Run `php artisan dry-standard:status`.
3. If the publish slot is closed, stop.
4. If a review is `validated` or `scheduled`, run `php artisan dry-standard:publish {slug}`.
5. If nothing is ready, pick the highest-priority `queued` product and start research. Do not publish a thin draft to hit three-a-week.
6. After a successful publish, confirm `feed.xml` and the category index updated.
