

<script>
    // Initialize the chart
    const canvas = document.getElementById('tradingChart');
    const ctx = canvas.getContext('2d');

    // Set canvas size with proper scaling
    function resizeCanvas() {
        const rect = canvas.parentElement.getBoundingClientRect();
        canvas.width = rect.width * window.devicePixelRatio;
        canvas.height = rect.height * window.devicePixelRatio;
        ctx.scale(window.devicePixelRatio, window.devicePixelRatio);
        canvas.style.width = `${rect.width}px`;
        canvas.style.height = `${rect.height}px`;
    }

    resizeCanvas();
    window.addEventListener('resize', resizeCanvas);

    // Sample data points (you would replace this with real-time data)
    let data = Array.from({
        length: 100
    }, (_, i) => ({
        time: new Date(Date.now() - (100 - i) * 1000),
        price: 174.7 + Math.random() * 0.1
    }));

    function drawChart() {
        ctx.clearRect(0, 0, canvas.width / window.devicePixelRatio, canvas.height / window.devicePixelRatio);

        // Draw grid
        ctx.strokeStyle = '#2a3142';
        ctx.lineWidth = 1;

        // Vertical grid lines
        for (let i = 0; i < canvas.width / window.devicePixelRatio; i += 50) {
            ctx.beginPath();
            ctx.moveTo(i, 0);
            ctx.lineTo(i, canvas.height / window.devicePixelRatio);
            ctx.stroke();
        }

        // Horizontal grid lines
        for (let i = 0; i < canvas.height / window.devicePixelRatio; i += 50) {
            ctx.beginPath();
            ctx.moveTo(0, i);
            ctx.lineTo(canvas.width / window.devicePixelRatio, i);
            ctx.stroke();
        }

        // Draw price line
        ctx.strokeStyle = '#3b82f6';
        ctx.lineWidth = 2;
        ctx.beginPath();

        const xStep = (canvas.width / window.devicePixelRatio) / (data.length - 1);
        const minPrice = Math.min(...data.map(d => d.price));
        const maxPrice = Math.max(...data.map(d => d.price));
        const priceRange = maxPrice - minPrice;
        const yScale = (canvas.height / window.devicePixelRatio) / priceRange;

        data.forEach((point, index) => {
            const x = index * xStep;
            const y = (canvas.height / window.devicePixelRatio) - ((point.price - minPrice) * yScale);

            if (index === 0) {
                ctx.moveTo(x, y);
            } else {
                ctx.lineTo(x, y);
            }
        });

        ctx.stroke();

        // Draw latest price
        const latestPrice = data[data.length - 1].price;
        ctx.fillStyle = '#94a3b8';
        ctx.font = '12px sans-serif';
        ctx.textAlign = 'right';
        ctx.fillText(latestPrice.toFixed(3), canvas.width / window.devicePixelRatio - 10, 20);
    }

    drawChart();
    window.addEventListener('resize', drawChart);

    // Simulate real-time updates
    setInterval(() => {
        const now = new Date();
        const newPrice = data[data.length - 1].price + (Math.random() - 0.5) * 0.01;
        data.shift();
        data.push({
            time: now,
            price: newPrice
        });
        drawChart();
    }, 1000);
</script>

