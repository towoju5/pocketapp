import { ChartManager, periodSecondsToKlinePeriod, COLOR_SCHEMES } from './chart.js';
import { parseServerDate, rearmCountdowns } from './tradeCards.js';

const TF_OPTIONS = [
    [5, 'S5'], [15, 'S15'], [30, 'S30'],
    [60, 'M1'], [180, 'M3'], [300, 'M5'], [900, 'M15'], [1800, 'M30'],
];
const CHART_TYPES = ['line', 'candles', 'bars', 'heikin'];
const MAX_TABS = 6;
const TABS_STORAGE_KEY = 'pocketapp:dashboard:tabs';
const CHART_PREFS_KEY = 'pocketapp:dashboard:chartPrefs';
const VALID_PERIOD_SECONDS = TF_OPTIONS.map(([sec]) => sec);
const INDICATORS_STORAGE_KEY = 'pocketapp:dashboard:indicators';
const DRAWINGS_STORAGE_KEY = 'pocketapp:dashboard:drawings';

const INDICATOR_OPTIONS = [
    ['MA', 'Moving Average'], ['EMA', 'EMA'], ['BOLL', 'Bollinger Bands'],
    ['MACD', 'MACD'], ['RSI', 'RSI'], ['KDJ', 'KDJ'], ['VOL', 'Volume'],
];

const DRAWING_TOOL_OPTIONS = [
    ['straightLine', 'Trend Line', 'fa-slash'],
    ['horizontalStraightLine', 'Horizontal Line', 'fa-minus'],
    ['rayLine', 'Ray Line', 'fa-arrow-right'],
    ['fibonacciLine', 'Fibonacci', 'fa-layer-group'],
    ['rect', 'Rectangle', 'fa-square'],
];

function fmtDuration(sec) {
    const h = Math.floor(sec / 3600);
    const m = Math.floor((sec % 3600) / 60);
    const s = sec % 60;
    return [h, m, s].map((n) => String(n).padStart(2, '0')).join(':');
}

function parseDuration(text) {
    const parts = text.split(':').map((n) => parseInt(n, 10) || 0);
    let sec = 0;
    if (parts.length === 3) sec = parts[0] * 3600 + parts[1] * 60 + parts[2];
    else if (parts.length === 2) sec = parts[0] * 60 + parts[1];
    else sec = parts[0] || 60;
    return Math.max(5, sec);
}

export default class TradingDashboard {
    constructor(options) {
        this.options = options;
        // Present only on pages (e.g. dashboard-ui.blade.php / base_url/ui)
        // that opt into driving the asset list + chart from the
        // independent Brokeret broadcast channel (see StreamBrokeretFeed /
        // BrokeretTicksUpdated on the backend) instead of this app's own DB
        // catalog + 'asset-prices' broadcast — see _initLiveFeed(). The
        // backend owns the actual WebSocket connection to Brokeret; the
        // browser only ever talks to this app's own broadcaster.
        this.liveFeedConfig = options.liveFeed || null;
        this.assetsBySymbol = new Map((options.assets || []).map((a) => [a.symbol, a]));

        const restoredTabs = this._restoreTabs();
        const restoredChartPrefs = this._restoreChartPrefs();

        this.state = {
            assetPopoverOpen: false,
            currentCat: options.initialAssetGroup || null,
            assetSearch: '',
            chartTypePopoverOpen: false,
            indicatorsPopoverOpen: false,
            drawingToolsPopoverOpen: false,
            currentChartType: restoredChartPrefs.chartType,
            showArea: restoredChartPrefs.showArea,
            autoScroll: true,
            tfMenuOpen: false,
            periodSeconds: restoredChartPrefs.periodSeconds,
            tfLabel: restoredChartPrefs.tfLabel,
            colorScheme: restoredChartPrefs.colorScheme,
            showGrid: restoredChartPrefs.showGrid,
            activeAssetSymbol: restoredTabs.activeAssetSymbol,
            openTabs: restoredTabs.openTabs,
            activePanel: null,
            tradeDurationSec: 60,
            tradeAmount: 10,
            tradeSubmitting: false,
        };

        this._cacheDom();
        this._bindStaticEvents();
        this._registerShellOverrides();
        this._initChart();
        // Sync the label/ticker/payout UI to whichever tab was actually
        // restored (may differ from the server-rendered initialSymbol) —
        // also renders tabs and applies the chart, so no need to call those separately.
        this._activateAsset(this.state.activeAssetSymbol);
        this._renderTfOptions();
        this._renderChartTypeOptions();
        this._startClock();
        if (this.liveFeedConfig) {
            this._initLiveFeed();
        } else {
            this._startAssetStatusPolling();
        }

        window.toggleTradeMenu = (button, tabKey) => this._toggleTradeMenu(button, tabKey);
    }

    _cacheDom() {
        this.el = {
            assetPopoverBtn: document.getElementById('assetPopoverBtn'),
            assetPopover: document.getElementById('assetPopover'),
            assetCatButtonsContainer: document.getElementById('assetCatButtons'),
            assetRowListContainer: document.getElementById('assetRowList'),
            assetSearchInput: document.getElementById('assetSearchInput'),
            activeAssetLabel: document.getElementById('activeAssetLabel'),
            rateLabel: document.getElementById('rateLabel'),

            chartTypeBtn: document.getElementById('chartTypeBtn'),
            chartTypePopover: document.getElementById('chartTypePopover'),
            chartTypeOptions: document.getElementById('chartTypeOptions'),
            toggleAreaBtn: document.getElementById('toggleAreaBtn'),
            toggleAutoscrollBtn: document.getElementById('toggleAutoscrollBtn'),
            toggleGridBtn: document.getElementById('toggleGridBtn'),
            colorSchemeOptions: document.getElementById('colorSchemeOptions'),

            indicatorsBtn: document.getElementById('indicatorsBtn'),
            indicatorsPopover: document.getElementById('indicatorsPopover'),
            indicatorOptions: document.getElementById('indicatorOptions'),

            drawingToolsBtn: document.getElementById('drawingToolsBtn'),
            drawingToolsPopover: document.getElementById('drawingToolsPopover'),
            drawingToolOptions: document.getElementById('drawingToolOptions'),
            clearDrawingsBtn: document.getElementById('clearDrawingsBtn'),

            assetTabs: document.getElementById('assetTabs'),
            addTabBtn: document.getElementById('addTabBtn'),

            klineChart: document.getElementById('kline-chart'),
            liveClock: document.getElementById('liveClock'),
            livePrice: document.getElementById('livePrice'),
            sourceDot: document.getElementById('sourceDot'),
            sourceLabel: document.getElementById('sourceLabel'),
            assetUnavailableBanner: document.getElementById('assetUnavailableBanner'),
            tradeControlsWrap: document.getElementById('tradeControlsWrap'),
            assetOfflineNotice: document.getElementById('assetOfflineNotice'),
            ctaButtons: document.querySelectorAll('.cta-button'),

            tfBtn: document.getElementById('tfBtn'),
            tfLabel: document.getElementById('tfLabel'),
            tfMenu: document.getElementById('tfMenu'),
            tfOptions: document.getElementById('tfOptions'),

            tradeForm: document.getElementById('tradeForm'),
            durationInput: document.getElementById('hs-trailing-icon'),
            durationStepDown: document.getElementById('durationStepDown'),
            durationStepUp: document.getElementById('durationStepUp'),
            durationPresetButtons: document.querySelectorAll('.duration-preset'),
            assetTicker: document.getElementById('assetTicker'),
            amountInput: document.getElementById('input_amount_field'),
            amountStepDown: document.getElementById('amountStepDown'),
            amountStepUp: document.getElementById('amountStepUp'),
            amountPresetButtons: document.querySelectorAll('.amount-preset'),
            directionInput: document.getElementById('direction'),
            profitPercentage: document.getElementById('profit_percentage'),
            payout: document.getElementById('payout'),

            railButtons: document.querySelectorAll('.right-nav-link'),
            mainContent: document.getElementById('mainContent'),
            hiddenSections: document.getElementById('hidden-sections'),

            timeFieldTrigger: document.getElementById('timeFieldTrigger'),
            timePickerPanel: document.getElementById('timePickerPanel'),
            timePickerClose: document.getElementById('timePickerClose'),
            tpHH: document.getElementById('tpHH'),
            tpMM: document.getElementById('tpMM'),
            tpSS: document.getElementById('tpSS'),
            tpStepButtons: document.querySelectorAll('.tp-step'),
            tpPresetButtons: document.querySelectorAll('.tp-preset'),

            amountFieldTrigger: document.getElementById('amountFieldTrigger'),
            amountPickerPanel: document.getElementById('amountPickerPanel'),
            amountPickerClose: document.getElementById('amountPickerClose'),
            apDisplay: document.getElementById('apDisplay'),
            apKeyButtons: document.querySelectorAll('.ap-key'),
        };
    }

