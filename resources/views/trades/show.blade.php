@extends('layouts.desktop.trading')

@section('title', 'Trade Details')
@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="max-w-2xl mx-auto">
        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl overflow-hidden">
            <div class="flex justify-between items-center p-4 bg-[#1c243c] border-b border-[#2a3350]">
                <h5 class="text-lg font-semibold text-white">Trade #{{ $trade->id }}</h5>
                <a href="{{ route('trade.index') }}" class="px-3 py-1.5 bg-[#2a3350] text-[#d7dcea] text-xs font-semibold rounded-lg hover:text-white">Back to Trades</a>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-5">
                    <div>
                        <dt class="text-xs text-[#7c86a3] mb-1">Asset</dt>
                        <dd class="text-sm font-semibold text-white">{{ $trade->trade_currency ?? ($trade->trade_extra_info['asset'] ?? '—') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-[#7c86a3] mb-1">Direction</dt>
                        <dd class="text-sm font-semibold {{ $trade->trade_direction === 'up' ? 'text-[#16c087]' : 'text-[#f4534a]' }}">
                            <i class="fa fa-arrow-{{ $trade->trade_direction === 'up' ? 'up' : 'down' }}"></i>
                            {{ $trade->trade_direction === 'up' ? 'BUY' : 'SELL' }}
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-[#7c86a3] mb-1">Amount</dt>
                        <dd class="text-sm font-semibold text-white">${{ number_format((float) $trade->trade_amount, 2) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-[#7c86a3] mb-1">Start Price</dt>
                        <dd class="text-sm text-[#d7dcea]">{{ number_format((float) $trade->start_price, 6) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-[#7c86a3] mb-1">Exit Price</dt>
                        <dd class="text-sm text-[#d7dcea]">{{ $trade->end_price ? number_format((float) $trade->end_price, 6) : '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-[#7c86a3] mb-1">Status</dt>
                        <dd>
                            <span class="px-2 py-1 rounded text-xs font-medium
                                {{ $trade->trade_status === 'win' ? 'bg-[#16c087]/15 text-[#16c087]' : ($trade->trade_status === 'lose' ? 'bg-[#f4534a]/15 text-[#f4534a]' : 'bg-[#7c86a3]/15 text-[#7c86a3]') }}">
                                {{ ucfirst($trade->trade_status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs text-[#7c86a3] mb-1">Close Time</dt>
                        <dd class="text-sm text-[#d7dcea]">{{ \Illuminate\Support\Carbon::parse($trade->trade_close_time)->format('Y-m-d H:i:s') }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs text-[#7c86a3] mb-1">Placed</dt>
                        <dd class="text-sm text-[#d7dcea]">{{ $trade->created_at->format('Y-m-d H:i') }}</dd>
                    </div>
                    @if($trade->trade_status !== 'pending')
                        @php($pl = $trade->trade_status === 'win' ? ($trade->trade_profit - $trade->trade_amount) : -$trade->trade_amount)
                        <div>
                            <dt class="text-xs text-[#7c86a3] mb-1">Profit / Loss</dt>
                            <dd class="text-sm font-semibold {{ $pl >= 0 ? 'text-[#16c087]' : 'text-[#f4534a]' }}">
                                {{ $pl >= 0 ? '+' : '' }}${{ number_format($pl, 2) }}
                            </dd>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
