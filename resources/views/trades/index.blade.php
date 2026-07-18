@extends('layouts.desktop.trading')

@section('title', 'Trade History')

@section('content')
<div class="flex-1 overflow-y-auto p-6">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-xl font-bold text-white">Trade History</h1>
            <div class="flex items-center gap-3">
                <div class="flex items-center bg-[#1c243c] border border-[#2a3350] rounded-lg p-1 text-xs font-bold">
                    <a href="{{ route('trade.index', array_merge(request()->except('page'), ['mode' => 'real'])) }}"
                        class="px-3 py-1.5 rounded-md {{ $mode === 'real' ? 'bg-[#4f8ef7] text-white' : 'text-[#7c86a3]' }}">Real</a>
                    <a href="{{ route('trade.index', array_merge(request()->except('page'), ['mode' => 'demo'])) }}"
                        class="px-3 py-1.5 rounded-md {{ $mode === 'demo' ? 'bg-[#4f8ef7] text-white' : 'text-[#7c86a3]' }}">Demo</a>
                </div>
                <button type="button" id="exportCsvBtn" class="bg-[#1c243c] border border-[#2a3350] text-white text-xs font-bold px-4 py-2 rounded-lg">
                    <i class="fa fa-download"></i> Export CSV
                </button>
            </div>
        </div>

        @php
            $count = $trades->total();
            $wins = (clone $trades->getCollection())->where('trade_status', 'win')->count();
            $winRate = $count ? round(($wins / $count) * 100) : 0;
            $netPl = $trades->getCollection()->sum(fn($t) => $t->trade_status === 'win' ? ($t->trade_profit - $t->trade_amount) : ($t->trade_status === 'lose' ? -$t->trade_amount : 0));
            $volume = $trades->getCollection()->sum('trade_amount');
        @endphp

        <div class="grid grid-cols-4 gap-3 mb-6">
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4">
                <div class="text-xs text-[#7c86a3] mb-1">Trades (this page)</div>
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
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-4">
                <div class="text-xs text-[#7c86a3] mb-1">Volume traded</div>
                <div class="text-lg font-bold text-white">${{ number_format($volume, 2) }}</div>
            </div>
        </div>

        <form method="GET" action="{{ route('trade.index') }}" class="flex flex-wrap items-end gap-3 mb-4 bg-[#171e33] border border-[#2a3350] rounded-xl p-4">
            <input type="hidden" name="mode" value="{{ $mode }}">
            <div>
                <label class="block text-xs text-[#7c86a3] mb-1">From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" class="bg-[#1c243c] border border-[#2a3350] rounded-lg px-2 py-1.5 text-sm text-white">
            </div>
            <div>
                <label class="block text-xs text-[#7c86a3] mb-1">To</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" class="bg-[#1c243c] border border-[#2a3350] rounded-lg px-2 py-1.5 text-sm text-white">
            </div>
            <div>
                <label class="block text-xs text-[#7c86a3] mb-1">Asset</label>
                <select name="asset" class="bg-[#1c243c] border border-[#2a3350] rounded-lg px-2 py-1.5 text-sm text-white">
                    <option value="">All assets</option>
                    @foreach($assets as $asset)
                        <option value="{{ $asset }}" @selected(request('asset') === $asset)>{{ $asset }}</option>
                    @endforeach
                </select>
            </div>
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
            @if(request()->anyFilled(['date_from', 'date_to', 'asset', 'result']))
                <a href="{{ route('trade.index', ['mode' => $mode]) }}" class="text-sm text-[#7c86a3]">Clear filters</a>
            @endif
        </form>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left" id="tradeHistoryTable">
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
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="text-[#d7dcea]">
                        @forelse($trades as $trade)
                            @php
                                $pl = $trade->trade_status === 'win' ? ($trade->trade_profit - $trade->trade_amount) : ($trade->trade_status === 'lose' ? -$trade->trade_amount : 0);
                            @endphp
                            <tr class="border-t border-[#1c243c]">
                                <td class="px-4 py-3 font-medium">{{ $trade->trade_currency }}</td>
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
                                <td class="px-4 py-3">
                                    <a href="{{ route('trade.show', $trade->id) }}" class="text-[#4f8ef7] text-xs hover:underline">View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-10 text-center text-[#7c86a3]">No trades match these filters.</td>
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

@push('js')
<script>
    document.getElementById('exportCsvBtn').addEventListener('click', function () {
        const rows = [['Asset', 'Date/time', 'Direction', 'Amount', 'Entry', 'Exit', 'Result', 'P/L']];
        document.querySelectorAll('#tradeHistoryTable tbody tr').forEach((tr) => {
            const cells = tr.querySelectorAll('td');
            if (cells.length < 8) return;
            rows.push(Array.from(cells).slice(0, 8).map((td) => td.textContent.trim().replace(/\s+/g, ' ')));
        });
        const csv = rows.map((r) => r.map((v) => `"${v.replace(/"/g, '""')}"`).join(',')).join('\n');
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `trade-history-${new Date().toISOString().slice(0, 10)}.csv`;
        a.click();
        URL.revokeObjectURL(url);
    });
</script>
@endpush
@endsection
