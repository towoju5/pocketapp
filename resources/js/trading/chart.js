import { init, dispose } from 'klinecharts';

const MAX_CANDLES = 500;

/**
 * Owns one asset's candle/line buffers, fed by ticks the ChartManager routes
 * to it from the app's own backend broadcast (see ChartManager._initBroadcast)
 * instead of each tab opening its own connection to iqcent — every user's
 * chart is driven by the exact same server-relayed stream this way. Kept
 * alive for as long as its tab is open, independent of whether it's the
 * currently active (visible/tradeable) tab — this is what lets switching
 * tabs be instant.
 */
export class AssetFeed {
    constructor(symbol, periodSeconds, onTick, historyUrl) {
        this.symbol = symbol;
        this.periodSeconds = periodSeconds;
        this.historyUrl = historyUrl;
        this.candles = [];
        this.haCandles = [];
        // Independent 1-second-resolution buffer used only for the 'line'/area
        // display so it keeps moving smoothly every second regardless of the
        // selected candle period (a customer trading on a 5s expiry shouldn't
        // see a chart frozen for up to a full M1/M5 bucket).
        this.linePoints = [];
        this._haPrevOpen = null;
        this._haPrevClose = null;
        this._haCurOpen = null;
        this.onTick = onTick;
        this.chartCallback = null;
        this.chartCallbackType = 'candles';
        this._closed = false;
        this.hasReceivedData = false;
        this.lastPrice = null;
        // Keep the current candle/time-axis moving forward every second even
        // between price ticks, so the chart never looks frozen while a trade
        // countdown is running.
        this._clockTimer = setInterval(() => this._advanceClock(), 1000);
    }

    /** Called by ChartManager whenever a broadcast tick for this symbol arrives. */
    ingestTick(price, epochMs) {
        this.hasReceivedData = true;
        this.lastPrice = price;
        const rawBar = this._pushOrUpdateCandle(price, epochMs);
        this._pushLinePoint(price, epochMs);

        // Unconditional, first: this must never be skipped or delayed by chart
        // rendering — the trade panel's live price/availability display depends
        // on it firing every tick. Trade entry pricing itself is server-side
        // (PriceFeedService), this feed is purely for chart/UI display now.
        try {
            this.onTick(this, price, epochMs);
        } catch (e) {
            console.error('[AssetFeed] onTick handler failed', e);
        }

        this._pushToChart(rawBar);
    }

    /** Runs every second regardless of ticks; carries the last known price forward. */
    _advanceClock() {
        if (this._closed || this.lastPrice == null) return;
        const rawBar = this._pushOrUpdateCandle(this.lastPrice, Date.now());
        this._pushLinePoint(this.lastPrice, Date.now());
        this._pushToChart(rawBar);
    }

    _pushToChart(rawBar) {
        if (!this.chartCallback) return;
        try {
            const bar = this.chartCallbackType === 'heikin' ? this.haCandles[this.haCandles.length - 1]
                : this.chartCallbackType === 'line' ? this.linePoints[this.linePoints.length - 1]
                : rawBar;
            this.chartCallback(bar);
        } catch (e) {
            console.error('[AssetFeed] chart push failed', e);
        }
    }

    /** Always-1-second buckets, independent of the candle period, for a smoothly-moving line/area view. */
    _pushLinePoint(price, epochMs) {
        const bucketStart = Math.floor(epochMs / 1000) * 1000;
        let last = this.linePoints[this.linePoints.length - 1];
        if (!last || bucketStart > last.timestamp) {
            last = { timestamp: bucketStart, open: price, high: price, low: price, close: price, volume: 0 };
            this.linePoints.push(last);
            if (this.linePoints.length > MAX_CANDLES) this.linePoints.shift();
        } else {
            last.close = price;
            last.high = Math.max(last.high, price);
            last.low = Math.min(last.low, price);
        }
        return last;
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
        if (!this.historyUrl) return this.candles;
        const params = new URLSearchParams({ symbol: this.symbol });
        const url = `${this.historyUrl}?${params.toString()}`;

        // History is a nice-to-have backfill, not something the chart's
        // first paint should ever wait on for long — ticks are already
        // flowing over the broadcast channel independently of this. Bounded
        // so a slow/stuck upstream (iqcent via the collector) can't hold the
        // chart's initial render hostage.
        const controller = new AbortController();
        const timeout = setTimeout(() => controller.abort(), 1500);

        try {
            const res = await fetch(url, { signal: controller.signal });
            const data = await res.json();
            // Raw [epochMs, price] ticks from our own rolling cache (see
            // PriceFeedService::getHistoryTicks) — replayed oldest-first
            // through the same bucketing the live feed uses, so they come
            // out correctly shaped for whatever period is currently
            // selected instead of needing a separate candle format.
            const ticks = Array.isArray(data?.ticks) ? data.ticks : [];
            if (ticks.length > 0) {
                this.hasReceivedData = true;
                ticks.sort((a, b) => a[0] - b[0]);
                for (const [epochMs, price] of ticks) {
                    this._pushOrUpdateCandle(price, epochMs);
                    this._pushLinePoint(price, epochMs);
                    this.lastPrice = price;
                }
            }
        } catch (e) {
            console.error('[AssetFeed] history fetch failed', e);
        } finally {
            clearTimeout(timeout);
        }
        return this.candles;
    }

