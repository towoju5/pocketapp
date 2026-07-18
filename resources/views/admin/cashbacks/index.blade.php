@extends('layouts.admin.app')

@section('title', 'Cashback Rules')

@section('content')
    <x-page-header title="Cashback Rules" subtitle="Loss/volume/promo-based cashback configuration.">
        <x-slot:actions>
            <a href="{{ route('admin.cashbacks.create') }}" class="brand-btn-primary">Add Cashback Rule</a>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>Type</th><th>Percentage</th><th>Volume Threshold</th><th>Promo Code</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($rules as $rule)
                    <tr>
                        <td class="capitalize text-white">{{ $rule->type }}</td>
                        <td>{{ $rule->percentage }}%</td>
                        <td>{{ $rule->volume_threshold ?? '—' }}</td>
                        <td>{{ $rule->promo_code ?? '—' }}</td>
                        <td><x-badge :status="$rule->active ? 'active' : 'cancelled'">{{ $rule->active ? 'Active' : 'Inactive' }}</x-badge></td>
                        <td class="text-right space-x-3">
                            <a href="{{ route('admin.cashbacks.edit', $rule) }}" class="text-brand-blue hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.cashbacks.destroy', $rule) }}" class="inline" onsubmit="return confirm('Delete this cashback rule?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-brand-danger hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-slate-400">No cashback rules yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $rules->links() }}</div>
    </x-glass-card>
@endsection
