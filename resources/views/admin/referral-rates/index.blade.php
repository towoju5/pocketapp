@extends('layouts.admin.app')

@section('title', 'Referral Rates')

@section('content')
    <x-page-header title="Referral Rates" subtitle="Commission percentages per level and activity type.">
        <x-slot:actions>
            <a href="{{ route('admin.referral-rates.create') }}" class="brand-btn-primary">New Rate</a>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>Level</th><th>Activity</th><th>Percentage</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($rates as $rate)
                    <tr>
                        <td>L{{ $rate->level }}</td>
                        <td class="capitalize">{{ $rate->activity_type }}</td>
                        <td>{{ $rate->percentage }}%</td>
                        <td><x-badge :status="$rate->is_active ? 'active' : 'cancelled'">{{ $rate->is_active ? 'Active' : 'Inactive' }}</x-badge></td>
                        <td class="text-right space-x-3">
                            <a href="{{ route('admin.referral-rates.edit', $rate) }}" class="text-brand-blue hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.referral-rates.destroy', $rate) }}" class="inline" onsubmit="return confirm('Delete this rate?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-brand-danger hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-slate-400">No rates configured.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>
    </x-glass-card>
@endsection
