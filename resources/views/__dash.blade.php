@extends('layouts.desktop.trading')

@section('title', 'Trading Dashboard')

@section('content')
@php
    $__coin = $data->symbol ?? 'USDCAD';
    $catLabels = [
        'FOREX' => 'Currencies', 'FOREX_OTC' => 'Currencies (OTC)',
        'CRYPTO' => 'Crypto', 'CRYPTO_OTC' => 'Crypto (OTC)',
        'METAL' => 'Commodities', 'METAL_OTC' => 'Commodities (OTC)',
        'STOCKS_USD' => 'Stocks (USD)', 'STOCKS_EUR' => 'Stocks (EUR)', 'STOCKS_OTC' => 'Stocks (OTC)',
        'INDEX_OTC' => 'Indices (OTC)',
    ];
    $__allAssets = get_assets();
    $__groups = $__allAssets->pluck('asset_group')->unique()->values();
    $__priceFeed = app(\App\Services\PriceFeedService::class);
@endphp

<div class="flex-1 flex min-h-0">

    {{-- ============ CHART COLUMN ============ --}}
    <div class="flex-1 flex flex-col min-w-0 relative">

        <div class="flex items-center gap-2 px-4 py-2.5 relative box-border">
            <button type="button" id="assetPopoverBtn" class="flex items-center gap-2.5 font-bold text-[15px] px-2.5 py-1.5 rounded-lg bg-transparent border-0 text-[#d7dcea] cursor-pointer">
                <span id="activeAssetLabel">{{ $data->symbol }}</span>
                <i class="fa fa-chevron-down" style="font-size:12px;"></i>
                <span class="w-px h-4 bg-[#2a3350] mx-1"></span>
                <span id="rateLabel" class="text-xs font-normal text-[#7c86a3]">{{ $data->name }}</span>
            </button>

            <button type="button" id="chartTypeBtn" class="w-[34px] h-[34px] rounded-lg bg-transparent border-0 text-[#7c86a3] cursor-pointer">
                <i class="fa fa-chart-simple"></i>
            </button>

            {{-- Asset popover --}}
            <div id="assetPopover" class="hidden absolute top-14 left-4 z-30 w-[650px] bg-[#171e33] border border-[#2a3350] rounded-2xl flex overflow-hidden" style="box-shadow:0 30px 80px rgba(0,0,0,0.5);">
                <div class="w-[190px] p-3 border-r border-[#2a3350] flex flex-col gap-1 box-border">
                    @foreach($__groups as $group)
                        <button type="button" class="asset-cat-btn {{ $loop->first ? 'asset-cat-btn--active' : '' }}" data-cat="{{ $group }}"
                            style="display:flex;align-items:center;gap:8px;padding:10px 12px;border-radius:8px;font-size:13px;font-weight:500;border:none;cursor:pointer;text-align:left;background:transparent;color:#7c86a3;width:100%;">
                            {{ $catLabels[$group] ?? ucfirst(strtolower($group)) }}
                        </button>
                    @endforeach
                    <p class="text-[11px] text-[#7c86a3] leading-relaxed mt-3 px-3">OTC quotes are provided by liquidity providers without exchange supervision.</p>
                </div>
                <div class="flex-1 p-3 flex flex-col min-h-0 box-border">
                    <div class="flex items-center gap-2 bg-[#1c243c] border border-[#2a3350] rounded-lg px-3 py-2 mb-3">
                        <input type="text" id="assetSearchInput" placeholder="Search" class="bg-transparent border-0 outline-none flex-1 text-[#d7dcea] text-sm">
                        <i class="fa fa-magnifying-glass text-[#7c86a3]" style="font-size:12px;"></i>
                    </div>
                    <div class="flex-1 overflow-y-auto" style="max-height:380px;">
                        @foreach($__allAssets as $asset)
                            @php($__online = $__priceFeed->isOnline($asset->symbol))
                            <div class="asset-row {{ $loop->first ? '' : 'hidden' }}" data-cat="{{ $asset->asset_group }}" data-symbol="{{ $asset->symbol }}"
                                data-search="{{ strtolower($asset->symbol . ' ' . $asset->name) }}" data-online="{{ $__online ? '1' : '0' }}"
                                style="display:flex;align-items:center;justify-content:space-between;padding:10px;border-radius:8px;cursor:pointer;">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-[13px] font-medium">{{ $asset->symbol }}</span>
                                    <span class="asset-status-badge text-[10px] font-semibold px-1.5 py-0.5 rounded {{ $__online ? 'hidden' : '' }}" style="background:rgba(124,134,163,0.15);color:#7c86a3;">Offline</span>
                                </div>
                                <span class="text-[#16c087] text-[13px] font-semibold">+{{ $asset->asset_profit_margin }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Chart type popover --}}
            <div id="chartTypePopover" class="hidden absolute top-14 z-30 w-[260px] bg-[#171e33] border border-[#2a3350] rounded-2xl p-4 box-border" style="left:290px;box-shadow:0 30px 80px rgba(0,0,0,0.5);">
                <p class="text-xs text-[#7c86a3] font-medium mb-2.5">Chart types</p>
                <div id="chartTypeOptions" class="grid grid-cols-2 gap-2 mb-4"></div>
                <hr class="border-[#2a3350] mb-3.5">
                <div class="flex justify-between items-center py-1.5">
                    <span class="text-[13px]">Show area</span>
                    <button type="button" id="toggleAreaBtn" class="toggle toggle--on"></button>
                </div>
                <div class="flex justify-between items-center py-1.5">
                    <span class="text-[13px]">Enable autoscroll</span>
                    <button type="button" id="toggleAutoscrollBtn" class="toggle toggle--on"></button>
                </div>
            </div>
        </div>

        {{-- Multi-asset tabs --}}
        <div class="flex items-center gap-1.5 px-4 pb-2 overflow-x-auto box-border">
            <div id="assetTabs" class="flex items-center gap-1.5"></div>
            <button type="button" id="addTabBtn" class="w-8 h-8 rounded-lg border border-[#2a3350] bg-transparent text-[#7c86a3] flex-shrink-0 cursor-pointer">+</button>
        </div>

        {{-- Chart canvas --}}
        <div class="flex-1 relative mx-4 rounded-xl overflow-hidden" style="background:radial-gradient(ellipse at 50% 30%,#1a2340 0%,#0f1526 70%);">
            <div class="absolute top-3.5 left-3.5 z-10 rounded-lg px-2.5 py-1.5 text-xs text-[#7c86a3] flex items-center gap-2" style="background:rgba(23,30,51,0.85);border:1px solid #2a3350;">
                <span id="liveClock"></span>
                <span id="livePrice" class="text-white font-bold"></span>
                <span class="flex items-center gap-1 text-[10px] font-medium">
                    <span id="sourceDot" class="source-dot"></span>
                    <!-- <span id="sourceLabel">Simulated</span> -->
                </span>
            </div>

            <div id="kline-chart" class="w-full h-full"></div>

            <div id="assetUnavailableBanner" class="hidden absolute top-3.5 left-1/2 -translate-x-1/2 z-20 rounded-lg px-4 py-2 text-xs font-semibold text-[#d97706] flex items-center gap-2" style="background:rgba(217,119,6,0.15);border:1px solid #d97706;">
                <i class="fa fa-triangle-exclamation"></i>
                This asset is currently not available for trading
            </div>

            <button type="button" id="tfBtn" class="absolute bottom-3.5 left-3.5 z-10 rounded-lg px-3 py-1.5 text-xs font-bold flex items-center gap-1.5 text-[#d7dcea] cursor-pointer" style="background:rgba(23,30,51,0.9);border:1px solid #2a3350;">
                <span id="tfLabel">M1</span>
                <i class="fa fa-chevron-up" style="font-size:10px;"></i>
            </button>
            <div id="tfMenu" class="hidden absolute bottom-14 left-3.5 z-10 rounded-lg p-1.5 grid grid-cols-4 gap-1 w-[220px]" style="background:#1c243c;border:1px solid #2a3350;">
                <div id="tfOptions" class="contents"></div>
            </div>
        </div>
    </div>

    {{-- ============ RIGHT SECTION ============ --}}
    <div class="flex flex-shrink-0 border-l border-[#2a3350]">

        <div class="w-[260px] p-5 border-r border-[#2a3350] box-border">
            <form method="POST" action="{{ route('trade.store') }}" id="tradeForm">
                @csrf
                <div class="text-xs text-[#7c86a3] mb-2">Time</div>
                <div class="bg-[#1c243c] border border-[#2a3350] rounded-lg px-3 py-2.5 flex items-center justify-between font-semibold mb-4">
                    <input type="text" id="hs-trailing-icon" name="duration" maxlength="8" placeholder="00:01:00" value="00:01:00"
                        class="bg-transparent border-0 outline-none text-[#d7dcea] font-semibold text-sm w-[70px]">
                    <input type="hidden" name="asset" id="assetTicker" value="{{ $__coin }}">
                    <i class="fa fa-clock text-[#7c86a3]"></i>
                </div>

                <div class="text-xs text-[#7c86a3] mb-2">Amount</div>
                <div class="bg-[#1c243c] border border-[#2a3350] rounded-lg px-3 py-2.5 flex items-center justify-between font-semibold mb-4">
                    <input type="text" pattern="^\d*\.?\d*$" step="any" id="input_amount_field" name="amount" autocomplete="off" placeholder="1000"
                        class="bg-transparent border-0 outline-none text-[#d7dcea] font-semibold text-sm w-[70px]">
                    <input type="hidden" name="direction" id="direction" value="">
                    <i class="fa fa-dollar-sign text-[#7c86a3]"></i>
                </div>

                <div class="text-xs text-[#7c86a3] mb-1.5">Payout</div>
                <div class="bg-[#1c243c] rounded-lg p-3 text-center mb-4">
                    <div id="profit_percentage" class="text-[#16c087] font-bold text-base">+{{ $data->asset_profit_margin }}%</div>
                    <div id="payout" class="text-[#16c087] text-xs mt-0.5">$0.00</div>
                </div>

                @if($isOutOfTradingHours == true)
                    <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-md">
                        <p class="font-semibold mb-1">Market Unavailable</p>
                        <p class="text-sm leading-relaxed">Trading for the selected asset is currently unavailable as the market is closed. Please select a different asset.</p>
                    </div>
                @else
                    <button type="button" name="action" data-value="up" class="cta-button w-full bg-green-500 hover:bg-green-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-2 mb-2.5">
                        <i class="fa fa-arrow-up"></i> BUY
                    </button>
                    <button type="button" name="action" data-value="down" class="cta-button w-full bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-2">
                        <i class="fa fa-arrow-down"></i> SELL
                    </button>
                @endif
            </form>
        </div>

        <div id="mainContent" class="w-[300px] flex-col border-r border-[#2a3350] overflow-y-auto" style="display:none;"></div>

        <div id="hidden-sections" class="hidden">
            <div id="rightTrades">@include('partials.dashboard._tradings')</div>
            <div id="rightSignals">@include('partials.dashboard._signal')</div>
            <div id="rightSocialTrading" class="bg-[#151726]">@include('partials.dashboard._social')</div>
            <div id="rightExpressTrades">@include('partials.dashboard._express', ['openedTrades' => $openedExpressTrades, 'closedTrades' => $closedExpressTrades])</div>
            <div id="rightTournaments">@include('partials.dashboard._tournaments')</div>
            <div id="rightPendingTrades">
                <div class="p-3 text-white">
                    <h2 class="text-xl font-bold mb-2">Pending Trades</h2>
                    <p class="text-sm text-[#7c86a3]">Monitor and manage your pending trade orders.</p>
                </div>
            </div>
            <div id="rightHotkeys">
                <div class="p-3 text-white">
                    <h2 class="text-xl font-bold mb-2">Hotkey Settings</h2>
                    <p class="text-sm text-[#7c86a3]">Configure and view your trading hotkeys.</p>
                </div>
            </div>
        </div>

        <div class="w-[78px] flex flex-col items-center p-3.5 px-2 gap-1 box-border">
            <a href="#" class="right-nav-link right-nav-link--active" data-section="rightTrades" style="background:#1c243c;border:1px solid #4f8ef7;border-radius:8px;padding:10px 6px;width:58px;display:flex;flex-direction:column;align-items:center;gap:6px;font-size:10px;font-weight:700;color:#4f8ef7;text-decoration:none;margin-bottom:4px;">
                <i class="fa fa-clock-rotate-left" style="font-size:18px;"></i>Trades
            </a>
            <a href="#" class="right-nav-link" data-section="rightSignals" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:10px 6px;width:58px;display:flex;flex-direction:column;align-items:center;gap:6px;font-size:10px;font-weight:700;color:#7c86a3;text-decoration:none;margin-bottom:4px;">
                <i class="fa fa-tower-broadcast" style="font-size:18px;"></i>Signals
            </a>
            <a href="#" class="right-nav-link" data-section="rightSocialTrading" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:10px 6px;width:58px;display:flex;flex-direction:column;align-items:center;gap:6px;font-size:10px;font-weight:700;color:#7c86a3;text-decoration:none;margin-bottom:4px;">
                <i class="fa fa-users" style="font-size:18px;"></i>Social
            </a>
            <a href="#" class="right-nav-link" data-section="rightExpressTrades" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:10px 6px;width:58px;display:flex;flex-direction:column;align-items:center;gap:6px;font-size:10px;font-weight:700;color:#7c86a3;text-decoration:none;margin-bottom:4px;">
                <i class="fa fa-bullseye" style="font-size:18px;"></i>Express
            </a>
            <a href="#" class="right-nav-link" data-section="rightTournaments" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:10px 6px;width:58px;display:flex;flex-direction:column;align-items:center;gap:6px;font-size:10px;font-weight:700;color:#7c86a3;text-decoration:none;margin-bottom:4px;">
                <i class="fa fa-trophy" style="font-size:18px;"></i>Tourneys
            </a>
            <a href="#" class="right-nav-link" data-section="rightPendingTrades" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:10px 6px;width:58px;display:flex;flex-direction:column;align-items:center;gap:6px;font-size:10px;font-weight:700;color:#7c86a3;text-decoration:none;margin-bottom:4px;">
                <i class="fa fa-hourglass-half" style="font-size:18px;"></i>Pending
            </a>
            <a href="#" class="right-nav-link" data-section="rightHotkeys" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:10px 6px;width:58px;display:flex;flex-direction:column;align-items:center;gap:6px;font-size:10px;font-weight:700;color:#7c86a3;text-decoration:none;">
                <i class="fa fa-keyboard" style="font-size:18px;"></i>Hotkeys
            </a>
        </div>
    </div>
