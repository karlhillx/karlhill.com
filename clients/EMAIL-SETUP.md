# Client email setup (Google Workspace)

One inbox, two addresses as aliases:

- **Primary mailbox:** `Contact@octavesoflove.com` (or your Workspace user)
- **Alias:** `Contact@keithhillmusic.com` → same inbox

Also useful later: `Booking@keithhillmusic.com` as another alias on the same user.

## 1. Google Workspace

1. Go to [Google Workspace](https://workspace.google.com/) → **Get Started**.
2. Enter your name and **primary domain** (recommend verifying `octavesoflove.com` first, or `keithhillmusic.com` if that is already the Workspace primary).
3. Verify the domain in GoDaddy:
   - My Products → DNS → Manage DNS
   - Add the Google **TXT** verification record
4. Create the user (e.g. `contact@…` or your personal Workspace user).
5. Sign in at [mail.google.com](https://mail.google.com) with that address.

## 2. Add the second domain + alias

If both domains should receive mail into one inbox:

1. Admin console → **Domains** → add / verify the second domain (`keithhillmusic.com` or `octavesoflove.com`).
2. Admin console → **Directory** → **Users** → select the user → **User information** → **Alternate email addresses (email aliases)**.
3. Add:
   - `Contact@octavesoflove.com`
   - `Contact@keithhillmusic.com`
4. Ensure MX records for **both** domains point to Google Workspace (Google’s MX values from the Admin console).

## 3. Domain forward for the website

In GoDaddy (or your DNS host) for **octavesoflove.com**:

- Prefer a **301 / domain forward** (or URL redirect) to:
  - `https://keithhillmusic.com/octaves-of-love/`
- Keep that path as the canonical Octaves of Love landing page.

Staging preview (this repo):

- Page: `/clients/keithhillmusic.com/octaves-of-love/`
- Domain stub: `/clients/octavesoflove.com/` → forwards to the page above

## 4. Bare-bones launch scope

Shipped now: about, sound-bath intro, ceremony details, contact form.

Add later as demand develops: calendar/booking, testimonials, mailing list, downloadable booking materials, expanded gallery.
