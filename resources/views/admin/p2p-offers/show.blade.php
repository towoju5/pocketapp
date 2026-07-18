@extends('layouts.admin.app')

@section('title', 'P2P Offer #' . $offer->id)

@section('content')
    <x-page-header :title="'Offer #' . $offer->id" subtitle="{{ $offer->maker->first_name }} {{ $offer->maker->last_name }}">
        <x-slot:actions><x-badge :status="$offer->status" /></x-slot:actions>
    </x-page-header>

    <x-glass-card title="Trades on this offer">
        <x-data-table>
            <thead>
                <tr><th>ID</th><th>Buyer</th><th>Seller</th><th>Amount</th><th>Status</th></tr>
            </thead>
            <tbody>
                @forelse ($offer->trades()->with(['buyer', 'seller'])->latest()->limit(20)->get() as $trade)
                    <tr>
                        <td>#{{ $trade->id }}</td>
                        <td>{{ $trade->buyer->first_name }}</td>
                        <td>{{ $trade->seller->first_name }}</td>
                        <td>{{ formatPrice($trade->amount) }}</td>
                        <td><x-badge :status="$trade->status" /></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-slate-400">No trades yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>
    </x-glass-card>
@endsection
