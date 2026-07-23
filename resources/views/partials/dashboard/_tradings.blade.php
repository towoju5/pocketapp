<div class="trades-panel">
    <div class="flex justify-between items-center pl-4 py-3 border-b border-[#4a2f7a]">
        <h1 class="text-white text-sm font-bold w-[80%]">Trades</h1>
        <div class="w-[20%] flex justify-end pr-3">
            <button onclick="window.location.href='{{ route('trade.index') }}'" class="w-7 h-7 rounded-full flex items-center justify-center" style="background:#4a2f7a;">
                <svg class="w-3 h-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex w-full">
        <button onclick="toggleTradeMenu(this, 'active')" class="trade-open-close relative py-2.5 text-gray-500 bg-[#0b1120] font-thin text-sm w-6/12 active-tab">
            Opened
            <div class="tab-indicator absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
        </button>
        <button onclick="toggleTradeMenu(this, 'closed')" class="trade-open-close relative py-2.5 text-gray-500 bg-[#272b3c] font-thin text-sm w-6/12">
            Closed
            <div class="tab-indicator hidden absolute bottom-0 left-0 w-full h-0.5 bg-blue-500"></div>
        </button>
    </div>

    <!-- Trade Containers -->
    <div class="trade-open-content trade_list-page trade-tab-content" data-tab="active">
        <div id="openTradeList" class="trade-list-stack">
            @forelse($active_trades as $trade)
                @include('mini-pages.trade-list')
            @empty
                <p class="trade-list-empty" id="openTradeListEmpty">No open trades yet — place one from the panel on the left.</p>
            @endforelse
        </div>
    </div>

    <div class="trade-closed-content trade_list-page trade-tab-content hidden" data-tab="closed">
        <div id="closedTradeList" class="trade-list-stack">
            @forelse($recent_closed_trades as $trade)
                @include('mini-pages.trade-list')
            @empty
                <p class="trade-list-empty" id="closedTradeListEmpty">No closed trades in the last 10 minutes.</p>
            @endforelse
        </div>
    </div>
</div>

<style>
    /* Overrides the shared toggleTradeMenu()'s hardcoded gray tab classes to
       match this panel's purple glass theme, without touching the JS itself
       (shared with _express/_tournaments/_signal tabs on different color
       schemes). Scoped to .trades-panel rather than #rightTrades: the panel
       switcher (TradingDashboard._togglePanel) copies #rightTrades's
       *innerHTML* into #mainContent, which drops the #rightTrades element
       itself — any #rightTrades-scoped selector stops matching the moment
       the panel is actually shown. .trades-panel travels with the content
       because it's inside that copied innerHTML. */
    .trades-panel .trade-open-close {
        color: #a190c9 !important;
        background: #241a44 !important;
    }
    .trades-panel .trade-open-close.active-tab {
        color: #fff !important;
        background: #4a2f7a !important;
    }
    .trades-panel .trade-open-close .tab-indicator {
        background: #f2a93b !important;
    }

    .trades-panel .trade-list-stack {
        display: flex;
        flex-direction: column;
        gap: 8px;
        padding: 10px;
    }

    .trades-panel .trade-list-empty {
        color: #a190c9;
        font-size: 13px;
        text-align: center;
        padding: 32px 12px;
    }

    .trades-panel .trade-card {
        background: rgba(74, 47, 122, 0.35);
        border: 1px solid #4a2f7a;
        border-left-width: 3px;
        border-radius: 10px;
        padding: 10px 12px;
    }

    .trades-panel .trade-card__row {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .trades-panel .trade-card__row--figures {
        display: flex;
        margin-top: 10px;
        gap: 8px;
    }

    .trades-panel .trade-card__row--figures > div {
        flex: 1;
    }

    .trades-panel .trade-card__asset {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .trades-panel .trade-card__dir {
        display: inline-flex;
        width: 20px;
        height: 20px;
        border-radius: 50%;
        align-items: center;
        justify-content: center;
        font-size: 10px;
    }

    .trades-panel .trade-card__dir--up {
        background: rgba(22, 192, 135, 0.15);
        color: #16c087;
    }

    .trades-panel .trade-card__dir--down {
        background: rgba(244, 83, 74, 0.15);
        color: #f4534a;
    }

    .trades-panel .trade-card__symbol {
        color: #fff;
        font-weight: 600;
        font-size: 13px;
    }

    .trades-panel .trade-card__pct {
        color: #f2a93b;
        font-size: 11px;
        font-weight: 600;
    }

    .trades-panel .trade-card__badge {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        padding: 2px 8px;
        border-radius: 999px;
    }

    .trades-panel .trade-card__badge--win {
        color: #16c087;
        background: rgba(22, 192, 135, 0.15);
    }

    .trades-panel .trade-card__badge--lose {
        color: #f4534a;
        background: rgba(244, 83, 74, 0.15);
    }

    .trades-panel .trade-card__badge--void {
        color: #a190c9;
        background: rgba(161, 144, 201, 0.15);
    }

    .trades-panel .trade-card__countdown {
        color: #fff;
        font-family: 'Courier New', monospace;
        font-size: 12px;
        font-weight: 600;
    }

    .trades-panel .trade-card__label {
        color: #7c6ba0;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.02em;
        margin-bottom: 2px;
    }

    .trades-panel .trade-card__value {
        color: #fff;
        font-size: 12px;
        font-weight: 600;
    }

    .trades-panel .trade-card__value--win {
        color: #16c087;
    }

    .trades-panel .trade-card__value--lose {
        color: #f4534a;
    }
</style>
