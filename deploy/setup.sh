#!/usr/bin/env bash
#
# NOTE: this script provisions the older Reverb-based configuration and
# defaults to a SQLite database. For a new install, prefer ../install.sh
# instead — it sets up the current recommended stack (MySQL, Redis, PHP 8.4,
# the Brokeret price stream, Ably broadcasting) end to end. This script is
# kept for sites that were already deployed with the older configuration it
# describes below.
#
# The iqcent headless-Chrome ticker collector this script used to also wire
# up (TickerController/CollectTicks) has been removed from the app entirely
# — Brokeret is now the only price source. If this script previously set up
# `pocketapp-ticker-collector` for you, that Supervisor program is now
# orphaned (references a command that no longer exists) and should be
# stopped/removed by hand: `supervisorctl stop pocketapp-ticker-collector:*
# && rm /etc/supervisor/conf.d/pocketapp-ticker-collector.conf &&
# supervisorctl update`. Any assets that only had a price via that collector
# (price_source='iqcent' with no Brokeret equivalent) no longer receive live
# prices and will show as unavailable for trading.
#
# One-shot bootstrap for a fresh Ubuntu/Debian VPS: installs every system
# dependency, configures .env, builds the app, wires up Supervisor (Reverb +
# queue workers), the cron scheduler, and an nginx vhost — then starts
# everything. Safe to re-run any time (every step checks before it acts) —
# re-running after a `git pull` is how you deploy updates.
#
# Usage (as root, from anywhere):
#   git clone <your-repo-url> /var/www/pocketapp
#   cd /var/www/pocketapp
#   DOMAIN=example.com sudo -E ./deploy/setup.sh
#
# Or let the script clone it for you:
#   GIT_REPO_URL=<your-repo-url> DOMAIN=example.com sudo -E ./deploy/setup.sh
#
# Env vars you can set before running (all optional):
#   DOMAIN          Your domain (e.g. example.com). Without it, APP_URL/nginx
#                    are left for you to configure by hand.
#   GIT_REPO_URL     Clone source if the app isn't already checked out here.
#   PHP_VERSION      Defaults to 8.4.
#   APP_USER         System user the app runs as. Defaults to www-data.
#
# See README.md for the full explanation of what each step does and why.

set -euo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
DOMAIN="${DOMAIN:-}"
GIT_REPO_URL="${GIT_REPO_URL:-}"
PHP_VERSION="${PHP_VERSION:-8.4}"
APP_USER="${APP_USER:-www-data}"

if [ "$(id -u)" -ne 0 ]; then
    echo "ERROR: run this as root (sudo -E ./deploy/setup.sh)." >&2
    exit 1
fi

if ! command -v apt-get >/dev/null 2>&1; then
    echo "ERROR: this script only supports apt-based systems (Ubuntu/Debian)." >&2
    exit 1
fi

echo "== 1/9: System packages =="
apt-get update -y
apt-get install -y software-properties-common curl git unzip ca-certificates \
    supervisor sqlite3 redis-server nginx certbot python3-certbot-nginx

if ! php -v 2>/dev/null | grep -q "PHP ${PHP_VERSION}"; then
    # Ubuntu's own repos only carry one PHP version at a time (often not the
    # one this app wants) — ondrej/php carries every supported version side
    # by side. Harmless no-op if it's already added or unavailable.
    add-apt-repository -y ppa:ondrej/php || true
    apt-get update -y
fi
apt-get install -y \
    "php${PHP_VERSION}-fpm" "php${PHP_VERSION}-cli" "php${PHP_VERSION}-sqlite3" \
    "php${PHP_VERSION}-curl" "php${PHP_VERSION}-mbstring" "php${PHP_VERSION}-xml" \
    "php${PHP_VERSION}-bcmath" "php${PHP_VERSION}-gd" "php${PHP_VERSION}-zip" \
    "php${PHP_VERSION}-intl" "php${PHP_VERSION}-redis"

# PriceFeedService/BrokeretFeedService talk to Redis directly (REDIS_CLIENT=
# phpredis in .env.example) regardless of which broadcaster/price pipeline is
# active — without the server running, price reads/writes fail outright.
systemctl enable --now redis-server

if ! command -v composer >/dev/null 2>&1; then
    curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
fi

if ! command -v node >/dev/null 2>&1; then
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -y nodejs
fi

