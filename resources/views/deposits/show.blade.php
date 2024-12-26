@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Deposit Details</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>Transaction Information</h5>
                            <p><strong>Amount:</strong> ${{ number_format($deposit->deposit_amount, 2) }}</p>
                            <p><strong>Bonus:</strong> ${{ number_format($deposit->deposit_bonus, 2) }}</p>
                            <p><strong>Total:</strong> ${{ number_format($deposit->deposit_amount + $deposit->deposit_bonus, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>Status Information</h5>
                            <p><strong>Status:</strong> 
                                <span class="badge bg-{{ $deposit->deposit_status === 'completed' ? 'success' : ($deposit->deposit_status === 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($deposit->deposit_status) }}
                                </span>
                            </p>
                            <p><strong>Date:</strong> {{ $deposit->deposit_date_time }}</p>
                            <p><strong>Method:</strong> {{ ucfirst($deposit->deposit_method) }}</p>
                        </div>
                    </div>

                    @if($deposit->deposit_status === 'pending')
                    <form action="{{ url('deposits/'.$deposit->id.'/cancel') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to cancel this deposit?')">
                            Cancel Deposit
                        </button>
                    </form>
                    @endif

                    <a href="{{ route('deposits.index') }}" class="btn btn-secondary">Back to Deposits</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
