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

<div class="flex-1 flex flex-col sm:flex-row min-h-0">

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
        <div id="chartCanvasWrap" class="flex-1 relative mx-4 rounded-xl overflow-hidden" style="background:radial-gradient(ellipse at 50% 30%,#2b1854 0%,#150c30 70%);">
            <div class="absolute top-3.5 left-3.5 z-10 rounded-lg px-2.5 py-1.5 text-xs text-[#a190c9] flex items-center gap-2" style="background:rgba(45,26,92,0.85);border:1px solid #4a2f7a;">
                <span id="liveClock"></span>
                <span id="livePrice" class="text-white font-bold"></span>
                <span class="flex items-center gap-1 text-[10px] font-medium">
                    <span id="sourceDot" class="source-dot"></span>
                    <!-- <span id="sourceLabel">Simulated</span> -->
                </span>
            </div>

            {{-- Win/lose result toasts stack here, bottom-left, well above
                 the timeframe button (bottom-3.5) so they never overlap it. --}}
            <div id="tradeResultToasts" class="absolute bottom-16 left-3.5 z-30 flex flex-col-reverse gap-2"></div>

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

        {{-- ============ TRADE FORM. Mobile: full-width floating sheet docked
             below the chart (in normal flow). Desktop (sm: and up): classic
             static right-column, part of the row next to the chart. ============ --}}
        <div class="w-full flex-shrink-0 rounded-t-2xl bg-[rgba(45,26,92,0.88)] backdrop-blur-[14px] border border-[#4a2f7a] shadow-[0_20px_60px_rgba(0,0,0,0.45)] p-4 sm:static sm:w-[260px] sm:max-w-none sm:flex-shrink-0 sm:rounded-none sm:bg-[#2d1a5c] sm:backdrop-blur-none sm:border-0 sm:border-l sm:border-r sm:border-[#4a2f7a] sm:shadow-none sm:p-5 sm:overflow-y-auto box-border">
            <form method="POST" action="{{ route('trade.store') }}" id="tradeForm">
                @csrf
                <input type="hidden" name="asset" id="assetTicker" value="{{ $__coin }}">
                <input type="hidden" name="direction" id="direction" value="">

                {{-- Time + Amount: side-by-side compact row on mobile, stacked full detail on desktop --}}
                <div class="grid grid-cols-2 gap-3 sm:block">
                    <div class="relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-[#a190c9]">Time</span>
                            <i class="fa fa-clock text-[#a190c9]" style="font-size:11px;"></i>
                        </div>
                        <div class="flex items-stretch gap-1.5 mb-2">
                            <button type="button" id="durationStepDown" class="max-sm:hidden sm:block w-9 flex-shrink-0 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] font-bold hover:text-white hover:border-[#f2a93b]">&minus;</button>
                            <input type="text" id="hs-trailing-icon" name="duration" maxlength="8" placeholder="00:01:00" value="00:01:00" readonly
                                class="max-sm:pointer-events-none flex-1 min-w-0 bg-[#3d2570] border border-[#4a2f7a] rounded-lg text-center outline-none text-[#ece5ff] font-semibold text-sm">
                            <button type="button" id="durationStepUp" class="max-sm:hidden sm:block w-9 flex-shrink-0 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] font-bold hover:text-white hover:border-[#f2a93b]">+</button>
                        </div>
                        <div id="durationPresets" class="max-sm:hidden sm:grid grid-cols-4 gap-1.5 mb-4">
                            <button type="button" data-seconds="30" class="duration-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">30s</button>
                            <button type="button" data-seconds="60" class="duration-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">1m</button>
                            <button type="button" data-seconds="300" class="duration-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">5m</button>
                            <button type="button" data-seconds="900" class="duration-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">15m</button>
                        </div>
                        <button type="button" id="timeFieldTrigger" class="max-sm:absolute max-sm:inset-0 max-sm:z-10 sm:hidden" aria-label="Edit trade duration"></button>
                    </div>

                    <div class="relative">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs text-[#a190c9]">Amount</span>
                            <i class="fa fa-dollar-sign text-[#a190c9]" style="font-size:11px;"></i>
                        </div>
                        <div class="flex items-stretch gap-1.5 mb-2">
                            <button type="button" id="amountStepDown" class="max-sm:hidden sm:block w-9 flex-shrink-0 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] font-bold hover:text-white hover:border-[#f2a93b]">&minus;</button>
                            <input type="text" inputmode="decimal" pattern="^\d*\.?\d*$" id="input_amount_field" name="amount" autocomplete="off" placeholder="10" value="10" readonly
                                class="max-sm:pointer-events-none flex-1 min-w-0 bg-[#3d2570] border border-[#4a2f7a] rounded-lg text-center outline-none text-[#ece5ff] font-semibold text-sm">
                            <button type="button" id="amountStepUp" class="max-sm:hidden sm:block w-9 flex-shrink-0 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] font-bold hover:text-white hover:border-[#f2a93b]">+</button>
                        </div>
                        <div id="amountPresets" class="max-sm:hidden sm:grid grid-cols-4 gap-1.5 mb-4">
                            <button type="button" data-amount="10" class="amount-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">$10</button>
                            <button type="button" data-amount="25" class="amount-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">$25</button>
                            <button type="button" data-amount="50" class="amount-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">$50</button>
                            <button type="button" data-amount="100" class="amount-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#3d2570] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">$100</button>
                        </div>
                        <button type="button" id="amountFieldTrigger" class="max-sm:absolute max-sm:inset-0 max-sm:z-10 sm:hidden" aria-label="Edit trade amount"></button>
                    </div>
                </div>

                {{-- ============ MOBILE TIME PICKER OVERLAY (HH:MM:SS scrubber + presets) ============ --}}
                <div id="timePickerPanel" class="hidden sm:!hidden rounded-xl p-4 mb-4" style="background:#3d2570;border:1px solid #4a2f7a;">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs text-[#a190c9] font-semibold uppercase tracking-wide">Trade Duration</span>
                        <button type="button" id="timePickerClose" class="w-6 h-6 rounded-md flex items-center justify-center text-[#a190c9]"><i class="fa fa-xmark"></i></button>
                    </div>
                    <div class="flex items-center justify-center gap-2.5 mb-4">
                        <div class="flex flex-col items-center gap-1.5">
                            <button type="button" data-tp-unit="h" data-tp-dir="1" class="tp-step w-9 h-8 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:text-white hover:border-[#f2a93b]"><i class="fa fa-chevron-up" style="font-size:10px;"></i></button>
                            <div id="tpHH" class="w-12 text-center text-2xl font-bold text-white tabular-nums">00</div>
                            <button type="button" data-tp-unit="h" data-tp-dir="-1" class="tp-step w-9 h-8 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:text-white hover:border-[#f2a93b]"><i class="fa fa-chevron-down" style="font-size:10px;"></i></button>
                        </div>
                        <span class="text-2xl font-bold text-[#a190c9]">:</span>
                        <div class="flex flex-col items-center gap-1.5">
                            <button type="button" data-tp-unit="m" data-tp-dir="1" class="tp-step w-9 h-8 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:text-white hover:border-[#f2a93b]"><i class="fa fa-chevron-up" style="font-size:10px;"></i></button>
                            <div id="tpMM" class="w-12 text-center text-2xl font-bold text-white tabular-nums">01</div>
                            <button type="button" data-tp-unit="m" data-tp-dir="-1" class="tp-step w-9 h-8 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:text-white hover:border-[#f2a93b]"><i class="fa fa-chevron-down" style="font-size:10px;"></i></button>
                        </div>
                        <span class="text-2xl font-bold text-[#a190c9]">:</span>
                        <div class="flex flex-col items-center gap-1.5">
                            <button type="button" data-tp-unit="s" data-tp-dir="1" class="tp-step w-9 h-8 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:text-white hover:border-[#f2a93b]"><i class="fa fa-chevron-up" style="font-size:10px;"></i></button>
                            <div id="tpSS" class="w-12 text-center text-2xl font-bold text-white tabular-nums">00</div>
                            <button type="button" data-tp-unit="s" data-tp-dir="-1" class="tp-step w-9 h-8 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:text-white hover:border-[#f2a93b]"><i class="fa fa-chevron-down" style="font-size:10px;"></i></button>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-1.5">
                        <button type="button" data-seconds="3" class="tp-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">S3</button>
                        <button type="button" data-seconds="15" class="tp-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">S15</button>
                        <button type="button" data-seconds="30" class="tp-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">S30</button>
                        <button type="button" data-seconds="60" class="tp-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">M1</button>
                        <button type="button" data-seconds="180" class="tp-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">M3</button>
                        <button type="button" data-seconds="300" class="tp-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">M5</button>
                        <button type="button" data-seconds="1800" class="tp-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">M30</button>
                        <button type="button" data-seconds="3600" class="tp-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">H1</button>
                        <button type="button" data-seconds="14400" class="tp-preset text-[11px] font-semibold py-1.5 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#a190c9] hover:border-[#f2a93b] hover:text-white">H4</button>
                    </div>
                </div>

                {{-- ============ MOBILE AMOUNT PICKER OVERLAY (value + calculator keypad) ============ --}}
                <div id="amountPickerPanel" class="hidden sm:!hidden rounded-xl p-4 mb-4" style="background:#3d2570;border:1px solid #4a2f7a;">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs text-[#a190c9] font-semibold uppercase tracking-wide">Trade Amount</span>
                        <button type="button" id="amountPickerClose" class="w-6 h-6 rounded-md flex items-center justify-center text-[#a190c9]"><i class="fa fa-xmark"></i></button>
                    </div>
                    <div id="apDisplay" class="text-center text-3xl font-bold text-white mb-3 tabular-nums">$10</div>
                    <div class="text-center text-[10px] text-[#a190c9] font-semibold mb-2 uppercase tracking-wide">Calculator</div>
                    <div class="grid grid-cols-3 gap-2">
                        <button type="button" data-ap-key="7" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">7</button>
                        <button type="button" data-ap-key="8" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">8</button>
                        <button type="button" data-ap-key="9" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">9</button>
                        <button type="button" data-ap-key="4" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">4</button>
                        <button type="button" data-ap-key="5" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">5</button>
                        <button type="button" data-ap-key="6" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">6</button>
                        <button type="button" data-ap-key="1" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">1</button>
                        <button type="button" data-ap-key="2" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">2</button>
                        <button type="button" data-ap-key="3" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">3</button>
                        <button type="button" data-ap-key="." class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">.</button>
                        <button type="button" data-ap-key="0" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]">0</button>
                        <button type="button" data-ap-key="back" class="ap-key text-base font-bold py-3 rounded-lg bg-[#2d1a5c] border border-[#4a2f7a] text-[#ece5ff] hover:border-[#f2a93b]"><i class="fa fa-delete-left"></i></button>
                    </div>
                </div>

                {{-- Availability is decided client-side by the chart's own
                     live feed (see ChartManager.onAvailabilityChange in
                     chart.js): once a symbol is actively streaming ticks it's
                     tradeable, matching the chart itself rather than a
                     stale server-computed snapshot from page load. While
                     offline, this whole block (payout + BUY/SELL) is swapped
                     out for #assetOfflineNotice below — not just disabled. --}}
                <div id="tradeControlsWrap">
                    {{-- Payout/profit summary: compact single row on mobile, existing stacked box on desktop --}}
                    <div class="hidden max-sm:flex items-center justify-between gap-2 bg-[#3d2570] rounded-lg p-3 mb-4 text-xs">
                        <div>
                            <div class="text-[#a190c9]">Payout</div>
                            <div id="payoutTotalMobile" class="text-white font-bold text-sm">$0.00</div>
                        </div>
                        <div id="profitPercentageMobile" class="text-[#16c087] font-bold text-sm">+{{ number_format($data->asset_profit_margin * 100, 0) }}%</div>
                        <div class="text-right">
                            <div class="text-[#a190c9]">Profit</div>
                            <div id="profitMobile" class="text-[#16c087] font-bold text-sm">+$0.00</div>
                        </div>
                    </div>

                    <div class="sm:block max-sm:hidden">
                        <div class="text-xs text-[#a190c9] mb-1.5">Payout</div>
                        <div class="bg-[#3d2570] rounded-lg p-3 text-center mb-4">
                            <div id="profit_percentage" class="text-[#16c087] font-bold text-base">+{{ number_format($data->asset_profit_margin * 100, 0) }}%</div>
                            <div id="payout" class="text-[#16c087] text-xs mt-0.5">$0.00</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-2 sm:block">
                        <button type="button" data-value="up" class="cta-button w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-2 sm:mb-2.5">
                            <i class="fa fa-arrow-up"></i> BUY
                        </button>
                        <button type="button" data-value="down" class="cta-button w-full bg-rose-500 hover:bg-rose-600 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-2">
                            <i class="fa fa-arrow-down"></i> SELL
                        </button>
                    </div>
                </div>

                <div id="assetOfflineNotice" class="hidden flex-col items-center text-center gap-2.5 rounded-xl p-5" style="background:linear-gradient(180deg,rgba(217,119,6,0.14),rgba(45,26,92,0.5));border:1px solid rgba(217,119,6,0.35);">
                    <div class="w-11 h-11 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(217,119,6,0.15);border:1px solid #d97706;">
                        <i class="fa fa-plug-circle-xmark" style="font-size:16px;color:#d97706;"></i>
                    </div>
                    <div class="text-sm font-bold text-white">Asset Offline</div>
                    <div class="text-xs text-[#a190c9] leading-relaxed">This asset isn't streaming right now. Pick another asset above to keep trading.</div>
                </div>
            </form>
        </div>

        {{-- ============ SECONDARY PANEL (Watch/Trades/Signals/etc). Mobile:
             floating overlay to the left of the icon strip. Desktop (sm: and
             up): classic static column, part of the row. ============ --}}
        <div id="mainContent" class="max-sm:hidden sm:static sm:w-[300px] sm:flex-shrink-0 sm:h-full sm:overflow-y-auto sm:bg-[#2d1a5c] sm:border-0 sm:border-r sm:border-[#4a2f7a]" style="display:none;"></div>

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

        {{-- ============ ICON RAIL (desktop only — mobile uses the bottom
             nav's "More" menu instead). Styled identically to the left
             sidebar rail (layouts/desktop/trading-rail.blade.php). ============ --}}
        <div class="max-sm:hidden sm:flex sm:flex-col sm:items-center sm:w-[78px] sm:flex-shrink-0 sm:p-3.5 sm:px-2 sm:gap-1 sm:box-border">
            <a href="#" class="right-nav-link" data-section="rightMarketWatch" style="background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:10px 6px;width:58px;display:flex;flex-direction:column;align-items:center;gap:6px;font-size:10px;font-weight:700;color:#7c86a3;text-decoration:none;margin-bottom:4px;">
                <i class="fa fa-list" style="font-size:18px;"></i>Watch
            </a>
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
    'historyUrl' => route('assets.history'),
]) !!}
</script>

@push('js')
<script>
    // window.Echo is set up by echo.js, imported by the app.js module bundle
    // loaded in <head>. Module scripts are deferred (execute after parsing,
    // right before DOMContentLoaded) — this inline script sits later in the
    // body and would otherwise run first, hitting window.Echo while it's
    // still undefined and throwing silently (killing this whole block, so
    // none of the real-time trade/balance/sound updates ever engaged).
    // Waiting for DOMContentLoaded guarantees app.js has already run.
    document.addEventListener('DOMContentLoaded', () => {
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
    });
</script>
@endpush
@endsection