echo
echo "== 2/9: Application code =="
if [ ! -f "$APP_ROOT/artisan" ]; then
    if [ -n "$GIT_REPO_URL" ]; then
        git clone "$GIT_REPO_URL" "$APP_ROOT"
    else
        echo "ERROR: no 'artisan' found in $APP_ROOT and no GIT_REPO_URL set — clone the repo there first, or set GIT_REPO_URL." >&2
        exit 1
    fi
fi
cd "$APP_ROOT"

echo
echo "== 3/9: .env =="
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

# Everything downstream (Reverb process, REVERB_* config, resources/js/echo.js)
# assumes broadcasting actually goes over Reverb — if this is left as 'log'
# (the framework default), broadcast() calls succeed silently and just write
# to the log file, so ticks would never actually reach a browser.
[ "$(get_env BROADCAST_CONNECTION)" = "reverb" ] || set_env BROADCAST_CONNECTION reverb

for KEY_VAR in REVERB_APP_ID REVERB_APP_KEY REVERB_APP_SECRET; do
    if [ -z "$(get_env "$KEY_VAR")" ]; then
        set_env "$KEY_VAR" "$(random_hex)"
    fi
done
set_env VITE_REVERB_APP_KEY '"${REVERB_APP_KEY}"'

# REVERB_HOST is what the BROWSER connects to — must be your real public
# domain, not localhost/127.0.0.1.
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

echo
echo "== 4/9: PHP & JS dependencies =="
composer install --no-interaction --prefer-dist --optimize-autoloader
npm install
npm run build

echo
echo "== 5/9: Database =="
[ -f database/database.sqlite ] || touch database/database.sqlite
php artisan migrate --force

ASSET_COUNT="$(sqlite3 database/database.sqlite "SELECT COUNT(*) FROM assets;" 2>/dev/null || echo 0)"
if [ "${ASSET_COUNT:-0}" -eq 0 ]; then
    php artisan db:seed --force
    ASSET_COUNT="$(sqlite3 database/database.sqlite "SELECT COUNT(*) FROM assets;" 2>/dev/null || echo 0)"
fi
echo "  $ASSET_COUNT assets in catalog"

echo
echo "== 6/9: storage:link, permissions, config cache =="
[ -L public/storage ] || php artisan storage:link
chown -R "$APP_USER:$APP_USER" storage bootstrap/cache database
php artisan config:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo
echo "== 7/9: Supervisor (Reverb, queue workers) =="
sed -e "s|/var/www/pocketapp|$APP_ROOT|g" \
    "$APP_ROOT/deploy/supervisor/pocketapp-reverb.conf" > /etc/supervisor/conf.d/pocketapp-reverb.conf
sed -e "s|/var/www/pocketapp|$APP_ROOT|g" \
    "$APP_ROOT/deploy/supervisor/pocketapp-queue-worker.conf" > /etc/supervisor/conf.d/pocketapp-queue-worker.conf

supervisorctl reread
supervisorctl update
supervisorctl restart pocketapp-reverb:* pocketapp-queue-worker:* || \
    supervisorctl start pocketapp-reverb:* pocketapp-queue-worker:*

echo "  NOTE: this script no longer sets up a price feed (the iqcent ticker"
echo "  collector it used to wire up here has been removed from the app)."
echo "  Set up the Brokeret stream by hand — see SETUP_GUIDE.md, or migrate"
echo "  this deployment to ../install.sh, which does it automatically."

echo
echo "== 8/9: Cron (Laravel scheduler) =="
# routes/console.php has Schedule::command(...) entries (plans:mature,
# p2p:expire-trades) — nothing runs them without this.
CRON_LINE="* * * * * cd $APP_ROOT && php artisan schedule:run >> /dev/null 2>&1"
( crontab -u "$APP_USER" -l 2>/dev/null | grep -vF "$APP_ROOT && php artisan schedule:run" ; echo "$CRON_LINE" ) \
    | crontab -u "$APP_USER" -

echo
echo "== 9/9: nginx =="
if [ -n "$DOMAIN" ]; then
    sed -e "s/example\.com/$DOMAIN/g" \
        -e "s|/var/www/pocketapp|$APP_ROOT|g" \
        -e "s|php8\.3-fpm|php${PHP_VERSION}-fpm|g" \
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
echo "  tail -f $APP_ROOT/storage/logs/reverb.log"
echo "  tail -f $APP_ROOT/storage/logs/queue-worker.log"
echo
echo "Next: create your first account at the site, then make it an admin:"
echo "  cd $APP_ROOT && php artisan tinker --execute=\"App\\Models\\User::where('email','you@example.com')->update(['is_admin' => true]);\""
