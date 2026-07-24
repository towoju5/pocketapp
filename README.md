# pocketapp

A Laravel binary-options trading platform: user wallets, deposits/payouts,
express trades, tournaments, social trading — with live asset price charts
fed by a self-hosted price collector (no third-party market data API; prices
are scraped directly from iqcent via a real headless Chrome browser, because
iqcent sits behind Cloudflare and blocks anything that isn't one).

## Architecture

```
iqcent.com (WebSocket price feed, behind Cloudflare)
        │
        ▼
Headless Chrome × N  ──  app/Http/Controllers/TickerController.php
 (Panther, one per     ──  driven by: php artisan ticks:collect
  batch of ~10 assets)     (Supervisor: pocketapp-ticker-collector)
        │
        ▼
PriceFeedService (cache: current price, tick history, online/offline)
        │
        ▼
AssetPriceBatchUpdated  ──  broadcast over Reverb (Supervisor: pocketapp-reverb)
        │
        ▼
Browser (Echo / resources/js/trading/chart.js) — every connected user's chart
```

Trade settlement, payouts, and loss-cashback all happen inside queued jobs
(Supervisor: `pocketapp-queue-worker`) — nothing settles without a worker
running. The scheduler (`routes/console.php`) matures investment plans and
expires stale P2P trades every run, driven by cron calling
`php artisan schedule:run` every minute.

Data lives in SQLite (`database/database.sqlite`, WAL mode — see
`config/database.php`), not a separate DB server, to keep the deploy surface
small.

## Prerequisites

A fresh Ubuntu or Debian VPS (root/sudo access) is all you need — the setup
script installs everything else (nginx, PHP, Composer, Node, Chrome,
Supervisor). You'll want:

- A domain name pointed (A record) at the server's IP, if you want HTTPS —
  not required to get the app running over plain HTTP first.
- The repo's git URL (or the code already `git clone`d onto the box).

## Quick start (fresh VPS → running site, one command)

```bash
git clone <your-repo-url> /var/www/pocketapp
cd /var/www/pocketapp
DOMAIN=example.com sudo -E ./deploy/setup.sh
```

Or let the script do the clone too:

```bash
GIT_REPO_URL=<your-repo-url> DOMAIN=example.com sudo -E ./deploy/setup.sh
```

Without `DOMAIN`, everything runs except the nginx vhost (which needs a real
hostname) — set it later and re-run; the script is safe to re-run any time,
including for deploying updates (see below).

Once DNS for your domain resolves to the server, enable HTTPS:

```bash
sudo certbot --nginx -d example.com
```

Then open the site and register your first account, and make it an admin:

```bash
cd /var/www/pocketapp
php artisan tinker --execute="App\Models\User::where('email','you@example.com')->update(['is_admin' => true]);"
```

### What the script does

`deploy/setup.sh` is idempotent — every step checks current state before
acting, so re-running it (e.g. after `git pull`) just brings things up to
date rather than redoing everything blindly.

1. **System packages** — nginx, PHP-FPM + required extensions, Composer,
   Node.js, Supervisor, sqlite3, Google Chrome (the browser
   `TickerController` drives — see Architecture above), certbot.
2. **Application code** — clones the repo if it isn't already checked out at
   the target path.
3. **`.env`** — copies `.env.example` if missing, generates `APP_KEY` and
   Reverb credentials (`REVERB_APP_ID/KEY/SECRET`), sets
   `BROADCAST_CONNECTION=reverb` (broadcasts silently go nowhere without
   this), and derives `REVERB_HOST`/`VITE_REVERB_*` from your domain — a
   browser connects to your domain, never to `localhost`.
4. **Dependencies** — `composer install`, `npm install`, `npm run build`
   (`VITE_*` values are baked into the JS at build time, so this must run
   after any `.env` change that touches them).
5. **Database** — creates the SQLite file, runs migrations, and seeds the
   ~150-asset catalog (`AssetSeeder`) if it's empty — nothing for the
   collector to subscribe to, and every chart shows "offline", without this.
6. **Permissions** — `storage:link`, ownership of `storage/`,
   `bootstrap/cache/`, and `database/` (SQLite's WAL mode writes sidecar
   files into that directory) to the app user, config/route/view caching.
7. **Chrome/chromedriver check** — prints both versions so a mismatch is
   obvious immediately rather than surfacing later as a silently-dead
   collector process.
8. **Supervisor** — installs `pocketapp-reverb`, `pocketapp-queue-worker`,
   and `pocketapp-ticker-collector` into `/etc/supervisor/conf.d/`, sizing
   the ticker collector's process count to the actual asset count
   (`ceil(assets / 10)`), and (re)starts all three.
9. **Cron** — installs the `schedule:run` entry for the app user.
10. **nginx** — generates a vhost from `deploy/nginx/pocketapp.conf.example`
    (PHP-FPM + the Reverb WebSocket proxy in one server block) if `DOMAIN`
    is set.

### Deploying updates

```bash
cd /var/www/pocketapp
git pull
sudo -E ./deploy/setup.sh
```

Re-running picks up new migrations, rebuilds frontend assets, and restarts
the queue workers and ticker collector so they run the new code — a `git
pull` alone does **not** do any of that (running PHP processes keep the old
code loaded in memory until restarted).

## Already deployed on a shared server? Just fixing the chart

If the app is already checked out and working (DB migrated and linked,
trades/payouts/queue workers all fine) on a box that also hosts other,
unrelated sites — don't use `deploy/setup.sh` above. It installs system
packages, generates a full nginx vhost, and manages Supervisor broadly; on a
shared box that's more than you want and risks touching things that belong
to another site.

Use `deploy/setup-streaming.sh` instead — it only sets up the two pieces the
chart actually needs (Reverb + the ticker collector) and is deliberately
scoped to never touch anything else on the box:

```bash
cd /var/www/pocketapp   # wherever this app actually lives
sudo ./deploy/setup-streaming.sh
```

What it does, and — just as importantly — what it deliberately doesn't:

- Fixes `.env` (`BROADCAST_CONNECTION=reverb`, generates Reverb credentials,
  derives `REVERB_HOST` from `APP_URL`) and rebuilds frontend assets so the
  change reaches the browser.
- Installs Google Chrome if it isn't already present (the browser
  `TickerController` drives — the matching `drivers/chromedriver` binary is
  already committed to the repo). A plain system package install; doesn't
  touch any other site.
