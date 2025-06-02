async function initializeComponents() {
    try {
        // Load top nav
        const topNavResponse = await fetch("components/top-nav.html");
        const topNavHtml = await topNavResponse.text();
        document.getElementById("top-nav").innerHTML = topNavHtml;

        // Initialize wallet modal after top nav is loaded
        window.initializeWalletModal();

        // Load bottom nav
        const bottomNavResponse = await fetch("components/bottom-nav.html");
        const bottomNavHtml = await bottomNavResponse.text();
        document.getElementById("bottom-nav").innerHTML = bottomNavHtml;

        // Initialize dropdown after components are loaded
        console.log("Components loaded, initializing dropdown...");
        initializeAccountDropdown();

        // Show welcome template content
        const template = document.getElementById("welcome-template");
        document.getElementById("main-content").innerHTML =
            template.innerHTML;

        // Initialize chart after template is loaded
        window.forexChart.initialize();

        // Initialize Time Modal Bindings after dynamic insertion
        initTimeModal();
        // Bind Calculator Modal events
        initCalcModal();
        // Initialize counter after template is loaded
        updateCounter();
        // Add navbar initialization
        initializeNavbar();
    } catch (error) {
        console.error("Error initializing components:", error);
    }
}

function initTimeModal() {
    const timeContainer = document.getElementById("timeContainer");
    const timeModal = document.getElementById("timeModal");
    const timeInput = document.getElementById("timeInput");
    const timeDisplay = document.getElementById("timeDisplay");
    const cancelTime = document.getElementById("cancelTime");
    const saveTime = document.getElementById("saveTime");

    if (!timeContainer) {
        console.error("Time container not found.");
        return;
    }

    timeContainer.addEventListener("click", () => {
        timeInput.value = timeDisplay.textContent.trim();
        timeModal.classList.remove("hidden");
    });

    cancelTime.addEventListener("click", () => {
        timeModal.classList.add("hidden");
    });
    timeModal.addEventListener("click", (e) => {
        if (e.target === timeModal) timeModal.classList.add("hidden");
    });
    saveTime.addEventListener("click", () => {
        const newTime = timeInput.value.trim() || "00:00:00";
        timeDisplay.textContent = newTime;
        timeModal.classList.add("hidden");
    });
}

function initCalcModal() {
    const amountContainer = document.getElementById("amountContainer");
    const calculatorModal = document.getElementById("calculatorModal");
    const calcDisplay = document.getElementById("calcDisplay");
    const cancelCalc = document.getElementById("cancelCalc");
    const confirmCalc = document.getElementById("confirmCalc");

    // Bind buttons for digits and operations
    document.querySelectorAll(".calc-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
            const value = btn.textContent.trim();
            if (value === "=") {
                try {
                    calcDisplay.value = eval(calcDisplay.value);
                } catch (error) {
                    calcDisplay.value = "Error";
                }
            } else {
                if (calcDisplay.value === "0" || calcDisplay.value === "Error") {
                    calcDisplay.value = value;
                } else {
                    calcDisplay.value += value;
                }
            }
        });
    });

    amountContainer.addEventListener("click", () => {
        const amountDisplay = document.getElementById("amountDisplay");
        calcDisplay.value = amountDisplay.textContent.trim();
        calculatorModal.classList.remove("hidden");
    });

    cancelCalc.addEventListener("click", () => {
        calculatorModal.classList.add("hidden");
    });
    calculatorModal.addEventListener("click", (e) => {
        if (e.target === calculatorModal)
            calculatorModal.classList.add("hidden");
    });

    document.getElementById("calcClear").addEventListener("click", () => {
        calcDisplay.value = "";
    });

    confirmCalc.addEventListener("click", () => {
        const amountDisplay = document.getElementById("amountDisplay");
        amountDisplay.textContent = calcDisplay.value || "0";
        calculatorModal.classList.add("hidden");
    });
}

function updateCounter() {
    const leftCounter = document.querySelector(".progress-left");
    const rightCounter = document.querySelector(".progress-right");
    let count = 0;

    setInterval(() => {
        count = count >= 100 ? 0 : count + 1;
        leftCounter.textContent = `${count}%`;
        rightCounter.textContent = `${100 - count}%`;
    }, 100); // Updates every 100ms for a total of 10 seconds (100 steps)
}

// The User at the left nav bar
const eyeIcon = document.querySelector(".fa-eye");
const eyeSlashIcon = document.querySelector(".fa-eye-slash");
const sensitiveData = document.querySelectorAll(".sensitive");

eyeIcon.addEventListener("click", function () {
    toggleVisibility();
});

eyeSlashIcon.addEventListener("click", function () {
    toggleVisibility();
});

function toggleVisibility() {
    sensitiveData.forEach((element) => {
        if (element.dataset.original) {
            element.textContent = element.dataset.original;
            element.removeAttribute("data-original");
        } else {
            element.dataset.original = element.textContent;
            element.textContent = "*******";
        }
    });
    eyeIcon.classList.toggle("hidden");
    eyeSlashIcon.classList.toggle("hidden");
}

// Start initialization
initializeComponents();

// sub left menu
let previousContent = "mainContent";
function showContent(section) {
    document.getElementById(previousContent).classList.add("hidden");
    let newContentId = section + "Content";
    document.getElementById(newContentId).classList.remove("hidden");
    previousContent = newContentId;
}

function goBack() {
    document.getElementById(previousContent).classList.add("hidden");
    document.getElementById("mainContent").classList.remove("hidden");
    previousContent = "mainContent";
}

// Language
function toggleDropdown() {
    let dropdown = document.getElementById("languageContent");
    dropdown.classList.toggle("hidden"); // Toggle visibility
}


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
    const chartContainer = document.getElementById("chart");

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