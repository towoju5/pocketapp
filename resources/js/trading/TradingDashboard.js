import { ChartManager, periodSecondsToKlinePeriod, COLOR_SCHEMES } from './chart.js';

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
        this._startAssetStatusPolling();

        window.toggleTradeMenu = (button, tabKey) => this._toggleTradeMenu(button, tabKey);
    }

    _cacheDom() {
        this.el = {
            assetPopoverBtn: document.getElementById('assetPopoverBtn'),
            assetPopover: document.getElementById('assetPopover'),
            assetCatButtons: document.querySelectorAll('.asset-cat-btn'),
            assetRows: document.querySelectorAll('.asset-row'),
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
        };
    }

    _bindStaticEvents() {
        this.el.assetPopoverBtn?.addEventListener('click', () => this._toggleAssetPopover());
        this.el.addTabBtn?.addEventListener('click', () => this._toggleAssetPopover(true));
        this.el.assetCatButtons.forEach((btn) => {
            btn.addEventListener('click', () => this._selectCategory(btn.dataset.cat));
        });
        this.el.assetSearchInput?.addEventListener('input', (e) => this._filterAssetRows(e.target.value));
        this.el.assetRows.forEach((row) => {
            row.addEventListener('click', () => this._selectAsset(row.dataset.symbol));
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
        this.el.assetCatButtons.forEach((btn) => {
            btn.classList.toggle('asset-cat-btn--active', btn.dataset.cat === cat);
        });
        this.el.assetRows.forEach((row) => {
            row.classList.toggle('hidden', row.dataset.cat !== cat);
        });
    }

    _filterAssetRows(search) {
        const needle = search.trim().toLowerCase();
        this.el.assetRows.forEach((row) => {
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

    _toggleAutoscroll() {
        this.state.autoScroll = !this.state.autoScroll;
        this.el.toggleAutoscrollBtn?.classList.toggle('toggle--on', this.state.autoScroll);
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

    _updatePayoutDisplay(marginOverride) {
        const asset = this.assetsBySymbol.get(this.state.activeAssetSymbol);
        // asset_profit_margin is a fraction (0.92 == 92%), not a 0-100 percentage.
        const margin = parseFloat(marginOverride ?? asset?.asset_profit_margin ?? this.options.initialProfitMargin ?? 0);
        const pct = (margin * 100).toFixed(0);
        if (this.el.profitPercentage) this.el.profitPercentage.textContent = `+${pct}%`;
        if (this.el.payout) {
            const profit = margin * this.state.tradeAmount;
            this.el.payout.textContent = `$${profit.toFixed(2)}`;
        }
        document.getElementById('ctaPercentUp')?.replaceChildren(document.createTextNode(`${pct}%`));
        document.getElementById('ctaPercentDown')?.replaceChildren(document.createTextNode(`${pct}%`));
    }

    async _submitTrade(direction) {
        if (!this.el.tradeForm || this.state.tradeSubmitting) return;

        this.state.tradeSubmitting = true;
        this.el.ctaButtons.forEach((btn) => { btn.disabled = true; });

        if (this.el.directionInput) this.el.directionInput.value = direction;

        try {
            const formData = new FormData(this.el.tradeForm);
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            const res = await fetch(this.el.tradeForm.action, {
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
                if (data.html) {
                    document.getElementById('tradesList')?.insertAdjacentHTML('afterbegin', data.html);
                }
                if (data.trade?.trade_close_time && data.trade?.trade_currency) {
                    const expiryMs = new Date(String(data.trade.trade_close_time).replace(' ', 'T')).getTime();
                    const entryPrice = data.trade.start_price != null ? parseFloat(data.trade.start_price) : undefined;
                    if (!Number.isNaN(expiryMs)) {
                        this.chart?.setExpiryLine(data.trade.id, data.trade.trade_currency, expiryMs, entryPrice);
                    }
                }
            } else {
                const message = data?.message || data?.errors || 'Unable to place trade. Please try again.';
                window.toastr?.error(typeof message === 'string' ? message : 'Unable to place trade. Please try again.');
            }
        } catch (e) {
            console.error('[TradingDashboard] trade submit failed', e);
            window.toastr?.error('Unable to place trade. Please try again.');
        } finally {
            this.state.tradeSubmitting = false;
            const asset = this.assetsBySymbol.get(this.state.activeAssetSymbol);
            this.el.ctaButtons.forEach((btn) => { btn.disabled = asset?.online === false; });
        }
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
            if (priceEl) priceEl.textContent = feed?.lastPrice != null ? feed.lastPrice : '—';
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
            pricePrecision: this.options.initialPricePrecision || 5,
            chartType: this.state.currentChartType,
            showArea: this.state.showArea,
            periodSeconds: this.state.periodSeconds,
            colorScheme: this.state.colorScheme,
            showGrid: this.state.showGrid,
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
        if (this.el.sourceLabel) this.el.sourceLabel.textContent = 'Live · iqcent';
        if (this.el.sourceDot) this.el.sourceDot.classList.add('source-dot--live');
        if (this.state.autoScroll) this.chart?.scrollToRealTime();
    }

    _onAssetAvailabilityChange(symbol, available) {
        if (symbol !== this.state.activeAssetSymbol) return;
        this.el.assetUnavailableBanner?.classList.toggle('hidden', available);
        this.el.ctaButtons.forEach((btn) => { btn.disabled = !available; });
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

        this.el.assetRows.forEach((row) => {
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
