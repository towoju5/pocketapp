# pocketapp

A Laravel binary-options trading platform: user wallets, deposits/payouts,
express trades, tournaments, social trading — with live asset price charts
fed by a direct WebSocket connection to Brokeret's price feed (no third-party
market data API, no headless browser — Brokeret isn't behind Cloudflare, so
the backend connects to it as a plain WS client).

## Architecture

```
feed.brokeret.com (WebSocket price feed)
        │
        ▼
StreamBrokeretTicks  ──  app/Console/Commands/StreamBrokeretTicks.php
                          driven by: php artisan ticks:stream-brokeret
                          (Supervisor: pocketapp-brokeret-stream)
        │
        ▼
PriceFeedService (Redis: current price, tick history, online/offline)
        │
        ▼
BridgeRedisTicks (php artisan ticks:bridge-redis) tails Redis and rebroadcasts
        │
        ▼
AssetPriceBatchUpdated  ──  broadcast over Ably/Reverb (Supervisor: pocketapp-reverb, if self-hosting Reverb)
        │
        ▼
Browser (Echo / resources/js/trading/chart.js) — every connected user's chart
```

base_url/ui runs a second, fully independent Brokeret connection
(`StreamBrokeretFeed` / `ticks:stream-brokeret-ui`) that streams every symbol
Brokeret offers straight to that dashboard — see `SETUP_GUIDE.md` §6 for the
full process list.

Trade settlement, payouts, and loss-cashback all happen inside queued jobs
(Supervisor: `pocketapp-queue-worker`) — nothing settles without a worker
running. The scheduler (`routes/console.php`) matures investment plans and
expires stale P2P trades every run, driven by cron calling
`php artisan schedule:run` every minute.

Data lives in SQLite (`database/database.sqlite`, WAL mode — see
`config/database.php`), not a separate DB server, to keep the deploy surface
small.

## Prerequisites

A fresh Ubuntu or Debian VPS (root/sudo access) is all you need — the
installer sets up everything else (nginx, PHP, MySQL, Redis, Composer, Node,
Supervisor). You'll want:

- A domain name pointed (A record) at the server's IP, if you want HTTPS —
  not required to get the app running over plain HTTP first.
- The repo's git URL (or the code already `git clone`d onto the box).
- A Brokeret WebSocket API key (`BROKERET_API_KEY`), and either an Ably key
  or nothing else (self-hosted Reverb is the fallback broadcaster) — see
  `SETUP_GUIDE.md` §2.4 for details.

## Quick start (fresh VPS → running site, one command)

```bash
git clone <your-repo-url> /var/www/pocketapp
cd /var/www/pocketapp
DOMAIN=example.com sudo -E ./install.sh
```

Or let the script do the clone too:

```bash
GIT_REPO_URL=<your-repo-url> DOMAIN=example.com sudo -E ./install.sh
```

`install.sh` is the current recommended installer — MySQL, Redis, the
Brokeret WebSocket price stream, and Ably broadcasting by default. It's
idempotent (every step checks current state before acting), so re-running it
after `git pull` is how you deploy updates. See `SETUP_GUIDE.md` for the full
walkthrough of every step, every environment variable, and the manual
step-by-step equivalent if you'd rather not run a script with root.

Once DNS for your domain resolves to the server, enable HTTPS:

```bash
sudo certbot --nginx -d example.com
```

Then open the site and register your first account, and make it an admin:

```bash
cd /var/www/pocketapp
php artisan tinker --execute="App\Models\User::where('email','you@example.com')->update(['is_admin' => true]);"
```

## Already deployed with the older SQLite/Reverb-only config?

Sites originally bootstrapped with `deploy/setup.sh` (SQLite, Reverb, no
Brokeret/Ably wiring) can keep using it for routine updates — `git pull`
then `sudo -E ./deploy/setup.sh`. It no longer sets up a price feed itself
(the iqcent-based collector it used to wire up has been removed from the
app); see that script's own header comment for how to add the Brokeret
stream to an existing deployment by hand, or migrate to `install.sh`.

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
   ```

6. **Supervisor** — copy each template from `deploy/supervisor/` into
   `/etc/supervisor/conf.d/`, replacing `/var/www/pocketapp` with your real
   path. Then:
   ```bash
   supervisorctl reread
   supervisorctl update
   supervisorctl start pocketapp-reverb:* pocketapp-queue-worker:*
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
| `pocketapp-reverb` | WebSocket server — delivers broadcasts to browsers (only if self-hosting Reverb instead of Ably) | `deploy/supervisor/pocketapp-reverb.conf` |
| `pocketapp-queue-worker` | Processes queued jobs — trade settlement, payouts, cashback | `deploy/supervisor/pocketapp-queue-worker.conf` |
| `pocketapp-brokeret-stream` | Connects to Brokeret's WebSocket feed, writes ticks into Redis | `deploy/supervisor/pocketapp-brokeret-stream.conf` |
| `pocketapp-redis-tick-bridge` | Tails Redis and rebroadcasts ticks to browsers | `deploy/supervisor/pocketapp-redis-tick-bridge.conf` |
| cron → `schedule:run` | Matures plans, expires stale P2P trades | installed by `install.sh`/`deploy/setup.sh` |

See `SETUP_GUIDE.md` §6 for the full list, including base_url/ui's
independent `pocketapp-brokeret-ui-stream`.

Check status: `sudo supervisorctl status`. Logs are in `storage/logs/`
(`reverb.log`, `queue-worker.log`, `brokeret-stream.log`,
`redis-tick-bridge.log`).

## Troubleshooting

- **Every chart shows "offline" / no price movement**: check
  `storage/logs/brokeret-stream.log` (Supervisor's raw stdout for
  `ticks:stream-brokeret`) and `storage/logs/laravel.log`. Confirm
  `pocketapp-brokeret-stream` and `pocketapp-redis-tick-bridge` are both
  running (`sudo supervisorctl status`) — the stream alone isn't enough,
  the bridge is what actually gets ticks to the browser.
- **Chart doesn't update live but `assets/history` shows data**: the price
  stream is working but the broadcast isn't reaching the browser. On Ably,
  check the browser devtools console for `[echo]` errors and confirm
  `ABLY_KEY`/`VITE_ABLY_PUBLIC_KEY` are set. On self-hosted Reverb, check
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
