@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Make a Deposit</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('deposits.store') }}">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="wallet_id" class="form-label">Select Wallet</label>
                            <select class="form-select @error('wallet_id') is-invalid @enderror" name="wallet_id" required>
                                <option value="">Choose wallet...</option>
                                @foreach($wallets as $wallet)
                                    <option value="{{ $wallet->id }}">{{ $wallet->name }}</option>
                                @endforeach
                            </select>
                            @error('wallet_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deposit_amount" class="form-label">Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">$</span>
                                <input type="number" class="form-control @error('deposit_amount') is-invalid @enderror" 
                                    name="deposit_amount" step="0.01" min="1" required>
                            </div>
                            @error('deposit_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="deposit_method" class="form-label">Payment Method</label>
                            <select class="form-select @error('deposit_method') is-invalid @enderror" name="deposit_method" required>
                                <option value="bitcoin">Bitcoin</option>
                                <option value="ethereum">Ethereum</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="credit_card">Credit Card</option>
                            </select>
                            @error('deposit_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Proceed with Deposit</button>
                            <a href="{{ route('deposits.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