    close() {
        this._closed = true;
        if (this._clockTimer) clearInterval(this._clockTimer);
    }
}

const CANDLE_TYPE_MAP = {
    candles: 'candle_solid',
    bars: 'ohlc',
    line: 'area',
    heikin: 'candle_solid',
};

export const COLOR_SCHEMES = {
    purple: { name: 'Purple', up: '#f2a93b', down: '#f2a93b', line: '#f2a93b' },
    classic: { name: 'Classic', up: '#16c087', down: '#f4534a', line: '#4f8ef7' },
    ocean: { name: 'Ocean', up: '#4f8ef7', down: '#f2a93b', line: '#4f8ef7' },
    mono: { name: 'Mono', up: '#d7dcea', down: '#7c86a3', line: '#d7dcea' },
    // Blue/orange (Wong/IBM colorblind-safe palette) — red/green is the one
    // combination deuteranopia/protanopia can't reliably tell apart, so this
    // scheme avoids it entirely instead of just tweaking the existing hues.
    colorblind: { name: 'Colorblind-safe', up: '#0072B2', down: '#E69F00', line: '#0072B2' },
};

/** '#f2a93b' + 0.35 -> 'rgba(242,169,59,0.35)' — used for the area-chart fill so it always matches the active line color instead of a hardcoded hue. */
function hexToRgba(hex, alpha) {
    const clean = hex.replace('#', '');
    const r = parseInt(clean.substring(0, 2), 16);
    const g = parseInt(clean.substring(2, 4), 16);
    const b = parseInt(clean.substring(4, 6), 16);
    return `rgba(${r},${g},${b},${alpha})`;
}

/**
 * Wraps a single klinecharts instance + a registry of per-symbol AssetFeed
 * "tabs". Exactly one feed is ever "active" (attached to the chart via
 * klinecharts' DataLoader subscribeBar hook) at a time.
 */
