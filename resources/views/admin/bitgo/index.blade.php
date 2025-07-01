@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <h2>Bitgo Wallets</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('admin.bitgo.create') }}" class="btn btn-primary mb-3">Add New Wallet</a>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Wallet ID</th>
                <th>Name</th>
                <th>Ticker</th>
                <th>Type</th>
                <th>Memo?</th>
                <th>Deposit?</th>
                <th>Payout?</th>
                <th>Logo</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($wallets as $wallet)
                <tr>
                    <td>{{ $wallet->id }}</td>
                    <td>{{ $wallet->wallet_id }}</td>
                    <td>{{ $wallet->wallet_name }}</td>
                    <td>{{ $wallet->wallet_ticker }}</td>
                    <td>{{ $wallet->type }}</td>
                    <td>{{ $wallet->require_memo ? 'Yes' : 'No' }}</td>
                    <td>{{ $wallet->can_deposit ? 'Yes' : 'No' }}</td>
                    <td>{{ $wallet->can_payout ? 'Yes' : 'No' }}</td>
                    <td>
                        @if($wallet->coin_logo)
                            <img src="{{ $wallet->coin_logo }}" alt="Logo" width="32">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.bitgo.edit', $wallet) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.bitgo.destroy', $wallet) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this wallet?');">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="10">No wallets found.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $wallets->links('pagination::bootstrap-4') }}
</div>
@endsection
