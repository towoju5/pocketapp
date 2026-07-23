@extends('layouts.admin.app')

@section('title', 'P2P Payment Methods')

@section('content')
    <x-page-header title="P2P Payment Methods" subtitle="Predefined payment methods customers can attach to a P2P offer.">
        <x-slot:actions>
            <form method="POST" action="{{ route('admin.payment-methods.store') }}" class="flex items-center gap-2">
                @csrf
                <input type="text" name="name" placeholder="e.g. Bank Transfer" class="brand-input-dark !w-auto" required>
                <button type="submit" class="brand-btn-primary">Add Method</button>
            </form>
        </x-slot:actions>
    </x-page-header>

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-brand-danger/20 bg-brand-danger/10 px-4 py-3 text-sm text-brand-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>Name</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($paymentMethods as $method)
                    <tr>
                        <td class="font-medium text-white">{{ $method->name }}</td>
                        <td><x-badge :status="$method->is_active ? 'active' : 'cancelled'">{{ $method->is_active ? 'Active' : 'Disabled' }}</x-badge></td>
                        <td class="text-right space-x-3">
                            <form method="POST" action="{{ route('admin.payment-methods.toggle', $method) }}" class="inline">
                                @csrf
                                <button type="submit" class="text-brand-blue hover:underline">{{ $method->is_active ? 'Disable' : 'Enable' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.payment-methods.destroy', $method) }}" class="inline" onsubmit="return confirm('Remove this payment method? Offers already using it keep their existing selection.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-brand-danger hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center py-10 text-slate-400">No payment methods yet — add one above.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>
    </x-glass-card>
@endsection
