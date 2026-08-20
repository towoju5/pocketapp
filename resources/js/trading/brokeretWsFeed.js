/**
 * Direct browser -> node-services/pricefeed live tick feed for base_url/ui's
 * legacy Brokeret fallback path (see TradingDashboard._initLiveFeed — this
 * only ever runs when liveFeedConfig.datafeedcl isn't configured). Modeled
 * on DataFeedClLiveFeed's connect/reconnect shape, but one-way and much
 * simpler: node-services/pricefeed pushes every symbol unsolicited (same as
 * the Echo 'brokeret-feed' channel it replaces), there's no per-symbol
 * subscribe handshake and no auth (see node-services/pricefeed/wsServer.js's
 * docblock for why).
 *
 * Payload shape is identical to the Echo path's `.ticks-updated` event
 * ({ ticks: [{symbol,bid,ask,mid,category,t}, ...] }) by construction (both
 * sides trace back to the same StreamBrokeretFeed::parseEntry() shape) — so
 * swapping this in for the Echo listener is a transport change only.
 */
export class BrokeretWsFeed {
    constructor(wsUrl, { onTicks, onStatusChange }) {
        this.wsUrl = wsUrl;
        this.onTicks = onTicks;
        this.onStatusChange = onStatusChange;
        this.ws = null;
        this._reconnectDelay = 2000;
        this._closed = false;
    }

    start() {
        this._connect();
    }

    _connect() {
        if (this._closed) return;
        this.onStatusChange('connecting');
        const ws = new WebSocket(this.wsUrl);
        this.ws = ws;

        ws.addEventListener('open', () => {
            this._reconnectDelay = 2000;
            this.onStatusChange('live');
        });

        ws.addEventListener('message', (event) => {
            let payload;
            try {
                payload = JSON.parse(event.data);
            } catch (e) {
                return;
            }
            if (!payload || !Array.isArray(payload.ticks) || !payload.ticks.length) return;
            this.onTicks(payload.ticks);
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

    _scheduleReconnect() {
        if (this._closed) return;
        this.onStatusChange('reconnecting');
        setTimeout(() => this._connect(), this._reconnectDelay);
        this._reconnectDelay = Math.min(this._reconnectDelay * 1.5, 15000);
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
