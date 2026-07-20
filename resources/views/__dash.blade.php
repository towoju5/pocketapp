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

    {{-- ============ CHART COLUMN (full-screen; trade form/panels float on top) ============ --}}
    <div class="flex-1 flex flex-col min-w-0 relative">

        <div class="flex items-center gap-2 px-4 py-2.5 relative box-border">
            <button type="button" id="assetPopoverBtn" class="flex items-center gap-2.5 font-bold text-[15px] px-2.5 py-1.5 rounded-lg bg-transparent border-0 text-[#ece5ff] cursor-pointer">
                <span id="activeAssetLabel">{{ $data->symbol }}</span>
                <i class="fa fa-chevron-down" style="font-size:12px;"></i>
                <span class="w-px h-4 bg-[#4a2f7a] mx-1"></span>
                <span id="rateLabel" class="text-xs font-normal text-[#a190c9]">{{ $data->name }}</span>
            </button>

            <button type="button" id="chartTypeBtn" class="w-[34px] h-[34px] rounded-lg bg-transparent border-0 text-[#a190c9] cursor-pointer">
                <i class="fa fa-chart-simple"></i>
            </button>

            <button type="button" id="indicatorsBtn" class="w-[34px] h-[34px] rounded-lg bg-transparent border-0 text-[#a190c9] cursor-pointer" title="Indicators">
                <i class="fa fa-wave-square"></i>
            </button>

            <button type="button" id="drawingToolsBtn" class="w-[34px] h-[34px] rounded-lg bg-transparent border-0 text-[#a190c9] cursor-pointer" title="Drawing tools">
                <i class="fa fa-pencil"></i>
            </button>

            {{-- Asset popover --}}
            <div id="assetPopover" class="hidden absolute top-14 left-4 right-4 sm:right-auto z-30 w-auto sm:w-[650px] max-w-[650px] bg-[#2d1a5c] border border-[#4a2f7a] rounded-2xl flex overflow-hidden" style="box-shadow:0 30px 80px rgba(0,0,0,0.5);">
                <div class="w-[110px] sm:w-[190px] p-2 sm:p-3 border-r border-[#4a2f7a] flex flex-col gap-1 box-border">
                    @foreach($__groups as $group)
                        <button type="button" class="asset-cat-btn {{ $loop->first ? 'asset-cat-btn--active' : '' }}" data-cat="{{ $group }}"
                            style="display:flex;align-items:center;gap:8px;padding:10px 12px;border-radius:8px;font-size:13px;font-weight:500;border:none;cursor:pointer;text-align:left;background:transparent;color:#a190c9;width:100%;">
                            {{ $catLabels[$group] ?? ucfirst(strtolower($group)) }}
                        </button>
                    @endforeach
                    <p class="text-[11px] text-[#a190c9] leading-relaxed mt-3 px-3">OTC quotes are provided by liquidity providers without exchange supervision.</p>
                </div>
                <div class="flex-1 p-3 flex flex-col min-h-0 box-border">
                    <div class="flex items-center gap-2 bg-[#3d2570] border border-[#4a2f7a] rounded-lg px-3 py-2 mb-3">
                        <input type="text" id="assetSearchInput" placeholder="Search" class="bg-transparent border-0 outline-none flex-1 text-[#ece5ff] text-sm">
                        <i class="fa fa-magnifying-glass text-[#a190c9]" style="font-size:12px;"></i>
                    </div>
                    <div class="flex-1 overflow-y-auto" style="max-height:380px;">
                        @foreach($__allAssets as $asset)
                            @php($__online = $__priceFeed->isOnline($asset->symbol))
                            <div class="asset-row {{ $loop->first ? '' : 'hidden' }}" data-cat="{{ $asset->asset_group }}" data-symbol="{{ $asset->symbol }}"
                                data-search="{{ strtolower($asset->symbol . ' ' . $asset->name) }}" data-online="{{ $__online ? '1' : '0' }}"
                                style="display:flex;align-items:center;justify-content:space-between;padding:10px;border-radius:8px;cursor:pointer;">
                                <div class="flex items-center gap-2.5">
                                    <span class="text-[13px] font-medium">{{ $asset->symbol }}</span>
                                    <span class="asset-status-badge text-[10px] font-semibold px-1.5 py-0.5 rounded {{ $__online ? 'hidden' : '' }}" style="background:rgba(124,134,163,0.15);color:#a190c9;">Offline</span>
                                </div>
                                <span class="text-[#16c087] text-[13px] font-semibold">+{{ $asset->asset_profit_margin }}%</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Chart type popover --}}
            <div id="chartTypePopover" class="hidden absolute top-14 left-4 sm:left-[290px] z-30 w-[260px] max-w-[calc(100vw-2rem)] bg-[#2d1a5c] border border-[#4a2f7a] rounded-2xl p-4 box-border" style="box-shadow:0 30px 80px rgba(0,0,0,0.5);">
                <p class="text-xs text-[#a190c9] font-medium mb-2.5">Chart types</p>
                <div id="chartTypeOptions" class="grid grid-cols-2 gap-2 mb-4"></div>
                <hr class="border-[#4a2f7a] mb-3.5">
                <div class="flex justify-between items-center py-1.5">
                    <span class="text-[13px]">Show area</span>
                    <button type="button" id="toggleAreaBtn" class="toggle toggle--on"></button>
                </div>
                <div class="flex justify-between items-center py-1.5">
                    <span class="text-[13px]">Enable autoscroll</span>
                    <button type="button" id="toggleAutoscrollBtn" class="toggle toggle--on"></button>
                </div>
                <div class="flex justify-between items-center py-1.5">
                    <span class="text-[13px]">Grid lines</span>
                    <button type="button" id="toggleGridBtn" class="toggle toggle--on"></button>
                </div>
                <hr class="border-[#4a2f7a] my-3.5">
                <p class="text-xs text-[#a190c9] font-medium mb-2">Color scheme</p>
                <div id="colorSchemeOptions" class="grid grid-cols-3 gap-2"></div>
            </div>

            {{-- Indicators popover --}}
            <div id="indicatorsPopover" class="hidden absolute top-14 left-4 sm:left-[324px] z-30 w-[220px] max-w-[calc(100vw-2rem)] bg-[#2d1a5c] border border-[#4a2f7a] rounded-2xl p-4 box-border" style="box-shadow:0 30px 80px rgba(0,0,0,0.5);">
                <p class="text-xs text-[#a190c9] font-medium mb-2.5">Indicators</p>
                <div id="indicatorOptions" class="flex flex-col gap-1"></div>
            </div>

            {{-- Drawing tools popover --}}
            <div id="drawingToolsPopover" class="hidden absolute top-14 left-4 sm:left-[360px] z-30 w-[190px] max-w-[calc(100vw-2rem)] bg-[#2d1a5c] border border-[#4a2f7a] rounded-2xl p-2 box-border" style="box-shadow:0 30px 80px rgba(0,0,0,0.5);">
                <p class="text-xs text-[#a190c9] font-medium mb-2 px-1.5 pt-1">Drawing tools</p>
                <div id="drawingToolOptions" class="flex flex-col gap-0.5"></div>
                <hr class="border-[#4a2f7a] my-2">
                <button type="button" id="clearDrawingsBtn" class="w-full text-left px-2 py-1.5 rounded-lg text-xs text-[#f2415c] hover:bg-[#3d2570]">
                    <i class="fa fa-trash" style="font-size:11px;"></i> Clear all drawings
                </button>
            </div>
        </div>

        {{-- Multi-asset tabs --}}
        <div class="flex items-center gap-1.5 px-4 pb-2 overflow-x-auto box-border">
            <div id="assetTabs" class="flex items-center gap-1.5"></div>
            <button type="button" id="addTabBtn" class="w-8 h-8 rounded-lg border border-[#4a2f7a] bg-transparent text-[#a190c9] flex-shrink-0 cursor-pointer">+</button>
        </div>

        {{-- Chart canvas --}}
        <div class="flex-1 relative mx-4 rounded-xl overflow-hidden" style="background:radial-gradient(ellipse at 50% 30%,#2b1854 0%,#150c30 70%);">
            <div class="absolute top-3.5 left-3.5 z-10 rounded-lg px-2.5 py-1.5 text-xs text-[#a190c9] flex items-center gap-2" style="background:rgba(45,26,92,0.85);border:1px solid #4a2f7a;">
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

            <button type="button" id="tfBtn" class="absolute bottom-3.5 left-3.5 z-10 rounded-lg px-3 py-1.5 text-xs font-bold flex items-center gap-1.5 text-[#ece5ff] cursor-pointer" style="background:rgba(45,26,92,0.9);border:1px solid #4a2f7a;">
                <span id="tfLabel">S5</span>
                <i class="fa fa-chevron-up" style="font-size:10px;"></i>
            </button>
            <div id="tfMenu" class="hidden absolute bottom-14 left-3.5 z-10 rounded-lg p-1.5 grid grid-cols-4 gap-1 w-[220px]" style="background:#3d2570;border:1px solid #4a2f7a;">
                <div id="tfOptions" class="contents"></div>
            </div>
        </div>
    </div>

        {{-- ============ FLOATING TRADE FORM (overlays the bottom-left of the
             chart; kept off the right edge so it never covers the Y-axis
             price labels / current-price tag) ============ --}}
        <div class="absolute bottom-4 left-4 z-20 w-[280px] max-w-[85vw] rounded-2xl p-4 box-border" style="background:rgba(45,26,92,0.88);backdrop-filter:blur(14px);border:1px solid #4a2f7a;box-shadow:0 20px 60px rgba(0,0,0,0.45);">
            <form method="POST" action="{{ route('trade.store') }}" id="tradeForm">
                @csrf
                <input type="hidden" name="asset" id="assetTicker" value="{{ $__coin }}">
                <input type="hidden" name="direction" id="direction" value="">

                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-[#a190c9]">Time</span>
                    <i class="fa fa-clock text-[#a190c9]" style="font-size:11px;"></i>
                </div>
                <div class="flex items-stretch gap-1.5 mb-2">
                    <button type="button" id="durationStepDown" class="w-9 flex-shrink-0 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] font-bold hover:text-white hover:border-[#f2a93b]">&minus;</button>
                    <input type="text" id="hs-trailing-icon" name="duration" maxlength="8" placeholder="00:01:00" value="00:01:00"
                        class="flex-1 min-w-0 bg-[#3d2570] border border-[#4a2f7a] rounded-lg text-center outline-none text-[#ece5ff] font-semibold text-sm">
                    <button type="button" id="durationStepUp" class="w-9 flex-shrink-0 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] font-bold hover:text-white hover:border-[#f2a93b]">+</button>
                </div>
                <div id="durationPresets" class="grid grid-cols-4 gap-1.5 mb-4">
                    <button type="button" data-seconds="30" class="duration-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">30s</button>
                    <button type="button" data-seconds="60" class="duration-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">1m</button>
                    <button type="button" data-seconds="300" class="duration-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">5m</button>
                    <button type="button" data-seconds="900" class="duration-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">15m</button>
                </div>

                <div class="flex items-center justify-between mb-2">
                    <span class="text-xs text-[#a190c9]">Amount</span>
                    <i class="fa fa-dollar-sign text-[#a190c9]" style="font-size:11px;"></i>
                </div>
                <div class="flex items-stretch gap-1.5 mb-2">
                    <button type="button" id="amountStepDown" class="w-9 flex-shrink-0 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] font-bold hover:text-white hover:border-[#f2a93b]">&minus;</button>
                    <input type="text" inputmode="decimal" pattern="^\d*\.?\d*$" id="input_amount_field" name="amount" autocomplete="off" placeholder="10" value="10"
                        class="flex-1 min-w-0 bg-[#3d2570] border border-[#4a2f7a] rounded-lg text-center outline-none text-[#ece5ff] font-semibold text-sm">
                    <button type="button" id="amountStepUp" class="w-9 flex-shrink-0 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] font-bold hover:text-white hover:border-[#f2a93b]">+</button>
                </div>
                <div id="amountPresets" class="grid grid-cols-4 gap-1.5 mb-4">
                    <button type="button" data-amount="10" class="amount-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">$10</button>
                    <button type="button" data-amount="25" class="amount-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">$25</button>
                    <button type="button" data-amount="50" class="amount-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">$50</button>
                    <button type="button" data-amount="100" class="amount-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">$100</button>
                </div>

                <div class="text-xs text-[#a190c9] mb-1.5">Payout</div>
                <div class="bg-[#3d2570] rounded-lg p-3 text-center mb-4">
                    <div id="profit_percentage" class="text-[#16c087] font-bold text-base">+{{ number_format($data->asset_profit_margin * 100, 0) }}%</div>
                    <div id="payout" class="text-[#16c087] text-xs mt-0.5">$0.00</div>
                </div>

                @if($isOutOfTradingHours == true)
                    <div class="mt-4 p-4 bg-yellow-50 border-l-4 border-yellow-500 text-yellow-800 rounded-md">
                        <p class="font-semibold mb-1">Market Unavailable</p>
                        <p class="text-sm leading-relaxed">Trading for the selected asset is currently unavailable as the market is closed. Please select a different asset.</p>
                    </div>
                @else
                    <button type="button" name="action" data-value="up" class="cta-button w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-1.5 mb-2.5">
                        <span id="ctaPercentUp">{{ number_format($data->asset_profit_margin * 100, 0) }}%</span> UP
                    </button>
                    <button type="button" name="action" data-value="down" class="cta-button w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-1.5">
                        <span id="ctaPercentDown">{{ number_format($data->asset_profit_margin * 100, 0) }}%</span> DOWN
                    </button>
                @endif
            </form>
        </div>

        {{-- ============ FLOATING SECONDARY PANEL (Watch/Trades/Signals/etc —
             opens to the LEFT of the icon strip below, well clear of the
             right-edge price axis) ============ --}}
        <div id="mainContent" class="absolute top-16 right-[62px] z-30 w-[300px] max-w-[80vw] max-h-[70vh] flex-col overflow-y-auto rounded-2xl p-1" style="display:none;background:rgba(45,26,92,0.92);backdrop-filter:blur(14px);border:1px solid #4a2f7a;box-shadow:0 20px 60px rgba(0,0,0,0.45);"></div>

        <div id="hidden-sections" class="hidden">
            <div id="rightMarketWatch"></div>
            <div id="rightTrades">@include('partials.dashboard._tradings')</div>
            <div id="rightSignals">@include('partials.dashboard._signal')</div>
            <div id="rightSocialTrading" class="bg-[#1f1440]">@include('partials.dashboard._social')</div>
            <div id="rightExpressTrades">@include('partials.dashboard._express', ['openedTrades' => $openedExpressTrades, 'closedTrades' => $closedExpressTrades])</div>
            <div id="rightTournaments">@include('partials.dashboard._tournaments')</div>
            <div id="rightPendingTrades">
                <div class="p-3 text-white">
                    <h2 class="text-xl font-bold mb-2">Pending Trades</h2>
                    <p class="text-sm text-[#a190c9]">Monitor and manage your pending trade orders.</p>
                </div>
            </div>
            <div id="rightHotkeys">
                <div class="p-3 text-white">
                    <h2 class="text-xl font-bold mb-2">Hotkey Settings</h2>
                    <p class="text-sm text-[#a190c9]">Configure and view your trading hotkeys.</p>
                </div>
            </div>
        </div>

        {{-- ============ FLOATING ICON STRIP (compact, icon-only, top-right
             below the toolbar — kept out of the bottom-left form's way and
             narrow enough to sit beside rather than over the price axis) ============ --}}
        <div class="absolute top-16 right-2 z-20 flex flex-col items-center gap-1.5">
            <a href="#" class="right-nav-link" data-section="rightMarketWatch" title="Market Watch" style="background:rgba(45,26,92,0.88);backdrop-filter:blur(8px);border:1px solid #4a2f7a;border-radius:8px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;color:#a190c9;text-decoration:none;">
                <i class="fa fa-list" style="font-size:14px;"></i>
            </a>
            <a href="#" class="right-nav-link right-nav-link--active" data-section="rightTrades" title="Trades" style="background:rgba(45,26,92,0.88);backdrop-filter:blur(8px);border:1px solid #f2a93b;border-radius:8px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;color:#f2a93b;text-decoration:none;">
                <i class="fa fa-clock-rotate-left" style="font-size:14px;"></i>
            </a>
            <a href="#" class="right-nav-link" data-section="rightSignals" title="Signals" style="background:rgba(45,26,92,0.88);backdrop-filter:blur(8px);border:1px solid #4a2f7a;border-radius:8px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;color:#a190c9;text-decoration:none;">
                <i class="fa fa-tower-broadcast" style="font-size:14px;"></i>
            </a>
            <a href="#" class="right-nav-link" data-section="rightSocialTrading" title="Social" style="background:rgba(45,26,92,0.88);backdrop-filter:blur(8px);border:1px solid #4a2f7a;border-radius:8px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;color:#a190c9;text-decoration:none;">
                <i class="fa fa-users" style="font-size:14px;"></i>
            </a>
            <a href="#" class="right-nav-link" data-section="rightExpressTrades" title="Express" style="background:rgba(45,26,92,0.88);backdrop-filter:blur(8px);border:1px solid #4a2f7a;border-radius:8px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;color:#a190c9;text-decoration:none;">
                <i class="fa fa-bullseye" style="font-size:14px;"></i>
            </a>
            <a href="#" class="right-nav-link" data-section="rightTournaments" title="Tournaments" style="background:rgba(45,26,92,0.88);backdrop-filter:blur(8px);border:1px solid #4a2f7a;border-radius:8px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;color:#a190c9;text-decoration:none;">
                <i class="fa fa-trophy" style="font-size:14px;"></i>
            </a>
            <a href="#" class="right-nav-link" data-section="rightPendingTrades" title="Pending" style="background:rgba(45,26,92,0.88);backdrop-filter:blur(8px);border:1px solid #4a2f7a;border-radius:8px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;color:#a190c9;text-decoration:none;">
                <i class="fa fa-hourglass-half" style="font-size:14px;"></i>
            </a>
            <a href="#" class="right-nav-link" data-section="rightHotkeys" title="Hotkeys" style="background:rgba(45,26,92,0.88);backdrop-filter:blur(8px);border:1px solid #4a2f7a;border-radius:8px;width:38px;height:38px;display:flex;align-items:center;justify-content:center;color:#a190c9;text-decoration:none;">
                <i class="fa fa-keyboard" style="font-size:14px;"></i>
            </a>
        </div>
    </div>
</div>

<style>
.toggle { position:relative;width:36px;height:20px;border-radius:10px;border:none;cursor:pointer;background:#4a2f7a; }
.toggle::before { content:'';position:absolute;top:2px;left:2px;width:16px;height:16px;border-radius:50%;background:white;transition:left .15s; }
.toggle--on { background:#f2a93b; }
.toggle--on::before { left:18px; }
.source-dot { width:6px;height:6px;border-radius:50%;background:#a190c9;display:inline-block; }
.source-dot--live { background:#16c087; }
.asset-cat-btn--active { background:rgba(242,169,59,0.15) !important; color:#f2a93b !important; }
.asset-row:hover { background:rgba(242,169,59,0.08); }
.asset-tab-chip { display:flex;align-items:center;gap:6px;padding:7px 12px;border-radius:8px;font-size:12px;font-weight:500;white-space:nowrap;flex-shrink:0;cursor:pointer;background:#3d2570;color:#a190c9;border:1px solid #4a2f7a; }
.asset-tab-chip--active { background:rgba(242,169,59,0.15);color:#f2a93b;border-color:#f2a93b; }
.asset-tab-chip__close { margin-left:4px; }
.chart-type-btn { display:flex;flex-direction:column;align-items:center;gap:6px;padding:10px 4px;border-radius:8px;border:1px solid #4a2f7a;background:#3d2570;font-size:11px;color:#a190c9;cursor:pointer; }
.chart-type-btn--active { border-color:#f2a93b;color:#fff; }
.tf-option-btn { padding:6px 4px;border-radius:6px;font-size:12px;border:none;cursor:pointer;background:transparent;color:#a190c9; }
.tf-option-btn--active { background:#f2a93b;color:#fff; }
.rail-nav-btn--active { border-color:#4f8ef7 !important;color:#4f8ef7 !important; }
.right-nav-link--active { border-color:#f2a93b !important;color:#f2a93b !important; }
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
