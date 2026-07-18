@extends('layouts.admin.app')

@section('title', 'P2P Offers')

@section('content')
    <x-page-header title="P2P Offers" subtitle="Monitor all offers published by users." />

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>Maker</th><th>Type</th><th>Currency</th><th>Price</th><th>Available</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($offers as $offer)
                    <tr>
                        <td>{{ $offer->maker->first_name }} {{ $offer->maker->last_name }}</td>
                        <td class="capitalize">{{ $offer->type }}</td>
                        <td>{{ $offer->currency }}</td>
                        <td>{{ formatPrice($offer->price_per_unit) }}</td>
                        <td>{{ formatPrice($offer->available_amount) }}</td>
                        <td><x-badge :status="$offer->status" /></td>
                        <td class="text-right"><a href="{{ route('admin.p2p-offers.show', $offer) }}" class="text-brand-blue hover:underline">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center py-10 text-slate-400">No offers yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $offers->links() }}</div>
    </x-glass-card>
@endsection
