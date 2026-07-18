@extends('layouts.admin.app')

@section('title', 'Plan Subscriptions')

@section('content')
    <x-page-header title="Plan Subscriptions" subtitle="All user subscriptions across every plan." />

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>User</th><th>Plan</th><th>Stake</th><th>Payout</th><th>Matures</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($subscriptions as $sub)
                    <tr>
                        <td>{{ $sub->user->first_name }} {{ $sub->user->last_name }}</td>
                        <td>{{ $sub->plan->name ?? '—' }}</td>
                        <td>{{ formatPrice($sub->stake_amount) }}</td>
                        <td>{{ formatPrice($sub->expected_payout) }}</td>
                        <td>{{ $sub->matures_at->format('d M, Y') }}</td>
                        <td><x-badge :status="$sub->status" /></td>
                        <td class="text-right">
                            @if ($sub->status === 'active')
                                <form method="POST" action="{{ route('admin.plan-subscriptions.cancel', $sub) }}" onsubmit="return confirm('Cancel and refund this subscription?')">
                                    @csrf
                                    <button type="submit" class="text-brand-danger hover:underline">Cancel &amp; Refund</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-10 text-slate-400">No subscriptions yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $subscriptions->links() }}</div>
    </x-glass-card>
@endsection
