/**
 * Handles live trade-card DOM updates pushed over the `trades.user.{id}`
 * private Echo channel (see App\Events\TradeUpdated). Kept separate from
 * TradingDashboard since it only touches the right-rail trade list, not
 * chart/asset state.
 */

/**
 * Server timestamps are always UTC (APP_TIMEZONE=UTC) but frequently arrive
 * as a naive "Y-m-d H:i:s" string with no timezone marker — e.g. Trade's
 * trade_close_time isn't cast to datetime, so a value freshly read back
 * from the DB is just the raw driver string. `new Date(...)` treats a
 * marker-less date-time string as *local* time, so any visitor whose
 * browser isn't in UTC gets a target time skewed by their own UTC offset —
 * for someone ahead of UTC that reads as already-past, showing 00:00:00
 * immediately on a trade that just opened. Force UTC explicitly whenever
 * the string doesn't already carry its own offset.
 */
export function parseServerDate(value) {
    if (!value) return null;
    let str = String(value).replace(' ', 'T');
    if (!/[Zz]|[+-]\d{2}:?\d{2}$/.test(str)) str += 'Z';
    const date = new Date(str);
    return Number.isNaN(date.getTime()) ? null : date;
}

export function updateOrInsertTradeCard(event) {
    if (!event || !event.id) return;
    const existing = document.getElementById(`trade-card-${event.id}`);
    if (!event.html) return;

    const wrapper = document.createElement('div');
    wrapper.innerHTML = event.html.trim();
    const fresh = wrapper.firstElementChild;
    if (!fresh) return;

    const isSettled = ['win', 'lose', 'void'].includes(event.trade_status);

    if (isSettled) {
        // Trade settled — move it out of "opened" and into "closed" (rather
        // than just deleting it) so it shows up there live, no reload needed.
        existing?.remove();
        window.tradingDashboard?.chart?.clearExpiryLine(event.id);

        if (event.trade_status === 'win' || event.trade_status === 'lose') {
            updateTopbarBalance(event);
            playResultSound(event.trade_status);
            showTradeResultToast(event);
        }

        const closedList = document.getElementById('closedTradeList');
        if (closedList) {
            document.getElementById('closedTradeListEmpty')?.remove();
            closedList.prepend(fresh);
        }
    } else if (existing) {
        existing.replaceWith(fresh);
        startCountdowns([event]);
    } else {
        // Brand-new pending trade, just placed — insert it live into
        // "opened" and reflect the stake debit immediately (the wallet
        // moves right away at placement, not just at settlement).
        const list = document.getElementById('openTradeList');
        if (list) {
            document.getElementById('openTradeListEmpty')?.remove();
            list.prepend(fresh);
        }
        updateTopbarBalance(event);
        startCountdowns([event]);
    }
}

/** Updates the topbar balance in place if this event's wallet matches the one currently shown. */
function updateTopbarBalance(event) {
    if (event.wallet_balance == null) return;
    const el = document.getElementById('topbarBalance');
    if (!el || el.dataset.walletSlug !== event.trade_wallet) return;
    el.textContent = `$${Number(event.wallet_balance).toFixed(2)}`;
}

let audioCtx = null;

/** Short synthesized chime/thud — no audio files to ship, works the instant a trade settles. */
function playResultSound(status) {
    try {
        audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
        if (audioCtx.state === 'suspended') audioCtx.resume();

        const notes = status === 'win' ? [523.25, 783.99] : [220, 164.81]; // C5->G5 up-chime, A3->E3 down-thud
        const noteDuration = 0.14;

        notes.forEach((freq, i) => {
            const osc = audioCtx.createOscillator();
            const gain = audioCtx.createGain();
            osc.type = status === 'win' ? 'sine' : 'triangle';
            osc.frequency.value = freq;
            const startAt = audioCtx.currentTime + i * noteDuration;
            gain.gain.setValueAtTime(0.0001, startAt);
            gain.gain.exponentialRampToValueAtTime(0.65, startAt + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.0001, startAt + noteDuration);
            osc.connect(gain).connect(audioCtx.destination);
            osc.start(startAt);
            osc.stop(startAt + noteDuration + 0.02);
        });
    } catch (e) {
        // Audio isn't available/allowed yet (e.g. no user interaction has
        // happened this page load) — silently skip, never block the UI over it.
    }
}

