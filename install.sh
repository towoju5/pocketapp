#!/usr/bin/env bash
#
# One-shot installer for a fresh Ubuntu/Debian VPS. Installs every system
# dependency (PHP 8.4, MySQL, Redis, Node, nginx, Supervisor, Certbot),
# configures `.env`, creates the MySQL database, builds the app, migrates
# and seeds the database, sets permissions, wires up Supervisor (queue
# worker + the Brokeret price stream) and the cron scheduler, and generates
# an nginx vhost — then starts everything.
#
# This is the recommended installer for a new deployment: MySQL (not
# SQLite), Redis, the Brokeret WebSocket price stream (not the legacy
# headless-Chrome collector), and Ably broadcasting by default. See
# SETUP_GUIDE.md for the full explanation of every piece and the manual,
# step-by-step equivalent of what this script automates.
#
# Safe to re-run any time — every step checks current state before acting,
# so re-running after `git pull` is how you deploy updates, and re-running
# after filling in ABLY_KEY is how you turn broadcasting on (see below).
#
# Usage (as root, from the repo root):
#   DOMAIN=example.com sudo -E ./install.sh
#
# Or let it clone the repo for you:
#   GIT_REPO_URL=<your-repo-url> DOMAIN=example.com sudo -E ./install.sh
#
# Environment variables (all optional):
#   DOMAIN                 Your domain (e.g. example.com). Without it,
#                          APP_URL/nginx are left for you to configure by
#                          hand.
#   GIT_REPO_URL           Clone source, if the app isn't already checked
#                          out at the path this script runs from.
#   PHP_VERSION            Defaults to 8.4.
#   APP_USER               System user the app runs as. Defaults to www-data.
#   DB_DATABASE            MySQL database name. Defaults to pocketapp.
#   DB_USERNAME            MySQL user. Defaults to pocketapp.
#   DB_PASSWORD            MySQL password. Auto-generated and saved to .env
#                          if not set.
#   BROADCASTER            "ably" (default) or "reverb".
#   ABLY_KEY               Full Ably key ("appId.keyId:keySecret"). If you
#                          don't have one yet, leave this unset — the
#                          script leaves broadcasting as an inert "log"
#                          placeholder and tells you how to finish the
#                          switch once you do (see note below).
#   VITE_ABLY_PUBLIC_KEY   Public half of ABLY_KEY. Derived automatically
#                          from ABLY_KEY if not set.
#   BROKERET_WS_URL        Defaults to wss://feed.brokeret.com/ws.
#   BROKERET_API_KEY       Your real Brokeret API key. Left as the
#                          "demo" placeholder if not set — fill it in by
#                          hand before going live.
#   ENABLE_LEGACY_COLLECTOR  "true" to also install Google Chrome and the
#                          headless-browser ticker-collector pool as a
#                          fallback price source. Off by default — the
#                          Brokeret stream doesn't need it. See
#                          SETUP_GUIDE.md §3 for why this needs much more
#                          RAM if enabled.
#
# IMPORTANT — Ably safety note: routes/channels.php resolves the configured
# broadcaster the instant it's loaded (on every request/artisan command).
# With BROADCAST_CONNECTION=ably and an empty ABLY_KEY, Ably's SDK throws
# immediately and the whole site 500s. This script only ever sets
# BROADCAST_CONNECTION=ably once a real ABLY_KEY is present (from this
# run's env var, or already saved in .env from a previous run) — otherwise
# it leaves it as the safe "log" placeholder. Get an Ably API key, then
# re-run with ABLY_KEY=... set (or add it to .env yourself and re-run) to
# finish turning broadcasting on.

set -euo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
DOMAIN="${DOMAIN:-}"
GIT_REPO_URL="${GIT_REPO_URL:-}"
PHP_VERSION="${PHP_VERSION:-8.4}"
APP_USER="${APP_USER:-www-data}"
DB_DATABASE="${DB_DATABASE:-pocketapp}"
DB_USERNAME="${DB_USERNAME:-pocketapp}"
DB_PASSWORD="${DB_PASSWORD:-}"
BROADCASTER="${BROADCASTER:-ably}"
ABLY_KEY="${ABLY_KEY:-}"
VITE_ABLY_PUBLIC_KEY="${VITE_ABLY_PUBLIC_KEY:-}"
BROKERET_WS_URL="${BROKERET_WS_URL:-wss://feed.brokeret.com/ws}"
BROKERET_API_KEY="${BROKERET_API_KEY:-}"
ENABLE_LEGACY_COLLECTOR="${ENABLE_LEGACY_COLLECTOR:-false}"
BATCH_SIZE=10

if [ "$(id -u)" -ne 0 ]; then
    echo "ERROR: run this as root (sudo -E ./install.sh)." >&2
    exit 1
fi

