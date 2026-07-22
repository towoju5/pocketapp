'use strict';

/**
 * Backend price collector.
 *
 * Holds the one real connection to iqcent's price WebSocket and relays
 * ticks into the Laravel app, which broadcasts them out to every connected
 * user over the app's own Reverb WebSocket (see AssetPriceBatchUpdated and
 * resources/js/trading/chart.js). This is what makes every user's chart show
 * the exact same stream instead of each browser connecting to iqcent
 * directly, and lets the backend track real online/offline status per asset.
 *
 * Why a headless *browser* instead of a plain WS/HTTP client: iqcent sits
 * behind Cloudflare, which 403s any non-browser connection attempt (verified
 * against both a PHP client with spoofed browser headers and a plain Node
 * `ws` client — both rejected before the handshake completes). A real
 * browser engine passes those checks, so this process runs one permanently
 * via Playwright instead of trying to fake it at the protocol level.
 *
 * Ticks are batched, not sent one request per tick: subscribing to the full
 * ~150-asset catalog at native tick rate would mean well over a hundred
 * requests/sec, and this app's dev server (`php artisan serve`) has no
 * concurrency headroom for that — confirmed empirically, even ~30 assets'
 * worth of per-tick requests backed up its connection queue. Ticks are
 * buffered in memory and flushed as one request per interval instead, so
 * request *count* stays low regardless of how many assets are streaming or
 * how fast they tick. A production php-fpm/Octane deployment doesn't need
 * this, but it costs nothing there either.
 *
 * Run via Supervisor (see deploy/supervisor/pocketapp-price-collector.conf) —
 * this is an infinite process, not a one-shot script.
 */

const { chromium } = require('playwright');

const APP_URL = (process.env.APP_URL || 'http://127.0.0.1:8001').replace(/\/$/, '');
const COLLECTOR_SECRET = process.env.PRICE_COLLECTOR_SECRET;
const IQCENT_ORIGIN = 'https://iqcent.com';
const FLUSH_INTERVAL_MS = 300;
const SYMBOL_REFRESH_MS = 10 * 60 * 1000;

if (!COLLECTOR_SECRET) {
    console.error('[collector] PRICE_COLLECTOR_SECRET is not set in the environment — refusing to start.');
    process.exit(1);
}

let browser = null;
let page = null;
let symbols = [];
let shuttingDown = false;
let relaunching = false;
let lastTickAt = Date.now();
let tickBuffer = [];
let forwardedCount = 0;

setInterval(() => {
    console.log(`[collector] forwarded ${forwardedCount} ticks in the last 30s`);
    forwardedCount = 0;
}, 30000);

/**
 * If real ticks stop arriving for too long, the in-page WS reconnect loop
 * isn't recovering on its own (observed: iqcent/Cloudflare can start
 * rejecting an existing session's reconnect attempts after enough activity,
 * and a fixed fast retry loop doesn't get past that) — force a full
 * relaunch for a clean browser fingerprint instead of trusting the same
 * flagged page to recover.
 */
const STALL_THRESHOLD_MS = 60000;
function startStallWatchdog() {
    setInterval(() => {
        if (shuttingDown || relaunching) return;
        if (Date.now() - lastTickAt > STALL_THRESHOLD_MS) {
            console.error(`[collector] no ticks in over ${STALL_THRESHOLD_MS / 1000}s — forcing a full relaunch`);
            relaunch();
        }
    }, 15000);
}

async function fetchSymbols() {
    const res = await fetch(`${APP_URL}/internal/assets/symbols`, {
        headers: { 'X-Collector-Secret': COLLECTOR_SECRET },
    });
    if (!res.ok) throw new Error(`symbols fetch failed: HTTP ${res.status}`);
    const all = await res.json();

    // Explicit override — an exact symbol list, regardless of DB order.
    // Only for manually verifying a specific symbol streams correctly;
    // unset in normal use (subscribes to the full catalog).
    const explicit = (process.env.PRICE_COLLECTOR_SYMBOL_LIST || '').split(',').map((s) => s.trim()).filter(Boolean);
    if (explicit.length) return all.filter((s) => explicit.includes(s));

    // Optional cap — not needed against a real php-fpm/Octane deployment,
    // just a debug knob for isolating issues against this dev sandbox.
    const limit = parseInt(process.env.PRICE_COLLECTOR_SYMBOL_LIMIT || '0', 10);
    return limit > 0 ? all.slice(0, limit) : all;
}

function bufferTick(symbol, price, t) {
    lastTickAt = Date.now();
    tickBuffer.push({ symbol, price, t });
}

async function flushTicks() {
    if (!tickBuffer.length) return;
    const batch = tickBuffer;
    tickBuffer = [];

    try {
        const res = await fetch(`${APP_URL}/internal/assets/ticks`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Collector-Secret': COLLECTOR_SECRET,
            },
            body: JSON.stringify({ ticks: batch }),
        });
        if (res.ok) {
            forwardedCount += batch.length;
        } else {
            console.error(`[collector] batch forward rejected: HTTP ${res.status} (${batch.length} ticks dropped)`);
        }
    } catch (e) {
        console.error(`[collector] batch forward failed: ${e.message} (${batch.length} ticks dropped)`);
    }
}

