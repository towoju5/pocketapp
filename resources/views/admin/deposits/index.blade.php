@extends('layouts.admin.app')

@section('title', 'Deposits')

@section('content')
    <x-page-header title="Deposits" subtitle="Oversight of customer deposit activity.">
        <x-slot:actions>
            <form method="GET" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="brand-input-dark !w-auto !py-2">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="failed" @selected(request('status') === 'failed')>Failed</option>
                    <option value="cancelled" @selected(request('status') === 'cancelled')>Cancelled</option>
                </select>
            </form>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Submitted</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($deposits as $deposit)
                    <tr>
                        <td>{{ $deposit->user->first_name }} {{ $deposit->user->last_name }}</td>
                        <td class="font-mono font-semibold text-white">{{ formatPrice((float) $deposit->deposit_amount) }}</td>
                        <td class="capitalize">{{ $deposit->deposit_method }}</td>
                        <td>{{ $deposit->created_at->format('d M, Y H:i') }}</td>
                        <td><x-badge :status="$deposit->deposit_status" /></td>
                        <td class="text-right">
                            <a href="{{ route('admin.deposits.show', $deposit) }}" class="text-brand-blue hover:underline">View</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-slate-400">No deposits yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $deposits->links() }}</div>
    </x-glass-card>
@endsection