- Seeds the asset catalog if it's empty, and installs/restarts **only**
  `pocketapp-reverb` and `pocketapp-ticker-collector` in Supervisor —
  never a blanket `supervisorctl restart`, which would restart every other
  site's Supervisor-managed processes on the same box too.
- **Never writes to nginx config.** It looks for this domain's existing
  vhost under `/etc/nginx/sites-enabled/` and checks whether the Reverb
  `location /app/` proxy block is already there. If it's missing, it tells
  you exactly which file to add it to (the two blocks are in
  `deploy/nginx/reverb-proxy.conf.example`) — editing someone else's vhost
  automatically on a box with multiple domains is exactly the kind of thing
  worth doing by hand, once, with your eyes on it.

After it finishes, tail `storage/logs/ticker-collector.log` and
`storage/logs/reverb.log`, then reload the site and check the browser
devtools console for `[echo]` lines (see `resources/js/echo.js`).

## Manual setup (if you'd rather not run the script)

Everything below is exactly what `deploy/setup.sh` automates — useful if
you're on a non-apt system, want a different web server, or just want to
understand each piece before trusting a script with root.

<details>
<summary>Expand for the manual, step-by-step version</summary>

1. **Install system packages**: nginx; PHP 8.2+ with `sqlite3`, `curl`,
   `mbstring`, `xml`, `bcmath`, `gd`, `zip`, `intl`; Composer; Node.js 20+;
   Supervisor; `sqlite3` CLI; Google Chrome (or Chromium — a real browser
   binary, not just the driver).

2. **Get the code and install dependencies**:
   ```bash
   git clone <your-repo-url> /var/www/pocketapp && cd /var/www/pocketapp
   composer install --no-interaction --prefer-dist --optimize-autoloader
   npm install && npm run build
   ```

