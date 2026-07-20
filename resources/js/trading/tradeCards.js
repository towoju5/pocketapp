/**
 * Handles live trade-card DOM updates pushed over the `trades.user.{id}`
 * private Echo channel (see App\Events\TradeUpdated). Kept separate from
 * TradingDashboard since it only touches the right-rail trade list, not
 * chart/asset state.
 */

export function updateOrInsertTradeCard(event) {
    if (!event || !event.id) return;
    const existing = document.getElementById(`trade-card-${event.id}`);
    if (!event.html) return;

    const wrapper = document.createElement('div');
    wrapper.innerHTML = event.html.trim();
    const fresh = wrapper.firstElementChild;
    if (!fresh) return;

    if (existing) {
        existing.replaceWith(fresh);
    } else {
        const list = document.getElementById('openTradeList');
        if (list) list.prepend(fresh);
    }

    if (event.trade_status === 'win' || event.trade_status === 'lose') {
        // Trade settled — it no longer belongs in the "opened" list.
        setTimeout(() => fresh.remove(), 1500);
        window.tradingDashboard?.chart?.clearExpiryLine(event.id);
    } else {
        startCountdowns([event]);
    }
}

export function startCountdowns(trades) {
    (trades || []).forEach((trade) => {
        const closeTime = trade.trade_close_time ? new Date(trade.trade_close_time.replace(' ', 'T')) : null;
        if (closeTime && !Number.isNaN(closeTime.getTime()) && trade.trade_currency) {
            const entryPrice = trade.start_price != null ? parseFloat(trade.start_price) : undefined;
            window.tradingDashboard?.chart?.setExpiryLine(trade.id, trade.trade_currency, closeTime.getTime(), entryPrice);
        }

        const el = document.getElementById(`countdown-${trade.id}`);
        if (!el) return;
        if (!closeTime || Number.isNaN(closeTime.getTime())) return;

        if (el.dataset.countdownActive === '1') return;
        el.dataset.countdownActive = '1';

        const tick = () => {
            const remaining = Math.max(0, Math.floor((closeTime.getTime() - Date.now()) / 1000));
            const h = String(Math.floor(remaining / 3600)).padStart(2, '0');
            const m = String(Math.floor((remaining % 3600) / 60)).padStart(2, '0');
            const s = String(remaining % 60).padStart(2, '0');
            el.textContent = `${h}:${m}:${s}`;
            if (remaining <= 0) clearInterval(interval);
        };
        const interval = setInterval(tick, 1000);
        tick();
    });
}