export class ChartManager {
    constructor(container, {
        onOrderTick, onAvailabilityChange, onDrawingsChanged, onUserDrag, pricePrecision = 5,
        chartType = 'candles', showArea = true, periodSeconds = 60,
        colorScheme = 'classic', showGrid = true, historyUrl = null,
    } = {}) {
        this.container = container;
        this.historyUrl = historyUrl;
        this.chart = init(container);
        this._resizeObserver = new ResizeObserver(() => {
            this.chart.resize();
            this._applyCenterOffset();
        });
        this._resizeObserver.observe(container);
        if (getComputedStyle(container).position === 'static') {
            container.style.position = 'relative';
        }
        this.feeds = new Map();
        this.activeSymbol = null;
        this.onUserDrag = onUserDrag || (() => {});
        // The user physically dragging/panning the chart should break the
        // center-lock until the caller re-enables auto-follow (see
        // TradingDashboard's autoScroll toggle) — onPaneDrag only fires for
        // real pointer-driven pans, not our own programmatic scrolls, so it
        // won't fight with _applyCenterOffset()/scrollToRealTime() below.
        this.chart.subscribeAction('onPaneDrag', () => this.onUserDrag());
        this.periodSeconds = periodSeconds;
        this.periodObj = periodSecondsToKlinePeriod(periodSeconds);
        this.chartType = chartType;
        this.showArea = showArea;
        this.colorScheme = COLOR_SCHEMES[colorScheme] ? colorScheme : 'classic';
        this.showGrid = showGrid;
        this.pricePrecision = pricePrecision;
        this.onOrderTick = onOrderTick;
        this.onAvailabilityChange = onAvailabilityChange || (() => {});
        this.onDrawingsChanged = onDrawingsChanged || (() => {});
        this._availabilityTimer = null;

        // Pending-trade expiry markers: tradeId -> { symbol, expiryMs, entryPrice },
        // plus which of those are currently drawn as overlays (only ever the
        // ones belonging to the active symbol) -> tradeId -> { verticalId, priceLineId }.
        this._expiryLines = new Map();
        this._renderedExpiryOverlays = new Map();
        // DOM-based "Expiration time" text labels (klinecharts overlay text
        // rendering isn't reliable enough for a rotated custom label, so this
        // is a plain positioned <div> kept in sync with the chart's own pan/
        // zoom via convertToPixel) -> tradeId -> { el, expiryMs }.
        this._expiryLabels = new Map();
        this._labelSyncTimer = setInterval(() => this._positionExpiryLabels(), 250);

        // Active indicators (name -> klinecharts indicator id) and user-drawn
        // overlays/drawing-tools (drawingId -> klinecharts overlay id) — both
        // apply globally to the chart, independent of which symbol tab is
        // active, since klinecharts recomputes/repositions them against
        // whatever data is currently loaded.
        this._indicatorIds = new Map();
        this._drawingIds = new Map();

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
        this._applyCenterOffset();
        this._initBroadcast();
    }

    /**
     * One shared subscription to the backend's own price broadcast (see
     * AssetPriceBatchUpdated/collector/index.js) instead of each AssetFeed
     * opening its own connection to iqcent — every open tab's ticks arrive
     * over this single channel and get routed to whichever feed matches the
     * symbol. Batched: one event can carry many ticks (possibly for many
     * symbols) from a single collector flush.
     */
    _initBroadcast() {
        if (!window.Echo) return;
        window.Echo.channel('asset-prices').listen('.prices-updated', (e) => {
            (e.ticks || []).forEach((tick) => this._onBroadcastTick(tick));
        });
    }

    _onBroadcastTick({ symbol, price, t }) {
        const feed = this.feeds.get(symbol);
        if (!feed) return;
        feed.ingestTick(price, t || Date.now());
    }

    _candlesForType(feed) {
        if (this.chartType === 'heikin') return feed.haCandles;
        if (this.chartType === 'line') return feed.linePoints.length ? feed.linePoints : feed.candles;
        return feed.candles;
    }

