@extends('layouts.admin.app')

@section('title', 'KYC Providers')

@section('content')
    <x-page-header title="KYC Providers" subtitle="Exactly one method is active at a time — activating one here automatically deactivates the rest." />

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
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($providers as $provider)
                    <tr>
                        <td class="font-medium text-white">{{ $provider->display_name }}</td>
                        <td><x-badge :status="$provider->is_active ? 'active' : 'cancelled'">{{ $provider->is_active ? 'Active' : 'Inactive' }}</x-badge></td>
                        <td class="text-right">
                            <a href="{{ route('admin.kyc-providers.edit', $provider) }}" class="text-brand-blue hover:underline">Configure</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </x-data-table>
    </x-glass-card>
@endsection
