#!/usr/bin/env bash
#
# Idempotent setup/start for the whole live-price-streaming pipeline:
# collector (Node/Playwright, scrapes iqcent) -> Laravel (ingest + broadcast)
# -> Reverb (WebSocket server) -> browser (Echo). Safe to re-run any time —
# every step checks before it acts.
#
# Assumes: code is already deployed/pulled to this directory, composer
# dependencies are installed, and you're running this as root (or with sudo)
# on the actual VPS. Does NOT touch nginx config for you — that step still
# needs a manual look since your vhost file's exact path/contents aren't
# something this script can safely guess at.
#
# Usage: sudo ./deploy/setup-streaming.sh

set -euo pipefail

APP_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
cd "$APP_ROOT"

if [ ! -f artisan ]; then
    echo "ERROR: no 'artisan' found in $APP_ROOT — run this from inside the Laravel app." >&2
    exit 1
fi

ENV_FILE="$APP_ROOT/.env"
if [ ! -f "$ENV_FILE" ]; then
    echo "ERROR: $ENV_FILE not found — create it first (copy .env.example and configure it)." >&2
    exit 1
fi

cp "$ENV_FILE" "$ENV_FILE.bak.$(date +%Y%m%d%H%M%S)"
echo "== Backed up .env before making any changes =="

get_env() { grep -E "^$1=" "$ENV_FILE" | tail -1 | cut -d '=' -f2- | sed 's/^"//; s/"$//'; }
set_env() {
    local key="$1" val="$2"
    if grep -qE "^$key=" "$ENV_FILE"; then
        # Use a different sed delimiter (|) since values may contain slashes (URLs).
        sed -i "s|^$key=.*|$key=$val|" "$ENV_FILE"
    else
        echo "$key=$val" >> "$ENV_FILE"
    fi
}
random_hex() { php -r 'echo bin2hex(random_bytes(16));'; }

echo
echo "== 1. PRICE_COLLECTOR_SECRET =="
SECRET="$(get_env PRICE_COLLECTOR_SECRET)"
if [ -z "$SECRET" ]; then
    SECRET="$(random_hex)"
    set_env PRICE_COLLECTOR_SECRET "$SECRET"
    echo "  generated a new one and added it to .env"
else
    echo "  already set, leaving as-is"
fi

echo
echo "== 2. BROADCAST_CONNECTION =="
# Everything else in this app (Reverb process, REVERB_* config, echo.js)
# assumes broadcasting actually goes over Reverb. If this is still 'log' or
# 'null' (the framework/dev default), broadcast() calls succeed silently
# and just write to the log file (or do nothing) — the collector can be
# running perfectly and the frontend subscribed correctly, and still
# nothing ever arrives, because it was never actually sent anywhere.
BROADCAST_CUR="$(get_env BROADCAST_CONNECTION)"
if [ "$BROADCAST_CUR" != "reverb" ]; then
    set_env BROADCAST_CONNECTION reverb
    echo "  was '$BROADCAST_CUR' — set to 'reverb'"
else
    echo "  already 'reverb'"
fi

echo
echo "== 3. Reverb app credentials =="
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
echo "== 4. Reverb host/scheme (what the BROWSER connects to) =="
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
        # Mirror via phpdotenv interpolation (matches this repo's existing
        # .env convention) so these can never drift from the values above.
        set_env VITE_REVERB_HOST '"${REVERB_HOST}"'
        set_env VITE_REVERB_SCHEME '"${REVERB_SCHEME}"'
        set_env VITE_REVERB_PORT '"${REVERB_PORT}"'
        echo "  REVERB_HOST was '$REVERB_HOST_CUR' (unreachable from a browser) — set to '$HOST_FROM_URL', derived from APP_URL"
    else
        echo "  WARNING: could not derive a host from APP_URL ('$APP_URL') — set REVERB_HOST/VITE_REVERB_HOST manually to your real domain." >&2
    fi
else
    echo "  REVERB_HOST already set to '$REVERB_HOST_CUR'"
fi

echo
echo "== 5. Clearing cached config =="
# If `php artisan config:cache` was ever run, every env() call outside of
# config/*.php files keeps returning the OLD values baked into that cache —
# .env edits above would silently have no effect until this runs.
php artisan config:clear

echo
echo "== 6. storage:link =="
if [ -L "$APP_ROOT/public/storage" ]; then
    echo "  already linked"
else
    php artisan storage:link
fi

echo
echo "== 7. Collector dependencies (Node + Playwright/Chromium) =="
(cd "$APP_ROOT/collector" && npm install)

echo
echo "== 8. Installing/updating supervisor programs =="
if command -v supervisorctl >/dev/null 2>&1; then
    SECRET_NOW="$(get_env PRICE_COLLECTOR_SECRET)"
    sed "s/__PRICE_COLLECTOR_SECRET__/$SECRET_NOW/" \
        "$APP_ROOT/deploy/supervisor/pocketapp-price-collector.conf" \
        > /etc/supervisor/conf.d/pocketapp-price-collector.conf
    cp "$APP_ROOT/deploy/supervisor/pocketapp-reverb.conf" /etc/supervisor/conf.d/pocketapp-reverb.conf
    cp "$APP_ROOT/deploy/supervisor/pocketapp-queue-worker.conf" /etc/supervisor/conf.d/pocketapp-queue-worker.conf

    supervisorctl reread
    supervisorctl update
    supervisorctl restart pocketapp-reverb:* pocketapp-price-collector:* pocketapp-queue-worker:* || true
else
    echo "  WARNING: supervisorctl not found — install/start supervisor yourself, then copy the confs from deploy/supervisor/ into /etc/supervisor/conf.d/ (substituting PRICE_COLLECTOR_SECRET in the price-collector one)." >&2
fi

echo
echo "== 9. Rebuilding frontend assets =="
# VITE_* vars are baked into the JS at build time — none of the .env fixes
# above reach the browser until this runs.
npm install
npm run build

echo
echo "== 10. Running database migrations =="
php artisan migrate --force

echo
echo "== 11. nginx WebSocket proxy check =="
if command -v nginx >/dev/null 2>&1; then
    if grep -rq "location /app/" /etc/nginx/sites-enabled/ 2>/dev/null; then
        echo "  found a 'location /app/' block already — assuming Reverb proxying is configured."
    else
        echo "  WARNING: no 'location /app/' block found in /etc/nginx/sites-enabled/." >&2
        echo "  Add the block from deploy/nginx/reverb-proxy.conf.example to your site's server{} block," >&2
        echo "  then run: sudo nginx -t && sudo systemctl reload nginx" >&2
    fi
else
    echo "  nginx not found on this machine — skipping (fine if it runs elsewhere/isn't used)."
fi

echo
echo "== Done. Current process status: =="
command -v supervisorctl >/dev/null 2>&1 && supervisorctl status || true

echo
echo "== Next: check these logs, then reload the site and check browser devtools console for [echo] lines =="
echo "  tail -50 $APP_ROOT/storage/logs/price-collector.log"
echo "  tail -50 $APP_ROOT/storage/logs/reverb.log"
