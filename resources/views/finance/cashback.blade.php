@extends('layouts.desktop.trading')

@section('title', 'Cashback')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="w-4/5 mx-auto">
        @include('partials.finance-header')

        <div class="grid lg:grid-cols-3 gap-5 mt-6">
            <div class="bg-[#171e33] border border-[#2a3350] text-[#d7dcea] p-6 rounded-xl lg:col-span-2">
                <div class="text-center">
                    <h2 class="text-xl font-semibold text-white">Your Cashback Rate</h2>
                    <p class="text-4xl font-bold text-[#4f8ef7]">{{ $rule ? number_format($rule->percentage, 2) : '0' }}%</p>
                    <p class="text-xs text-[#7c86a3] mt-1">
                        @if($rule)
                            Automatically applied to every losing trade
                        @else
                            No cashback program is currently active
                        @endif
                    </p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6 text-center">
                    <div class="border border-[#2a3350] p-4 rounded-lg">
                        <div class="text-xs text-[#7c86a3] mb-1">Last payout</div>
                        <div class="font-semibold text-white">{{ $lastPayout ? '$' . number_format($lastPayout->amountFloat, 2) : '—' }}</div>
                    </div>
                    <div class="border border-[#2a3350] p-4 rounded-lg">
                        <div class="text-xs text-[#7c86a3] mb-1">Total cashback earned</div>
                        <div class="font-semibold text-[#16c087]">${{ number_format($totalCashback, 2) }}</div>
                    </div>
                    <div class="border border-[#2a3350] p-4 rounded-lg">
                        <div class="text-xs text-[#7c86a3] mb-1">Current rate</div>
                        <div class="font-semibold text-white">{{ $rule ? number_format($rule->percentage, 2) . '%' : '—' }}</div>
                    </div>
                    <div class="border border-[#2a3350] p-4 rounded-lg">
                        <div class="text-xs text-[#7c86a3] mb-1">Last payout date</div>
                        <div class="font-semibold text-white">{{ $lastPayout ? $lastPayout->created_at->format('Y-m-d') : '—' }}</div>
                    </div>
                </div>

                <div class="mt-6 text-sm text-[#7c86a3]">
                    <h3 class="font-semibold text-[#d7dcea]">How it works</h3>
                    <ul class="list-decimal list-inside space-y-2 mt-2">
                        <li>Cashback is a percentage of your stake automatically credited back to your wallet whenever a trade settles as a loss.</li>
                        <li>The rate is set by the platform and can change — the amount shown above always reflects the currently active rate.</li>
                        <li>Cashback is credited to the same wallet (demo or real) the original trade was placed from.</li>
                        <li>There's nothing to activate or purchase — if a cashback rate is active, it applies automatically to every qualifying trade.</li>
                    </ul>
                </div>
            </div>

            <div class="bg-[#171e33] border border-[#2a3350] text-[#d7dcea] p-6 rounded-xl">
                @if($rule)
                    <div class="p-4 bg-[#1c243c] border border-dashed border-[#16c087] rounded-lg">
                        <p class="text-sm text-[#7c86a3]">You're currently earning <span class="text-[#16c087] font-semibold">{{ number_format($rule->percentage, 2) }}%</span> back on every losing trade — no action needed.</p>
                    </div>
                @else
                    <div class="p-4 bg-[#1c243c] border border-dashed border-[#f4534a] rounded-lg">
                        <p class="text-sm text-[#7c86a3]">There's no active cashback program right now. Check back later.</p>
                    </div>
                @endif

                <div class="mt-6">
                    <h3 class="text-sm font-bold text-white mb-3">Recent Cashback Payouts</h3>
                    <div class="space-y-2">
                        @forelse($payouts as $payout)
                            <div class="flex items-center justify-between border border-[#2a3350] rounded-lg p-3 text-sm">
                                <div>
                                    <div class="text-white font-semibold">${{ number_format($payout->amountFloat, 2) }}</div>
                                    <div class="text-[#7c86a3] text-xs">{{ $payout->created_at->format('Y-m-d H:i') }}</div>
                                </div>
                                <span class="bg-[#16c087]/15 text-[#16c087] px-2 py-1 rounded text-xs">Paid</span>
                            </div>
                        @empty
                            <div class="border border-dashed border-[#2a3350] rounded-lg p-4 text-center text-xs text-[#7c86a3]">No cashback payouts yet.</div>
                        @endforelse
                    </div>
                    @if($payouts->hasPages())
                        <div class="mt-3">{{ $payouts->links('pagination::tailwind') }}</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