<script>

    // Chart Initialization
    const chartContainer = document.getElementById('chart');
    const chart = LightweightCharts.createChart(chartContainer, {
        width: '100%',
        height: '100%',
        layout: {
            background: {
                type: 'solid',
                color: 'transparent'
            },
            textColor: '#fff',
            attributionLogo: true
        },
        grid: {
            vertLines: {
                color: '#293341',
            },
            horzLines: {
                color: '#293341',
            },
        },
        crosshair: {
            mode: LightweightCharts.CrosshairMode.Normal,
        },
        rightPriceScale: {
            borderVisible: true,
        },
        timeScale: {
            borderVisible: false,
            timeVisible: true,
            secondsVisible: true,
            rightOffset: 50,
            barSpacing: 6,
            minBarSpacing: 0.5,
            fixLeftEdge: false,
            fixRightEdge: false,
            lockVisibleTimeRangeOnResize: true,
            rightBarStaysOnScroll: true,
        },
    });

    // Add Area Series
    const lineSeries = chart.addAreaSeries({
        topColor: 'rgba(33, 150, 243, 0.56)',
        bottomColor: 'rgba(33, 150, 243, 0.04)',
        lineColor: '#2196f3',
        lineWidth: 2,
        lastValueVisible: true,
        priceLineVisible: true,
        priceLineSource: LightweightCharts.PriceLineSource.LastBar,
        crosshairMarkerVisible: true,
        crosshairMarkerRadius: 6,
        crosshairMarkerBorderColor: '#ffffff',
        crosshairMarkerBackgroundColor: '#2196f3',
    });

    // Resize Chart on Window Resize
    window.addEventListener('resize', () => {
        chart.resize(chartContainer.offsetWidth, chartContainer.offsetHeight);
    });

    // Function to fetch initial chart data API
    const fetchInitialData = async () => {
        try {
            let candleUrl = "{{ url('api/stream/chart/' . $__coin) }}";

            const response = await fetch(candleUrl);
            const candles = await response.json();

            console.log('Candles:', candles); // Log the candles to see its structure

            if (Array.isArray(candles)) { // Check if candles is an array
                const formattedInitialData = candles
                    .map(candle => ({
                        time: candle.ts,
                        value: candle.c,
                    }))
                    .filter(item => item.time !== null && item.value !== null);
                lineSeries.setData(formattedInitialData);
            } else {
                console.error('Unexpected response format:', candles);
            }
        } catch (error) {
            console.error('Error fetching initial data:', error);
        }
    };


    // Function to update chart with incremental data
    const updateChartWithNewData = (data) => {
        data.forEach(item => {
            // Check if item has 'd' and it contains data
            if (item.d && Array.isArray(item.d) && item.d.length > 0) {
                const pairData = item.d[0];

                // Check if 'pair' and 'rate' exist in the first item of 'd'
                if (pairData.pair && pairData.rate) {
                    lineSeries.update({
                        time: Math.floor((pairData.ts || Date.now()) /
                            1000), // Use 'ts' if it exists or default to current timestamp
                        value: pairData.rate,
                    });
                }
            }
        });
    };

    // WebSocket URL
    const websocketUrl = "wss://ws-plus.olymptrade.com/connect";

    // WebSocket Initialization
    const socket = new WebSocket(websocketUrl);

    socket.onopen = () => {
        console.log('WebSocket connected');
        // Send subscription message
        const subscriptionMessage = JSON.stringify([{
            "e": 10,
            "t": 2,
            "d": {
                "pairs": ["{{ $__coin}}"],
                "chart_tfs": [3600, 86400, 604800, 2592000],
                "with_forecast": true
            },
            "uuid": "1"
        }]);
        socket.send(subscriptionMessage);
    };

    socket.onmessage = (event) => {
        try {
            const message = JSON.parse(event.data);
            updateChartWithNewData(message);
        } catch (error) {
            console.error('Error processing WebSocket message:', error);
        }
    };

    socket.onclose = () => {
        console.log('WebSocket disconnected');
    };

    socket.onerror = (error) => {
        console.error('WebSocket error:', error);
    };

    // Fetch initial data before setting up WebSocket
    fetchInitialData();

    window.onload = function() {
        // Connect to the trade.created channel
        var tradeChannel = Echo.channel('trade.created');
        var tradeUpdateChannel = Echo.channel('trade.updated');

        if (tradeChannel) {
            toastr.success("Trade update connected");
            console.log('Echo connected successfully');
        }

        // Listen for the 'trade.created' event
        tradeChannel.listen('.trade.created', function(data) {
            if (data && data.id) {
                console.log('Trade Created:', data);
                toastr.success(`Trade event received: ID: ${data.id}`);
            } else {
                console.error('Invalid trade.created event data:', data);
            }
        });

        // Listen for the 'trade-updated' event
        tradeUpdateChannel.listen('.trade-updated', function(data) {
            if (data && data.id) {
                console.log('Trade Updated:', data);
                toastr.success(`Update on trade ${data.id} received`);
            } else {
                console.error('Invalid trade-updated event data:', data);
            }
        });
    };


    // handle form submission.
    $('#tradeForm').on('submit', function(e) {
        e.preventDefault();
        $('.cta-button').prop('disabled', true);
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.status) {
                    toastr.success(response.message);
                    // Display trade data
                    const trade = response.trade;
                    const tradeHtml = response.html;
                    $('#tradesList').prepend(tradeHtml);

                    // Start countdown
                    let timeLeft = trade.trade_close_time;
                    const countdownInterval = setInterval(() => {
                        if (timeLeft <= 0) {
                            clearInterval(countdownInterval);
                            $(`.countdown-${trade.id}`).text('Completed');
                            return;
                        }

                        $(`.countdown-${trade.id}`).text(`${timeLeft} seconds`);
                        timeLeft--;
                    }, 1000);

                    // Reset form
                    $('#tradeForm')[0].reset();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(xhr) {
                // toastr.error('An error occurred while placing the trade');
                console.error(xhr);
            }
        });
        $('.cta-button').prop('disabled', false);
    });
</script>

<script>
    // Toggle Dropdown
    document.getElementById("assetBtn").addEventListener("click", function() {
        document.getElementById("assetDropDown").classList.toggle("hidden");
    });

    // Stock Data
    const stocks = "{!! get_assets() !!}";

    // Load stocks
    function loadStocks(filter = "all") {
        const stockList = document.getElementById("stockList");
        stockList.innerHTML = "";
        stocks.forEach(stock => {
            if (filter === "all" || stock.category === filter) {
                const li = document.createElement("li");
                li.className = "p-2 flex justify-between hover:bg-gray-700 cursor-pointer";
                li.innerHTML = `<span>${stock.name}</span> <span class="text-green-400">${stock.payout}</span>`;
                li.onclick = function() {
                    document.getElementById("selectedAsset").innerText = stock.name;
                    document.getElementById("assetDropDown").classList.add("hidden");
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
    document.getElementById("searchBar").addEventListener("keyup", function() {
        const searchValue = this.value.toLowerCase();
        const stockItems = document.querySelectorAll("#stockList li");
        stockItems.forEach(item => {
            item.style.display = item.textContent.toLowerCase().includes(searchValue) ? "flex" : "none";
        });
    });
