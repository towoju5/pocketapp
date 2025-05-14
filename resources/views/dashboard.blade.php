@extends('layouts.app')

@section('title', 'Dashboard')
@section('content')
    <div class="row gx-3">
        <div class="col-xxl-9 col-lg-10 col-md-12">
            <div class="card mb-3">
                <div class="card-body">

                    <div class="row mb-3">
                        <div class="col-md-12">
                            <form class="row g-2 align-items-center">
                                <div class="col-sm-4">
                                    <select class="form-select" id="assetType">
                                        <!-- Asset types will be dynamically populated -->
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <select class="form-select" id="assetList">
                                        <!-- Asset list will be dynamically populated -->
                                    </select>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div id="tradingview_chart" style="height: 500px;"></div>

                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-lg-2 col-md-12">
            <div class="card mb-3">
                <div class="card-body">

                    <form id="tradeForm" onsubmit="submitTrade(event)">
                        @csrf
                        <input type="hidden" name="asset" id="selectedAsset" value="MTC-USD">

                        <div class="mb-3">
                            <label class="form-label">Amount</label>
                            <input type="number" class="form-control" name="amount" step="0.01" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Duration</label>
                            <div class="row g-2">
                                <input class="form-control" name="duration" placeholder="Seconds" min="0"
                                    type="time" id="startTime" step="1">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trade Direction</label>
                            <select class="form-select" name="direction" required>
                                <option value="up">Up</option>
                                <option value="down">Down</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Place Trade</button>
                    </form>
                    <div class="row mt-4" hidden>
                        <div class="col-12">
                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#ongoing" role="tab">Ongoing
                                        Trades</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#assets" role="tab">Available
                                        Assets</a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="ongoing" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Asset</th>
                                                    <th>Amount</th>
                                                    <th>Direction</th>
                                                    <th>Duration</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Add your ongoing trades data here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="tab-pane" id="assets" role="tabpanel">
                                    <div class="table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>Asset</th>
                                                    <th>Current Price</th>
                                                    <th>24h Change</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Add your assets list data here -->
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="row mt-3" x-data="tradingDashboard()">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active': activeTab === 'active' }"
                                @click.prevent="activeTab = 'active'" href="#">
                                Active Trades (<span x-text="activeTrades.length"></span>)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" :class="{ 'active': activeTab === 'closed' }"
                                @click.prevent="activeTab = 'closed'" href="#">
                                Closed Trades (<span x-text="closedTrades.length"></span>)
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Asset</th>
                                    <th>Amount</th>
                                    <th>Direction</th>
                                    <th x-show="activeTab === 'active'">Time Remaining</th>
                                    <th x-show="activeTab === 'closed'">Result</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <template x-for="trade in displayedTrades" :key="trade.id">
                                    <tr>
                                        <td x-text="trade.trade_currency"></td>
                                        <td x-text="trade.trade_amount"></td>
                                        <td>
                                            <span
                                                :class="trade.trade_direction === 'up' ? 'badge bg-success' :
                                                    'badge bg-danger'"
                                                x-text="trade.trade_direction">
                                            </span>
                                        </td>
                                        <td x-show="activeTab === 'active'" x-text="getTimeRemaining(trade)"></td>
                                        <td x-show="activeTab === 'closed'" x-text="trade.trade_result || 'N/A'"></td>
                                        <td>
                                            <span :class="getStatusBadgeClass(trade.trade_status)"
                                                x-text="trade.trade_status"></span>
                                        </td>
                                    </tr>
                                </template>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script src="//s3.tradingview.com/tv.js"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', () => {
            const assetTypeSelector = document.getElementById('assetType');
            const assetListSelector = document.getElementById('assetList');
            const selectedAssetInput = document.getElementById('selectedAsset');

            // Fetch assets from the backend
            fetch('/api/assets') // Replace with your actual API route
                .then(response => response.json())
                .then(data => {
                    populateAssetTypeOptions(data);
                });

            // Populate asset type options
            function populateAssetTypeOptions(assets) {
                const assetTypes = [...new Set(assets.map(asset => asset.asset_group))];
                console.log(assets);
                
                assetTypes.forEach(type => {
                    const option = document.createElement('option');
                    option.value = type;
                    option.text = type.charAt(0).toUpperCase() + type.slice(1);
                    assetTypeSelector.appendChild(option);
                });

                // Populate asset list when type changes
                assetTypeSelector.addEventListener('change', function() {
                    const selectedType = this.value;
                    const filteredAssets = assets.filter(asset => asset.asset_group === selectedType);
                    populateAssetList(filteredAssets);
                });

                // Automatically load the first type
                if (assetTypes.length > 0) {
                    assetTypeSelector.value = assetTypes[0];
                    assetTypeSelector.dispatchEvent(new Event('change'));
                }
            }

            // Populate asset list dropdown
            function populateAssetList(filteredAssets) {
                assetListSelector.innerHTML = '';

                filteredAssets.forEach(asset => {
                    const option = document.createElement('option');
                    option.value = asset.display_symbol;
                    option.text = asset.name;
                    assetListSelector.appendChild(option);
                });

                // Update the chart when asset changes
                assetListSelector.addEventListener('change', function() {
                    selectedAssetInput.value = this.value;
                    updateChart(this.value);
                });

                // Automatically load the first asset
                if (filteredAssets.length > 0) {
                    assetListSelector.value = filteredAssets[0].display_symbol;
                    assetListSelector.dispatchEvent(new Event('change'));
                }
            }

            // Update the TradingView chart
            function updateChart(symbol) {
                const container = document.getElementById('tradingview_chart');
                container.innerHTML = ''; // Clear previous chart
                let finalSymbol = symbol.replace("/", "").toUpperCase();
                // console.log(finalSymbol);
                new TradingView.widget({
                    "container_id": "tradingview_chart",
                    "autosize": true,
                    "symbol": finalSymbol,
                    "interval": "1",
                    "theme": "dark",
                    "style": "2",
                    "locale": "en",
                    "toolbar_bg": "#f1f3f6",
                    "enable_publishing": true,
                    "hide_top_toolbar": false,
                    "hide_side_toolbar": false,
                    "allow_symbol_change": false,
                    "studies": [
                        "MAExp@tv-basicstudies"
                    ]
                });
            }
        });

        function submitTrade(e) {
            e.preventDefault();

            const form = document.getElementById('tradeForm');
            const formData = new FormData(form);

            fetch('{{ route('binary.trade') }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Handle success
                        alert('Trade placed successfully');
                        form.reset();
                    } else {
                        // Handle error
                        alert(data.message || 'Error placing trade');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error placing trade');
                });
        }

        window.onload = function() {
            var channel = Echo.channel('trade.created'); // Ensure this matches the backend channel
            channel.listen('.trade.created', function(data) { // Ensure the event name matches
                console.log('Trade Created:', data);
                alert(JSON.stringify(data));
            });
        };

        document.addEventListener('alpine:init', () => {
            Alpine.data('tradingDashboard', () => ({
                trades: [],
                activeTab: 'active',

                init() {
                    this.fetchTrades();
                    this.initEcho();

                    setInterval(() => {
                        this.updateTradeStatuses();
                    }, 1000);
                },

                get activeTrades() {
                    return this.trades.filter(trade => !this.isExpired(trade) && trade
                        .trade_status !== 'closed');
                },

                get closedTrades() {
                    return this.trades.filter(trade => this.isExpired(trade) || trade
                        .trade_status === 'closed');
                },

                get displayedTrades() {
                    return this.activeTab === 'active' ? this.activeTrades : this.closedTrades;
                },

                fetchTrades() {
                    fetch('/api/trades')
                        .then(response => response.json())
                        .then(trades => {
                            this.trades = trades;
                        });
                },

                initEcho() {
                    Echo.channel('trade.created')
                        .listen('.trade.created', (trade) => {
                            this.trades.push(trade);
                        });
                },

                getTimeRemaining(trade) {
                    const endTime = new Date(trade.created_at).getTime() +
                        (parseInt(trade.trade_close_time) * 1000);
                    const now = new Date().getTime();
                    const timeLeft = endTime - now;

                    if (timeLeft <= 0) return 'Expired';

                    const seconds = Math.floor((timeLeft / 1000) % 60);
                    const minutes = Math.floor((timeLeft / (1000 * 60)) % 60);
                    const hours = Math.floor((timeLeft / (1000 * 60 * 60)) % 24);

                    return `${hours}h ${minutes}m ${seconds}s`;
                },

                isExpired(trade) {
                    const endTime = new Date(trade.created_at).getTime() +
                        (parseInt(trade.trade_close_time) * 1000);
                    return new Date().getTime() > endTime;
                },

                updateTradeStatuses() {
                    this.trades = this.trades.map(trade => {
                        if (this.isExpired(trade) && trade.trade_status === 'pending') {
                            return {
                                ...trade,
                                trade_status: 'closed'
                            };
                        }
                        return trade;
                    });
                },

                getStatusBadgeClass(status) {
                    const classes = {
                        'pending': 'badge bg-warning',
                        'closed': 'badge bg-secondary',
                        'won': 'badge bg-success',
                        'lost': 'badge bg-danger'
                    };
                    return classes[status] || 'badge bg-info';
                }
            }));
        });
    </script>
@endpush
