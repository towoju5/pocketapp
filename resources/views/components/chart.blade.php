@push('js')
<script>
    // calculate percentage expected return profit
    function calculate_trade_profit() {
        const asset_profit = parseFloat("{{ $data->asset_profit_margin }}"); // e.g. 0.9
        const inputEl = document.getElementById("input_amount_field");
        const input_amount = parseFloat(inputEl.value);

        if (!isNaN(asset_profit) && !isNaN(input_amount)) {
            const profit = (asset_profit / 100) * input_amount;
            document.getElementById('payout').textContent = '$' + profit.toFixed(2);
            document.getElementById('payout_total').textContent = '$' + (profit + input_amount).toFixed(2);
        } else {
            console.log('Invalid number entered');
        }
    }
    
    window.Echo.channel(`trades.user.${userId}`)
        .listen('TradeUpdated', (event) => {
            console.log('Trade updated:', event);

            updateOrInsertTradeCard(event);
            startCountdowns([event]);
        });

    window.Echo.channel('signals')
        .listen('SignalCreated', (e) => {
            console.log("New Signal:", e);
        });

</script>

@if($isOutOfTradingHours == false)
<script>
    /***********************************************************
     *               GLOBALS & INITIAL SETUP
     ***********************************************************/
    const restApiUrl = "https://iqcent.com/trade-api/history";
    const websocketUrl = "wss://iqcent.com/trade-api-ws/api/ws/price";
    let ws;
    let chart;
    let series;
    let chartType = "line";
    let showArea = true;
    let autoScroll = true;
    let latestRate = null;
    let candleData = [];

    function initChart() {
        const chartContainer = document.getElementById("chart-container");

        chart = LightweightCharts.createChart(chartContainer, {
            layout: {
                backgroundColor: 'transparent',
                textColor: '#ffffff',
            },
            grid: {
                vertLines: { visible: false },
                horzLines: { visible: false },
            },
            timeScale: {
                rightOffset: 30,
                barSpacing: 8,
                fixLeftEdge: false,
            },
        });

        chart.applyOptions({
            localization: {
                priceFormatter: (price) => {
                    return price.toFixed(5); // Example: Format to 5 decimal places
                }
            }
        });
        
        createSeries(chartType);
        handleResize();
    }

    /***********************************************************
     *               SERIES CREATION / SWITCHING
     ***********************************************************/
    function createSeries(type) {
        if (series) {
            chart.removeSeries(series);
            series = null;
        }

        switch (type) {
            case 'line':
                if (showArea) {
                    series = chart.addAreaSeries({
                        topColor: 'rgba(67, 83, 254, 0.4)',
                        bottomColor: 'rgba(67, 83, 254, 0.0)',
                        lineColor: '#4353fe',
                    });
                } else {
                    series = chart.addLineSeries({ color: '#fff' });
                }
                break;
            case 'bar':
                series = chart.addBarSeries({
                    upColor: '#4bffb5',
                    downColor: '#ff4976',
                    borderVisible: false,
                });
                break;
            case 'candlestick':
            case 'heikinAshi':
                series = chart.addCandlestickSeries({
                    upColor: '#4bffb5',
                    downColor: '#ff4976',
                    borderVisible: false,
                    wickVisible: true,
                });
                break;
        }

        updateSeriesData();
    }

    function updateSeriesData() {
        if (!series) return;

        if (chartType === 'heikinAshi') {
            const haData = transformToHeikinAshi(candleData);
            series.setData(haData);
        } else if (chartType === 'line') {
            const lineData = candleData.map(c => ({
                time: c.time,
                value: c.close,
            }));
            series.setData(lineData);
        } else {
            series.setData(candleData);
        }
    }

    function transformToHeikinAshi(data) {
        if (!data || data.length === 0) return [];
        let haData = [];
        let prevHaOpen = data[0].open;
        let prevHaClose = data[0].close;

        for (let i = 0; i < data.length; i++) {
            const d = data[i];
            const haClose = (d.open + d.high + d.low + d.close) / 4;
            const haOpen = (prevHaOpen + prevHaClose) / 2;
            const haHigh = Math.max(d.high, haOpen, haClose);
            const haLow = Math.min(d.low, haOpen, haClose);

            haData.push({
                time: d.time,
                open: haOpen,
                high: haHigh,
                low: haLow,
                close: haClose,
            });

            prevHaOpen = haOpen;
            prevHaClose = haClose;
        }

        return haData;
    }

    /***********************************************************
     *            FETCH HISTORICAL DATA (REST API)
     ***********************************************************/
    async function fetchHistoricalData() {
        try {
            const from = Math.floor((Date.now() - 3600 * 1000 * 24) / 1000); // 24 hours ago
            const to = Math.floor(Date.now() / 1000);
            const symbol = encodeURIComponent("{{ $chart_coin }}_Strike");
            const resolution = 1;

            const url = `${restApiUrl}?from=${from}&to=${to}&symbol=${symbol}&firstDataRequest=true&resolution=${resolution}`;

            const response = await fetch(url);
            const data = await response.json();

            if (data && data.result && Array.isArray(data.result)) {
                candleData = data.result.map(c => ({
                    time: Math.floor(c.time / 1000), // Convert from milliseconds to seconds
                    open: c.open,
                    high: c.high,
                    low: c.low,
                    close: c.close,
                }));
                // console.log(candleData);
                updateSeriesData();
                scrollIfEnabled();
            } else {
                console.error("Unexpected historical data format", data);
            }
        } catch (error) {
            console.error("Failed to fetch historical data", error);
        }
    }

    /***********************************************************
     *            LIVE DATA VIA WEBSOCKET
     ***********************************************************/
    function connectWebSocket() {
        ws = new WebSocket(websocketUrl);

        ws.onopen = () => {
            // console.log("WebSocket connected");
            const subscribeMessage = {
                id: "{{ $chart_coin }}", // Match the REST API symbol
                param: "Option",
                operation: "SUBSCRIBE.TICK"
            };
            ws.send(JSON.stringify(subscribeMessage));
        };

        ws.onmessage = (event) => {
            try {
                const message = JSON.parse(event.data);
                // console.log("WebSocket message received:", message);

                if (message && typeof message.p === 'number' && typeof message.t === 'number') {
                    const now = Math.floor(message.t / 1000); // Convert ms to seconds
                    const price = parseFloat(message.p.toFixed(6)); // Precision 6 decimals
                    latestRate = price;
                    const orderToken = btoa(latestRate.toString());
                    document.getElementById('order_token').value = orderToken;
                    document.getElementById('order_time').value = message.t;
                    // For candleData (for bars/candles/heikin ashi):
                    const lastCandle = candleData[candleData.length - 1];
                    if (!lastCandle || now >= lastCandle.time + 60) {
                        candleData.push({
                            time: now,
                            open: price,
                            high: price,
                            low: price,
                            close: price,
                        });
                    } else {
                        lastCandle.close = price;
                        lastCandle.high = Math.max(lastCandle.high, price);
                        lastCandle.low = Math.min(lastCandle.low, price);
                    }

                    // For real-time update:
                    if (chartType === 'line') {
                        series.update({ time: now, value: price });
                    } else if (chartType === 'candlestick' || chartType === 'bar' || chartType === 'heikinAshi') {
                        updateSeriesData(); // Full update for candle types
                    }

                    scrollIfEnabled();
                } else {
                    console.error("Unexpected websocket data:", message);
                }
            } catch (error) {
                console.error("Failed to process WebSocket message:", error);
            }
        };

        ws.onerror = (err) => {
            console.error("WebSocket error", err);
        };

        ws.onclose = () => {
            console.warn("WebSocket closed. Reconnecting...");
            setTimeout(connectWebSocket, 3000);
        };
    }


    /***********************************************************
     *                AUTO-SCROLL CONTROL
     ***********************************************************/
    function scrollIfEnabled() {
        if (autoScroll) {
            chart.timeScale().scrollToRealTime();
        }
    }

    /***********************************************************
     *              UI EVENT HANDLERS
     ***********************************************************/
    function setChartType(type) {
        chartType = type;
        createSeries(chartType);
    }

    function toggleArea() {
        showArea = !showArea;
        if (chartType === 'line') {
            createSeries('line');
        }
    }

    function toggleAutoScroll() {
        autoScroll = !autoScroll;
        if (autoScroll) {
            scrollIfEnabled();
        }
    }

    /***********************************************************
     *                CHART RESIZING
     ***********************************************************/
    function handleResize() {
        const chartContainer = document.getElementById("chart-container");
        function resizeChart() {
            chart.applyOptions({
                width: chartContainer.clientWidth,
                height: chartContainer.clientHeight,
            });
        }
        window.addEventListener("resize", resizeChart);
        new ResizeObserver(resizeChart).observe(chartContainer);
        resizeChart();
    }

    /***********************************************************
     *                INIT EVERYTHING
     ***********************************************************/
    initChart();
    fetchHistoricalData();
    connectWebSocket();
