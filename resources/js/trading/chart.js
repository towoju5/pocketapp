import { init, dispose } from 'klinecharts';

const REST_HISTORY_URL = 'https://iqcent.com/trade-api/history';
const WS_URL = 'wss://iqcent.com/trade-api-ws/api/ws/price';
const MAX_CANDLES = 500;
const HISTORY_WINDOW_SECONDS = 3600 * 24;

/**
 * Owns one iqcent REST+WS feed for a single asset symbol. Kept alive for as
 * long as its tab is open, independent of whether it's the currently active
 * (visible/tradeable) tab — this is what lets switching tabs be instant.
 */
export class AssetFeed {
    constructor(symbol, periodSeconds, onTick) {
        this.symbol = symbol;
        this.periodSeconds = periodSeconds;
        this.candles = [];
        this.haCandles = [];
        this._haPrevOpen = null;
        this._haPrevClose = null;
        this._haCurOpen = null;
        this.onTick = onTick;
        this.chartCallback = null;
        this.chartCallbackType = 'candles';
        this._closed = false;
        this._reconnectTimer = null;
        this.ws = null;
        this.hasReceivedData = false;
        this.lastPrice = null;
        this._connect();
        // Keep the current candle/time-axis moving forward every second even
        // between price ticks, so the chart never looks frozen while a trade
        // countdown is running.
        this._clockTimer = setInterval(() => this._advanceClock(), 1000);
    }

    _connect() {
        this.ws = new WebSocket(WS_URL);
        this.ws.onopen = () => {
            this.ws.send(JSON.stringify({ id: this.symbol, param: 'Option', operation: 'SUBSCRIBE.TICK' }));
        };
        this.ws.onmessage = (event) => this._handleMessage(event);
        this.ws.onerror = () => {};
        this.ws.onclose = () => {
            if (this._closed) return;
            this._reconnectTimer = setTimeout(() => this._connect(), 3000);
        };
    }

    _handleMessage(event) {
        let message;
        try {
            message = JSON.parse(event.data);
        } catch (e) {
            return;
        }
        if (!message || typeof message.p !== 'number' || typeof message.t !== 'number') return;

        this.hasReceivedData = true;
        const price = parseFloat(message.p.toFixed(6));
        this.lastPrice = price;
        const rawBar = this._pushOrUpdateCandle(price, message.t);

        // Unconditional, first: this must never be skipped or delayed by chart
        // rendering — the trade panel's live price/availability display depends
        // on it firing every tick. Trade entry pricing itself is server-side
        // (PriceFeedService), this feed is purely for chart/UI display now.
        try {
            this.onTick(this, message, price);
        } catch (e) {
            console.error('[AssetFeed] onTick handler failed', e);
        }

        if (this.chartCallback) {
            try {
                const bar = this.chartCallbackType === 'heikin' ? this.haCandles[this.haCandles.length - 1] : rawBar;
                this.chartCallback(bar);
            } catch (e) {
                console.error('[AssetFeed] chart push failed', e);
            }
        }
    }

    /** Runs every second regardless of ticks; carries the last known price forward. */
    _advanceClock() {
        if (this._closed || this.lastPrice == null) return;
        const rawBar = this._pushOrUpdateCandle(this.lastPrice, Date.now());
        if (this.chartCallback) {
            try {
                const bar = this.chartCallbackType === 'heikin' ? this.haCandles[this.haCandles.length - 1] : rawBar;
                this.chartCallback(bar);
            } catch (e) {
                console.error('[AssetFeed] chart push failed', e);
            }
        }
    }

    _pushOrUpdateCandle(price, epochMs) {
        const bucketMs = this.periodSeconds * 1000;
        const bucketStart = Math.floor(epochMs / bucketMs) * bucketMs;
        let last = this.candles[this.candles.length - 1];
        const isNew = !last || bucketStart > last.timestamp;

        if (isNew) {
            last = { timestamp: bucketStart, open: price, high: price, low: price, close: price, volume: 0 };
            this.candles.push(last);
            if (this.candles.length > MAX_CANDLES) this.candles.shift();
        } else {
            last.close = price;
            last.high = Math.max(last.high, price);
            last.low = Math.min(last.low, price);
        }
        this._pushHeikinCandle(last, isNew);
        return last;
    }