    _applyStyles() {
        const candleType = CANDLE_TYPE_MAP[this.chartType] || 'candle_solid';
        const scheme = COLOR_SCHEMES[this.colorScheme] || COLOR_SCHEMES.classic;
        const gridColor = this.showGrid ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0)';
        this.chart.setStyles({
            grid: { horizontal: { color: gridColor }, vertical: { color: gridColor } },
            candle: {
                type: candleType,
                bar: {
                    upColor: scheme.up, downColor: scheme.down, noChangeColor: '#888888',
                    upBorderColor: scheme.up, downBorderColor: scheme.down,
                    upWickColor: scheme.up, downWickColor: scheme.down,
                },
                area: {
                    lineSize: 2,
                    lineColor: scheme.line,
                    value: 'close',
                    backgroundColor: this.showArea
                        ? [{ offset: 0, color: hexToRgba(scheme.line, 0.35) }, { offset: 1, color: hexToRgba(scheme.line, 0.02) }]
                        : 'rgba(0,0,0,0)',
                },
                priceMark: { last: { upColor: scheme.up, downColor: scheme.down, text: { color: '#ffffff' } } },
            },
            xAxis: { axisLine: { color: '#4a2f7a' }, tickText: { color: '#b8a4e0' } },
            yAxis: { axisLine: { color: '#4a2f7a' }, tickText: { color: '#b8a4e0' } },
            crosshair: { horizontal: { line: { color: scheme.line } }, vertical: { line: { color: scheme.line } } },
        });
    }

    setColorScheme(scheme) {
        if (!COLOR_SCHEMES[scheme]) return;
        this.colorScheme = scheme;
        this._applyStyles();
    }

    setShowGrid(showGrid) {
        this.showGrid = showGrid;
        this._applyStyles();
    }

    /** Ensure a feed exists for `symbol`, opening its WS connection if new. */
    openTab(symbol) {
        if (this.feeds.has(symbol)) return this.feeds.get(symbol);
        const feed = new AssetFeed(symbol, this.periodSeconds, (feed, price, epochMs) => {
            if (feed.symbol === this.activeSymbol) {
                this.onOrderTick(price, epochMs);
                this.onAvailabilityChange(feed.symbol, true);
            }
        }, this.historyUrl);
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
        // Ensure the newly-loaded symbol opens centered (not klinecharts'
        // default right-anchored real-time position) on first load/tab switch.
        this.scrollToRealTime();
    }

    /**
     * Mark a pending trade's close time on the chart with a light dashed
     * vertical line plus an "Expiration time" label, and its entry price with
     * a horizontal dashed line — so the customer can see both where they
     * entered and when the trade expires relative to price action. Only ever
     * rendered while `symbol`'s tab is the active one; re-applied automatically
     * on tab switch.
     */
    setExpiryLine(tradeId, symbol, expiryMs, entryPrice) {
        if (!tradeId || !symbol || !expiryMs) return;
        this._expiryLines.set(tradeId, { symbol, expiryMs, entryPrice });
        if (symbol === this.activeSymbol) this._redrawExpiryLines();
    }

    /** Remove a trade's expiry marker (call once it settles win/lose). */
    clearExpiryLine(tradeId) {
        this._expiryLines.delete(tradeId);
        const overlays = this._renderedExpiryOverlays.get(tradeId);
        if (overlays) {
            if (overlays.verticalId) this.chart.removeOverlay({ id: overlays.verticalId });
            if (overlays.priceLineId) this.chart.removeOverlay({ id: overlays.priceLineId });
            this._renderedExpiryOverlays.delete(tradeId);
        }
        this._removeExpiryLabel(tradeId);
    }

    /** Redraw expiry overlays for whichever trades belong to the active symbol. */
    _redrawExpiryLines() {
        this._renderedExpiryOverlays.forEach((overlays) => {
            if (overlays.verticalId) this.chart.removeOverlay({ id: overlays.verticalId });
            if (overlays.priceLineId) this.chart.removeOverlay({ id: overlays.priceLineId });
        });
        this._renderedExpiryOverlays.clear();
        this._expiryLabels.forEach((_, tradeId) => this._removeExpiryLabel(tradeId));

        this._expiryLines.forEach(({ symbol, expiryMs, entryPrice }, tradeId) => {
            if (symbol !== this.activeSymbol) return;

            const verticalId = this.chart.createOverlay({
                name: 'verticalStraightLine',
                lock: true,
                points: [{ timestamp: expiryMs }],
                styles: {
                    line: { color: 'rgba(215,220,234,0.4)', size: 1, style: 'dashed' },
                },
            });

            let priceLineId = null;
            if (typeof entryPrice === 'number' && Number.isFinite(entryPrice)) {
                priceLineId = this.chart.createOverlay({
                    name: 'priceLine',
                    lock: true,
                    points: [{ value: entryPrice }],
                    styles: {
                        line: { color: '#f2a93b', size: 1, style: 'dashed' },
                        text: { color: '#ffffff', backgroundColor: '#f2a93b' },
                    },
                });
            }

            if (verticalId) this._renderedExpiryOverlays.set(tradeId, { verticalId, priceLineId });
            this._createExpiryLabel(tradeId, expiryMs);
        });
    }

    _createExpiryLabel(tradeId, expiryMs) {
        const el = document.createElement('div');
        el.className = 'chart-expiry-label';
        el.textContent = 'Expiration time';
        el.style.cssText = [
            'position:absolute', 'top:8px', 'font-size:10px', 'font-weight:600',
            'color:#d7dcea', 'background:rgba(23,30,51,0.9)', 'border:1px solid #2a3350',
            'padding:3px 5px', 'border-radius:4px', 'white-space:nowrap', 'pointer-events:none',
            'z-index:15', 'transform:translateX(-50%)', 'writing-mode:vertical-rl',
            'letter-spacing:0.3px', 'display:none',
        ].join(';');
        this.container.appendChild(el);
        this._expiryLabels.set(tradeId, { el, expiryMs });
        this._positionExpiryLabels();
    }

    _removeExpiryLabel(tradeId) {
        const entry = this._expiryLabels.get(tradeId);
        if (entry) {
            entry.el.remove();
            this._expiryLabels.delete(tradeId);
        }
    }

    _positionExpiryLabels() {
        if (!this._expiryLabels.size) return;
        this._expiryLabels.forEach(({ el, expiryMs }) => {
            try {
                const coord = this.chart.convertToPixel({ timestamp: expiryMs }, { paneId: 'candle_pane' });
                const point = Array.isArray(coord) ? coord[0] : coord;
                const x = point?.x;
                if (typeof x === 'number' && Number.isFinite(x) && x >= 0) {
                    el.style.left = `${x}px`;
                    el.style.display = '';
                } else {
                    el.style.display = 'none';
                }
            } catch (e) {
                el.style.display = 'none';
            }
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

    // ---- indicators ----

    isIndicatorActive(name) {
        return this._indicatorIds.has(name);
    }

    getActiveIndicatorNames() {
        return Array.from(this._indicatorIds.keys());
    }

    addIndicator(name, calcParams) {
        if (this._indicatorIds.has(name)) return;
        const id = this.chart.createIndicator(calcParams ? { name, calcParams } : name, false);
        if (id) this._indicatorIds.set(name, id);
    }

    removeIndicator(name) {
        const id = this._indicatorIds.get(name);
        if (!id) return;
        this.chart.removeIndicator({ id });
        this._indicatorIds.delete(name);
    }

    // ---- drawing tools (user-created overlays: trendlines, fib, etc.) ----

    /** Starts an interactive draw — the user clicks the chart to place points. */
    startDrawing(overlayName) {
        const drawingId = `drawing_${Date.now()}_${Math.random().toString(36).slice(2, 8)}`;
        const overlayId = this.chart.createOverlay({
            name: overlayName,
            styles: { line: { color: '#4f8ef7', size: 1.5 } },
            onDrawEnd: () => {
                this.onDrawingsChanged();
                return true;
            },
        });
        if (overlayId) this._drawingIds.set(drawingId, overlayId);
        return drawingId;
    }

    clearDrawings() {
        this._drawingIds.forEach((overlayId) => this.chart.removeOverlay({ id: overlayId }));
        this._drawingIds.clear();
        this.onDrawingsChanged();
    }

    hasDrawings() {
        return this._drawingIds.size > 0;
    }

    /** Plain-data snapshot of every user-drawn overlay, safe to JSON.stringify. */
    serializeDrawings() {
        const ourIds = new Set(this._drawingIds.values());
        const overlays = this.chart.getOverlays() || [];
        return overlays
            .filter((o) => ourIds.has(o.id))
            .map((o) => ({
                name: o.name,
                points: (o.points || []).map((p) => ({ timestamp: p.timestamp, value: p.value })),
                styles: o.styles || undefined,
            }))
            .filter((o) => o.points.length > 0);
    }

    /** Recreates previously-serialized drawings (call once after the chart is ready). */
    restoreDrawings(saved) {
        (saved || []).forEach(({ name, points, styles }) => {
            if (!name || !Array.isArray(points) || points.length === 0) return;
            const overlayId = this.chart.createOverlay({
                name,
                points,
                styles,
                onDrawEnd: () => {
                    this.onDrawingsChanged();
                    return true;
                },
            });
            if (overlayId) this._drawingIds.set(`drawing_restored_${overlayId}`, overlayId);
        });
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

    /**
     * Keeps the latest/current price bar centered horizontally in the pane
     * (rather than klinecharts' default right-anchored real-time position),
     * matching the reference design where the price sits mid-chart with
     * empty look-ahead space to the right. Recomputed on resize.
     */
    _applyCenterOffset() {
        const width = this.container?.clientWidth || 0;
        if (!width) return;
        const half = Math.round(width / 2);
        this.chart.setMaxOffsetRightDistance(half);
        this.chart.setOffsetRightDistance(half);
    }

    scrollToRealTime() {
        this._applyCenterOffset();
        this.chart.scrollToRealTime();
    }

    dispose() {
        if (this._availabilityTimer) clearTimeout(this._availabilityTimer);
        if (this._labelSyncTimer) clearInterval(this._labelSyncTimer);
        this._expiryLabels.forEach(({ el }) => el.remove());
        this._expiryLabels.clear();
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
