@extends('layouts.admin.app')

@section('title', 'Withdrawal Requests')

@section('content')
    <x-page-header title="Withdrawal Requests" subtitle="Review and approve customer withdrawal requests.">
        <x-slot:actions>
            <form method="GET" class="flex items-center gap-2">
                <select name="status" onchange="this.form.submit()" class="brand-input-dark !w-auto !py-2">
                    <option value="">All statuses</option>
                    <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                    <option value="completed" @selected(request('status') === 'completed')>Completed</option>
                    <option value="rejected" @selected(request('status') === 'rejected')>Rejected</option>
                </select>
            </form>
        </x-slot:actions>
    </x-page-header>

    @if (session('success'))
        <div class="mb-6 rounded-xl border-l-4 border-brand-emerald bg-brand-emerald/10 px-5 py-4 text-sm font-semibold text-brand-emerald">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-6 rounded-xl border-l-4 border-brand-danger bg-brand-danger/10 px-5 py-4 text-sm font-semibold text-brand-danger">{{ session('error') }}</div>
    @endif

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Requested</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($payouts as $payout)
                    <tr>
                        <td>{{ $payout->user->first_name }} {{ $payout->user->last_name }}</td>
                        <td class="font-mono font-semibold text-white">{{ formatPrice((float) $payout->payout_amount) }}</td>
                        <td class="capitalize">{{ $payout->payout_method }}</td>
                        <td>{{ $payout->created_at->format('d M, Y H:i') }}</td>
                        <td><x-badge :status="$payout->payout_status" /></td>
                        <td class="text-right">
                            <a href="{{ route('admin.payouts.show', $payout) }}" class="text-brand-blue hover:underline">Review</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-slate-400">No withdrawal requests yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $payouts->links() }}</div>
    </x-glass-card>
@endsection
