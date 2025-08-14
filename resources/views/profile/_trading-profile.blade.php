@extends('layouts.app')

@section('title', 'Trading Profile')

@section('content')
<style>
    .tpblock {
        background-color: #161a2f;
        border-radius: 10px;
        margin-bottom: 20px;
        padding: 12px;
    }

    .trading-list {
        background-color: rgba(48, 153, 245, .1);
        border-left: 4px solid #3099f5;
        opacity: 1;
        border-radius: 5px;
        font-size: 12px;
        padding: 7px 0 7px 12px;
        -webkit-transition: opacity .4s ease-out;
        transition: opacity .4s ease-out;
    }
</style>
<div class="lg:p-4 lg:py-4 w-full">

    @if(!is_mobile())
    @include('partials.profile')
    @endif
    <div class="panel box-border personal-info-panel">
        <div class="panel-heading">
            <div class="hidden lg:block panel-title">Identity info</div>
        </div>
        <div class="panel-body">
            <div class="bg-gray-900 text-white">
                <!-- Container -->
                <div class="lg:p-8">
                    <!-- Header -->
                    <div class="text-lg font-semibold mb-6">Trading profile</div>

                    <!-- Main Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-2">
                        <!-- Left Panel -->
                        <div class="tpblock p-6 rounded-md tpblock">
                            <div class="flex items-center gap-4 mb-4">
                                <div class="w-16 h-16 bg-gray-700 rounded-full"></div>
                                <div>
                                    <div class="text-xl font-bold">{{ $user->username }}</div>
                                    <div class="text-sm text-gray-400">UID 90373611</div>
                                </div>
                            </div>
                            <div class="text-2xl font-bold mb-4">$0</div>
                            <button class="w-full bg-green-500 text-white py-2 px-4 rounded-md font-semibold">
                                Invest real money
                            </button>


                            <div class="mt-6 space-y-2">
                                <div class="trading-list">
                                    <span>Trades:</span>
                                    <span>{{ $totalTrades }}</span>
                                </div>

                                <div class="trading-list">
                                    <span>Profitable trades:</span>
                                    <span>{{ number_format($profitableTradesPercentage, 2) }}%</span>
                                </div>
                                <div class="trading-list">
                                    <span>Trading turnover:</span>
                                    <span>${{ number_format($tradingTurnover, 2) }}</span>
                                </div>
                                <div class="trading-list">
                                    <span>Trading profit:</span>
                                    <span>${{ number_format($tradingProfit, 2) }}</span>
                                </div>
                                <div class="trading-list">
                                    <span>Max. trade:</span>
                                    <span>${{ number_format($maxTrade, 2) }}</span>
                                </div>
                                <div class="trading-list">
                                    <span>Min. trade:</span>
                                    <span>${{ number_format($minTrade, 2) }}</span>
                                </div>
                                <div class="trading-list">
                                    <span>Max. profit:</span>
                                    <span>${{ number_format($maxProfit, 2) }}</span>
                                </div>
                            </div>

                            <div class="mt-6 hidden">
                                <button class="w-full bg-orange-500 text-white py-2 px-4 rounded-md font-semibold">
                                    Get 50% bonus
                                </button>
                            </div>
                        </div>

                        <!-- Right Panel -->
                        <div class="lg:col-span-4 lg:grid lg:grid-cols-1 gap-6 tpblock">
                            <!-- Profitability Chart -->
                            <div class="tpblock shadow-xl p-6 rounded-md mb-3">
                                <div class="text-lg font-semibold mb-4">Profitability</div>
                                <canvas id="profitabilityChart"></canvas>
                            </div>

                            <!-- Distribution by Assets -->
                            <div class="lg:grid lg:grid-cols-2 gap-6">
                                <div class="tpblock shadow-xl p-6 rounded-md flex items-center justify-center mb-3">
                                    {{-- <span class="text-gray-400">Trades distribution by assets<br>No trading history</span> --}}
                                    <canvas id="tradeAmountsChart"></canvas>
                                </div>
                                <div class="tpblock shadow-xl p-6 rounded-md flex items-center justify-center mb-2">
                                    {{-- <span class="text-gray-400">Trade amounts by assets<br>No trading history</span> --}}
                                    <canvas id="tradesDistributionChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@push('js')
<script src="//cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Profitability Chart Data
    const profitabilityLabels = @json($profitabilityData->keys());
    const profitabilityValues = @json($profitabilityData->values());

    // Trade Amounts by Assets Data
    const tradeAmountsLabels = @json($tradeAmountsByAssets->keys());
    const tradeAmountsValues = @json($tradeAmountsByAssets->values());

    // Trades Distribution by Assets Data
    const tradesDistributionLabels = @json($tradesDistributionByAssets->keys());
    const tradesDistributionValues = @json($tradesDistributionByAssets->values());

    // Profitability Chart
    const profitabilityCtx = document.getElementById('profitabilityChart').getContext('2d');
    new Chart(profitabilityCtx, {
        type: 'line',
        data: {
            labels: profitabilityLabels,
            datasets: [{
                label: 'Profitability',
                data: profitabilityValues,
                borderColor: 'rgba(75, 192, 192, 1)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderWidth: 2,
            }]
        }
    });

    // Trade Amounts by Assets Chart
    const tradeAmountsCtx = document.getElementById('tradeAmountsChart').getContext('2d');
    new Chart(tradeAmountsCtx, {
        type: 'bar',
        data: {
            labels: tradeAmountsLabels,
            datasets: [{
                label: 'Trade Amounts',
                data: tradeAmountsValues,
                backgroundColor: 'rgba(153, 102, 255, 0.6)',
                borderColor: 'rgba(153, 102, 255, 1)',
                borderWidth: 1,
            }]
        }
    });

    // Trades Distribution by Assets Chart
    const tradesDistributionCtx = document.getElementById('tradesDistributionChart').getContext('2d');
    new Chart(tradesDistributionCtx, {
        type: 'pie',
        data: {
            labels: tradesDistributionLabels,
            datasets: [{
                label: 'Trades Distribution',
                data: tradesDistributionValues,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.6)',
                    'rgba(54, 162, 235, 0.6)',
                    'rgba(255, 206, 86, 0.6)',
                    'rgba(75, 192, 192, 0.6)',
                    'rgba(153, 102, 255, 0.6)',
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                ],
                borderWidth: 1,
            }]
        }
    });
</script>
@endpush