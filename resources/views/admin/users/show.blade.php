@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <div class="card mb-4">
        <div class="card-body">
            <h2>User Details</h2>
            <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
            <p><strong>Username:</strong> {{ $user->username }}</p>
            <p><strong>Email:</strong> {{ $user->email }}</p>
            <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
            <p><strong>KYC Level:</strong> {{ $user->kyc_level }}</p>
            <p><strong>Trade Level:</strong> {{ $user->trade_level }}</p>

            <!-- Button to trigger Wallet Modal -->
            <button type="button" class="btn btn-primary mt-3" data-bs-toggle="modal" data-bs-target="#walletActionModal">
                Manage Wallets
            </button>
        </div>
    </div>

    <!-- Wallet Action Modal -->
    <div class="modal fade" id="walletActionModal" tabindex="-1" aria-labelledby="walletActionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.wallets.update', $user->id) }}" method="POST" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="walletActionModalLabel">
                        Manage Wallet - {{ $user->first_name }} {{ $user->last_name }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Select Wallet -->
                    <div class="mb-3">
                        <label for="wallet" class="form-label">Select Wallet</label>
                        <select name="wallet" id="wallet" class="form-select" required>
                            <option value="" disabled selected>Choose wallet</option>
                            @foreach($user->wallets as $wallet)
                                <option value="{{ $wallet->slug }}">
                                    {{ ucfirst($wallet->name) }} (Balance: ${{ number_format($wallet->balance, 2) }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Select Action -->
                    <div class="mb-3">
                        <label for="action" class="form-label">Action</label>
                        <select name="action" id="action" class="form-select" required>
                            <option value="" disabled selected>Select action</option>
                            <option value="credit">Credit</option>
                            <option value="debit">Debit</option>
                        </select>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount (₦)</label>
                        <input type="number" name="amount" id="amount" step="0.01" min="0.01" class="form-control" placeholder="Enter amount" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
