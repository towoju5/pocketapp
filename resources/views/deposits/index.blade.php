
@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Deposit History</h4>
                    <a href="{{ route('deposits.create') }}" class="btn btn-primary">New Deposit</a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                    <th>Bonus</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($deposits as $deposit)
                                <tr>
                                    <td>{{ $deposit->deposit_date_time }}</td>
                                    <td>${{ number_format($deposit->deposit_amount, 2) }}</td>
                                    <td>{{ $deposit->deposit_method }}</td>
                                    <td>${{ number_format($deposit->deposit_bonus, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $deposit->deposit_status === 'completed' ? 'success' : ($deposit->deposit_status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($deposit->deposit_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('deposits.show', $deposit->id) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $deposits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
