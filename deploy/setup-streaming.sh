#!/usr/bin/env bash
#
# Wires up ONLY the live-chart streaming pipeline (Reverb + the ticker
# collector) on a server where pocketapp is already deployed and working —
# code checked out, DB migrated/linked, queue workers already running
# everything else. For a server that also hosts other, unrelated sites/domains
# — this script never touches anything belonging to another site: no system
# package installs beyond a browser for the collector, no nginx vhost is
# created or rewritten (just checked, with instructions printed if something's
# missing), and Supervisor commands are scoped to exactly
# `pocketapp-reverb:*` / `pocketapp-ticker-collector:*` — never a blanket
# restart that could disturb another site's worker/daemon on the same box.
#
# (For bootstrapping a brand new VPS from scratch instead, see
# deploy/setup.sh.)
#
# Safe to re-run any time — every step checks before it acts.
#
# Usage (as root, from the app directory):
#   cd /var/www/pocketapp   # wherever this app actually lives
#   sudo ./deploy/setup-streaming.sh

set -euo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_ROOT"

if [ "$(id -u)" -ne 0 ]; then
    echo "ERROR: run this as root (sudo ./deploy/setup-streaming.sh)." >&2
    exit 1
fi

if [ ! -f artisan ]; then
    echo "ERROR: no 'artisan' found in $APP_ROOT — run this from inside the deployed app." >&2
    exit 1
fi

ENV_FILE="$APP_ROOT/.env"
if [ ! -f "$ENV_FILE" ]; then
    echo "ERROR: $ENV_FILE not found — this expects an already-configured deployment." >&2
    exit 1
fi

cp "$ENV_FILE" "$ENV_FILE.bak.$(date +%Y%m%d%H%M%S)"
echo "== Backed up .env before making any changes =="

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

echo
echo "== 1. BROADCAST_CONNECTION =="
# Everything else here assumes broadcasting actually goes over Reverb. If
# this is still 'log' or 'null', broadcast() calls succeed silently and
# either write to the log file or do nothing — the collector can be running
# perfectly and the frontend subscribed correctly, and still nothing ever
# arrives, because it was never actually sent anywhere.
BROADCAST_CUR="$(get_env BROADCAST_CONNECTION)"
if [ "$BROADCAST_CUR" != "reverb" ]; then
    set_env BROADCAST_CONNECTION reverb
    echo "  was '$BROADCAST_CUR' — set to 'reverb'"
else
    echo "  already 'reverb'"
fi

echo
echo "== 2. Reverb app credentials =="
for KEY_VAR in REVERB_APP_ID REVERB_APP_KEY REVERB_APP_SECRET; do
    CUR="$(get_env "$KEY_VAR")"
    if [ -z "$CUR" ]; then
        NEW="$(random_hex)"
        set_env "$KEY_VAR" "$NEW"
        echo "  $KEY_VAR was empty — generated one"
    else
        echo "  $KEY_VAR already set"
    fi
done
# The frontend's key must match the server app's key for the WS handshake
# to authenticate at all.
set_env VITE_REVERB_APP_KEY '"${REVERB_APP_KEY}"'

echo
echo "== 3. Reverb host/scheme (what the BROWSER connects to) =="
APP_URL="$(get_env APP_URL)"
REVERB_HOST_CUR="$(get_env REVERB_HOST)"
if [ -z "$REVERB_HOST_CUR" ] || [ "$REVERB_HOST_CUR" = "localhost" ] || [ "$REVERB_HOST_CUR" = "127.0.0.1" ]; then
    HOST_FROM_URL="$(php -r '$u=parse_url($argv[1]); echo $u["host"] ?? "";' "$APP_URL")"
    SCHEME_FROM_URL="$(php -r '$u=parse_url($argv[1]); echo (($u["scheme"] ?? "https") === "https") ? "https" : "http";' "$APP_URL")"
    PORT_FROM_SCHEME="80"; [ "$SCHEME_FROM_URL" = "https" ] && PORT_FROM_SCHEME="443"

    if [ -n "$HOST_FROM_URL" ]; then
        set_env REVERB_HOST "$HOST_FROM_URL"
        set_env REVERB_SCHEME "$SCHEME_FROM_URL"
        set_env REVERB_PORT "$PORT_FROM_SCHEME"
        set_env VITE_REVERB_HOST '"${REVERB_HOST}"'
        set_env VITE_REVERB_SCHEME '"${REVERB_SCHEME}"'
        set_env VITE_REVERB_PORT '"${REVERB_PORT}"'
        echo "  REVERB_HOST was '$REVERB_HOST_CUR' (unreachable from a browser) — set to '$HOST_FROM_URL', derived from APP_URL"
    else
        echo "  WARNING: could not derive a host from APP_URL ('$APP_URL') — set REVERB_HOST/VITE_REVERB_HOST manually to this site's real domain." >&2
    fi
