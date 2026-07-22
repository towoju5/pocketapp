@extends('layouts.desktop.trading')

@section('title', 'Deposit Details')
@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-white">Deposit #{{ $deposit->id }}</h1>
            <a href="{{ route('deposits.create') }}" class="text-[#4f8ef7] text-sm hover:underline">&larr; Back to deposit</a>
        </div>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-5 space-y-4">
            <div class="flex justify-between items-center pb-4 border-b border-[#2a3350]">
                <span class="text-[#7c86a3] text-sm">Amount</span>
                <span class="text-white text-lg font-bold">${{ number_format((float) $deposit->deposit_amount, 2) }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-[#7c86a3] text-sm">Status</span>
                <span class="px-2 py-1 rounded text-xs font-medium
                    {{ $deposit->deposit_status === 'completed' ? 'bg-[#16c087]/15 text-[#16c087]' : ($deposit->deposit_status === 'rejected' ? 'bg-[#f4534a]/15 text-[#f4534a]' : 'bg-[#7c86a3]/15 text-[#7c86a3]') }}">
                    {{ ucfirst($deposit->deposit_status) }}
                </span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-[#7c86a3] text-sm">Method</span>
                <span class="text-[#d7dcea] text-sm">{{ ucfirst($deposit->deposit_method) }}</span>
            </div>
            <div class="flex justify-between items-center">
                <span class="text-[#7c86a3] text-sm">Date</span>
                <span class="text-[#d7dcea] text-sm">{{ \Illuminate\Support\Carbon::parse($deposit->deposit_date_time)->format('Y-m-d H:i') }}</span>
            </div>
            @if(!empty($deposit->deposit_extra_info['wallet_address']))
                <div class="flex justify-between items-center gap-4">
                    <span class="text-[#7c86a3] text-sm flex-shrink-0">Deposit Address</span>
                    <span class="text-[#d7dcea] text-sm break-all text-right">{{ $deposit->deposit_extra_info['wallet_address'] }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
