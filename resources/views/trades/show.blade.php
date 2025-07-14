@extends('layouts.app')

@section('title', 'Trade Details')

@section('content')
<div class="container mx-auto py-6">
    <div class="panel box-border personal-info-panel mb-3">
        <div class="panel-heading bg-gray-100 dark:bg-gray-700 py-2 px-4 rounded-t-xl flex justify-between items-center">
            <div class="panel-title text-lg font-semibold text-gray-100">
                Trade Details #{{ $trade->id ?? 'N/A' }}
            </div>
            <a href="{{ route('trade.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded hover:bg-gray-500">Back to Trades</a>
        </div>

        <div class="w-full border border-[#292d4a] rounded-b-xl p-3">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-[#1b1e35] border border-[#292d4a] rounded-xl p-6 shadow-lg text-sm">

                {{-- Left Column --}}
                <div>
                    <dl class="space-y-4">
                        <div>
                            <dt class="font-semibold text-white">Asset</dt>
                            <dd class="text-gray-300 break-all">{{ $trade->trade_extra_info['asset'] ?? optional($trade->asset)->symbol ?? 'N/A' }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-white">Amount</dt>
                            <dd class="text-green-400">{{ formatPrice($trade->trade_amount ?? $trade->trade_extra_info['amount'] ?? 0, 6) }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-white">Direction</dt>
                            <dd class="text-gray-300">{{ ucfirst($trade->trade_direction ?? $trade->trade_extra_info['direction'] ?? 'N/A') }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-white">Start Price</dt>
                            <dd class="text-gray-300">{{ formatPrice($trade->start_price ?? $trade->trade_extra_info['start_price'] ?? 0, 6) }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-white">Trade Type</dt>
                            <dd class="text-gray-300">{{ ucfirst($trade->trade_type ?? 'N/A') }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-white">Wallet</dt>
                            <dd class="text-gray-300">{{ $trade->trade_wallet ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- Right Column --}}
                <div>
                    <dl class="space-y-4">
                        <div>
                            <dt class="font-semibold text-white">Current Price</dt>
                            <dd class="text-gray-300">{{ formatPrice($trade->end_price ?? $trade->trade_extra_info['currentPrice'] ?? 0, 6) }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-white">Profit</dt>
                            <dd class="text-green-400">{{ formatPrice($trade->trade_profit ?? 0, 2) }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-white">Profit Margin</dt>
                            <dd class="text-gray-300">{{ formatPrice(($trade->asset['asset_profit_margin'] ?? $trade->trade_percentage ?? 0), 2) }}%</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-white">Status</dt>
                            <dd>
                                <span class="inline-block px-3 py-1 text-sm font-semibold rounded-full
                                    {{ ($trade->trade_status ?? 'pending') === 'pending' ? 'bg-yellow-400 text-yellow-800' : 'bg-green-500 text-white' }}">
                                    {{ ucfirst($trade->trade_status ?? 'pending') }}
                                </span>
                            </dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-white">Close Time</dt>
                            <dd class="text-gray-300">{{ \Carbon\Carbon::parse($trade->trade_close_time ?? '')->format('Y-m-d H:i:s') }}</dd>
                        </div>

                        <div>
                            <dt class="font-semibold text-white">Date</dt>
                            <dd class="text-gray-300">{{ \Carbon\Carbon::parse($trade->created_at ?? '')->format('Y-m-d H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
