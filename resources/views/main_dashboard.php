@extends('layouts.app')

@section('title', 'Trading Dashboard')

@section('content')
    <div class="flex-grow flex flex-col">
        <div id="chart" class="flex-grow w-full"></div>
    </div>

    {{-- left side toggle --}}
    <div class="min-w-[20rem] p-2 hidden" id="hideShowMenuLeft">
        <span id="tradesList"></span>
    </div>

    {{-- right sidebar --}}
    <div class="bg-gray-800 w-60 flex min-h-screen">
        <div class="column-1 w-full">
            {{-- // add form for trading --}}
            <form method="POST" action="{{ route('trade.store') }}" class="p-3 rounded-lg text-white space-y-4"
                id="tradeForm">
                @csrf

                <!-- Time Input -->
                <div class="max-w-sm space-y-3">
                    <div>
                        <label for="hs-trailing-icon" class="block text-sm font-light mb-2">Time</label>
                        <div class="relative">
                            <input type="text" id="hs-trailing-icon" name="duration"
                                class="p-2 pe-11 block w-full border-[#293341] rounded-lg text-sm bg-[#1f2334]"
                                id="timeInput" maxlength="8" placeholder="00:01:00" value="00:01:00" name=duration">
                            <input type="hidden" name="asset" id="assetTicker" value="USDCAD_OTC">
                            <div
                                class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-10 border-l p-3 border-[#293341]">
                                <i class="fa-regular fa-clock bg-[#23283b]"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Amount Input -->
                <div class="max-w-sm space-y-3">
                    <div>
                        <label for="hs-trailing-icon" class="block text-sm font-light mb-2">Amount</label>
                        <div class="relative">
                            <input type="text" id="hs-trailing-icon" name="amount"
                                class="p-2 pe-11 block w-full border-[#293341] rounded-lg text-sm bg-[#1f2334]"
                                placeholder="1" value="1" name="amount">
                            <input type="hidden" name="direction" id="direction" value="">
                            <div
                                class="absolute inset-y-0 end-0 flex items-center pointer-events-none z-10 border-l p-3 border-[#293341]">
                                <svg class="currency-icon currency-icon--usd" width="18" height="18"
                                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <circle cx="12" cy="12" r="11" stroke="currentColor" stroke-width="2">
                                    </circle>
                                    <path
                                        d="M15 9h-4a1 1 0 1 0 0 2h2a3 3 0 0 1 0 6v1a1 1 0 0 1-2 0v-1H9a1 1 0 0 1 0-2h4a1 1 0 0 0 0-2h-2a3 3 0 0 1 0-6V6a1 1 0 0 1 2 0v1h2a1 1 0 1 1 0 2Z"
                                        fill="currentColor"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payout Display -->
                <div class="text-sm">
                    <label>Payout</label>
                    <div
                        class="text-green-400 border border-dashed rounded-lg mb-3 border-[#293341] p-3 flex justify-between">
                        <span id="profit_percentage">+92% </span>
                        <span id="payout">$19.20</span>
                    </div>

                    <!-- Buy and Sell Buttons -->
                    <div class="gap-2 space-y-2">
                        <button type="button" name="action" data-value="up"
                            class="_hover-up cta-button transition duration-300 ease-in-out gap-4 bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-green-400 w-full">
                            <i class="fas fa-arrow-up"></i>
                            BUY
                        </button>
                        <button type="button" name="action" data-value="down"
                            class="_hover-down cta-button transition duration-300 ease-in-out gap-4 bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md focus:outline-none focus:ring-2 focus:ring-red-400 w-full">
                            <i class="fas fa-arrow-up"></i>
                            SELL
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- right side toggle --}}
    <div class="min-w-[20rem] p-2 hidden" id="hideShowMenu"></div>

    @include('layouts.right')
    @php $__coin = "XAUUSD" @endphp
@endsection


@push('js')
    <script src="//unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
    <script>
        // WebSocket URL
        const websocketUrl = "wss://ws-plus.olymptrade.com/connect";

        // Chart Initialization
        const chartContainer = document.getElementById('chart');
        const chart = LightweightCharts.createChart(chartContainer, {
            width: chartContainer.offsetWidth,
            height: chartContainer.offsetHeight,
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

        // Function to fetch initial data from Olymp API
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

                    // Optional: Hide the preloader after 3 seconds
                    // setTimeout(() => {
                    //     document.querySelector(".__hidePreLoader").classList.toggle("hidden");
                    // }, 3000);
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


        // WebSocket Initialization
        const socket = new WebSocket(websocketUrl);

        socket.onopen = () => {
            console.log('WebSocket connected');
            // Send subscription message
            const subscriptionMessage = JSON.stringify([{
                "e": 10,
                "t": 2,
                "d": {
                    "pairs": ["{{ $__coin }}"],
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
            tradeChannel.listen('.trade-updated', function(data) {
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
@endpush
