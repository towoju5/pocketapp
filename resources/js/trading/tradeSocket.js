import { io } from 'socket.io-client';

/**
 * Wraps the Node trade-execution socket (node-services/tradesocket) for the
 * feature-flagged alternate path in TradingDashboard._submitTrade. Auth: the
 * `auth` option below is a function, so socket.io calls it on every
 * (re)connect attempt and fetches a fresh key each time — see
 * RealtimeAuthController's docblock for why the key itself is long-lived
 * (liveness/revocation is handled by tradesocket's own background heartbeat,
 * not by this fetch).
 */
export class TradeSocket {
    constructor(url) {
        this.socket = io(url, {
            autoConnect: false,
            auth: (cb) => {
                fetch('/realtime/token', { headers: { Accept: 'application/json' } })
                    .then((r) => r.json())
                    .then(({ token }) => cb({ token }))
                    .catch(() => cb({}));
            },
        });
    }

    connect() {
        this.socket.connect();
    }

    /** Resolves with the same {status, message, trade, html} shape TradeController::placeTrade's fetch response already has. */
    placeTrade(payload) {
        return new Promise((resolve, reject) => {
            if (!this.socket.connected) {
                reject(new Error('Trade socket not connected'));
                return;
            }
            this.socket.emit('trade:place', payload, (ack) => resolve(ack));
        });
    }

    /** cb receives the same curated shape TradeUpdated::payload() broadcasts over Echo — see tradeCards.js's updateOrInsertTradeCard. */
    onTradeUpdated(cb) {
        this.socket.on('trade:updated', cb);
    }

    get connected() {
        return this.socket.connected;
    }
}