else
    echo "  REVERB_HOST already set to '$REVERB_HOST_CUR'"
fi

echo
echo "== 4. Clearing cached config =="
# If `php artisan config:cache` was ever run, every env() call outside of
# config/*.php files keeps returning the OLD values baked into that cache —
# the .env edits above would silently have no effect until this runs.
php artisan config:clear

echo
echo "== 5. Google Chrome (the browser TickerController drives) =="
# The chromedriver BINARY is already committed at drivers/chromedriver —
# only the browser itself is a real system dependency here. Installing this
# is a plain system package and doesn't touch any other site on this box.
if command -v google-chrome-stable >/dev/null 2>&1 || command -v google-chrome >/dev/null 2>&1; then
    echo "  already installed"
else
    if command -v apt-get >/dev/null 2>&1; then
        curl -fsSL -o /tmp/chrome.deb https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb
        apt-get update -y && apt-get install -y /tmp/chrome.deb || apt-get -f install -y
        rm -f /tmp/chrome.deb
    else
        echo "  WARNING: no apt-get on this system — install Google Chrome or Chromium yourself, then re-run." >&2
    fi
fi
chmod +x "$APP_ROOT/drivers/chromedriver"
CHROME_BIN="$(command -v google-chrome-stable || command -v google-chrome || true)"
if [ -n "$CHROME_BIN" ]; then
    echo "  chrome:       $("$CHROME_BIN" --version)"
    echo "  chromedriver: $("$APP_ROOT/drivers/chromedriver" --version)"
    echo "  (major versions should roughly match — if the collector can't start, regenerate with: composer install && vendor/bin/bdi detect drivers)"
fi

echo
echo "== 6. Asset catalog =="
DB_PATH="$(get_env DB_DATABASE)"
DB_PATH="${DB_PATH:-$APP_ROOT/database/database.sqlite}"
ASSET_COUNT="$(sqlite3 "$DB_PATH" "SELECT COUNT(*) FROM assets;" 2>/dev/null || echo 0)"
if [ "${ASSET_COUNT:-0}" -eq 0 ]; then
    echo "  assets table is empty — seeding (AssetSeeder)"
    php artisan db:seed --force
    ASSET_COUNT="$(sqlite3 "$DB_PATH" "SELECT COUNT(*) FROM assets;" 2>/dev/null || echo 0)"
else
    echo "  $ASSET_COUNT assets already present — nothing to seed"
fi

echo
echo "== 7. Rebuilding frontend assets =="
# VITE_REVERB_* values are baked into the JS at build time — none of the
# .env fixes above reach the browser until this runs.
npm install
npm run build

echo
echo "== 8. Ownership of writable dirs =="
if id www-data >/dev/null 2>&1; then
    chown -R www-data:www-data "$APP_ROOT/storage" "$APP_ROOT/bootstrap/cache" "$APP_ROOT/database"
fi

