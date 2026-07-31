@extends('layouts.admin.app')

@section('title', 'Payment Providers')

@section('content')
    <x-page-header title="Payment Providers" subtitle="Configure gateway credentials and enable deposits/payouts. Credentials are encrypted at rest." />

    @if (session('success'))
        <div class="mb-6 rounded-xl border border-brand-emerald/20 bg-brand-emerald/10 px-4 py-3 text-sm text-brand-emerald">
            {{ session('success') }}
        </div>
    @endif

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr>
                    <th>Provider</th>
                    <th>Type</th>
                    <th>Mode</th>
                    <th>Status</th>
                    <th>Deposit</th>
                    <th>Payout</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($providers as $provider)
                    @php $mode = $provider->config['mode'] ?? 'test'; @endphp
                    <tr>
                        <td class="font-medium text-white">{{ $provider->display_name }}</td>
                        <td class="capitalize text-slate-400">{{ $provider->type }}</td>
                        <td><x-badge :status="$mode === 'live' ? 'success' : 'warning'">{{ ucfirst($mode) }}</x-badge></td>
                        <td><x-badge :status="$provider->is_active ? 'active' : 'cancelled'">{{ $provider->is_active ? 'Active' : 'Disabled' }}</x-badge></td>
                        <td><x-badge :status="$provider->can_deposit ? 'active' : 'cancelled'">{{ $provider->can_deposit ? 'Yes' : 'No' }}</x-badge></td>
                        <td><x-badge :status="$provider->can_payout ? 'active' : 'cancelled'">{{ $provider->can_payout ? 'Yes' : 'No' }}</x-badge></td>
                        <td class="text-right">
                            <a href="{{ route('admin.payment-providers.edit', $provider) }}" class="text-brand-blue hover:underline">Configure</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-data-table>
    </x-glass-card>
@endsection