</script>
@endif

<script>
  /***********************************************************
   *                STOCK Loading and s
   ***********************************************************/
    var assets = {!! get_assets() !!};

	// Convert assets to the required format
	const stocks = assets.map(asset => {
		return {
			name: asset.name,
			symbol: asset.symbol,
			payout: asset.asset_profit_margin + "%", // Default payout
			category: asset.asset_group // Categorizing based on asset group
		};
	});


	// Load stocks
	function loadStocks(filter = "all") {
		const stockList = document.getElementById("stockList");
		stockList.innerHTML = "";
		stocks.forEach((stock) => {
			if (filter === "all" || stock.category === filter) {
				const li = document.createElement("li");
				li.className =
					"p-2 flex justify-between hover:bg-gray-700 cursor-pointer";
				li.innerHTML = `<span>${stock.name}</span> <span class="text-green-400">${stock.payout}</span>`;
				li.onclick = function () {
                    document.getElementById("selectedAsset").innerText = stock.name;
                    const sanitizedSymbol = stock.symbol.replace(/\//g, '--'); // Replace all '/' with '--'
                    window.location.href = "/dashboard/" + sanitizedSymbol;
                    document
                        .getElementById("assetDropDown")
                        .classList.add("hidden");
                };
				stockList.appendChild(li);
			}
		});
	}

	loadStocks();

	// Filter Stocks
	function filterStocks(category) {
		loadStocks(category);
	}

	// Search Functionality
	document.getElementById("searchBar").addEventListener("keyup", function () {
		const searchValue = this.value.toLowerCase();
		const stockItems = document.querySelectorAll("#stockList li");
		stockItems.forEach((item) => {
			item.style.display = item.textContent
				.toLowerCase()
				.includes(searchValue)
				? "flex"
				: "none";
		});
	});

	// Close Dropdown on Outside Click
	document.addEventListener("click", function (event) {
		if (
			!document.getElementById("assetBtn").contains(event.target) &&
			!document.getElementById("assetDropDown").contains(event.target)
		) {
			document.getElementById("assetDropDown").classList.add("hidden");
		}
	});

	// stock dropdown
	document.getElementById("chartTpyeBtn").addEventListener("click", function () {
		document.getElementById("chartTpyeDropDown").classList.toggle("hidden");
    });
    document.addEventListener("click", function (event) {
		if (
			!document.getElementById("chartTpyeBtn").contains(event.target) &&
			!document.getElementById("chartTpyeDropDown").contains(event.target)
		) {
			document.getElementById("chartTpyeDropDown").classList.add("hidden");
		}
	});
</script>
@endpush


@push('css')
<!-- Styles -->
<style>
    #chart-container {
        margin-top: -1rem;
        width: 100%;
        height: 90vh; /* Adjust height as needed */
        background-color: transparent;
    }
    #controls {
        padding: 10px;
        background: #f8f8f8;
    }
    button {
        margin: 5px;
        padding: 8px;
        cursor: pointer;
    }
    
    .active-tab {
        border-bottom: 2px solid #3b82f6;
        /* blue-500 */
    }

    .tv-lightweight-charts {
        width: 100% !important;
        height: auto;
    }

    table {
        width: 100% !important;
    }

    body {
        overflow: hidden !important;
    }

    .container {
        background-color: #222436;
        padding: 15px;
        border-radius: 8px;
        width: 300px;
    }

    .title {
        font-size: 14px;
        margin-bottom: 10px;
    }

    .chart-types {
        display: flex;
        gap: 10px;
        margin-bottom: 15px;
    }

    .chart-types li {
        background: #2c2e48;
        border: none;
        padding: 10px;
        border-radius: 5px;
        color: white;
        flex: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .chart-types li.active {
        background: #4a4d71;
    }

    .settings {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .setting {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .toggle {
        width: 34px;
        height: 18px;
        background: #555;
        border-radius: 9px;
        position: relative;
        cursor: pointer;
    }

    .toggle::before {
        content: '';
        width: 14px;
        height: 14px;
        background: white;
        border-radius: 50%;
        position: absolute;
        top: 2px;
        left: 2px;
        transition: 0.3s;
    }

    .toggle.active {
        background: #4caf50;
    }

    .toggle.active::before {
        left: 18px;
    }



    /* // scss to css */
    .right-widget-container .signals-list,
    .right-sidebar-modal .signals-list {
        --second-column-width: 130px;
        --gap: 2px;
        display: block;
        flex-direction: column;
        overflow: hidden;
        width: 100%;
    }

    .right-widget-container .signals-list .copy-signal-item,
    .right-sidebar-modal .signals-list .copy-signal-item {
        display: flex;
        flex: 1;
        flex-direction: column;
        margin-left: calc(-1 * var(--gap));
        padding: 8px 10px;
        font-size: 13px;
    }

    .right-widget-container .signals-list .copy-signal-item>*,
    .right-sidebar-modal .signals-list .copy-signal-item>* {
        margin-left: var(--gap);
    }

    .right-widget-container .signals-list .copy-signal-item__row,
    .right-sidebar-modal .signals-list .copy-signal-item__row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-left: -10px;
    }

    .right-widget-container .signals-list .copy-signal-item__row>*,
    .right-sidebar-modal .signals-list .copy-signal-item__row>* {
        margin-left: 10px;
    }

    .right-widget-container .signals-list .copy-signal-item .progress-info,
    .right-sidebar-modal .signals-list .copy-signal-item .progress-info {
        position: relative;
        display: flex;
        align-items: center;
        width: 128px;
    }

    .right-widget-container .signals-list .copy-signal-item .progress-info .progress,
    .right-sidebar-modal .signals-list .copy-signal-item .progress-info .progress {
        position: absolute;
        top: 0;
        bottom: 0;
        overflow: hidden;
        margin: auto;
        width: 100%;
    }

    .right-widget-container .signals-list .copy-signal-item .icons-column,
    .right-sidebar-modal .signals-list .copy-signal-item .icons-column {
        width: 20px;
        white-space: nowrap;
        text-align: center;
    }

    .right-widget-container .signals-list .copy-signal-item .icons-column--three,
    .right-sidebar-modal .signals-list .copy-signal-item .icons-column--three {
        width: 30px;
    }

    .right-widget-container .signals-list .copy-signal-item .icons-column--four,
    .right-sidebar-modal .signals-list .copy-signal-item .icons-column--four {
        width: 36px;
    }

    .right-widget-container .signals-list .copy-signal-item__symbol,
    .right-sidebar-modal .signals-list .copy-signal-item__symbol {
        flex: 1;
        white-space: nowrap;
    }

    .right-widget-container .signals-list .copy-signal-item__symbol .price,
    .right-sidebar-modal .signals-list .copy-signal-item__symbol .price {
        white-space: nowrap;
    }

    .right-widget-container .signals-list .copy-signal-item__symbol.pointer,
    .right-sidebar-modal .signals-list .copy-signal-item__symbol.pointer {
        cursor: pointer;
    }

    .right-widget-container .signals-list .copy-signal-item__price,
    .right-sidebar-modal .signals-list .copy-signal-item__price {
        flex: 1;
    }

    .right-widget-container .signals-list .copy-signal-item__progress,
    .right-sidebar-modal .signals-list .copy-signal-item__progress {
        display: flex;
        width: var(--second-column-width);
    }

    .right-widget-container .signals-list .copy-signal-item__progress>*,
    .right-sidebar-modal .signals-list .copy-signal-item__progress>* {
        margin-left: 10px;
    }

    .right-widget-container .signals-list .copy-signal-item__progress>*:first-child,
    .right-sidebar-modal .signals-list .copy-signal-item__progress>*:first-child {
        margin-left: 0;
    }

    .right-widget-container .signals-list .copy-signal-item__progress .trade-opened,
    .right-sidebar-modal .signals-list .copy-signal-item__progress .trade-opened {
        margin-top: -2px;
        width: 100%;
        font-size: 12px;
        text-align: center;
    }

    .right-widget-container .signals-list .copy-signal-item__action,
    .right-sidebar-modal .signals-list .copy-signal-item__action {
        width: var(--second-column-width);
    }

    .right-widget-container .signals-list .copy-signal-item__action a,
    .right-sidebar-modal .signals-list .copy-signal-item__action a {
        padding: 1px 6px 2px;
        width: 100%;
        font-size: 12px;
        color: #fff;
    }

    .theme-dark-blue .right-widget-container .signals-list .copy-signal-item__action a,
    .theme-dark-blue .right-sidebar-modal .signals-list .copy-signal-item__action a {
        border-color: #025b44;
        background-color: #025b44;
    }

    .theme-light .right-widget-container .signals-list .copy-signal-item__action a,
    .theme-light .right-sidebar-modal .signals-list .copy-signal-item__action a {
        background-color: #5cb85c;
    }

    .theme-light .right-widget-container .signals-list .copy-signal-item__action a:hover,
    .theme-light .right-sidebar-modal .signals-list .copy-signal-item__action a:hover {
        background-color: #449d44;
    }

    .right-widget-container .signals-list .copy-signal-item__stats,
    .right-sidebar-modal .signals-list .copy-signal-item__stats {
        justify-content: space-between;
        font-size: 11px;
    }

    .right-widget-container .signals-list .copy-signal-item .tooltip-content,
    .right-sidebar-modal .signals-list .copy-signal-item .tooltip-content {
        left: -70px;
        padding: 3px 6px;
    }

    .right-widget-container .signals-list .copy-signal-item .tooltip-content .tooltip-text,
    .right-sidebar-modal .signals-list .copy-signal-item .tooltip-content .tooltip-text {
        font-size: 12px;
    }

    .right-widget-container .signals-list .signal-label,
    .right-sidebar-modal .signals-list .signal-label {
        min-height: 32px;
        font-size: 11px;
    }

    .right-widget-container .signals-list .signal-item,
    .right-sidebar-modal .signals-list .signal-item {
        display: flex;
        justify-content: space-between;
    }

    .right-widget-container .signals-list .signal-item>span,
    .right-sidebar-modal .signals-list .signal-item>span {
        padding: 7px;
    }

    .right-widget-container .signals-list .signal-item>span.price,
    .right-sidebar-modal .signals-list .signal-item>span.price {
        margin-right: 10px;
    }

    .right-widget-container .signals-list .updates-wrapper,
    .right-widget-container .signals-list .all-wrapper,
    .right-sidebar-modal .signals-list .updates-wrapper,
    .right-sidebar-modal .signals-list .all-wrapper {
        display: block;
    }
</style>
@endpush