echo
echo "== 9. Installing/updating Supervisor programs (scoped to this app only) =="
if command -v supervisorctl >/dev/null 2>&1; then
    sed -e "s|/var/www/pocketapp|$APP_ROOT|g" \
        "$APP_ROOT/deploy/supervisor/pocketapp-reverb.conf" \
        > /etc/supervisor/conf.d/pocketapp-reverb.conf

    BATCH_SIZE=10
    NUMPROCS=$(( (ASSET_COUNT + BATCH_SIZE - 1) / BATCH_SIZE ))
    [ "$NUMPROCS" -lt 1 ] && NUMPROCS=1
    if [ -z "$CHROME_BIN" ]; then
        echo "  WARNING: pinning PANTHER_CHROME_BINARY to a Chrome that wasn't found — the collector will fail to start until a real (non-snap) Chrome is installed." >&2
    fi
    sed -e "s|/var/www/pocketapp|$APP_ROOT|g" -e "s/^numprocs=.*/numprocs=$NUMPROCS/" \
        -e "s|__PANTHER_CHROME_BINARY__|$CHROME_BIN|g" \
        "$APP_ROOT/deploy/supervisor/pocketapp-ticker-collector.conf" \
        > /etc/supervisor/conf.d/pocketapp-ticker-collector.conf
    echo "  ticker collector: $NUMPROCS process(es) (batch size $BATCH_SIZE, $ASSET_COUNT assets)"

    supervisorctl reread
    supervisorctl update
    # Deliberately scoped to just these two program groups — this box may be
    # running Supervisor programs for other sites; a blanket
    # `supervisorctl restart` would restart those too.
    supervisorctl restart pocketapp-reverb:* pocketapp-ticker-collector:* || \
        supervisorctl start pocketapp-reverb:* pocketapp-ticker-collector:*
else
    echo "  WARNING: supervisorctl not found — install Supervisor, then copy deploy/supervisor/pocketapp-reverb.conf and deploy/supervisor/pocketapp-ticker-collector.conf into /etc/supervisor/conf.d/ (substituting the app path and numprocs) yourself." >&2
fi

echo
echo "== 10. nginx WebSocket proxy check =="
# Deliberately does NOT write to any nginx config — this box likely serves
# other domains from other vhost files, and blindly editing one is exactly
# the kind of thing that's easy to get wrong on a shared box. Just checks,
# and tells you precisely what to add and where if it's missing.
DOMAIN_HOST="$(php -r '$u=parse_url($argv[1]); echo $u["host"] ?? "";' "$(get_env APP_URL)")"
if command -v nginx >/dev/null 2>&1; then
    MATCHED_CONF="$(grep -rl "server_name[^;]*\b$DOMAIN_HOST\b" /etc/nginx/sites-enabled/ 2>/dev/null | head -1 || true)"
    if [ -n "$MATCHED_CONF" ] && grep -q "location /app/" "$MATCHED_CONF"; then
        echo "  found the Reverb proxy block already in $MATCHED_CONF"
    else
        echo "  WARNING: no 'location /app/' Reverb proxy block found for $DOMAIN_HOST." >&2
        if [ -n "$MATCHED_CONF" ]; then
            echo "  Add the two location blocks from deploy/nginx/reverb-proxy.conf.example into the existing" >&2
            echo "  server { } block in: $MATCHED_CONF" >&2
        else
            echo "  Couldn't find which vhost file serves $DOMAIN_HOST under /etc/nginx/sites-enabled/ —" >&2
            echo "  add the two location blocks from deploy/nginx/reverb-proxy.conf.example to whichever file does." >&2
        fi
        echo "  Then: sudo nginx -t && sudo systemctl reload nginx" >&2
    fi
else
    echo "  nginx not found on this machine — skipping (fine if it runs elsewhere/isn't used)."
fi

echo
echo "== Done. Current status of this app's processes: =="
command -v supervisorctl >/dev/null 2>&1 && supervisorctl status pocketapp-reverb:\* pocketapp-ticker-collector:\* || true

echo
echo "== Next: check these logs, then reload the site and check browser devtools console for [echo] lines =="
echo "  tail -50 $APP_ROOT/storage/logs/ticker-collector.log"
echo "  tail -50 $APP_ROOT/storage/logs/reverb.log"
