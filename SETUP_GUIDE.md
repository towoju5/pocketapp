# PocketApp — Installation & Setup Guide

A Laravel-based binary-options / trading platform: user wallets, deposits &
withdrawals, express trades, tournaments, social/P2P trading, and live asset
price charts, broadcast to every connected browser in real time.

This guide covers everything needed to take the codebase from a `.zip`
download to a running production site on a Linux VPS: software
requirements, minimum server specs, installation, configuration, the
background services the app depends on, and troubleshooting.

---

## Table of contents

1. [Architecture overview](#1-architecture-overview)
2. [Requirements](#2-requirements)
3. [Minimum VPS server requirements](#3-minimum-vps-server-requirements)
4. [Installation](#4-installation)
5. [Environment configuration reference](#5-environment-configuration-reference)
6. [Background services](#6-background-services)
7. [First-run checklist](#7-first-run-checklist)
8. [HTTPS / SSL](#8-https--ssl)
9. [Updating the application](#9-updating-the-application)
10. [Troubleshooting](#10-troubleshooting)
11. [Going live checklist](#11-going-live-checklist)

---

## 1. Architecture overview

```
Price feed (WebSocket)
        │
        ▼
Redis (ticks:{symbol}, latest_tick:{symbol})
        │
        ▼
PriceFeedService  ──►  broadcast (Ably or self-hosted Reverb)
        │                        │
        ▼                        ▼
Trade placement / settlement   Browser (Echo) — live chart & prices

Queue worker (database driver) — trade settlement, payouts, cashback,
notifications. Nothing settles without this running.

Cron → schedule:run — matures investment plans, expires stale P2P trades.
```

Data is stored in **SQLite** (`database/database.sqlite`, WAL mode) rather
than a separate database server, to keep the deploy surface small. Redis is
used as a cache/pub-sub layer for the live price feed, not as the primary
datastore.

Real-time updates (price ticks, trade status, wallet balance, chat) are
delivered over WebSockets through one of two interchangeable broadcasters,
selected by a single `.env` value (`BROADCAST_CONNECTION`):

| Broadcaster | `BROADCAST_CONNECTION` | Extra process to run? | Notes |
|---|---|---|---|
| **Ably** (hosted) | `ably` | No | Recommended. No WebSocket server to operate yourself; Ably handles connection scaling. Requires a free or paid [Ably](https://ably.com) account and API key. |
| **Laravel Reverb** (self-hosted) | `reverb` | Yes — `reverb:start` | Use this if you'd rather not depend on a third-party realtime service. Runs entirely on your own VPS; your server handles every WebSocket connection directly. |

Both are already wired up on the frontend (`resources/js/echo.js`) — no code
changes or rebuild are needed to switch between them, just the `.env` value
and (for Reverb only) making sure `reverb:start` is running.

---

## 2. Requirements

### 2.1 Server / OS

- **Ubuntu 22.04 LTS or 24.04 LTS** (or another modern Debian-based distro).
  These instructions assume `apt`.
- Root or `sudo` access.
- A domain name with its **A record** pointed at the server's IP, if you
  want HTTPS from the start (not required to get the app running over plain
  HTTP first).

### 2.2 Software

| Component | Version | Purpose |
|---|---|---|
| PHP | **8.2 or newer** (8.3 recommended) | Application runtime |
| Composer | 2.x | PHP dependency management |
| Node.js | **18 or newer** (20 recommended) + npm | Building frontend assets (Vite) |
| SQLite 3 | any recent version | Primary datastore |
| Redis | 6.x or newer | Live price cache / pub-sub for the feed pipeline |
| nginx | any recent version | Web server / reverse proxy |
| Supervisor | any recent version | Keeps the queue worker and price-feed processes running permanently |
| Certbot | any recent version | Free HTTPS certificates (optional but recommended) |

**Required PHP extensions:** `bcmath`, `ctype`, `curl`, `dom`, `fileinfo`,
`filter`, `hash`, `iconv`, `json`, `libxml`, `mbstring`, `openssl`, `pcre`,
`pdo`, `pdo_sqlite`, `sqlite3`, `phar`, `reflection`, `session`, `simplexml`,
`tokenizer`, `xml`, `xmlwriter`, `zip`, `gd`, `intl`, and **`redis`**
(phpredis — `.env.example` sets `REDIS_CLIENT=phpredis`; this is a PHP
extension, not a Composer package, so `composer install` alone will not
provide it).

### 2.3 Optional / feature-specific dependencies

- **Google Chrome (stable) + the bundled `drivers/chromedriver`** — only
  needed if you enable the legacy headless-browser price collector
  (`ticks:collect`). The default/recommended price pipeline (`ticks:stream-brokeret`)
  is a plain WebSocket client and does not need a browser at all. See
  [§6](#6-background-services) for which pipeline you actually need running.

### 2.4 Third-party accounts

| Service | Required? | Used for |
|---|---|---|
| [Ably](https://ably.com) | Required, unless you self-host Reverb instead | Realtime broadcasting (price ticks, trade updates, chat, balance sync) |
| Brokeret WebSocket feed | Required | Live market price data (`BROKERET_WS_URL`, `BROKERET_API_KEY` in `.env`) |
| [NOWPayments](https://nowpayments.io) | Optional | Crypto deposit processing |
| [BitGo](https://www.bitgo.com) | Optional | Crypto wallet generation/custody |
| DeepSeek API | Optional | AI-generated trading signals |
| ZenRows | Optional | Scraping fallback used by the signal service |

None of the optional integrations block installation — the app runs and
trades settle without them; the related admin screens simply have nothing
to talk to until keys are added.

---

## 3. Minimum VPS server requirements

Actual load depends on concurrent users and which price pipeline you run,
but as a baseline:

| Tier | vCPU | RAM | Storage | Suitable for |
|---|---|---|---|---|
| **Minimum** | 1 vCPU | 2 GB | 20 GB SSD | Development, staging, or a small live site with light traffic. Ably for broadcasting, `ticks:stream-brokeret` for prices (no headless browser). |
| **Recommended** | 2 vCPU | 4 GB | 40 GB SSD | A production site with real trading volume. Comfortable headroom for the queue worker, price stream, PHP-FPM workers, and `npm run build`. |
| **Self-hosted Reverb** | 2 vCPU | 4–8 GB | 40 GB SSD | Same as Recommended, but budget extra RAM/CPU if you expect hundreds of concurrent WebSocket connections, since your own server (not Ably) is holding every connection open. |
| **Legacy Chrome collector enabled** | 4 vCPU | 8 GB+ | 60 GB SSD | Only if you also run `ticks:collect` (`pocketapp-ticker-collector`) as a fallback price source. Each headless Chrome instance uses roughly 150–300 MB RAM, and the pool runs one instance per ~10 tracked assets — this adds up fast on a large asset catalog. |

Notes:

- **RAM during build**: `npm run build` (Vite) can transiently use 1 GB+ of
  RAM while bundling. On a 1 GB VPS this will fail or get OOM-killed —
  either provision at least 2 GB, or add a swap file before building
  (`fallocate -l 2G /swapfile && chmod 600 /swapfile && mkswap /swapfile && swapon /swapfile`).
- **Disk growth**: the SQLite database accumulates trade, tick, and wallet
  transaction history over time. Plan storage (and backups) accordingly for
  an actively-traded site, and monitor `database/database.sqlite`'s size.
- **Bandwidth**: with Ably, most WebSocket bandwidth is offloaded to Ably's
  infrastructure. With self-hosted Reverb, every connected browser's
  WebSocket traffic flows through your VPS directly — factor that into your
  provider's bandwidth allowance if you expect high concurrency.

---

## 4. Installation

### 4.1 Get the code onto the server

```bash
git clone <your-repo-or-extracted-zip> /var/www/pocketapp
cd /var/www/pocketapp
```

(If you downloaded a `.zip` from Envato instead of cloning, extract it to
`/var/www/pocketapp` and continue from there.)

### 4.2 Install system packages

```bash
sudo apt-get update
sudo apt-get install -y software-properties-common curl git unzip ca-certificates \
    supervisor sqlite3 redis-server nginx certbot python3-certbot-nginx

# PHP 8.3 (ondrej/php PPA carries current versions on Ubuntu)
sudo add-apt-repository -y ppa:ondrej/php
sudo apt-get update
sudo apt-get install -y php8.3-fpm php8.3-cli php8.3-sqlite3 php8.3-curl \
    php8.3-mbstring php8.3-xml php8.3-bcmath php8.3-gd php8.3-zip \
    php8.3-intl php8.3-redis

sudo systemctl enable --now redis-server

# Composer
curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Node.js 20
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo bash -
sudo apt-get install -y nodejs
```

### 4.3 Install dependencies and build assets

```bash
cd /var/www/pocketapp
composer install --no-interaction --prefer-dist --optimize-autoloader
npm install
npm run build
```

### 4.4 Configure `.env`

```bash
cp .env.example .env
php artisan key:generate
```

At minimum, set:

```
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Price feed (see your Brokeret account/documentation for these values)
BROKERET_WS_URL=wss://feed.brokeret.com/ws
BROKERET_API_KEY=your-real-key

# Broadcasting — pick ONE:
# Option A (recommended): Ably
BROADCAST_CONNECTION=ably
ABLY_KEY=appId.keyId:keySecret        # full key, server-side only
VITE_ABLY_PUBLIC_KEY=appId.keyId      # public half only, safe for the browser

# Option B: self-hosted Reverb (skip if using Ably)
# BROADCAST_CONNECTION=reverb
# REVERB_APP_ID=<random>
# REVERB_APP_KEY=<random>
# REVERB_APP_SECRET=<random>
# REVERB_HOST=your-domain.com
# REVERB_SCHEME=https
# REVERB_PORT=443
# VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
# VITE_REVERB_HOST="${REVERB_HOST}"
# VITE_REVERB_SCHEME="${REVERB_SCHEME}"
# VITE_REVERB_PORT="${REVERB_PORT}"
```

Rebuild frontend assets after any `VITE_*` change: `npm run build` (these
values are baked into the JS bundle at build time — editing `.env` alone
does not update an already-built bundle).

Fill in any optional integrations you plan to use (`NOWPAYMENTS_*`,
`BITGO_*`, `DEEPSEEK_API_KEY`, `ZENROWS_API_KEY`) — see
[§5](#5-environment-configuration-reference).

### 4.5 Database

```bash
touch database/database.sqlite
php artisan migrate --force
php artisan db:seed --force   # first run only — seeds the tradable asset catalog
```

### 4.6 Permissions

```bash
php artisan storage:link
sudo chown -R www-data:www-data storage bootstrap/cache database
```

### 4.7 Cache & optimize

```bash
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 4.8 Background services (Supervisor)

See [§6](#6-background-services) for the full list and what each one does.
At minimum, for a working site you need the queue worker and a price
pipeline running under Supervisor:

```bash
for conf in pocketapp-queue-worker pocketapp-brokeret-stream pocketapp-redis-tick-bridge; do
    sudo cp deploy/supervisor/$conf.conf /etc/supervisor/conf.d/
done
# Edit each copied file if your app path isn't /var/www/pocketapp

sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start pocketapp-queue-worker:* pocketapp-brokeret-stream pocketapp-redis-tick-bridge
```

If you chose `BROADCAST_CONNECTION=reverb` in step 4.4, also install and
start `deploy/supervisor/pocketapp-reverb.conf`.

### 4.9 Cron (Laravel scheduler)

```bash
( sudo crontab -u www-data -l 2>/dev/null; \
  echo "* * * * * cd /var/www/pocketapp && php artisan schedule:run >> /dev/null 2>&1" \
) | sudo crontab -u www-data -
```

This drives `plans:mature` and `p2p:expire-trades` (see `routes/console.php`).

### 4.10 nginx

Use `deploy/nginx/pocketapp.conf.example` as a starting point for your
vhost (PHP-FPM passthrough, plus the `/app/` and `/apps/` WebSocket proxy
locations needed only if you're self-hosting Reverb). Substitute your
domain, app path, and PHP-FPM socket path, then:

```bash
sudo ln -s /etc/nginx/sites-available/your-domain.com /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

---

## 5. Environment configuration reference

### Core (required)

| Variable | Description |
|---|---|
| `APP_ENV` | `production` on a live server |
| `APP_DEBUG` | `false` on a live server — never expose stack traces publicly |
| `APP_URL` | Your site's public URL |
| `APP_KEY` | Generated by `php artisan key:generate` |
| `DB_CONNECTION` | `sqlite` (default) |
| `REDIS_HOST` / `REDIS_PORT` / `REDIS_PASSWORD` | Redis connection (defaults work for a local install) |
| `BROKERET_WS_URL` / `BROKERET_API_KEY` | Live price feed connection |
| `BROADCAST_CONNECTION` | `ably` or `reverb` — see [§1](#1-architecture-overview) |

### Broadcasting

| Variable | Required when |
|---|---|
| `ABLY_KEY` | `BROADCAST_CONNECTION=ably` — full key, **server-side only, never expose to the browser** |
| `VITE_ABLY_PUBLIC_KEY` | `BROADCAST_CONNECTION=ably` — public half only (everything before the `:`) |
| `REVERB_APP_ID` / `REVERB_APP_KEY` / `REVERB_APP_SECRET` | `BROADCAST_CONNECTION=reverb` |
| `REVERB_HOST` / `REVERB_SCHEME` / `REVERB_PORT` | `BROADCAST_CONNECTION=reverb` — must be your real public domain, not `localhost` |
| `VITE_REVERB_*` | Mirrors of the above, baked into the frontend build |

### Optional integrations

| Variable | Feature |
|---|---|
| `NOWPAYMENTS_API_KEY` / `NOWPAYMENTS_IPN_SECRET` / `NOWPAYMENTS_ENV` | Crypto deposits via NOWPayments |
| `BITGO_API_KEY` / `BITGO_ENV` / `BITGO_WALLET_PASSPHRASE` | BitGo wallet integration |
| `DEEPSEEK_API_KEY` | AI trading signals |
| `ZENROWS_API_KEY` | Scraping fallback for signal generation |
| `MAIL_*` | Outbound email (password resets, notifications) |
| `AWS_*` | S3-compatible file storage, if not using local disk |

Never commit `.env` — it holds `APP_KEY`, broadcaster secrets, and any
payment/API credentials you add. It's already excluded via `.gitignore`.

---

## 6. Background services

The app needs several long-running processes in addition to PHP-FPM/nginx.
All are managed by Supervisor; templates are in `deploy/supervisor/`.

| Process | Command | Required? |
|---|---|---|
| Queue worker | `php artisan queue:work database --sleep=1 --tries=3` | **Always.** Trade settlement, payouts, and loss-cashback all happen inside queued jobs — without this, trades never resolve. |
| Brokeret price stream | `php artisan ticks:stream-brokeret` | **Yes**, for the default/recommended price pipeline. Connects directly to the Brokeret WebSocket feed and writes ticks into Redis. |
| Redis → broadcast bridge | `php artisan ticks:bridge-redis` | **Yes**, alongside the stream above. Tails Redis and rebroadcasts ticks so charts update live in the browser. |
| Brokeret feed for `/ui` dashboard | `php artisan ticks:stream-brokeret-ui` | Only if you use the `/ui` live-dashboard route — it runs its own independent feed/broadcast, separate from the main dashboard's pipeline. |
| Reverb server | `php artisan reverb:start` | Only if `BROADCAST_CONNECTION=reverb`. Not needed with Ably. |
| Legacy headless-browser collector | `php artisan ticks:collect --batch=N --size=10` | Optional fallback price source (scrapes iqcent via headless Chrome). Not required if the Brokeret stream is running. Needs Google Chrome installed — see [§2.3](#23-optional--feature-specific-dependencies). |
| Cron → `schedule:run` | via crontab, not Supervisor | **Always.** Matures investment plans and expires stale P2P trades every minute. |

Check status any time with:

```bash
sudo supervisorctl status
```

Logs live in `storage/logs/` (`queue-worker.log`, `brokeret-stream.log`,
`redis-tick-bridge.log`, `reverb.log`, `ticker-collector.log`,
`laravel.log`).

**After every deploy**, restart the queue worker and any long-running
commands so they pick up new code — a `git pull` alone does not do this
(`php artisan queue:restart` triggers a graceful worker restart; the
price-stream processes need an explicit `supervisorctl restart`).

---

## 7. First-run checklist

1. Visit your site and register an account.
2. Promote it to admin:
   ```bash
   cd /var/www/pocketapp
   php artisan tinker --execute="App\Models\User::where('email','you@example.com')->update(['is_admin' => true]);"
   ```
3. Confirm `sudo supervisorctl status` shows every configured process as
   `RUNNING`.
4. Open a trading page and confirm the chart shows a live price and updates
   (not "offline").
5. Place a small test trade and confirm it settles (win or loss) once its
   duration elapses — this exercises the queue worker end-to-end.

---

## 8. HTTPS / SSL

Once DNS for your domain resolves to the server:

```bash
sudo certbot --nginx -d your-domain.com
```

If you're using self-hosted Reverb, make sure `REVERB_SCHEME=https` and
`REVERB_PORT=443` in `.env`, then `npm run build` again — a page served
over HTTPS refuses to open a plain `ws://` connection as mixed content.

---

## 9. Updating the application

```bash
cd /var/www/pocketapp
git pull            # or deploy your new code however you distribute updates
composer install --no-interaction --prefer-dist --optimize-autoloader
npm install && npm run build
php artisan migrate --force
php artisan config:cache && php artisan route:cache && php artisan view:cache
php artisan queue:restart
sudo supervisorctl restart pocketapp-brokeret-stream pocketapp-redis-tick-bridge
```

---

## 10. Troubleshooting

**Chart shows "offline" / no price movement**
Check `storage/logs/brokeret-stream.log` and `storage/logs/laravel.log`.
Confirm `pocketapp-brokeret-stream` and `pocketapp-redis-tick-bridge` are
both `RUNNING` in `supervisorctl status`, and that `BROKERET_WS_URL` /
`BROKERET_API_KEY` are correct in `.env`.

**Chart doesn't update live but price history loads fine**
The feed is working but the broadcast isn't reaching the browser. If using
Ably, check `ABLY_KEY` is valid and `/broadcasting/auth` is reachable. If
using Reverb, confirm `reverb:start` is running and nginx's `/app/` and
`/apps/` proxy blocks are configured (see
`deploy/nginx/reverb-proxy.conf.example`).

**Trades never settle / balances don't update**
`pocketapp-queue-worker` isn't running, or is still running old code after
a deploy — restart it with `php artisan queue:restart`.

**`.env` changes seem to have no effect**
If `php artisan config:cache` has ever been run, `env()` calls outside
`config/*.php` keep returning cached values — run
`php artisan config:clear` after any `.env` edit, then re-cache if desired.

**"cannot create snap home dir" (only relevant if using the legacy Chrome collector)**
Ubuntu's `apt install chromium`/`chromium-browser` on 22.04+ silently
resolves to the **snap-packaged** build, which cannot run as a service user
like `www-data`. Install real Google Chrome via the official `.deb`
instead.

---

## 11. Going live checklist

- [ ] `APP_ENV=production` and `APP_DEBUG=false`
- [ ] `.env` is not committed to version control and has restrictive file
      permissions (`chmod 600 .env`)
- [ ] HTTPS is enabled (`certbot --nginx`)
- [ ] All Supervisor processes required for your configuration show
      `RUNNING`
- [ ] Cron is installed for the app user (`crontab -u www-data -l`)
- [ ] Regular backups are configured for `database/database.sqlite` and
      `.env` — this app moves real trading balances; treat backups and
      server access accordingly
- [ ] Telescope access is restricted — `app/Providers/TelescopeServiceProvider.php`'s
      `gate()` should list only your own admin email(s) before this ships
      to a public server, or disable Telescope in production entirely
      (`TELESCOPE_ENABLED=false`)
