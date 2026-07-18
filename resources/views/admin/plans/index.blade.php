@extends('layouts.admin.app')

@section('title', 'Plans')

@section('content')
    <x-page-header title="Plans" subtitle="Investment tiers traders can subscribe to.">
        <x-slot:actions>
            <a href="{{ route('admin.plans.create') }}" class="brand-btn-primary">New Plan</a>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Yield</th>
                    <th>Duration</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($plans as $plan)
                    <tr>
                        <td class="font-semibold text-white">
                            <span class="mr-2 inline-block h-2.5 w-2.5 rounded-full" style="background-color: {{ $plan->badge_color ?? '#3b82f6' }}"></span>
                            {{ $plan->name }}
                        </td>
                        <td class="capitalize">{{ $plan->amount_type }}</td>
                        <td>{{ $plan->roi_percentage }}%</td>
                        <td>{{ $plan->duration_days }} days</td>
                        <td><x-badge :status="$plan->is_active ? 'active' : 'cancelled'">{{ $plan->is_active ? 'Active' : 'Inactive' }}</x-badge></td>
                        <td class="text-right space-x-3">
                            <a href="{{ route('admin.plans.edit', $plan) }}" class="text-brand-blue hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.plans.destroy', $plan) }}" class="inline" onsubmit="return confirm('Delete this plan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-brand-danger hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-slate-400">No plans yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $plans->links() }}</div>
    </x-glass-card>
@endsection
