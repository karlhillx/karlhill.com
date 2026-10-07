# Deployment and monitoring

Pushes to `main` or `master` run only PHP 8.5 and shell syntax checks before deployment, with a three-minute timeout and no dependency installs, asset build, or browser setup. The full CI suite (tests, build, Pint, bundle budget, a11y) runs only when CI is manually dispatched from Actions; it does not trigger deployment. Pull requests do not run CI automatically.

When you change the HTML shell, offline fallback, or the precache list in
`public/sw.js`, bump the `CACHE` version string (e.g. `karlhill-offline-v4` →
`v5`) so clients drop stale caches on activate.

**Use the GitHub Actions Deploy workflow.** It streams a tarball of the repo over SSH straight into the Docker app container (`docker exec ... tar xzf - -C /var/www/html`) — it never touches the host's git checkout, so it can't be blocked by host-vs-container file ownership. Add these **environment secrets** under Settings → Environments → production:

| Secret | Value |
|--------|-------|
| `DEPLOY_HOST` | `karlhill.com` |
| `DEPLOY_USER` | `karl` |
| `DEPLOY_SSH_KEY` | private SSH key with access to the server |
| `DEPLOY_CONTAINER` | `karl-karlhill-1` (optional) |

With those set, successful push-triggered syntax checks on `main` or `master` deploy automatically. Deployment checks out the exact SHA checked by that run, not the latest branch tip. You can also dispatch Deploy manually from the Actions tab, bypassing CI. Asset compilation and dependency installation still happen in the existing remote deployment script.

> **Don't use `scripts/deploy.sh` unless the Actions workflow itself is down.** It runs `git pull` on the *host*, which requires the host user to own every tracked file. The app container writes some paths as its own runtime user, so a host-side `git pull` can start failing with `Permission denied` on unlink/create — and recovering requires root on the box, which you may not have. This has already caused a real production incident (a stale deploy left `/lead` 404ing — that path now redirects to the Jacobs case study) that had to be fixed by reaching into the container as root. If the Actions workflow is genuinely unavailable, fix ownership from *inside* the container (`docker exec -u root ... chown`) before falling back to this script — don't `sudo chown` the host tree.

## Monitoring

- **Errors** — set `LOG_STACK=daily,slack` and `LOG_SLACK_WEBHOOK_URL` in production. The `slack` channel has its own `LOG_SLACK_LEVEL` (default `error`) so the file log can stay verbose. A Discord webhook works when suffixed with `/slack`.
- **Browser reports** — with `REPORTING_ENABLED=true`, CSP/NEL/integrity reports posted to `/report` are retained in `storage/app/reports/latest.json` and mirrored to the log at `REPORTING_LOG_LEVEL` (default `warning`; `none` to silence), so they flow to the same sink as exceptions. `INTEGRITY_POLICY=auto` promotes to an enforcing `Integrity-Policy` header once Vite SRI hashes exist and no integrity violations remain in that window.

SSH shortcut:

```bash
make ssh   # uses SSH_USER and PRODUCTION from .env
```
