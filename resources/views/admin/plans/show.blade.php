@extends('layouts.admin.app')

@section('title', $plan->name)

@section('content')
    <x-page-header :title="$plan->name" subtitle="{{ $plan->roi_percentage }}% over {{ $plan->duration_days }} days">
        <x-slot:actions>
            <a href="{{ route('admin.plans.edit', $plan) }}" class="brand-btn-outline">Edit</a>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card title="Subscriptions">
        <x-data-table>
            <thead>
                <tr><th>User</th><th>Stake</th><th>Payout</th><th>Matures</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse ($plan->subscriptions()->with('user')->latest()->limit(20)->get() as $sub)
                    <tr>
                        <td>{{ $sub->user->first_name }} {{ $sub->user->last_name }}</td>
                        <td>{{ formatPrice($sub->stake_amount) }}</td>
                        <td>{{ formatPrice($sub->expected_payout) }}</td>
                        <td>{{ $sub->matures_at->format('d M, Y') }}</td>
                        <td><x-badge :status="$sub->status" /></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-slate-400">No subscriptions yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>
    </x-glass-card>
@endsection