    _pushHeikinCandle(rawLast, isNew) {
        const haClose = (rawLast.open + rawLast.high + rawLast.low + rawLast.close) / 4;
        const haOpen = isNew
            ? (this._haPrevOpen == null ? (rawLast.open + rawLast.close) / 2 : (this._haPrevOpen + this._haPrevClose) / 2)
            : this._haCurOpen;
        const haHigh = Math.max(rawLast.high, haOpen, haClose);
        const haLow = Math.min(rawLast.low, haOpen, haClose);
        const haBar = { timestamp: rawLast.timestamp, open: haOpen, high: haHigh, low: haLow, close: haClose, volume: rawLast.volume };

        if (isNew) {
            if (this.haCandles.length) {
                this._haPrevOpen = this._haCurOpen;
                this._haPrevClose = this.haCandles[this.haCandles.length - 1].close;
            }
            this._haCurOpen = haOpen;
            this.haCandles.push(haBar);
            if (this.haCandles.length > MAX_CANDLES) this.haCandles.shift();
        } else {
            this.haCandles[this.haCandles.length - 1] = haBar;
        }
        return haBar;
    }

    async fetchHistory() {
        const from = Math.floor((Date.now() - HISTORY_WINDOW_SECONDS * 1000) / 1000);
        const to = Math.floor(Date.now() / 1000);
        const symbolParam = encodeURIComponent(`${this.symbol}_Strike`);
        const url = `${REST_HISTORY_URL}?from=${from}&to=${to}&symbol=${symbolParam}&firstDataRequest=true&resolution=1`;

        try {
            const res = await fetch(url);
            const data = await res.json();
            if (data && Array.isArray(data.result) && data.result.length > 0) {
                this.hasReceivedData = true;
                this.candles = data.result.map((c) => ({
                    timestamp: c.time,
                    open: c.open,
                    high: c.high,
                    low: c.low,
                    close: c.close,
                    volume: 0,
                }));
                this._rebuildHeikinFromScratch();
            }
        } catch (e) {
            console.error('[AssetFeed] history fetch failed', e);
        }
        return this.candles;
    }

    _rebuildHeikinFromScratch() {
        this.haCandles = [];
        this._haPrevOpen = null;
        this._haPrevClose = null;
        this._haCurOpen = null;
        for (const c of this.candles) {
            this._pushHeikinCandle(c, true);
        }
    }

    close() {
        this._closed = true;
        if (this._reconnectTimer) clearTimeout(this._reconnectTimer);
        if (this._clockTimer) clearInterval(this._clockTimer);
        if (this.ws) this.ws.close();
    }
}

const CANDLE_TYPE_MAP = {
    candles: 'candle_solid',
    bars: 'ohlc',
    line: 'area',
    heikin: 'candle_solid',
};

/**
 * Wraps a single klinecharts instance + a registry of per-symbol AssetFeed
 * "tabs". Exactly one feed is ever "active" (attached to the chart via
 * klinecharts' DataLoader subscribeBar hook) at a time.
 */