/** Periodically re-pull the tradable symbol list and subscribe to anything new, without restarting the browser. */
function scheduleSymbolRefresh() {
    setInterval(async () => {
        try {
            const fresh = await fetchSymbols();
            const known = new Set(symbols);
            const added = fresh.filter((s) => !known.has(s));
            symbols = fresh;
            if (added.length && page) {
                console.log(`[collector] subscribing to ${added.length} newly-added symbol(s)`);
                await page.evaluate((newSymbols) => {
                    if (window.__collectorSubscribe) window.__collectorSubscribe(newSymbols);
                }, added);
            }
        } catch (e) {
            console.error('[collector] symbol refresh failed', e.message);
        }
    }, SYMBOL_REFRESH_MS);
}

async function launch() {
    console.log('[collector] launching headless browser...');
    browser = await chromium.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-dev-shm-usage'],
    });
    const context = await browser.newContext();
    page = await context.newPage();
    page.on('pageerror', (err) => console.error('[collector:page] ERROR', err.message));

    await page.exposeFunction('__collectorReportTick', (symbol, price, t) => {
        bufferTick(symbol, price, t);
    });

    // Navigate to iqcent's own origin first so the WS connection presents
    // exactly like a real visitor's browser tab instead of a blank page.
    await page.goto(IQCENT_ORIGIN, { waitUntil: 'domcontentloaded', timeout: 30000 }).catch((e) => {
        console.error('[collector] navigation to iqcent failed (continuing anyway)', e.message);
    });

    symbols = await fetchSymbols();
    console.log(`[collector] subscribing to ${symbols.length} symbols`);

    await page.evaluate((initialSymbols) => {
        let ws = null;
        let subscribedIds = new Set();

        let backoffMs = 3000;
        function connect() {
            ws = new WebSocket('wss://iqcent.com/trade-api-ws/api/ws/price');
            ws.onopen = () => {
                backoffMs = 3000; // reset backoff on a successful handshake
                subscribedIds.forEach((id) => {
                    ws.send(JSON.stringify({ id, param: 'Option', operation: 'SUBSCRIBE.TICK' }));
                });
            };
            ws.onmessage = (event) => {
                let msg;
                try {
                    msg = JSON.parse(event.data);
                } catch (e) {
                    return;
                }
                // Tick pushes key the symbol as "s" (SUBSCRIBE.TICK requests use
                // "id" for which symbol to subscribe to — a different field on
                // the send side than what comes back on the receive side).
                if (!msg || typeof msg.p !== 'number' || typeof msg.t !== 'number' || !msg.s) return;
                window.__collectorReportTick(msg.s, msg.p, msg.t);
            };
            // Exponential backoff (capped) instead of a fixed fast retry — a
            // tight reconnect loop looks more automated, not less, to
            // whatever flagged the connection in the first place.
            ws.onclose = () => {
                setTimeout(connect, backoffMs);
                backoffMs = Math.min(backoffMs * 2, 30000);
            };
            ws.onerror = () => {};
        }

        window.__collectorSubscribe = (ids) => {
            ids.forEach((id) => subscribedIds.add(id));
            if (ws && ws.readyState === WebSocket.OPEN) {
                ids.forEach((id) => ws.send(JSON.stringify({ id, param: 'Option', operation: 'SUBSCRIBE.TICK' })));
            }
        };

        window.__collectorSubscribe(initialSymbols);
        connect();
    }, symbols);
    lastTickAt = Date.now(); // give the fresh connection a full stall window before judging it

    page.once('close', () => {
        // `relaunching` is already true for the entire duration of an
        // intentional relaunch()'s own `browser.close()` call — without this
        // check, that close firing THIS handler calls relaunch() again,
        // which calls relaunch() again, forever, on a ~2s cycle, regardless
        // of whether the new browser is healthy. Only a close nobody asked
        // for should trigger a new relaunch.
        if (shuttingDown || relaunching) return;
        console.error('[collector] page closed unexpectedly, relaunching...');
        setTimeout(relaunch, 2000);
    });
    page.once('crash', () => {
        if (shuttingDown || relaunching) return;
        console.error('[collector] page crashed, relaunching...');
        setTimeout(relaunch, 2000);
    });
}

let lastRelaunchAt = 0;
const RELAUNCH_MIN_GAP_MS = 5000;

async function relaunch() {
    if (relaunching) return;
    // Belt-and-suspenders against any other path that ends up calling this
    // repeatedly for a genuine, persistent reason — never spin faster than
    // this regardless of who's asking.
    const sinceLast = Date.now() - lastRelaunchAt;
    if (sinceLast < RELAUNCH_MIN_GAP_MS) {
        setTimeout(relaunch, RELAUNCH_MIN_GAP_MS - sinceLast);
        return;
    }
    lastRelaunchAt = Date.now();
    relaunching = true;
    console.log('[collector] relaunching...');
    try {
        if (browser) await browser.close();
    } catch (e) {
        // already gone
    }
    launch()
        .then(() => {
            relaunching = false;
            console.log('[collector] relaunch complete');
        })
        .catch((e) => {
            console.error('[collector] relaunch failed', e.message);
            relaunching = false;
            setTimeout(relaunch, 5000);
        });
}

launch()
    .then(() => {
        scheduleSymbolRefresh();
        startStallWatchdog();
        setInterval(flushTicks, FLUSH_INTERVAL_MS);
    })
    .catch((e) => {
        console.error('[collector] failed to start', e.message);
        process.exit(1);
    });

process.on('SIGTERM', async () => {
    console.log('[collector] SIGTERM received, shutting down...');
    shuttingDown = true;
    await flushTicks().catch(() => {});
    try {
        if (browser) await browser.close();
    } catch (e) {
        // ignore
    }
    process.exit(0);
});
