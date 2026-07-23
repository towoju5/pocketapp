@extends('layouts.admin.app')

@section('title', 'Trades')

@section('content')
    <x-page-header title="Trades" subtitle="Oversight of all binary options trades platform-wide.">
        <x-slot:actions>
            <form method="GET" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="brand-input-dark !w-auto !py-2">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="win" @selected(request('status') === 'win')>Win</option>
                    <option value="lose" @selected(request('status') === 'lose')>Lose</option>
                </select>
                <select name="mode" onchange="this.form.submit()" class="brand-input-dark !w-auto !py-2">
                    <option value="">Real + Demo</option>
                    <option value="real" @selected(request('mode') === 'real')>Real only</option>
                    <option value="demo" @selected(request('mode') === 'demo')>Demo only</option>
                </select>
            </form>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Asset</th>
                    <th>Direction</th>
                    <th>Amount</th>
                    <th>Wallet</th>
                    <th>Status</th>
                    <th>Placed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($trades as $trade)
                    <tr>
                        <td>{{ $trade->user->first_name ?? 'User' }} {{ $trade->user->last_name ?? '' }}</td>
                        <td class="font-medium">{{ $trade->trade_currency }}</td>
                        <td>
                            <span class="{{ $trade->trade_direction === 'up' ? 'text-brand-emerald' : 'text-brand-danger' }}">
                                <i class="fa fa-arrow-{{ $trade->trade_direction === 'up' ? 'up' : 'down' }}"></i>
                                {{ $trade->trade_direction === 'up' ? 'BUY' : 'SELL' }}
                            </span>
                        </td>
                        <td class="font-mono font-semibold text-white">{{ formatPrice($trade->trade_amount) }}</td>
                        <td class="text-xs uppercase text-slate-400">{{ str_contains($trade->trade_wallet, 'real') ? 'Real' : 'Demo' }}</td>
                        <td><x-badge :status="$trade->trade_status === 'lose' ? 'danger' : ($trade->trade_status === 'win' ? 'success' : 'pending')">{{ ucfirst($trade->trade_status) }}</x-badge></td>
                        <td class="text-xs text-slate-400">{{ $trade->created_at->format('d M, H:i:s') }}</td>
                        <td>
                            <div class="flex items-center gap-2 whitespace-nowrap">
                                <a href="{{ route('admin.trades.show', $trade) }}" class="text-xs font-semibold text-brand-blue hover:underline">View</a>
                                @if ($trade->trade_status === 'pending' || $trade->trade_status === 'open')
                                    <form method="POST" action="{{ route('admin.trades.force-win', $trade) }}" class="js-force-form">
                                        @csrf
                                        <input type="hidden" name="notes" class="js-notes">
                                        <button type="submit" class="text-xs font-semibold text-brand-emerald hover:underline">Win</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.trades.force-lose', $trade) }}" class="js-force-form">
                                        @csrf
                                        <input type="hidden" name="notes" class="js-notes">
                                        <button type="submit" class="text-xs font-semibold text-brand-danger hover:underline">Lose</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.trades.void', $trade) }}" class="js-force-form">
                                        @csrf
                                        <input type="hidden" name="notes" class="js-notes">
                                        <button type="submit" class="text-xs font-semibold text-slate-400 hover:underline">Void</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center py-10 text-slate-400">No trades yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $trades->links() }}</div>
    </x-glass-card>

    <script>
        document.querySelectorAll('.js-force-form').forEach((form) => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const action = form.querySelector('button').textContent.trim();
                const notes = prompt(`Reason for forcing this trade to ${action.toUpperCase()} (required):`);
                if (!notes) return;
                if (!confirm(`Force this trade to ${action.toUpperCase()}?`)) return;
                form.querySelector('.js-notes').value = notes;
                form.submit();
            });
        });
    </script>
@endsection