/** Bottom-left-of-chart toast: "Won/Lost $X on SYMBOL", auto-dismissing. */
function showTradeResultToast(event) {
    const stack = document.getElementById('tradeResultToasts');
    if (!stack) return;

    const isWin = event.trade_status === 'win';
    const profit = Number(event.trade_profit || 0) - Number(event.trade_amount || 0);
    const amountLabel = isWin ? `+$${profit.toFixed(2)}` : `-$${Number(event.trade_amount || 0).toFixed(2)}`;

    const toast = document.createElement('div');
    toast.className = 'flex items-center gap-2.5 rounded-lg px-3.5 py-2.5 text-sm font-semibold shadow-lg';
    toast.style.cssText = isWin
        ? 'background:rgba(22,192,135,0.15);border:1px solid #16c087;color:#16c087;box-shadow:0 10px 30px rgba(0,0,0,0.35);'
        : 'background:rgba(244,83,74,0.15);border:1px solid #f4534a;color:#f4534a;box-shadow:0 10px 30px rgba(0,0,0,0.35);';
    toast.innerHTML = `
        <i class="fa fa-${isWin ? 'circle-check' : 'circle-xmark'}"></i>
        <span>${isWin ? 'Won' : 'Lost'} ${amountLabel} on ${event.trade_currency || 'trade'}</span>
    `;

    stack.appendChild(toast);
    setTimeout(() => {
        toast.style.transition = 'opacity 0.4s';
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 400);
    }, 4500);
}

/**
 * Starts (or restarts) a single countdown element's own interval against a
 * fixed target time. Deliberately does NOT check for a "already running"
 * flag on the element: the trades panel gets displayed by cloning
 * #rightTrades's *innerHTML* into #mainContent (see
 * TradingDashboard._togglePanel) every time it's opened, which produces
 * brand-new DOM nodes with no memory of any interval that was ticking on
 * the node it was cloned from — that previous interval keeps running
 * uselessly against the old, now-orphaned node while the freshly-cloned,
 * visible one just sits at its static "--:--:--" text forever. Re-arming
 * unconditionally on every element handed to us fixes that: a stale
 * interval targeting an orphaned node is harmless (it's not attached to
 * anything visible), and the visible node always gets a fresh, correct one.
 */
function armCountdownElement(el, closeTimeMs) {
    const tick = () => {
        const remaining = Math.max(0, Math.floor((closeTimeMs - Date.now()) / 1000));
        const h = String(Math.floor(remaining / 3600)).padStart(2, '0');
        const m = String(Math.floor((remaining % 3600) / 60)).padStart(2, '0');
        const s = String(remaining % 60).padStart(2, '0');
        el.textContent = `${h}:${m}:${s}`;
        if (remaining <= 0) clearInterval(interval);
    };
    const interval = setInterval(tick, 1000);
    tick();
}

/** Re-arms every countdown element within `root` from its own data-close-time attribute — safe to call repeatedly, e.g. every time the trades panel is (re)displayed. */
export function rearmCountdowns(root = document) {
    root.querySelectorAll('[data-close-time]').forEach((el) => {
        const closeTime = parseServerDate(el.dataset.closeTime);
        if (!closeTime) return;
        armCountdownElement(el, closeTime.getTime());
    });
}

export function startCountdowns(trades) {
    (trades || []).forEach((trade) => {
        const closeTime = parseServerDate(trade.trade_close_time);
        if (closeTime && trade.trade_currency) {
            const entryPrice = trade.start_price != null ? parseFloat(trade.start_price) : undefined;
            window.tradingDashboard?.chart?.setExpiryLine(trade.id, trade.trade_currency, closeTime.getTime(), entryPrice);
        }

        const el = document.getElementById(`countdown-${trade.id}`);
        if (!el || !closeTime) return;
        armCountdownElement(el, closeTime.getTime());
    });
}