</div>

<style>
.toggle { position:relative;width:36px;height:20px;border-radius:10px;border:none;cursor:pointer;background:#2a3350; }
.toggle::before { content:'';position:absolute;top:2px;left:2px;width:16px;height:16px;border-radius:50%;background:white;transition:left .15s; }
.toggle--on { background:#4f8ef7; }
.toggle--on::before { left:18px; }
.source-dot { width:6px;height:6px;border-radius:50%;background:#7c86a3;display:inline-block; }
.source-dot--live { background:#16c087; }
.asset-cat-btn--active { background:rgba(79,142,247,0.15) !important; color:#4f8ef7 !important; }
.asset-row:hover { background:rgba(79,142,247,0.08); }
.asset-tab-chip { display:flex;align-items:center;gap:6px;padding:7px 12px;border-radius:8px;font-size:12px;font-weight:500;white-space:nowrap;flex-shrink:0;cursor:pointer;background:#1c243c;color:#7c86a3;border:1px solid #2a3350; }
.asset-tab-chip--active { background:rgba(79,142,247,0.15);color:#4f8ef7;border-color:#4f8ef7; }
.asset-tab-chip__close { margin-left:4px; }
.chart-type-btn { display:flex;flex-direction:column;align-items:center;gap:6px;padding:10px 4px;border-radius:8px;border:1px solid #2a3350;background:#1c243c;font-size:11px;color:#7c86a3;cursor:pointer; }
.chart-type-btn--active { border-color:#4f8ef7;color:#fff; }
.tf-option-btn { padding:6px 4px;border-radius:6px;font-size:12px;border:none;cursor:pointer;background:transparent;color:#7c86a3; }
.tf-option-btn--active { background:#4f8ef7;color:#fff; }
.rail-nav-btn--active { border-color:#4f8ef7 !important;color:#4f8ef7 !important; }
.right-nav-link--active { border-color:#4f8ef7 !important;color:#4f8ef7 !important; }
</style>

<script type="application/json" id="trading-dashboard-config">
{!! json_encode([
    'assets' => $__allAssets->map(fn($a) => [
        'symbol' => $a->symbol,
        'name' => $a->name,
        'asset_group' => $a->asset_group,
        'asset_profit_margin' => $a->asset_profit_margin,
        'is_otc' => (bool) $a->is_otc,
        'online' => $__priceFeed->isOnline($a->symbol),
    ])->values(),
    'initialSymbol' => $data->symbol,
    'initialAssetGroup' => $data->asset_group,
    'initialProfitMargin' => $data->asset_profit_margin,
    'userId' => auth()->id(),
    'assetStatusUrl' => route('assets.status'),
]) !!}
</script>

@push('js')
<script>
    window.Echo.private('trades.user.{{ auth()->id() }}')
        .listen('TradeUpdated', (event) => {
            if (window.updateOrInsertTradeCard) window.updateOrInsertTradeCard(event);
        });

    window.Echo.channel('signals')
        .listen('SignalCreated', (e) => {
            console.log('New Signal:', e);
        });

    if (window.startCountdowns) {
        window.startCountdowns(@json($active_trades));
    }
</script>
@endpush
@endsection
