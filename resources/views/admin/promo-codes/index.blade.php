@extends('layouts.admin.app')

@section('title', 'Promo Codes')

@section('content')
    <x-page-header title="Promo Codes" subtitle="Deposit-bonus codes customers can redeem.">
        <x-slot:actions>
            <a href="{{ route('admin.promo-codes.create') }}" class="brand-btn-primary">Add Promo Code</a>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>Code</th><th>Title</th><th>Discount</th><th>Valid From</th><th>Valid Until</th><th>Redemptions</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($promoCodes as $promo)
                    <tr>
                        <td class="font-mono text-white">{{ $promo->promo_code }}</td>
                        <td class="text-white">{{ $promo->promo_title }}</td>
                        <td>{{ $promo->promo_discount_type === 'percentage' ? $promo->promo_discount . '%' : '$' . number_format($promo->promo_discount, 2) }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($promo->promo_start_date_time)->format('Y-m-d') }}</td>
                        <td>{{ \Illuminate\Support\Carbon::parse($promo->promo_ends_date_time)->format('Y-m-d') }}</td>
                        <td>{{ $promo->redemptions_count }}</td>
                        <td class="text-right space-x-3">
                            <a href="{{ route('admin.promo-codes.edit', $promo) }}" class="text-brand-blue hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.promo-codes.destroy', $promo) }}" class="inline" onsubmit="return confirm('Delete this promo code?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-brand-danger hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-10 text-slate-400">No promo codes yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $promoCodes->links() }}</div>
    </x-glass-card>
@endsection
