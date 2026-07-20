@extends('layouts.desktop.trading')

@section('title', 'Express Trade History')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="w-4/5 mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-white">Express Trade History</h1>
        </div>

        @php
            $count = $trades->total();
            $wins = (clone $trades->getCollection())->where('trade_status', 'win')->count();
            $winRate = $count ? round(($wins / $count) * 100) : 0;
            $netPl = $trades->getCollection()->sum(fn($t) => $t->trade_status === 'win' ? ($t->trade_profit - $t->trade_amount) : ($t->trade_status === 'lose' ? -$t->trade_amount : 0));
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4">
                <div class="text-xs text-[#7c86a3] mb-1">Express trades (this page)</div>
                <div class="text-lg font-bold text-white">{{ $count }}</div>
            </div>
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4">
                <div class="text-xs text-[#7c86a3] mb-1">Win rate</div>
                <div class="text-lg font-bold text-white">{{ $winRate }}%</div>
            </div>
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4">
                <div class="text-xs text-[#7c86a3] mb-1">Net P/L (this page)</div>
                <div class="text-lg font-bold {{ $netPl >= 0 ? 'text-[#16c087]' : 'text-[#f4534a]' }}">${{ number_format($netPl, 2) }}</div>
            </div>
        </div>

        <form method="GET" action="{{ route('express-trades.index') }}" class="flex items-end gap-3 mb-4 bg-[#171e33] border border-[#2a3350] rounded-xl p-4">
            <div>
                <label class="block text-xs text-[#7c86a3] mb-1">Result</label>
                <select name="result" class="bg-[#1c243c] border border-[#2a3350] rounded-lg px-2 py-1.5 text-sm text-white">
                    <option value="all" @selected(request('result', 'all') === 'all')>All</option>
                    <option value="win" @selected(request('result') === 'win')>Wins</option>
                    <option value="lose" @selected(request('result') === 'lose')>Losses</option>
                    <option value="pending" @selected(request('result') === 'pending')>Pending</option>
                </select>
            </div>
            <button type="submit" class="bg-[#4f8ef7] text-white text-sm font-semibold px-4 py-2 rounded-lg">Filter</button>
        </form>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="text-[#7c86a3] text-xs uppercase bg-[#1c243c]">
                        <tr>
                            <th class="px-4 py-3">Asset</th>
                            <th class="px-4 py-3">Date/time</th>
                            <th class="px-4 py-3">Direction</th>
                            <th class="px-4 py-3">Amount</th>
                            <th class="px-4 py-3">Entry</th>
                            <th class="px-4 py-3">Exit</th>
                            <th class="px-4 py-3">Result</th>
                            <th class="px-4 py-3">P/L</th>
                        </tr>
                    </thead>
                    <tbody class="text-[#d7dcea]">
                        @forelse($trades as $trade)
                            @php
                                $pl = $trade->trade_status === 'win' ? ($trade->trade_profit - $trade->trade_amount) : ($trade->trade_status === 'lose' ? -$trade->trade_amount : 0);
                            @endphp
                            <tr class="border-t border-[#1c243c]">
                                <td class="px-4 py-3 font-medium">{{ $trade->trade_currency ?? optional($trade->asset)->symbol ?? '—' }}</td>
                                <td class="px-4 py-3 text-[#7c86a3]">{{ $trade->created_at->format('Y-m-d H:i') }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $trade->trade_direction === 'up' ? 'text-[#16c087]' : 'text-[#f4534a]' }}">
                                        <i class="fa fa-arrow-{{ $trade->trade_direction === 'up' ? 'up' : 'down' }}"></i>
                                        {{ $trade->trade_direction === 'up' ? 'BUY' : 'SELL' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">${{ number_format((float) $trade->trade_amount, 2) }}</td>
                                <td class="px-4 py-3">{{ number_format((float) $trade->start_price, 6) }}</td>
                                <td class="px-4 py-3">{{ $trade->end_price ? number_format((float) $trade->end_price, 6) : '—' }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-2 py-1 rounded text-xs font-medium
                                        {{ $trade->trade_status === 'win' ? 'bg-[#16c087]/15 text-[#16c087]' : ($trade->trade_status === 'lose' ? 'bg-[#f4534a]/15 text-[#f4534a]' : 'bg-[#7c86a3]/15 text-[#7c86a3]') }}">
                                        {{ ucfirst($trade->trade_status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-semibold {{ $pl >= 0 ? 'text-[#16c087]' : 'text-[#f4534a]' }}">
                                    {{ $pl >= 0 ? '+' : '' }}${{ number_format($pl, 2) }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-10 text-center text-[#7c86a3]">No express trades yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-4 border-t border-[#2a3350]">
                {{ $trades->links('pagination::tailwind') }}
            </div>
        </div>
    </div>
</div>
@endsection