export class ChartManager {
    constructor(container, { onOrderTick, onAvailabilityChange, pricePrecision = 5 } = {}) {
        this.chart = init(container);
        this._resizeObserver = new ResizeObserver(() => this.chart.resize());
        this._resizeObserver.observe(container);
        this.feeds = new Map();
        this.activeSymbol = null;
        this.periodSeconds = 60;
        this.periodObj = { type: 'minute', span: 1 };
        this.chartType = 'candles';
        this.showArea = true;
        this.pricePrecision = pricePrecision;
        this.onOrderTick = onOrderTick;
        this.onAvailabilityChange = onAvailabilityChange || (() => {});
        this._availabilityTimer = null;

        // Pending-trade expiry markers: tradeId -> { symbol, expiryMs }, plus
        // which of those are currently drawn as overlays (only ever the ones
        // belonging to the active symbol) -> tradeId -> overlay id.
        this._expiryLines = new Map();
        this._renderedExpiryOverlays = new Map();

        this._applyStyles();
        this.chart.setDataLoader({
            getBars: async ({ symbol, callback }) => {
                const feed = this.feeds.get(symbol.ticker);
                if (!feed) {
                    callback([], false);
                    return;
                }
                const candles = feed.candles.length ? feed.candles : await feed.fetchHistory();
                callback(this._candlesForType(feed), false);
            },
            subscribeBar: ({ symbol, callback }) => {
                const feed = this.feeds.get(symbol.ticker);
                if (!feed) return;
                feed.chartCallbackType = this.chartType;
                feed.chartCallback = callback;
            },
            unsubscribeBar: ({ symbol }) => {
                const feed = this.feeds.get(symbol.ticker);
                if (feed) feed.chartCallback = null;
            },
        });
    }

    _candlesForType(feed) {
        return this.chartType === 'heikin' ? feed.haCandles : feed.candles;
    }

    _applyStyles() {
        const candleType = CANDLE_TYPE_MAP[this.chartType] || 'candle_solid';
        this.chart.setStyles({
            grid: { horizontal: { color: 'rgba(255,255,255,0.05)' }, vertical: { color: 'rgba(255,255,255,0.05)' } },
            candle: {
                type: candleType,
                bar: {
                    upColor: '#16c087', downColor: '#f4534a', noChangeColor: '#888888',
                    upBorderColor: '#16c087', downBorderColor: '#f4534a',
                    upWickColor: '#16c087', downWickColor: '#f4534a',
                },
                area: {
                    lineSize: 2,
                    lineColor: '#4f8ef7',
                    value: 'close',
                    backgroundColor: this.showArea
                        ? [{ offset: 0, color: 'rgba(79,142,247,0.35)' }, { offset: 1, color: 'rgba(79,142,247,0.02)' }]
                        : 'rgba(0,0,0,0)',
                },
                priceMark: { last: { upColor: '#16c087', downColor: '#f4534a', text: { color: '#ffffff' } } },
            },
            xAxis: { axisLine: { color: '#2a3350' }, tickText: { color: '#7c86a3' } },
            yAxis: { axisLine: { color: '#2a3350' }, tickText: { color: '#7c86a3' } },
            crosshair: { horizontal: { line: { color: '#4f8ef7' } }, vertical: { line: { color: '#4f8ef7' } } },
        });
    }

    /** Ensure a feed exists for `symbol`, opening its WS connection if new. */
    openTab(symbol) {
        if (this.feeds.has(symbol)) return this.feeds.get(symbol);
        const feed = new AssetFeed(symbol, this.periodSeconds, (feed, message, price) => {
            if (feed.symbol === this.activeSymbol) {
                this.onOrderTick(price, message.t);
                this.onAvailabilityChange(feed.symbol, true);
            }
        });
        this.feeds.set(symbol, feed);
        return feed;
    }

    /** Fully close and forget a tab's feed (only when the user closes the tab). */
    closeTab(symbol) {
        const feed = this.feeds.get(symbol);
        if (feed) {
            feed.close();
            this.feeds.delete(symbol);
        }
        if (this.activeSymbol === symbol) this.activeSymbol = null;
    }

    /** Make `symbol` the visible/tradeable chart — opens its feed if needed. */
    activate(symbol, precision) {
        this.openTab(symbol);
        this.activeSymbol = symbol;
        if (precision) this.pricePrecision = precision;
        this.chart.setSymbol({ ticker: symbol, pricePrecision: this.pricePrecision, volumePrecision: 0 });
        this.chart.setPeriod(this.periodObj);
        this._scheduleAvailabilityCheck(symbol);
        this._redrawExpiryLines();
    }