if ! command -v apt-get >/dev/null 2>&1; then
    echo "ERROR: this script only supports apt-based systems (Ubuntu/Debian)." >&2
    exit 1
fi

echo "== 1/12: System packages =="
apt-get update -y
apt-get install -y software-properties-common curl git unzip ca-certificates \
    supervisor mysql-server redis-server nginx certbot python3-certbot-nginx

if ! php -v 2>/dev/null | grep -q "PHP ${PHP_VERSION}"; then
    # Ubuntu's own repos only carry one PHP version at a time (often not the
    # one this app wants) — ondrej/php carries every supported version side
    # by side. Harmless no-op if it's already added or unavailable.
    add-apt-repository -y ppa:ondrej/php || true
    apt-get update -y
fi
apt-get install -y \
    "php${PHP_VERSION}-fpm" "php${PHP_VERSION}-cli" "php${PHP_VERSION}-mysql" \
    "php${PHP_VERSION}-curl" "php${PHP_VERSION}-mbstring" "php${PHP_VERSION}-xml" \
    "php${PHP_VERSION}-bcmath" "php${PHP_VERSION}-gd" "php${PHP_VERSION}-zip" \
    "php${PHP_VERSION}-intl" "php${PHP_VERSION}-redis"

systemctl enable --now mysql
systemctl enable --now redis-server

if ! command -v composer >/dev/null 2>&1; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

if ! command -v node >/dev/null 2>&1; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -y nodejs
fi

echo
echo "== 2/12: Application code =="
if [ ! -f "$APP_ROOT/artisan" ]; then
    if [ -n "$GIT_REPO_URL" ]; then
        git clone "$GIT_REPO_URL" "$APP_ROOT"
    else
        echo "ERROR: no 'artisan' found in $APP_ROOT and no GIT_REPO_URL set — clone/extract the app there first, or set GIT_REPO_URL." >&2
        exit 1
    fi
fi
cd "$APP_ROOT"

echo
echo "== 3/12: .env =="
ENV_FILE="$APP_ROOT/.env"
[ -f "$ENV_FILE" ] || cp "$APP_ROOT/.env.example" "$ENV_FILE"
cp "$ENV_FILE" "$ENV_FILE.bak.$(date +%Y%m%d%H%M%S)"

get_env() { grep -E "^$1=" "$ENV_FILE" | tail -1 | cut -d '=' -f2- | sed 's/^"//; s/"$//'; }
set_env() {
    local key="$1" val="$2"
    if grep -qE "^$key=" "$ENV_FILE"; then
        sed -i "s|^$key=.*|$key=$val|" "$ENV_FILE"
    else
        echo "$key=$val" >> "$ENV_FILE"
    fi
}
random_hex() { php -r 'echo bin2hex(random_bytes(16));'; }

if [ -n "$DOMAIN" ]; then
    set_env APP_URL "https://$DOMAIN"
fi
set_env APP_ENV production
set_env APP_DEBUG false

if [ -z "$(get_env APP_KEY)" ]; then
    php artisan key:generate --ansi --force
fi

echo
echo "== 4/12: MySQL database =="
if [ -z "$DB_PASSWORD" ]; then
    DB_PASSWORD="$(get_env DB_PASSWORD)"
    if [ -z "$DB_PASSWORD" ] || [ "$DB_PASSWORD" = "a-strong-password" ]; then
        DB_PASSWORD="$(random_hex)"
    fi
fi
set_env DB_CONNECTION mysql
set_env DB_HOST 127.0.0.1
set_env DB_PORT 3306
set_env DB_DATABASE "$DB_DATABASE"
set_env DB_USERNAME "$DB_USERNAME"
set_env DB_PASSWORD "$DB_PASSWORD"