    /** Live NodeLists queried on demand rather than cached once — dashboard-ui's live-feed mode appends category buttons/rows well after construction. */
    _assetCatButtons() {
        return this.el.assetCatButtonsContainer?.querySelectorAll('.asset-cat-btn') ?? [];
    }

    _assetRows() {
        return this.el.assetRowListContainer?.querySelectorAll('.asset-row') ?? [];
    }

    _bindStaticEvents() {
        this.el.assetPopoverBtn?.addEventListener('click', () => this._toggleAssetPopover());
        this.el.addTabBtn?.addEventListener('click', () => this._toggleAssetPopover(true));
        // Delegated (not bound per-element) so category buttons/rows added
        // dynamically after construction — live-feed mode — work with no
        // extra wiring.
        this.el.assetCatButtonsContainer?.addEventListener('click', (e) => {
            const btn = e.target.closest('.asset-cat-btn');
            if (btn) this._selectCategory(btn.dataset.cat);
        });
        this.el.assetSearchInput?.addEventListener('input', (e) => this._filterAssetRows(e.target.value));
        this.el.assetRowListContainer?.addEventListener('click', (e) => {
            const row = e.target.closest('.asset-row');
            if (row) this._selectAsset(row.dataset.symbol);
        });

        this.el.chartTypeBtn?.addEventListener('click', () => this._toggleChartTypePopover());
        this.el.toggleAreaBtn?.addEventListener('click', () => this._toggleArea());
        this.el.toggleAutoscrollBtn?.addEventListener('click', () => this._toggleAutoscroll());
        this.el.toggleGridBtn?.addEventListener('click', () => this._toggleGrid());

        this.el.indicatorsBtn?.addEventListener('click', () => this._toggleIndicatorsPopover());
        this.el.drawingToolsBtn?.addEventListener('click', () => this._toggleDrawingToolsPopover());
        this.el.clearDrawingsBtn?.addEventListener('click', () => this._clearDrawings());

        this.el.tfBtn?.addEventListener('click', () => this._toggleTfMenu());

        this.el.durationInput?.addEventListener('change', (e) => this._onDurationInput(e.target.value));
        this.el.durationStepDown?.addEventListener('click', () => this._stepDuration(-30));
        this.el.durationStepUp?.addEventListener('click', () => this._stepDuration(30));
        this.el.durationPresetButtons.forEach((btn) => {
            btn.addEventListener('click', () => this._setDuration(parseInt(btn.dataset.seconds, 10)));
        });

        this.el.amountInput?.addEventListener('input', (e) => this._onAmountInput(e.target.value));
        this.el.amountStepDown?.addEventListener('click', () => this._stepAmount(-5));
        this.el.amountStepUp?.addEventListener('click', () => this._stepAmount(5));
        this.el.amountPresetButtons.forEach((btn) => {
            btn.addEventListener('click', () => this._setAmount(parseFloat(btn.dataset.amount)));
        });

        this.el.timeFieldTrigger?.addEventListener('click', () => this._toggleTimePicker());
        this.el.timePickerClose?.addEventListener('click', () => this._toggleTimePicker(false));
        this.el.tpStepButtons.forEach((btn) => {
            btn.addEventListener('click', () => this._stepTimePickerUnit(btn.dataset.tpUnit, parseInt(btn.dataset.tpDir, 10)));
        });
        this.el.tpPresetButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                this._setDuration(parseInt(btn.dataset.seconds, 10));
                this._syncTimePickerDisplay();
            });
        });

        this.el.amountFieldTrigger?.addEventListener('click', () => this._toggleAmountPicker());
        this.el.amountPickerClose?.addEventListener('click', () => this._toggleAmountPicker(false));
        this.el.apKeyButtons.forEach((btn) => {
            btn.addEventListener('click', () => this._onApKey(btn.dataset.apKey));
        });

        this.el.ctaButtons.forEach((btn) => {
            btn.addEventListener('click', () => this._submitTrade(btn.dataset.value));
        });

        this.el.railButtons.forEach((btn) => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                this._onRail(btn.dataset.section);
            });
        });

        document.addEventListener('click', (e) => this._onOutsideClick(e));
    }

    // ---- shared shell (left-nav flyout, balance/avatar menus — owned by TradingShell) ----

    _registerShellOverrides() {
        // "Market" has no flyout section of its own — on the dashboard it opens the asset popover instead.
        window.tradingShell?.registerNavOverride('market', () => this._toggleAssetPopover(true));
    }

    // ---- asset popover ----

    _toggleAssetPopover(forceOpen) {
        this.state.assetPopoverOpen = forceOpen === true ? true : !this.state.assetPopoverOpen;
        this.state.chartTypePopoverOpen = false;
        this.el.assetPopover?.classList.toggle('hidden', !this.state.assetPopoverOpen);
        this.el.chartTypePopover?.classList.add('hidden');
    }

    _selectCategory(cat) {
        this.state.currentCat = cat;
        this._assetCatButtons().forEach((btn) => {
            btn.classList.toggle('asset-cat-btn--active', btn.dataset.cat === cat);
        });
        this._assetRows().forEach((row) => {
            row.classList.toggle('hidden', row.dataset.cat !== cat);
        });
    }

    _filterAssetRows(search) {
        const needle = search.trim().toLowerCase();
        this._assetRows().forEach((row) => {
            const matchesCat = !needle || row.dataset.cat === this.state.currentCat;
            const matchesSearch = !needle || (row.dataset.search || '').includes(needle);
            row.classList.toggle('hidden', needle ? !matchesSearch : row.dataset.cat !== this.state.currentCat);
            void matchesCat;
        });
    }

    _selectAsset(symbol) {
        if (!symbol || !this.assetsBySymbol.has(symbol)) return;
        this.state.assetPopoverOpen = false;
        this.el.assetPopover?.classList.add('hidden');

        if (!this.state.openTabs.includes(symbol)) {
            const tabs = this.state.openTabs.slice();
            if (tabs.length >= MAX_TABS) {
                const removed = tabs.shift();
                this.chart.closeTab(removed);
            }
            tabs.push(symbol);
            this.state.openTabs = tabs;
        }
        this._activateAsset(symbol);
        this._renderTabs();
    }

    _closeTab(symbol, event) {
        event.stopPropagation();
        if (this.state.openTabs.length <= 1) return;
        const idx = this.state.openTabs.indexOf(symbol);
        this.state.openTabs = this.state.openTabs.filter((s) => s !== symbol);
        this.chart.closeTab(symbol);
        if (this.state.activeAssetSymbol === symbol) {
            const next = this.state.openTabs[Math.max(0, idx - 1)];
            this._activateAsset(next);
        }
        this._renderTabs();
    }

    _restoreTabs() {
        let saved = null;
        try {
            saved = JSON.parse(localStorage.getItem(TABS_STORAGE_KEY) || 'null');
        } catch (e) {
            saved = null;
        }

        let openTabs = Array.isArray(saved?.openTabs)
            ? saved.openTabs.filter((symbol) => this.assetsBySymbol.has(symbol))
            : [];

        // Only fall back to the server-rendered default asset when nothing was
        // actually restored (first-ever visit, or localStorage was cleared) —
        // existing restored tabs shouldn't have the default forced back in.
        if (openTabs.length === 0) {
            openTabs.push(this.options.initialSymbol);
        }
        if (openTabs.length > MAX_TABS) openTabs = openTabs.slice(-MAX_TABS);

        const activeAssetSymbol = (saved?.activeAssetSymbol && openTabs.includes(saved.activeAssetSymbol))
            ? saved.activeAssetSymbol
            : this.options.initialSymbol;

        return { openTabs, activeAssetSymbol };
    }

    _persistTabs() {
        try {
            localStorage.setItem(TABS_STORAGE_KEY, JSON.stringify({
                openTabs: this.state.openTabs,
                activeAssetSymbol: this.state.activeAssetSymbol,
            }));
        } catch (e) {
            // Storage unavailable (private mode, quota, etc.) — tabs just won't persist.
        }
    }

    _restoreChartPrefs() {
        // Default to a short, tick-level timeframe (5s) rather than M1 — binary
        // trades commonly expire in 5-60s, and a 1-minute default candle span
        // shows ~90 minutes of history at default zoom, which flattens exactly
        // the kind of second-to-second movement that matters for short expiries.
        const defaults = { chartType: 'line', showArea: true, periodSeconds: 5, tfLabel: 'S5', colorScheme: 'purple', showGrid: true };
        try {
            const saved = JSON.parse(localStorage.getItem(CHART_PREFS_KEY) || 'null');
            if (!saved) return defaults;

            return {
                chartType: CHART_TYPES.includes(saved.chartType) ? saved.chartType : defaults.chartType,
                showArea: typeof saved.showArea === 'boolean' ? saved.showArea : defaults.showArea,
                periodSeconds: VALID_PERIOD_SECONDS.includes(saved.periodSeconds) ? saved.periodSeconds : defaults.periodSeconds,
                tfLabel: typeof saved.tfLabel === 'string' ? saved.tfLabel : defaults.tfLabel,
                colorScheme: COLOR_SCHEMES[saved.colorScheme] ? saved.colorScheme : defaults.colorScheme,
                showGrid: typeof saved.showGrid === 'boolean' ? saved.showGrid : defaults.showGrid,
            };
        } catch (e) {
            return defaults;
        }
    }

    _persistChartPrefs() {
        try {
            localStorage.setItem(CHART_PREFS_KEY, JSON.stringify({
                chartType: this.state.currentChartType,
                showArea: this.state.showArea,
                periodSeconds: this.state.periodSeconds,
                tfLabel: this.state.tfLabel,
                colorScheme: this.state.colorScheme,
                showGrid: this.state.showGrid,
            }));
        } catch (e) {
            // Storage unavailable — chart prefs just won't persist.
        }
    }

    _renderTabs() {
        this._persistTabs();
        if (!this.el.assetTabs) return;
        this.el.assetTabs.innerHTML = '';
        this.state.openTabs.forEach((symbol) => {
            const asset = this.assetsBySymbol.get(symbol) || { symbol, name: symbol };
            const active = symbol === this.state.activeAssetSymbol;
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'asset-tab-chip' + (active ? ' asset-tab-chip--active' : '');
            chip.innerHTML = `<span>${asset.symbol}</span>`;
            if (this.state.openTabs.length > 1) {
                const close = document.createElement('span');
                close.className = 'asset-tab-chip__close';
                close.textContent = '✕';
                close.addEventListener('click', (e) => this._closeTab(symbol, e));
                chip.appendChild(close);
            }
            chip.addEventListener('click', () => this._activateAsset(symbol));
            this.el.assetTabs.appendChild(chip);
        });
    }

    /**
     * How many decimal places this symbol's price should be quoted/scaled to.
     * Standard FX convention: JPY-quote pairs move in units ~100x larger than
     * other majors (100 JPY ≈ 1 USD), so they're conventionally shown with 2
     * fewer decimals (3 vs 5) for the SAME pip-level granularity — this isn't
     * "less precise", it's the correct scale for that pair. Crypto/indices
     * trade at much larger nominal prices, so 2 decimals is standard there.
     * This also sets klinecharts' y-axis minimum-span floor (10^-precision),
     * so getting this right per-asset (instead of one flat value for every
     * instrument) is what lets the chart zoom in tightly on small moves.
     */
    _inferPricePrecision(symbol) {
        const upper = symbol.toUpperCase();
        if (/(BTC|ETH|XRP|LTC|BNB|SOL|DOGE)/.test(upper)) return 2;
        if (/JPY/.test(upper)) return 3;
        return 5;
    }

    _activateAsset(symbol) {
        const asset = this.assetsBySymbol.get(symbol);
        if (!asset) return;

        this.state.activeAssetSymbol = symbol;
        // Seed from the backend's last-known online status; the client-side
        // tick-based check (chart.js) refines this once ticks start arriving.
        this._onAssetAvailabilityChange(symbol, asset.online !== false);
        this.chart?.activate(symbol, this._inferPricePrecision(symbol));

        if (this.el.assetTicker) this.el.assetTicker.value = symbol.replace(/\//g, '--');
        if (this.el.activeAssetLabel) this.el.activeAssetLabel.textContent = symbol;
        if (this.el.rateLabel) this.el.rateLabel.textContent = asset.name || symbol;

        this._updatePayoutDisplay(asset.asset_profit_margin);
        this._renderTabs();
    }

    // ---- chart type popover ----

    _toggleChartTypePopover() {
        this.state.chartTypePopoverOpen = !this.state.chartTypePopoverOpen;
        this.state.assetPopoverOpen = false;
        this.el.chartTypePopover?.classList.toggle('hidden', !this.state.chartTypePopoverOpen);
        this.el.assetPopover?.classList.add('hidden');
    }

    _renderChartTypeOptions() {
        if (!this.el.chartTypeOptions) return;
        this.el.chartTypeOptions.innerHTML = '';
        const labels = { line: 'Line', candles: 'Candles', bars: 'Bars', heikin: 'Heikin Ashi' };
        CHART_TYPES.forEach((type) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'chart-type-btn' + (type === this.state.currentChartType ? ' chart-type-btn--active' : '');
            btn.textContent = labels[type];
            btn.addEventListener('click', () => this._selectChartType(type));
            this.el.chartTypeOptions.appendChild(btn);
        });
    }

    _selectChartType(type) {
        this.state.currentChartType = type;
        this.chart.setChartType(type);
        this._renderChartTypeOptions();
        this._persistChartPrefs();
    }

    _toggleArea() {
        this.state.showArea = !this.state.showArea;
        this.chart.toggleArea(this.state.showArea);
        this.el.toggleAreaBtn?.classList.toggle('toggle--on', this.state.showArea);
        this._persistChartPrefs();
    }

    _toggleAutoscroll(force) {
        this.state.autoScroll = typeof force === 'boolean' ? force : !this.state.autoScroll;
        this.el.toggleAutoscrollBtn?.classList.toggle('toggle--on', this.state.autoScroll);
        // Re-centering immediately on re-enable means the user doesn't have
        // to wait for the next price tick to jump back to center.
        if (this.state.autoScroll) this.chart?.scrollToRealTime();
    }

    _toggleGrid() {
        this.state.showGrid = !this.state.showGrid;
        this.chart.setShowGrid(this.state.showGrid);
        this.el.toggleGridBtn?.classList.toggle('toggle--on', this.state.showGrid);
        this._persistChartPrefs();
    }

    _renderColorSchemeOptions() {
        if (!this.el.colorSchemeOptions) return;
        this.el.colorSchemeOptions.innerHTML = '';
        Object.entries(COLOR_SCHEMES).forEach(([key, scheme]) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.title = scheme.name;
            btn.className = 'h-8 rounded-lg border' + (key === this.state.colorScheme ? ' border-[#4f8ef7]' : ' border-[#2a3350]');
            btn.style.background = `linear-gradient(90deg, ${scheme.up} 50%, ${scheme.down} 50%)`;
            btn.addEventListener('click', () => this._setColorScheme(key));
            this.el.colorSchemeOptions.appendChild(btn);
        });
    }

    _setColorScheme(key) {
        this.state.colorScheme = key;
        this.chart.setColorScheme(key);
        this._renderColorSchemeOptions();
        this._persistChartPrefs();
    }

    // ---- indicators ----

    _toggleIndicatorsPopover() {
        this.state.indicatorsPopoverOpen = !this.state.indicatorsPopoverOpen;
        this.state.drawingToolsPopoverOpen = false;
        this.el.indicatorsPopover?.classList.toggle('hidden', !this.state.indicatorsPopoverOpen);
        this.el.drawingToolsPopover?.classList.add('hidden');
    }

    _renderIndicatorOptions() {
        if (!this.el.indicatorOptions) return;
        this.el.indicatorOptions.innerHTML = '';
        INDICATOR_OPTIONS.forEach(([name, label]) => {
            const row = document.createElement('label');
            row.className = 'flex items-center justify-between py-1.5 text-[13px] cursor-pointer';
            const active = this.chart?.isIndicatorActive(name) ?? false;
            row.innerHTML = `<span>${label}</span>`;
            const toggle = document.createElement('button');
            toggle.type = 'button';
            toggle.className = 'toggle' + (active ? ' toggle--on' : '');
            toggle.addEventListener('click', () => this._toggleIndicator(name, toggle));
            row.appendChild(toggle);
            this.el.indicatorOptions.appendChild(row);
        });
    }

    _toggleIndicator(name, toggleEl) {
        if (this.chart?.isIndicatorActive(name)) {
            this.chart.removeIndicator(name);
        } else {
            this.chart?.addIndicator(name);
        }
        toggleEl?.classList.toggle('toggle--on', this.chart?.isIndicatorActive(name) ?? false);
        this._persistIndicators();
    }

    _persistIndicators() {
        try {
            localStorage.setItem(INDICATORS_STORAGE_KEY, JSON.stringify(this.chart?.getActiveIndicatorNames() ?? []));
        } catch (e) {
            // Storage unavailable — indicators just won't persist.
        }
    }

    _restoreIndicators() {
        try {
            const saved = JSON.parse(localStorage.getItem(INDICATORS_STORAGE_KEY) || '[]');
            if (Array.isArray(saved)) {
                saved.forEach((name) => {
                    if (INDICATOR_OPTIONS.some(([n]) => n === name)) this.chart?.addIndicator(name);
                });
            }
        } catch (e) {
            // Ignore malformed storage — start with no indicators.
        }
    }

    // ---- drawing tools ----

    _toggleDrawingToolsPopover() {
        this.state.drawingToolsPopoverOpen = !this.state.drawingToolsPopoverOpen;
        this.state.indicatorsPopoverOpen = false;
        this.el.drawingToolsPopover?.classList.toggle('hidden', !this.state.drawingToolsPopoverOpen);
        this.el.indicatorsPopover?.classList.add('hidden');
    }

    _renderDrawingToolOptions() {
        if (!this.el.drawingToolOptions) return;
        this.el.drawingToolOptions.innerHTML = '';
        DRAWING_TOOL_OPTIONS.forEach(([overlayName, label, icon]) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'w-full text-left px-2 py-1.5 rounded-lg text-[13px] text-[#d7dcea] hover:bg-[#1c243c] flex items-center gap-2';
            btn.innerHTML = `<i class="fa ${icon}" style="font-size:11px;width:14px;"></i> ${label}`;
            btn.addEventListener('click', () => this._startDrawing(overlayName));
            this.el.drawingToolOptions.appendChild(btn);
        });
    }

    _startDrawing(overlayName) {
        this.chart?.startDrawing(overlayName);
        this.state.drawingToolsPopoverOpen = false;
        this.el.drawingToolsPopover?.classList.add('hidden');
    }

    _clearDrawings() {
        this.chart?.clearDrawings();
    }

    _persistDrawings() {
        try {
            localStorage.setItem(DRAWINGS_STORAGE_KEY, JSON.stringify(this.chart?.serializeDrawings() ?? []));
        } catch (e) {
            // Storage unavailable — drawings just won't persist.
        }
    }

    _restoreDrawings() {
        try {
            const saved = JSON.parse(localStorage.getItem(DRAWINGS_STORAGE_KEY) || '[]');
            if (Array.isArray(saved) && saved.length) this.chart?.restoreDrawings(saved);
        } catch (e) {
            // Ignore malformed storage — start with no drawings.
        }
    }

    // ---- timeframe menu ----

    _toggleTfMenu() {
        this.state.tfMenuOpen = !this.state.tfMenuOpen;
        this.el.tfMenu?.classList.toggle('hidden', !this.state.tfMenuOpen);
    }

    _renderTfOptions() {
        if (!this.el.tfOptions) return;
        this.el.tfOptions.innerHTML = '';
        TF_OPTIONS.forEach(([sec, label]) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'tf-option-btn' + (sec === this.state.periodSeconds ? ' tf-option-btn--active' : '');
            btn.textContent = label;
            btn.addEventListener('click', () => this._selectTf(sec, label));
            this.el.tfOptions.appendChild(btn);
        });
    }

    _selectTf(sec, label) {
        this.state.periodSeconds = sec;
        this.state.tfLabel = label;
        this.state.tfMenuOpen = false;
        this.el.tfMenu?.classList.add('hidden');
        if (this.el.tfLabel) this.el.tfLabel.textContent = label;
        this.chart.setPeriod(sec, periodSecondsToKlinePeriod(sec));
        this._renderTfOptions();
        this._persistChartPrefs();
    }

    // ---- trade panel ----

    _onDurationInput(text) {
        this.state.tradeDurationSec = parseDuration(text);
        if (this.el.durationInput) this.el.durationInput.value = fmtDuration(this.state.tradeDurationSec);
    }

    _stepDuration(deltaSec) {
        this.state.tradeDurationSec = Math.max(5, this.state.tradeDurationSec + deltaSec);
        if (this.el.durationInput) this.el.durationInput.value = fmtDuration(this.state.tradeDurationSec);
    }

    _setDuration(seconds) {
        this.state.tradeDurationSec = Math.max(5, seconds);
        if (this.el.durationInput) this.el.durationInput.value = fmtDuration(this.state.tradeDurationSec);
    }

    _onAmountInput(value) {
        const v = parseFloat(value);
        this.state.tradeAmount = Number.isNaN(v) || v < 1 ? 1 : v;
        this._updatePayoutDisplay();
    }

    _stepAmount(delta) {
        this.state.tradeAmount = Math.max(1, Math.round((this.state.tradeAmount + delta) * 100) / 100);
        if (this.el.amountInput) this.el.amountInput.value = this.state.tradeAmount;
        this._updatePayoutDisplay();
    }

    _setAmount(amount) {
        this.state.tradeAmount = Math.max(1, amount);
        if (this.el.amountInput) this.el.amountInput.value = this.state.tradeAmount;
        this._updatePayoutDisplay();
    }

    // ---- mobile time/amount picker overlays ----

    _toggleTimePicker(force) {
        const next = typeof force === 'boolean' ? force : !!this.el.timePickerPanel?.classList.contains('hidden');
        if (next) this._toggleAmountPicker(false);
        this.el.timePickerPanel?.classList.toggle('hidden', !next);
        if (next) this._syncTimePickerDisplay();
    }

    _syncTimePickerDisplay() {
        const sec = this.state.tradeDurationSec;
        const h = Math.floor(sec / 3600);
        const m = Math.floor((sec % 3600) / 60);
        const s = sec % 60;
        if (this.el.tpHH) this.el.tpHH.textContent = String(h).padStart(2, '0');
        if (this.el.tpMM) this.el.tpMM.textContent = String(m).padStart(2, '0');
        if (this.el.tpSS) this.el.tpSS.textContent = String(s).padStart(2, '0');
    }

    _stepTimePickerUnit(unit, dir) {
        const deltas = { h: 3600, m: 60, s: 1 };
        const delta = (deltas[unit] || 1) * dir;
        this.state.tradeDurationSec = Math.max(5, this.state.tradeDurationSec + delta);
        if (this.el.durationInput) this.el.durationInput.value = fmtDuration(this.state.tradeDurationSec);
        this._syncTimePickerDisplay();
    }

    _toggleAmountPicker(force) {
        const next = typeof force === 'boolean' ? force : !!this.el.amountPickerPanel?.classList.contains('hidden');
        if (next) this._toggleTimePicker(false);
        this.el.amountPickerPanel?.classList.toggle('hidden', !next);
        if (next) {
            this.state.apBuffer = String(this.state.tradeAmount);
            this._syncAmountPickerDisplay();
        }
    }

    _syncAmountPickerDisplay() {
        if (this.el.apDisplay) this.el.apDisplay.textContent = `$${this.state.apBuffer || '0'}`;
    }

    _onApKey(key) {
        let buf = this.state.apBuffer ?? String(this.state.tradeAmount);
        if (key === 'back') {
            buf = buf.length > 1 ? buf.slice(0, -1) : '';
        } else if (key === '.') {
            if (!buf.includes('.')) buf += (buf === '' ? '0.' : '.');
        } else {
            buf += key;
        }
        this.state.apBuffer = buf;
        this._syncAmountPickerDisplay();

        const v = parseFloat(buf);
        if (!Number.isNaN(v)) {
            this.state.tradeAmount = Math.max(1, v);
            if (this.el.amountInput) this.el.amountInput.value = this.state.tradeAmount;
            this._updatePayoutDisplay();
        }
    }

    _updatePayoutDisplay(marginOverride) {
        const asset = this.assetsBySymbol.get(this.state.activeAssetSymbol);
        // asset_profit_margin is a fraction (0.92 == 92%), not a 0-100 percentage.
        const margin = parseFloat(marginOverride ?? asset?.asset_profit_margin ?? this.options.initialProfitMargin ?? 0);
        const pct = (margin * 100).toFixed(0);
        const profit = margin * this.state.tradeAmount;
        if (this.el.profitPercentage) this.el.profitPercentage.textContent = `+${pct}%`;
        if (this.el.payout) {
            this.el.payout.textContent = `$${profit.toFixed(2)}`;
        }
        document.getElementById('ctaPercentUp')?.replaceChildren(document.createTextNode(`${pct}%`));
        document.getElementById('ctaPercentDown')?.replaceChildren(document.createTextNode(`${pct}%`));

        // Compact mobile summary row (see resources/views/__dash.blade.php) —
        // purely additive, only present in the mobile layout markup.
        document.getElementById('profitPercentageMobile')?.replaceChildren(document.createTextNode(`+${pct}%`));
        document.getElementById('profitMobile')?.replaceChildren(document.createTextNode(`+$${profit.toFixed(2)}`));
        document.getElementById('payoutTotalMobile')?.replaceChildren(document.createTextNode(`$${(this.state.tradeAmount + profit).toFixed(2)}`));
    }

    async _submitTrade(direction) {
        if (!this.el.tradeForm || this.state.tradeSubmitting) return;

        this.state.tradeSubmitting = true;
        this.el.ctaButtons.forEach((btn) => { btn.disabled = true; });

        if (this.el.directionInput) this.el.directionInput.value = direction;

        // Optimistic UI: the request/response round trip (network + Laravel
        // bootstrap + DB writes) is real time the click otherwise just sits
        // there — showing the card/countdown/chart marker off the same
        // inputs the server will use (current price, amount, duration)
        // before the response even lands makes the click feel instant
        // regardless of that latency. Reconciled with the real trade below;
        // removed outright if placement actually fails.
        const optimistic = this._renderOptimisticTradeCard(direction);

        try {
            const formData = new FormData(this.el.tradeForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const res = await fetch(this.el.tradeForm.getAttribute('action'), {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    ...(csrfToken ? { 'X-CSRF-TOKEN': csrfToken } : {}),
                },
                body: formData,
            });
            const data = await res.json().catch(() => null);

            if (res.ok && data?.status) {
                window.toastr?.success(data.message || 'Trade placed successfully!');
                this._removeOptimisticTradeCard(optimistic?.id);
                // Insert + arm the countdown from this response rather than
                // waiting on the TradeUpdated broadcast (which is
                // ShouldBroadcast — queued — and can lag behind a busy/absent
                // worker): reuses the exact same insert/countdown logic the
                // live broadcast path uses (tradeCards.js), just fed from the
                // XHR response instead of the socket event. The broadcast
                // still arrives shortly after and reconciles wallet_balance,
                // which isn't in this response.
                if (data.trade && data.html && window.updateOrInsertTradeCard) {
                    window.updateOrInsertTradeCard({ ...data.trade, html: data.html });
                }
                if (data.trade?.trade_close_time && data.trade?.trade_currency) {
                    const expiryDate = parseServerDate(data.trade.trade_close_time);
                    const entryPrice = data.trade.start_price != null ? parseFloat(data.trade.start_price) : undefined;
                    if (expiryDate) {
                        this.chart?.setExpiryLine(data.trade.id, data.trade.trade_currency, expiryDate.getTime(), entryPrice);
                    }
                }
            } else {
                this._removeOptimisticTradeCard(optimistic?.id);
                const message = data?.message || data?.errors || 'Unable to place trade. Please try again.';
                window.toastr?.error(typeof message === 'string' ? message : 'Unable to place trade. Please try again.');
            }
        } catch (e) {
            this._removeOptimisticTradeCard(optimistic?.id);
            console.error('[TradingDashboard] trade submit failed', e);
            window.toastr?.error('Unable to place trade. Please try again.');
        } finally {
            this.state.tradeSubmitting = false;
            const asset = this.assetsBySymbol.get(this.state.activeAssetSymbol);
            this.el.ctaButtons.forEach((btn) => { btn.disabled = asset?.online === false; });
        }
    }

    /**
     * Builds and inserts a provisional trade card off the same inputs the
     * server is about to receive (symbol, amount, duration, direction) plus
     * whatever price is currently on screen — not the server's actual
     * entry price, which is read from Redis a moment later in placeTrade().
     * Purely a perceived-latency trick (see _submitTrade): the real trade
     * from the server response replaces this one outright, so a few cents
     * of drift between this provisional price and the real entry price is
     * never visible for more than the length of the request.
     */
    _renderOptimisticTradeCard(direction) {
        const list = document.getElementById('openTradeList');
        if (!list) return null;

        const symbol = this.state.activeAssetSymbol;
        const asset = this.assetsBySymbol.get(symbol);
        const amount = this.state.tradeAmount;
        const margin = parseFloat(asset?.asset_profit_margin ?? this.options.initialProfitMargin ?? 0);
        const payout = amount + margin * amount;
        const profit = margin * amount;
        const pct = (margin * 100).toFixed(0);
        const closeTimeMs = Date.now() + this.state.tradeDurationSec * 1000;
        const entryPrice = this.chart?.feeds.get(symbol)?.lastPrice;
        const id = `optimistic-${Date.now()}`;
        const up = direction === 'up';

        document.getElementById('openTradeListEmpty')?.remove();
        const wrapper = document.createElement('div');
        wrapper.innerHTML = `
            <div class="trade-card" id="trade-card-${id}" style="border-left-color:#f2a93b;">
                <div class="trade-card__row">
                    <div class="trade-card__asset">
                        <span class="trade-card__dir trade-card__dir--${up ? 'up' : 'down'}"><i class="fas fa-arrow-${up ? 'up' : 'down'}"></i></span>
                        <span class="trade-card__symbol">${symbol}</span>
                        <span class="trade-card__pct">+${pct}%</span>
                    </div>
                    <div class="trade-card__countdown" id="countdown-${id}">--:--:--</div>
                </div>
                <div class="trade-card__row trade-card__row--figures">
                    <div><div class="trade-card__label">Stake</div><div class="trade-card__value">$${amount.toFixed(2)}</div></div>
                    <div><div class="trade-card__label">Potential Payout</div><div class="trade-card__value">$${payout.toFixed(2)}</div></div>
                    <div><div class="trade-card__label">Potential Profit</div><div class="trade-card__value">+$${profit.toFixed(2)}</div></div>
                </div>
            </div>
        `.trim();
        list.prepend(wrapper.firstElementChild);

        window.startCountdowns?.([{
            id, trade_currency: symbol,
            trade_close_time: new Date(closeTimeMs).toISOString(),
            start_price: entryPrice,
        }]);

        return { id };
    }

    _removeOptimisticTradeCard(id) {
        if (!id) return;
        document.getElementById(`trade-card-${id}`)?.remove();
        this.chart?.clearExpiryLine(id);
    }

    // ---- right rail (trades/signals/social/express/tournaments/pending/hotkeys) ----

    _onRail(sectionId) {
        const isSame = this.state.activePanel === sectionId;
        this.state.activePanel = isSame ? null : sectionId;

        if (!this.el.mainContent || !this.el.hiddenSections) return;

        this._stopMarketWatchTicker();
        if (this.state.activePanel === 'rightMarketWatch') {
            this.el.mainContent.style.display = 'block';
            this._renderMarketWatch();
            this._startMarketWatchTicker();
        } else if (this.state.activePanel) {
            const source = this.el.hiddenSections.querySelector(`#${this.state.activePanel}`);
            this.el.mainContent.style.display = 'block';
            this.el.mainContent.innerHTML = source ? source.innerHTML : '';
            // Cloning via innerHTML produces brand-new DOM nodes with no
            // memory of any countdown interval ticking on the node it was
            // cloned from — re-arm every countdown in the freshly-shown
            // copy from its own data-close-time attribute so it actually
            // counts down instead of sitting frozen (see tradeCards.js).
            rearmCountdowns(this.el.mainContent);
        } else {
            this.el.mainContent.style.display = 'none';
            this.el.mainContent.innerHTML = '';
        }
        this.el.railButtons.forEach((btn) => {
            btn.classList.toggle('right-nav-link--active', btn.dataset.section === this.state.activePanel);
        });
    }

    // ---- market watch ----

    _renderMarketWatch() {
        if (!this.el.mainContent) return;
        const assets = Array.from(this.assetsBySymbol.values());
        const rows = assets.map((asset) => `
            <div class="market-watch-row" data-symbol="${asset.symbol}" data-search="${(asset.symbol + ' ' + (asset.name || '')).toLowerCase()}"
                style="display:flex;align-items:center;justify-content:space-between;padding:10px 14px;border-top:1px solid #1c243c;cursor:pointer;">
                <div>
                    <div style="font-size:13px;font-weight:600;color:#d7dcea;">${asset.symbol}</div>
                    <div style="font-size:11px;color:#7c86a3;">${asset.name || ''}</div>
                </div>
                <div style="text-align:right;">
                    <div class="mw-price" style="font-size:13px;font-weight:600;color:#d7dcea;">&mdash;</div>
                    <div class="mw-status" style="font-size:10px;color:#7c86a3;">&mdash;</div>
                </div>
            </div>`).join('');

        this.el.mainContent.innerHTML = `
            <div style="padding:14px 14px 8px;">
                <input type="text" id="marketWatchSearch" placeholder="Search assets"
                    style="width:100%;background:#1c243c;border:1px solid #2a3350;border-radius:8px;padding:8px 10px;font-size:12px;color:#d7dcea;outline:none;box-sizing:border-box;">
            </div>
            <div id="marketWatchList">${rows}</div>
        `;

        this.el.mainContent.querySelectorAll('.market-watch-row').forEach((row) => {
            row.addEventListener('click', () => this._selectAsset(row.dataset.symbol));
        });

        this.el.mainContent.querySelector('#marketWatchSearch')?.addEventListener('input', (e) => {
            const needle = e.target.value.trim().toLowerCase();
            this.el.mainContent.querySelectorAll('.market-watch-row').forEach((row) => {
                row.style.display = !needle || row.dataset.search.includes(needle) ? 'flex' : 'none';
            });
        });

        this._refreshMarketWatchPrices();
    }

    _refreshMarketWatchPrices() {
        if (!this.el.mainContent) return;
        this.el.mainContent.querySelectorAll('.market-watch-row').forEach((row) => {
            const symbol = row.dataset.symbol;
            const asset = this.assetsBySymbol.get(symbol);
            const feed = this.chart?.feeds.get(symbol);
            const online = asset?.online !== false;
            const priceEl = row.querySelector('.mw-price');
            const statusEl = row.querySelector('.mw-status');
            const price = asset?.lastPrice ?? feed?.lastPrice;
            if (priceEl) priceEl.textContent = price != null ? this._formatLivePrice(symbol, price) : '—';
            if (statusEl) {
                statusEl.textContent = online ? 'Online' : 'Offline';
                statusEl.style.color = online ? '#16c087' : '#7c86a3';
            }
        });
    }

    _startMarketWatchTicker() {
        this._stopMarketWatchTicker();
        this._marketWatchTimer = setInterval(() => {
            if (this.state.activePanel === 'rightMarketWatch') this._refreshMarketWatchPrices();
        }, 2000);
    }

    _stopMarketWatchTicker() {
        if (this._marketWatchTimer) {
            clearInterval(this._marketWatchTimer);
            this._marketWatchTimer = null;
        }
    }

    _toggleTradeMenu(button, tabKey) {
        const container = button.closest('div')?.parentElement;
        if (!container) return;
        const tabs = container.querySelectorAll('.trade-open-close');
        const indicators = container.querySelectorAll('.tab-indicator');
        const contents = container.querySelectorAll('.trade-tab-content');

        tabs.forEach((tab) => {
            tab.classList.remove('text-gray-200', 'bg-[#0b1120]', 'active-tab');
            tab.classList.add('text-gray-500', 'bg-[#272b3c]');
        });
        indicators.forEach((indicator) => indicator.classList.add('hidden'));
        contents.forEach((content) => content.classList.add('hidden'));

        button.classList.remove('text-gray-500', 'bg-[#272b3c]');
        button.classList.add('text-gray-200', 'bg-[#0b1120]', 'active-tab');
        button.querySelector('.tab-indicator')?.classList.remove('hidden');

        const activeContent = container.querySelector(`.trade-tab-content[data-tab="${tabKey}"]`);
        activeContent?.classList.remove('hidden');
    }

    // ---- outside-click closing for popovers/menus ----

    _onOutsideClick(e) {
        if (this.state.assetPopoverOpen && this.el.assetPopover && !this.el.assetPopover.contains(e.target) && !this.el.assetPopoverBtn?.contains(e.target) && !this.el.addTabBtn?.contains(e.target)) {
            this._toggleAssetPopover(false);
            this.state.assetPopoverOpen = false;
            this.el.assetPopover.classList.add('hidden');
        }
        if (this.state.chartTypePopoverOpen && this.el.chartTypePopover && !this.el.chartTypePopover.contains(e.target) && !this.el.chartTypeBtn?.contains(e.target)) {
            this.state.chartTypePopoverOpen = false;
            this.el.chartTypePopover.classList.add('hidden');
        }
        if (this.state.tfMenuOpen && this.el.tfMenu && !this.el.tfMenu.contains(e.target) && !this.el.tfBtn?.contains(e.target)) {
            this.state.tfMenuOpen = false;
            this.el.tfMenu.classList.add('hidden');
        }
        if (this.state.indicatorsPopoverOpen && this.el.indicatorsPopover && !this.el.indicatorsPopover.contains(e.target) && !this.el.indicatorsBtn?.contains(e.target)) {
            this.state.indicatorsPopoverOpen = false;
            this.el.indicatorsPopover.classList.add('hidden');
        }
        if (this.state.drawingToolsPopoverOpen && this.el.drawingToolsPopover && !this.el.drawingToolsPopover.contains(e.target) && !this.el.drawingToolsBtn?.contains(e.target)) {
            this.state.drawingToolsPopoverOpen = false;
            this.el.drawingToolsPopover.classList.add('hidden');
        }
    }

    // ---- chart ----

    _initChart() {
        if (!this.el.klineChart) return;
        this.chart = new ChartManager(this.el.klineChart, {
            onOrderTick: (price, epochMs) => this._onOrderTick(price, epochMs),
            onAvailabilityChange: (symbol, available) => this._onAssetAvailabilityChange(symbol, available),
            onDrawingsChanged: () => this._persistDrawings(),
            // Manually panning the chart breaks the center-follow lock, same
            // as clicking the "Enable autoscroll" toggle off — matches how
            // real trading platforms stop auto-centering once you've dragged.
            onUserDrag: () => this._toggleAutoscroll(false),
            pricePrecision: this.options.initialPricePrecision || 5,
            chartType: this.state.currentChartType,
            showArea: this.state.showArea,
            periodSeconds: this.state.periodSeconds,
            colorScheme: this.state.colorScheme,
            showGrid: this.state.showGrid,
            historyUrl: this.options.historyUrl,
            disableBroadcast: !!this.liveFeedConfig,
        });
        this.el.toggleAreaBtn?.classList.toggle('toggle--on', this.state.showArea);
        this.el.toggleGridBtn?.classList.toggle('toggle--on', this.state.showGrid);
        if (this.el.tfLabel) this.el.tfLabel.textContent = this.state.tfLabel;
        this._renderColorSchemeOptions();
        this._restoreIndicators();
        this._renderIndicatorOptions();
        this._restoreDrawings();
        this._renderDrawingToolOptions();
        // Activation itself happens in _activateAsset() right after this call,
        // so the label/ticker/payout UI stays in sync with the chart.
    }

    _onOrderTick(price, epochMs) {
        if (this.el.livePrice) this.el.livePrice.textContent = String(price);
        if (this.el.sourceLabel) this.el.sourceLabel.textContent = this.liveFeedConfig ? 'Live · Brokeret' : 'Live · iqcent';
        if (this.el.sourceDot) this.el.sourceDot.classList.add('source-dot--live');
        if (this.state.autoScroll) this.chart?.scrollToRealTime();
    }

    // ---- live feed (base_url/ui — backend-mediated Brokeret broadcast) ----

    /**
     * Subscribes to the backend's own Brokeret rebroadcast (see
     * StreamBrokeretFeed / App\Events\BrokeretTicksUpdated) — the browser
     * never connects to Brokeret directly; the backend owns that WebSocket
     * and relays ticks over this app's normal broadcaster (Ably) instead,
     * the same way chart.js's default _initBroadcast() does for the main
     * dashboard's 'asset-prices' channel, just on a separate channel/event
     * so the two pipelines can't interfere with each other. The asset
     * catalog isn't known up front like it is for the DB-backed dashboard,
     * so categories and rows are built incrementally as symbols are first
     * observed on the channel.
     */
    _initLiveFeed() {
        this._setFeedStatus('connecting');
        if (!window.Echo) return;

        window.Echo.channel('brokeret-feed').listen('.ticks-updated', (e) => {
            this._markLiveFeedActive();
            this._onLiveTicks((e.ticks || []).map((t) => ({
                symbol: t.symbol, bid: t.bid, ask: t.ask, mid: t.mid, category: t.category, t: t.t,
            })));
        });

        this._startLiveFeedStaleCheck();
    }

    /** Ticks arrive several times a second while connected — a gap longer than this means the feed (or its backend process) has gone quiet. */
    _markLiveFeedActive() {
        this._lastLiveFeedTickAt = Date.now();
        this._setFeedStatus('live');
    }

    _startLiveFeedStaleCheck() {
        const STALE_MS = 20000;
        const ASSET_OFFLINE_MS = 45000;
        setInterval(() => {
            if (this._lastLiveFeedTickAt && (Date.now() - this._lastLiveFeedTickAt) > STALE_MS) {
                this._setFeedStatus('reconnecting');
            }
            const now = Date.now();
            this.assetsBySymbol.forEach((asset, symbol) => {
                if (asset.online === false || !asset._lastTickAt) return;
                if ((now - asset._lastTickAt) > ASSET_OFFLINE_MS) this._onLiveAssetOffline(symbol);
            });
        }, 5000);
    }

    _setFeedStatus(status) {
        this.el.sourceDot?.classList.toggle('source-dot--live', status === 'live');
        if (this.el.sourceLabel) {
            this.el.sourceLabel.textContent = status === 'live' ? 'Live · Brokeret'
                : status === 'reconnecting' ? 'Reconnecting…' : 'Connecting…';
        }
    }

    _liveCategoryLabel(cat) {
        const labels = this.liveFeedConfig?.categoryLabels || {};
        return labels[cat] || (cat ? cat.charAt(0).toUpperCase() + cat.slice(1) : 'Other');
    }

    _onLiveTicks(updates) {
        updates.forEach(({ symbol, bid, ask, mid, category, t }) => {
            let asset = this.assetsBySymbol.get(symbol);
            if (!asset) {
                asset = {
                    symbol, name: symbol, asset_group: category,
                    // No DB payout config exists for a symbol discovered
                    // purely from the stream — fall back to whatever margin
                    // the server-seeded default asset carries.
                    asset_profit_margin: this.options.initialProfitMargin ?? 0.85,
                    is_otc: false,
                };
                this.assetsBySymbol.set(symbol, asset);
            }
            asset.bid = bid;
            asset.ask = ask;
            asset.lastPrice = mid;
            asset.online = true;
            asset._lastTickAt = Date.now();

            if (!asset._rowRendered) {
                // Brokeret's own category wins once observed, even for the
                // server-seeded default asset (whose asset_group otherwise
                // came from the unrelated DB taxonomy).
                asset.asset_group = category;
                asset._rowRendered = true;
                this._ensureCategoryButton(category);
                this._addAssetRow(asset);
                if (!this.state.currentCat) this._selectCategory(category);
            } else {
                this._updateAssetRowPrice(symbol, mid);
            }

            // Only symbols with an open chart tab have a feed to route into.
            if (this.chart?.feeds.has(symbol)) {
                this.chart.ingestExternalTick(symbol, mid, t);
            }
            if (symbol === this.state.activeAssetSymbol) {
                this._onAssetAvailabilityChange(symbol, true);
            }
        });
    }

    _onLiveAssetOffline(symbol) {
        const asset = this.assetsBySymbol.get(symbol);
        if (asset) asset.online = false;
        this.el.assetRowListContainer?.querySelector(`[data-symbol="${symbol}"] .asset-status-badge`)?.classList.remove('hidden');
        if (symbol === this.state.activeAssetSymbol) this._onAssetAvailabilityChange(symbol, false);
    }

    _ensureCategoryButton(cat) {
        const container = this.el.assetCatButtonsContainer;
        if (!container || container.querySelector(`[data-cat="${cat}"]`)) return;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'asset-cat-btn';
        btn.dataset.cat = cat;
        btn.style.cssText = 'display:flex;align-items:center;gap:8px;padding:10px 12px;border-radius:8px;font-size:13px;font-weight:500;border:none;cursor:pointer;text-align:left;background:transparent;color:#a190c9;width:100%;';
        btn.textContent = this._liveCategoryLabel(cat);
        container.appendChild(btn);
    }

    _addAssetRow(asset) {
        const container = this.el.assetRowListContainer;
        if (!container || container.querySelector(`[data-symbol="${asset.symbol}"]`)) return;
        document.getElementById('assetRowListEmpty')?.remove();
        const row = document.createElement('div');
        row.className = 'asset-row' + (asset.asset_group === this.state.currentCat ? '' : ' hidden');
        row.dataset.cat = asset.asset_group || '';
        row.dataset.symbol = asset.symbol;
        row.dataset.search = (asset.symbol + ' ' + (asset.name || '')).toLowerCase();
        row.dataset.online = '1';
        row.style.cssText = 'display:flex;align-items:center;justify-content:space-between;padding:10px;border-radius:8px;cursor:pointer;';
        row.innerHTML = `
            <div class="flex items-center gap-2.5">
                <span class="text-[13px] font-medium">${asset.symbol}</span>
                <span class="asset-status-badge hidden text-[10px] font-semibold px-1.5 py-0.5 rounded" style="background:rgba(124,134,163,0.15);color:#a190c9;">Offline</span>
            </div>
            <span class="row-price text-[13px] font-semibold text-[#a190c9]">&mdash;</span>
        `;
        container.appendChild(row);
        this._updateAssetRowPrice(asset.symbol, asset.lastPrice);
    }

    _updateAssetRowPrice(symbol, price) {
        const row = this.el.assetRowListContainer?.querySelector(`[data-symbol="${symbol}"]`);
        if (!row) return;
        const priceEl = row.querySelector('.row-price');
        if (priceEl && price != null) priceEl.textContent = this._formatLivePrice(symbol, price);
        row.querySelector('.asset-status-badge')?.classList.add('hidden');
    }

    _formatLivePrice(symbol, price) {
        return Number(price).toFixed(this._inferPricePrecision(symbol));
    }

    _onAssetAvailabilityChange(symbol, available) {
        if (symbol !== this.state.activeAssetSymbol) return;
        this.el.assetUnavailableBanner?.classList.toggle('hidden', available);
        this.el.ctaButtons.forEach((btn) => { btn.disabled = !available; });

        // Offline: swap the whole payout+BUY/SELL block out for a dedicated
        // notice rather than just disabling the buttons in place.
        this.el.tradeControlsWrap?.classList.toggle('hidden', !available);
        this.el.assetOfflineNotice?.classList.toggle('hidden', available);
        this.el.assetOfflineNotice?.classList.toggle('flex', !available);
    }

    // ---- backend asset online/offline status ----

    _startAssetStatusPolling() {
        if (!this.options.assetStatusUrl) return;
        const poll = () => this._refreshAssetStatus();
        poll();
        setInterval(poll, 15000);
    }

    async _refreshAssetStatus() {
        try {
            const res = await fetch(this.options.assetStatusUrl, { headers: { Accept: 'application/json' } });
            if (!res.ok) return;
            const status = await res.json();
            this._applyAssetStatus(status);
        } catch (e) {
            // Network hiccup — keep last-known status, next poll will retry.
        }
    }

    _applyAssetStatus(status) {
        if (!status || typeof status !== 'object') return;

        this.assetsBySymbol.forEach((asset, symbol) => {
            if (Object.prototype.hasOwnProperty.call(status, symbol)) {
                asset.online = !!status[symbol];
            }
        });

        this._assetRows().forEach((row) => {
            const symbol = row.dataset.symbol;
            if (!Object.prototype.hasOwnProperty.call(status, symbol)) return;
            const online = !!status[symbol];
            row.dataset.online = online ? '1' : '0';
            row.querySelector('.asset-status-badge')?.classList.toggle('hidden', online);
        });

        const activeSymbol = this.state.activeAssetSymbol;
        if (activeSymbol && Object.prototype.hasOwnProperty.call(status, activeSymbol)) {
            this._onAssetAvailabilityChange(activeSymbol, !!status[activeSymbol]);
        }
    }

    // ---- clock ----

    _startClock() {
        const update = () => {
            const now = new Date();
            const hh = String(now.getUTCHours() - 4 < 0 ? now.getUTCHours() + 20 : now.getUTCHours() - 4).padStart(2, '0');
            const mm = String(now.getUTCMinutes()).padStart(2, '0');
            const ss = String(now.getUTCSeconds()).padStart(2, '0');
            if (this.el.liveClock) this.el.liveClock.textContent = `${hh}:${mm}:${ss} UTC-4`;
        };
        update();
        setInterval(update, 1000);
    }
}