    /**
     * Mark a pending trade's close time on the chart with a light dashed
     * vertical line, so the customer can see where/when their trade expires
     * relative to price action. Only ever rendered while `symbol`'s tab is
     * the active one; re-applied automatically on tab switch.
     */
    setExpiryLine(tradeId, symbol, expiryMs) {
        if (!tradeId || !symbol || !expiryMs) return;
        this._expiryLines.set(tradeId, { symbol, expiryMs });
        if (symbol === this.activeSymbol) this._redrawExpiryLines();
    }

    /** Remove a trade's expiry marker (call once it settles win/lose). */
    clearExpiryLine(tradeId) {
        this._expiryLines.delete(tradeId);
        const overlayId = this._renderedExpiryOverlays.get(tradeId);
        if (overlayId) {
            this.chart.removeOverlay({ id: overlayId });
            this._renderedExpiryOverlays.delete(tradeId);
        }
    }

    /** Redraw expiry overlays for whichever trades belong to the active symbol. */
    _redrawExpiryLines() {
        this._renderedExpiryOverlays.forEach((overlayId) => this.chart.removeOverlay({ id: overlayId }));
        this._renderedExpiryOverlays.clear();

        this._expiryLines.forEach(({ symbol, expiryMs }, tradeId) => {
            if (symbol !== this.activeSymbol) return;
            const overlayId = this.chart.createOverlay({
                name: 'verticalStraightLine',
                lock: true,
                points: [{ timestamp: expiryMs }],
                styles: {
                    line: { color: 'rgba(215,220,234,0.4)', size: 1, style: 'dashed' },
                },
            });
            if (overlayId) this._renderedExpiryOverlays.set(tradeId, overlayId);
        });
    }

    /**
     * No live tick and no history for `symbol` within a reasonable window is
     * treated as "this asset isn't tradable right now" — a real signal (not a
     * guess), since both the REST history call and the WS subscribe already
     * happen unconditionally the moment the tab opens.
     */
    _scheduleAvailabilityCheck(symbol) {
        if (this._availabilityTimer) clearTimeout(this._availabilityTimer);
        this._availabilityTimer = setTimeout(() => {
            if (this.activeSymbol !== symbol) return;
            const feed = this.feeds.get(symbol);
            const available = !!(feed && (feed.hasReceivedData || feed.candles.length > 0));
            this.onAvailabilityChange(symbol, available);
        }, 8000);
    }

    setPeriod(periodSeconds, periodObj) {
        this.periodSeconds = periodSeconds;
        this.periodObj = periodObj;
        for (const feed of this.feeds.values()) {
            feed.periodSeconds = periodSeconds;
        }
        // Force a fresh history load at the new resolution for the active feed.
        const feed = this.activeSymbol && this.feeds.get(this.activeSymbol);
        if (feed) {
            feed.candles = [];
            feed.haCandles = [];
        }
        this.chart.setPeriod(periodObj);
    }

    setChartType(type) {
        this.chartType = type;
        this._applyStyles();
        const feed = this.activeSymbol && this.feeds.get(this.activeSymbol);
        if (feed) feed.chartCallbackType = type;
        // Re-trigger getBars for the active symbol so the new candle shape (raw vs heikin) loads.
        if (this.activeSymbol) {
            this.chart.setSymbol({ ticker: this.activeSymbol, pricePrecision: this.pricePrecision, volumePrecision: 0 });
        }
    }

    toggleArea(showArea) {
        this.showArea = showArea;
        this._applyStyles();
    }

    scrollToRealTime() {
        this.chart.scrollToRealTime();
    }

    dispose() {
        if (this._availabilityTimer) clearTimeout(this._availabilityTimer);
        this._resizeObserver?.disconnect();
        for (const feed of this.feeds.values()) feed.close();
        this.feeds.clear();
        dispose(this.chart);
    }
}

export function periodSecondsToKlinePeriod(seconds) {
    if (seconds < 60) return { type: 'second', span: seconds };
    if (seconds < 3600) return { type: 'minute', span: Math.round(seconds / 60) };
    return { type: 'hour', span: Math.round(seconds / 3600) };
}