3. **Configure `.env`**:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Then set, at minimum:
   ```
   APP_ENV=production
   APP_DEBUG=false
   APP_URL=https://example.com
   BROADCAST_CONNECTION=reverb
   REVERB_APP_ID=<random>
   REVERB_APP_KEY=<random>
   REVERB_APP_SECRET=<random>
   REVERB_HOST=example.com
   REVERB_SCHEME=https
   REVERB_PORT=443
   VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
   VITE_REVERB_HOST="${REVERB_HOST}"
   VITE_REVERB_SCHEME="${REVERB_SCHEME}"
   VITE_REVERB_PORT="${REVERB_PORT}"
   ```
   Rebuild frontend assets after any `VITE_*` change: `npm run build`.

4. **Database**:
   ```bash
   touch database/database.sqlite
   php artisan migrate --force
   php artisan db:seed --force   # only needed the first time (assets table)
   ```

5. **Permissions**:
   ```bash
   php artisan storage:link
   chown -R www-data:www-data storage bootstrap/cache database
   chmod +x drivers/chromedriver
   ```

6. **Supervisor** — copy each template from `deploy/supervisor/` into
   `/etc/supervisor/conf.d/`, replacing `/var/www/pocketapp` with your real
   path. For `pocketapp-ticker-collector.conf`, set `numprocs` to
   `ceil(number of rows in the assets table / 10)`. Then:
   ```bash
   supervisorctl reread
   supervisorctl update
   supervisorctl start pocketapp-reverb:* pocketapp-queue-worker:* pocketapp-ticker-collector:*
   ```

7. **Cron** (as the app user):
   ```
   * * * * * cd /var/www/pocketapp && php artisan schedule:run >> /dev/null 2>&1
   ```

8. **nginx** — use `deploy/nginx/pocketapp.conf.example` as your vhost
   (PHP-FPM passthrough + the `/app/` and `/apps/` Reverb WebSocket proxy
   locations), substituting your domain, app path, and PHP-FPM socket path.
   Then `sudo certbot --nginx -d example.com` for HTTPS.

</details>

## Processes this app needs running at all times

| Program (Supervisor) | What it does | Config |
|---|---|---|
| `pocketapp-reverb` | WebSocket server — delivers broadcasts to browsers | `deploy/supervisor/pocketapp-reverb.conf` |
| `pocketapp-queue-worker` | Processes queued jobs — trade settlement, payouts, cashback | `deploy/supervisor/pocketapp-queue-worker.conf` |
| `pocketapp-ticker-collector` | Headless-Chrome pool streaming live prices from iqcent | `deploy/supervisor/pocketapp-ticker-collector.conf` |
| cron → `schedule:run` | Matures plans, expires stale P2P trades | installed by `deploy/setup.sh` |

Check status: `sudo supervisorctl status`. Logs are in `storage/logs/`
(`reverb.log`, `queue-worker.log`, `ticker-collector.log`).

## Troubleshooting

- **Every chart shows "offline" / no price movement**: check
  `storage/logs/ticker-collector.log`. Most common cause is a
  Chrome/chromedriver version mismatch — compare `google-chrome --version`
  against `./drivers/chromedriver --version`; if they've drifted apart,
  regenerate: `composer install && vendor/bin/bdi detect drivers`, then
  `sudo supervisorctl restart pocketapp-ticker-collector:*`.
- **Chart doesn't update live but `assets/history` shows data**: the
  collector is working but Reverb isn't reaching the browser — check
  `sudo supervisorctl status pocketapp-reverb`, and confirm nginx's `/app/`
  and `/apps/` proxy blocks are in place and `REVERB_HOST`/`VITE_REVERB_HOST`
  match your real domain (then `npm run build` again if you changed them).
- **Trades never settle / balances don't update**: `pocketapp-queue-worker`
  isn't running, or is still running old code after a deploy — restart it
  (`php artisan queue:restart` triggers a graceful restart Supervisor picks
  back up automatically).
- **`.env` changes seem to have no effect**: if `php artisan config:cache`
  was ever run, `env()` calls outside `config/*.php` keep returning the
  cached values — `php artisan config:clear` after any `.env` edit.

## Security

- Never commit `.env` — it holds `APP_KEY`, `REVERB_APP_SECRET`, and other
  live credentials.
- This app moves real trading balances; treat DB backups, `.env`, and server
  access accordingly.