mysql <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_DATABASE}\` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USERNAME}'@'127.0.0.1' IDENTIFIED BY '${DB_PASSWORD}';
ALTER USER '${DB_USERNAME}'@'127.0.0.1' IDENTIFIED BY '${DB_PASSWORD}';
GRANT ALL PRIVILEGES ON \`${DB_DATABASE}\`.* TO '${DB_USERNAME}'@'127.0.0.1';
FLUSH PRIVILEGES;
SQL
echo "  database '$DB_DATABASE' and user '$DB_USERNAME' ready"

echo
echo "== 5/12: Price feed =="
if [ -n "$BROKERET_API_KEY" ]; then
    set_env BROKERET_API_KEY "$BROKERET_API_KEY"
fi
set_env BROKERET_WS_URL "$BROKERET_WS_URL"
if [ "$(get_env BROKERET_API_KEY)" = "demo" ] || [ -z "$(get_env BROKERET_API_KEY)" ]; then
    echo "  WARNING: BROKERET_API_KEY is still a placeholder — set your real key in .env before going live." >&2
fi

echo
echo "== 6/12: Broadcasting ($BROADCASTER) =="
if [ "$BROADCASTER" = "reverb" ]; then
    set_env BROADCAST_CONNECTION reverb
    for KEY_VAR in REVERB_APP_ID REVERB_APP_KEY REVERB_APP_SECRET; do
        if [ -z "$(get_env "$KEY_VAR")" ]; then
            set_env "$KEY_VAR" "$(random_hex)"
        fi
    done
    set_env VITE_REVERB_APP_KEY '"${REVERB_APP_KEY}"'

    REVERB_HOST_CUR="$(get_env REVERB_HOST)"
    if [ -z "$REVERB_HOST_CUR" ] || [ "$REVERB_HOST_CUR" = "localhost" ] || [ "$REVERB_HOST_CUR" = "127.0.0.1" ]; then
        APP_URL_NOW="$(get_env APP_URL)"
        HOST_FROM_URL="$(php -r '$u=parse_url($argv[1]); echo $u["host"] ?? "";' "$APP_URL_NOW")"
        if [ -n "$HOST_FROM_URL" ]; then
            set_env REVERB_HOST "$HOST_FROM_URL"
            set_env REVERB_SCHEME https
            set_env REVERB_PORT 443
            set_env VITE_REVERB_HOST '"${REVERB_HOST}"'
            set_env VITE_REVERB_SCHEME '"${REVERB_SCHEME}"'
            set_env VITE_REVERB_PORT '"${REVERB_PORT}"'
        else
            echo "  WARNING: no DOMAIN/APP_URL set yet — set REVERB_HOST/VITE_REVERB_HOST by hand before going live." >&2
        fi
    fi
    echo "  Reverb configured — remember to run 'php artisan reverb:start' under Supervisor (done below)."
else
    # Ably: routes/channels.php resolves the broadcaster the instant it's
    # loaded — flipping BROADCAST_CONNECTION to 'ably' with no real ABLY_KEY
    # makes every request/artisan command throw. Only do it once a real key
    # is present (from this run's env var, or already saved from a prior run).
    if [ -n "$ABLY_KEY" ]; then
        set_env ABLY_KEY "$ABLY_KEY"
    fi
    if [ -z "$VITE_ABLY_PUBLIC_KEY" ] && [ -n "$(get_env ABLY_KEY)" ]; then
        SAVED_ABLY_KEY="$(get_env ABLY_KEY)"
        VITE_ABLY_PUBLIC_KEY="${SAVED_ABLY_KEY%%:*}"
    fi
    if [ -n "$VITE_ABLY_PUBLIC_KEY" ]; then
        set_env VITE_ABLY_PUBLIC_KEY "$VITE_ABLY_PUBLIC_KEY"
    fi

    if [ -n "$(get_env ABLY_KEY)" ]; then
        set_env BROADCAST_CONNECTION ably
        echo "  ABLY_KEY present — BROADCAST_CONNECTION set to 'ably'."
    else
        set_env BROADCAST_CONNECTION log
        echo "  WARNING: no ABLY_KEY set — leaving BROADCAST_CONNECTION=log (inert placeholder)." >&2
        echo "  Get an API key from https://ably.com, then re-run:" >&2
        echo "    ABLY_KEY='appId.keyId:keySecret' sudo -E ./install.sh" >&2
        echo "  (or add ABLY_KEY/VITE_ABLY_PUBLIC_KEY to .env yourself and re-run) to turn broadcasting on." >&2
    fi
fi

echo
echo "== 7/12: PHP & JS dependencies =="
composer install --no-interaction --prefer-dist --optimize-autoloader
npm install
npm run build

echo
echo "== 8/12: Database migrate & seed =="
php artisan migrate --force

ASSET_COUNT="$(mysql -N -B "$DB_DATABASE" -e "SELECT COUNT(*) FROM assets;" 2>/dev/null || echo 0)"
if [ "${ASSET_COUNT:-0}" -eq 0 ]; then
    php artisan db:seed --force
    ASSET_COUNT="$(mysql -N -B "$DB_DATABASE" -e "SELECT COUNT(*) FROM assets;" 2>/dev/null || echo 0)"
fi
echo "  $ASSET_COUNT assets in catalog"

echo
echo "== 9/12: storage:link, permissions, config cache =="
[ -L public/storage ] || php artisan storage:link
chown -R "$APP_USER:$APP_USER" storage bootstrap/cache
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo
echo "== 10/12: Supervisor =="
copy_supervisor_conf() {
    local name="$1"
    sed -e "s|/var/www/pocketapp|$APP_ROOT|g" \
        "$APP_ROOT/deploy/supervisor/$name.conf" > "/etc/supervisor/conf.d/$name.conf"
}

copy_supervisor_conf pocketapp-queue-worker
copy_supervisor_conf pocketapp-brokeret-stream
copy_supervisor_conf pocketapp-redis-tick-bridge
START_PROGRAMS="pocketapp-queue-worker:* pocketapp-brokeret-stream pocketapp-redis-tick-bridge"

if [ "$BROADCASTER" = "reverb" ]; then
    copy_supervisor_conf pocketapp-reverb
    START_PROGRAMS="$START_PROGRAMS pocketapp-reverb:*"
fi

if [ "$ENABLE_LEGACY_COLLECTOR" = "true" ]; then
    echo "  ENABLE_LEGACY_COLLECTOR=true — installing Google Chrome + the headless-browser collector pool"
    if ! command -v google-chrome-stable >/dev/null 2>&1 && ! command -v google-chrome >/dev/null 2>&1; then
        curl -fsSL -o /tmp/chrome.deb https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
        apt-get install -y /tmp/chrome.deb || apt-get -f install -y
        rm -f /tmp/chrome.deb
    fi
    CHROME_BIN="$(command -v google-chrome-stable || command -v google-chrome || true)"
    chmod +x "$APP_ROOT/drivers/chromedriver"

    NUMPROCS=$(( (ASSET_COUNT + BATCH_SIZE - 1) / BATCH_SIZE ))
    [ "$NUMPROCS" -lt 1 ] && NUMPROCS=1
    if [ -z "$CHROME_BIN" ]; then
        echo "  WARNING: no Chrome binary found — the collector will fail to start until one is installed." >&2
    fi
    sed -e "s|/var/www/pocketapp|$APP_ROOT|g" -e "s/^numprocs=.*/numprocs=$NUMPROCS/" \
        -e "s|__PANTHER_CHROME_BINARY__|$CHROME_BIN|g" \
        "$APP_ROOT/deploy/supervisor/pocketapp-ticker-collector.conf" > /etc/supervisor/conf.d/pocketapp-ticker-collector.conf
    START_PROGRAMS="$START_PROGRAMS pocketapp-ticker-collector:*"
    echo "  ticker collector: $NUMPROCS process(es) (batch size $BATCH_SIZE, $ASSET_COUNT assets)"
fi

supervisorctl reread
supervisorctl update
# shellcheck disable=SC2086
supervisorctl restart $START_PROGRAMS || supervisorctl start $START_PROGRAMS

echo
echo "== 11/12: Cron (Laravel scheduler) =="
CRON_LINE="* * * * * cd $APP_ROOT && php artisan schedule:run >> /dev/null 2>&1"
( crontab -u "$APP_USER" -l 2>/dev/null | grep -vF "$APP_ROOT && php artisan schedule:run" ; echo "$CRON_LINE" ) \
    | crontab -u "$APP_USER" -

echo
echo "== 12/12: nginx =="
if [ -n "$DOMAIN" ]; then
    sed -e "s/example\.com/$DOMAIN/g" \
        -e "s|/var/www/pocketapp|$APP_ROOT|g" \
        -e "s|php8\.4-fpm|php${PHP_VERSION}-fpm|g" \
        "$APP_ROOT/deploy/nginx/pocketapp.conf.example" > "/etc/nginx/sites-available/$DOMAIN"
    ln -sf "/etc/nginx/sites-available/$DOMAIN" "/etc/nginx/sites-enabled/$DOMAIN"
    nginx -t && systemctl reload nginx
    echo "  nginx configured for http://$DOMAIN"
    echo "  Once DNS for $DOMAIN points at this server's IP, enable HTTPS with:"
    echo "    sudo certbot --nginx -d $DOMAIN"
else
    echo "  DOMAIN not set — skipped. Configure nginx by hand using deploy/nginx/pocketapp.conf.example, or re-run with DOMAIN=yourdomain.com."
fi

echo
echo "== Done =="
supervisorctl status
echo
echo "Logs:"
echo "  tail -f $APP_ROOT/storage/logs/brokeret-stream.log"
echo "  tail -f $APP_ROOT/storage/logs/redis-tick-bridge.log"
echo "  tail -f $APP_ROOT/storage/logs/queue-worker.log"
echo
echo "Still needed before this is production-ready:"
[ "$(get_env BROADCAST_CONNECTION)" = "log" ] && echo "  - Set a real ABLY_KEY and re-run (see the Broadcasting step above)."
{ [ "$(get_env BROKERET_API_KEY)" = "demo" ] || [ -z "$(get_env BROKERET_API_KEY)" ]; } && echo "  - Set a real BROKERET_API_KEY in .env."
echo
echo "Next: create your first account at the site, then make it an admin:"
echo "  cd $APP_ROOT && php artisan tinker --execute=\"App\\Models\\User::where('email','you@example.com')->update(['is_admin' => true]);\""
