@extends('layouts.admin.app')

@section('title', 'P2P Trades')

@section('content')
    <x-page-header title="P2P Trades" subtitle="All escrow trades across the platform." />

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>ID</th><th>Buyer</th><th>Seller</th><th>Amount</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($trades as $trade)
                    <tr>
                        <td>#{{ $trade->id }}</td>
                        <td>{{ $trade->buyer->first_name }} {{ $trade->buyer->last_name }}</td>
                        <td>{{ $trade->seller->first_name }} {{ $trade->seller->last_name }}</td>
                        <td>{{ formatPrice($trade->amount) }}</td>
                        <td><x-badge :status="$trade->status" /></td>
                        <td class="text-right"><a href="{{ route('admin.p2p-trades.show', $trade) }}" class="text-brand-blue hover:underline">Review</a></td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-slate-400">No trades yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $trades->links() }}</div>
    </x-glass-card>
@endsection
