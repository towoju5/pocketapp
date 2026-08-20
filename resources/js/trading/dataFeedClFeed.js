/**
 * Direct browser -> datafeedcl.xyz (wss://datafeedcl.xyz/ws) live tick feed
 * for the trading dashboard's chart. Unlike the Brokeret pipeline (backend-
 * mediated — see StreamBrokeretFeed / TradingDashboard.js's legacy
 * 'brokeret-feed' Echo path), this connects straight from the browser:
 * datafeedcl.xyz's REST API sends no Access-Control-Allow-Origin header (so
 * the browser can't call it directly — the one-time symbol catalog stays
 * server-proxied, see HomeController / DataFeedClService::fetchSymbolCatalog),
 * but its WebSocket handshake enforces no such restriction, so live ticks
 * (and their own backfill) reach the tab with no backend relay in between.
 *
 * Protocol (confirmed against the live feed, not guessed):
 *   -> {"action": "subscribe", "asset": "EURUSD_otc"}
 *   <- {"type": "history", "asset": "EURUSD_otc", "ticks": [{"value": "1.23584", "timestamp": 1787151603.577}, ...]}
 *   <- {"type": "price", "asset": "EURUSD_otc", "value": "1.23602", "timestamp": 1787151628.706}
 * 'value' is a numeric string, 'timestamp' is epoch *seconds* (fractional).
 * There's no bid/ask spread in this feed — just one price — and no per-tick
 * category; 'history' is sent once, right after subscribing, as a backfill
 * of recent ticks, then 'price' streams continuously. datafeedcl pushes
 * nothing until asked, unlike Brokeret's firehose. That turns out to matter
 * beyond protocol shape: this class only ever subscribes to a symbol once a
 * chart tab is actually opened for it (see ChartManager's onOpenTab hook,
 * wired up in TradingDashboard._onChartTabOpened) — never the full
 * ~150-symbol catalog up front — so a customer idly browsing the asset
 * popover doesn't leave a few hundred live subscriptions running for markets
 * they never open. The asset popover itself is still fully browsable
 * immediately, seeded from the server-fetched catalog
 * (TradingDashboard._seedAssetCatalog) — that list needs no subscription,
 * only the live price/chart once something is actually opened does.
 *
 * No ticks are stored anywhere server-side for this feed (no Redis, no
 * background daemon) — 'history' is the only backfill source, kept
 * in-memory only for as long as a chart tab stays open (see
 * AssetFeed._rawHistory in chart.js).
 */
export class DataFeedClLiveFeed {
    constructor(wsUrl, { onTicks, onHistory, onStatusChange }) {
        this.wsUrl = wsUrl;
        this.onTicks = onTicks;
        this.onHistory = onHistory;
        this.onStatusChange = onStatusChange;
        this.ws = null;
        /** Symbols a chart tab actually wants ticks for — subscribed on connect/reconnect (see _connect) and the moment subscribe() is first called for a new one. */
        this._wanted = new Set();
        this._reconnectDelay = 2000;
        this._closed = false;
    }

    start() {
        this._connect();
    }

    /** Called once per symbol the moment a chart tab actually opens for it — see ChartManager's onOpenTab. Safe to call again for an already-subscribed symbol (no-op). Queues silently if the socket isn't open yet; flushed on the next 'open' (including after a reconnect). */
    subscribe(symbol) {
        if (!symbol || this._wanted.has(symbol)) return;
        this._wanted.add(symbol);
        this._send({ action: 'subscribe', asset: symbol });
    }

    _connect() {
        if (this._closed) return;
        this.onStatusChange('connecting');
        const ws = new WebSocket(this.wsUrl);
        this.ws = ws;

        ws.addEventListener('open', () => {
            this._reconnectDelay = 2000;
            this.onStatusChange('live');
            // Re-subscribe to whichever symbols already had open tabs before
            // this (re)connect — a fresh socket remembers nothing server-side.
            this._wanted.forEach((symbol) => this._send({ action: 'subscribe', asset: symbol }));
        });

        ws.addEventListener('message', (event) => {
            let payload;
            try {
                payload = JSON.parse(event.data);
            } catch (e) {
                return;
            }
            if (!payload || typeof payload !== 'object' || !payload.asset) return;

            if (payload.type === 'history') {
                const ticks = this._parseHistory(payload.ticks);
                if (ticks.length) this.onHistory(payload.asset, ticks);
                return;
            }

            if (payload.type === 'price') {
                const price = this._toNumber(payload.value);
                if (price === null) return;
                const t = this._toEpochMs(payload.timestamp);
                this.onTicks([{ symbol: payload.asset, bid: price, ask: price, mid: price, t }]);
            }
        });

        ws.addEventListener('close', () => this._scheduleReconnect());
        ws.addEventListener('error', () => {
            try {
                ws.close();
            } catch (e) {
                // Already closing.
            }
        });
    }

    /** {value, timestamp}[] (not guaranteed sorted) -> [[epochMs, price], ...] oldest-first, for AssetFeed.ingestHistory. */
    _parseHistory(ticks) {
        if (!Array.isArray(ticks)) return [];
        const parsed = [];
        for (const entry of ticks) {
            const price = this._toNumber(entry?.value);
            if (price === null) continue;
            parsed.push([this._toEpochMs(entry?.timestamp), price]);
        }
        parsed.sort((a, b) => a[0] - b[0]);
        return parsed;
    }

    _toNumber(value) {
        const n = typeof value === 'string' ? parseFloat(value) : value;
        return typeof n === 'number' && Number.isFinite(n) ? n : null;
    }

    /** datafeedcl timestamps are fractional epoch *seconds*; the rest of the app works in epoch ms. */
    _toEpochMs(timestamp) {
        const n = this._toNumber(timestamp);
        return n === null ? Date.now() : Math.round(n * 1000);
    }

    _scheduleReconnect() {
        if (this._closed) return;
        this.onStatusChange('reconnecting');
        setTimeout(() => this._connect(), this._reconnectDelay);
        this._reconnectDelay = Math.min(this._reconnectDelay * 1.5, 15000);
    }

    _send(obj) {
        if (this.ws && this.ws.readyState === WebSocket.OPEN) {
            this.ws.send(JSON.stringify(obj));
        }
    }

    close() {
        this._closed = true;
        try {
            this.ws?.close();
        } catch (e) {
            // Already closed.
        }
    }
}
