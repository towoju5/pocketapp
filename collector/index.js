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
const COLLECTOR_SECRET = process.env.PRICE_COLLECTOR_SECRET ?? "31b9d4d7812efd3021af7fc354efe33c0453d4eeee743aeb";
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
let relaunching = false; // guards the reactive (stall/crash-triggered) full relaunch
let warmSwapping = false; // guards the proactive overlap-relaunch below
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
 * flagged page to recover. This is the reactive fallback; warmSwap() below
 * is what's meant to keep this from ever actually needing to fire.
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
    const res = await fetch(`${APP_URL}/api/internal/assets/symbols`, {
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
        const res = await fetch(`${APP_URL}/api/internal/assets/ticks`, {
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

/**
 * Builds a fully-subscribed browser+page and hands it back — deliberately
 * does NOT touch the shared `browser`/`page` globals or attach any
 * close/crash handling. This lets warmSwap() run a brand-new page fully
 * alongside the currently-active one (both delivering real ticks) and only
 * cut over once the new one is confirmed live, instead of the old
 * close-then-relaunch approach which always had a dead window while the new
 * browser spun up from scratch.
 */
async function spawnPage() {
    const newBrowser = await chromium.launch({
        headless: true,
        args: ['--no-sandbox', '--disable-dev-shm-usage'],
    });
    const context = await newBrowser.newContext();
    const newPage = await context.newPage();
    newPage.on('pageerror', (err) => console.error('[collector:page] ERROR', err.message));

    await newPage.exposeFunction('__collectorReportTick', (symbol, price, t) => {
        bufferTick(symbol, price, t);
    });

    // iqcent's SUBSCRIBE.TICK has no visible ack/reject in the message
    // shapes seen so far (onmessage only ever recognized {p,t,s} tick
    // pushes) — logging any other shape surfaces one if it exists, instead
    // of it being silently dropped like every non-tick message currently is.
    const seenUnknownShapes = new Set();
    await newPage.exposeFunction('__collectorReportUnknownMessage', (raw) => {
        const shape = Object.keys(JSON.parse(raw)).sort().join(',') || '(non-object)';
        if (seenUnknownShapes.has(shape) || seenUnknownShapes.size >= 10) return;
        seenUnknownShapes.add(shape);
        console.log(`[collector] unrecognized WS message shape [${shape}]: ${raw.slice(0, 300)}`);
    });

    // Whether a subscription actually "succeeded" isn't something iqcent
    // confirms directly — the only signal available is whether a tick for
    // that symbol shows up afterward. Reports back which subscribed symbols
    // did/didn't tick within the window below, every time a batch is
    // subscribed (initial catalog AND later incremental adds).
    await newPage.exposeFunction('__collectorReportSubscriptionResult', (label, successful, failed) => {
        console.log(`[collector] ${label} subscription result: ${successful.length} confirmed ticking, ${failed.length} silent so far (of ${successful.length + failed.length})`);
        if (successful.length) console.log(`[collector]   ticking: ${successful.join(', ')}`);
        if (failed.length) console.warn(`[collector]   silent (rejected, rate-limited, or just not trading right now): ${failed.join(', ')}`);
    });

    // Navigate to iqcent's own origin first so the WS connection presents
    // exactly like a real visitor's browser tab instead of a blank page.
    await newPage.goto(IQCENT_ORIGIN, { waitUntil: 'domcontentloaded', timeout: 30000 }).catch((e) => {
        console.error('[collector] navigation to iqcent failed (continuing anyway)', e.message);
    });

    const subscribedSymbols = await fetchSymbols();
    console.log(`[collector] subscribing to ${subscribedSymbols.length} symbols`);

    await newPage.evaluate((initialSymbols) => {
        // iqcent appears to cap how many SUBSCRIBE.TICK subscriptions a
        // single WS connection actually honors — verified empirically: one
        // connection subscribing to the full ~158-symbol catalog only ever
        // got ticks for ~16 of them (reproducible), regardless of how the
        // sends were paced (tight loop vs staggered barely moved the
        // number). Splitting the catalog across a pool of connections
        // instead got 127/158 at 8 connections and 133/158 at 16 — a real
        // per-connection ceiling, not a send-rate issue. 16 connections also
        // measurably destabilized the pool (stalls roughly every ~2min vs
        // ~4min at 1 connection) — presumably more simultaneous connections
        // from one IP/session reads as more suspicious, not less. 8 trades
        // a small amount of coverage for materially better stability.
        const NUM_CONNECTIONS = 8;
        const connections = []; // index -> { ws, symbols: Set<string>, backoffMs }
        const tickedSymbols = new Set(); // every symbol that has ever produced at least one tick

        // Checks which of `ids` are (not) in tickedSymbols yet and reports
        // the split back to Node. Fires VERIFY_DELAY_MS after a subscribe
        // batch is sent — long enough for a normally-ticking asset to have
        // produced at least one tick, short enough to still be a useful
        // per-batch signal rather than a lifetime one.
        const VERIFY_DELAY_MS = 30000;
        function verifySubscriptions(ids, label) {
            setTimeout(() => {
                const successful = ids.filter((id) => tickedSymbols.has(id));
                const failed = ids.filter((id) => !tickedSymbols.has(id));
                window.__collectorReportSubscriptionResult(label, successful, failed);
            }, VERIFY_DELAY_MS);
        }

        function connectPoolMember(idx) {
            const conn = connections[idx];
            const ws = new WebSocket('wss://iqcent.com/trade-api-ws/api/ws/price');
            conn.ws = ws;
            ws.onopen = () => {
                conn.backoffMs = 3000; // reset backoff on a successful handshake
                conn.symbols.forEach((id) => {
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
                if (!msg || typeof msg.p !== 'number' || typeof msg.t !== 'number' || !msg.s) {
                    window.__collectorReportUnknownMessage(event.data);
                    return;
                }
                tickedSymbols.add(msg.s);
                window.__collectorReportTick(msg.s, msg.p, msg.t);
            };
            // Exponential backoff (capped) instead of a fixed fast retry — a
            // tight reconnect loop looks more automated, not less, to
            // whatever flagged the connection in the first place.
            ws.onclose = () => {
                setTimeout(() => connectPoolMember(idx), conn.backoffMs);
                conn.backoffMs = Math.min(conn.backoffMs * 2, 30000);
            };
            ws.onerror = () => {};
        }

        for (let i = 0; i < NUM_CONNECTIONS; i++) {
            connections.push({ ws: null, symbols: new Set(), backoffMs: 3000 });
        }
        initialSymbols.forEach((id, i) => connections[i % NUM_CONNECTIONS].symbols.add(id));
        connections.forEach((_, idx) => connectPoolMember(idx));
        verifySubscriptions(initialSymbols, 'initial');

        window.__collectorSubscribe = (ids) => {
            ids.forEach((id) => {
                // Add each new symbol to whichever pool connection currently
                // holds the fewest, keeping the pool balanced over time.
                let target = connections[0];
                connections.forEach((c) => { if (c.symbols.size < target.symbols.size) target = c; });
                target.symbols.add(id);
                if (target.ws && target.ws.readyState === WebSocket.OPEN) {
                    target.ws.send(JSON.stringify({ id, param: 'Option', operation: 'SUBSCRIBE.TICK' }));
                }
            });
            verifySubscriptions(ids, 'incremental');
        };
    }, subscribedSymbols);

    return { browser: newBrowser, page: newPage, symbols: subscribedSymbols };
}

/**
 * Makes `candidate` the active page: swaps the shared globals over to it and
 * attaches close/crash handling. The handler checks page identity (not a
 * boolean flag) so it can tell a deliberate hand-off (an older page closing
 * because warmSwap() retired it) apart from a real unexpected death of the
 * page that's actually current at the time — the old approach's global
 * `relaunching` flag couldn't make that distinction for overlapping pages.
 */
function promote(candidate) {
    browser = candidate.browser;
    page = candidate.page;
    symbols = candidate.symbols;
    lastTickAt = Date.now(); // give the fresh page a full stall window before judging it

    const thisPage = page;
    page.once('close', () => {
        if (shuttingDown || page !== thisPage) return; // expected — a newer page already took over
        console.error('[collector] active page closed unexpectedly, relaunching...');
        setTimeout(relaunch, 2000);
    });
    page.once('crash', () => {
        if (shuttingDown || page !== thisPage) return;
        console.error('[collector] active page crashed, relaunching...');
        setTimeout(relaunch, 2000);
    });
}

async function launch() {
    console.log('[collector] launching headless browser...');
    const candidate = await spawnPage();
    promote(candidate);
    scheduleNextWarmSwap();
}

let lastRelaunchAt = 0;
const RELAUNCH_MIN_GAP_MS = 5000;

/** Reactive fallback: something actually died unexpectedly. Closes it and starts fresh — necessarily has a gap, unlike warmSwap(). */
async function relaunch() {
    if (relaunching) return;
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

/**
 * Proactive fix for the actual root cause: empirically the pool goes quiet
 * ~150s after a fresh subscribe regardless of anything tried at the
 * individual-connection level (staggered starts, rotating connections one at
 * a time) — strongly suggesting whatever flags it is scoped to the *page's*
 * browser session/fingerprint, not each WebSocket's own lifetime. So instead
 * of waiting for that cliff and paying for a cold relaunch (browser boot +
 * subscribe, with zero ticks flowing the whole time), spin up a full
 * replacement page well before the cliff, let it run side-by-side with the
 * still-healthy current one, and only then cut over — the audience never
 * sees a gap because the outgoing page is still ticking right up until the
 * moment the incoming one takes over.
 */
const WARM_SWAP_INTERVAL_MS = 100000; // comfortably under the observed ~150s cliff

function scheduleNextWarmSwap() {
    setTimeout(warmSwap, WARM_SWAP_INTERVAL_MS);
}

async function warmSwap() {
    if (shuttingDown || warmSwapping || relaunching) {
        scheduleNextWarmSwap();
        return;
    }
    warmSwapping = true;
    console.log('[collector] warm-swapping to a fresh page ahead of the usual stall window...');
    try {
        const candidate = await spawnPage();
        const oldBrowser = browser;
        promote(candidate);
        console.log('[collector] warm swap complete, retiring previous page');
        // Give it a moment in case anything was still mid-flight, then let it go.
        setTimeout(() => {
            oldBrowser.close().catch(() => {});
        }, 3000);
    } catch (e) {
        console.error('[collector] warm swap failed, keeping current page', e.message);
        scheduleNextWarmSwap();
    } finally {
        warmSwapping = false;
    }
}

/**
 * A self-scheduling loop, not setInterval — setInterval fires on a fixed
 * clock regardless of whether the previous flush's request already
 * completed. If the server ever takes longer than FLUSH_INTERVAL_MS to
 * process a batch (very possible under this SQLite-backed cache with pooled
 * connections now sending far more volume), requests start overlapping and
 * stack up, and concurrent writers to the same SQLite file can commit out of
 * order — an older tick's transaction finishing after a newer one's silently
 * overwrites it with a stale price/timestamp. Awaiting each flush fully
 * before scheduling the next guarantees the collector itself never has more
 * than one ingest request in flight.
 */
async function flushLoop() {
    while (!shuttingDown) {
        await flushTicks();
        await new Promise((r) => setTimeout(r, FLUSH_INTERVAL_MS));
    }
}

launch()
    .then(() => {
        scheduleSymbolRefresh();
        startStallWatchdog();
        flushLoop();
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
