@extends('layouts.admin.app')

@section('title', $user->first_name . ' — Referrals')

@section('content')
    <x-page-header :title="$user->first_name . ' ' . $user->last_name" subtitle="Referral code: {{ $user->referral_code }}" />

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-glass-card title="Direct Downline">
            <x-data-table>
                <thead><tr><th>User</th><th>Joined</th></tr></thead>
                <tbody>
                    @forelse ($downline as $d)
                        <tr><td>{{ $d->first_name }} {{ $d->last_name }}</td><td>{{ $d->created_at->format('d M, Y') }}</td></tr>
                    @empty
                        <tr><td colspan="2" class="text-center py-10 text-slate-400">No direct referrals.</td></tr>
                    @endforelse
                </tbody>
            </x-data-table>
        </x-glass-card>

        <x-glass-card title="Commission History">
            <x-data-table>
                <thead><tr><th>From</th><th>Level</th><th>Amount</th></tr></thead>
                <tbody>
                    @forelse ($commissions as $c)
                        <tr>
                            <td>{{ $c->referredUser->first_name ?? 'Trader' }}</td>
                            <td>L{{ $c->level }}</td>
                            <td>{{ formatPrice($c->commission_amount) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center py-10 text-slate-400">No commissions yet.</td></tr>
                    @endforelse
                </tbody>
            </x-data-table>
        </x-glass-card>
    </div>
@endsection
