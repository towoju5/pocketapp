@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        #chart {
            height: 60vh;
            width: 100%;
        }

        #tv-attr-logo {
            display: none;
        }
    </style>
    <div class="row gx-3">
        <div class="col-xxl-12">
            <div class="card mb-3">
                <div class="card-body">
                    <div class="card-header" id="tickerTitle"></div>
                    <div id="chart"></div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('js')
    <script src="https://unpkg.com/lightweight-charts/dist/lightweight-charts.standalone.production.js"></script>
    <script>
        // Initialize WebSocket
        const socket = new WebSocket("wss://finnhub.io/ws");

        // Define the ticker symbol
        const ticker = "OANDA:GBP_USD";
        // const ticker = "COINBASE:BTC-USD"; //"OANDA:GBP_USD";
        $("#tickerTitle").text(ticker);
        // Setup the Lightweight Chart
        const chartContainer = document.getElementById('chart');
        const chart = LightweightCharts.createChart(chartContainer, {
            width: chartContainer.offsetWidth,
            height: chartContainer.offsetHeight,
            layout: {
                backgroundColor: '#ffffff',
                textColor: '#000000',
            },
            grid: {
                vertLines: {
                    color: '#e0e0e0',
                },
                horzLines: {
                    color: '#e0e0e0',
                },
            },
            timeScale: {
                timeVisible: true,
                secondsVisible: false,
            },
        });

        const lineSeries = chart.addLineSeries({
            color: 'blue',
            lineWidth: 2,
        });

        // Store received data
        const priceData = [];

        // WebSocket event handlers
        socket.onopen = () => {
            console.log("WebSocket connection opened");

            // Subscribe to the ticker
            const subscription = {
                type: 50,
                ticker: ticker
            };
            socket.send(JSON.stringify(subscription));
        };

        socket.onmessage = (event) => {
            const message = JSON.parse(event.data);

            // Handle response type 52
            if (message.type === 52 && message.content && message.content.ticker === ticker) {
                const currentPrice = message.content.price;
                const timestamp = Math.floor(Date.now() / 1000);

                // Push new price data
                priceData.push({
                    time: timestamp,
                    value: currentPrice
                });

                // Update the chart with new data
                lineSeries.setData(priceData);

                console.log(`New price received: ${currentPrice} for ${ticker}`);
            }
        };

        socket.onclose = () => {
            console.log("WebSocket connection closed");
        };

        socket.onerror = (error) => {
            console.error("WebSocket error:", error);
        };
    </script>
@endpush
