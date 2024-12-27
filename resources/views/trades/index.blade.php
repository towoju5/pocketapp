@extends('layouts.app')

@section('title', 'Trades')
@section('content')
    <div class="row gx-3">
        <div class="col-xxl-12">
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="card-title">Trading History</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Asset</th>
                                    <th>Amount</th>
                                    <th>Type</th>
                                    <th>Entry Price</th>
                                    <th>Exit Price</th>
                                    <th>Status</th>
                                    <th>Profit/Loss</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($trades as $trade)
                                <tr>
                                    <td>{{ $trade->id }}</td>
                                    <td>{{ $trade->asset }}</td>
                                    <td>${{ number_format($trade->amount, 2) }}</td>
                                    <td>{{ ucfirst($trade->type) }}</td>
                                    <td>${{ number_format($trade->entry_price, 2) }}</td>
                                    <td>${{ number_format($trade->exit_price, 2) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $trade->status === 'completed' ? 'success' : 'warning' }}">
                                            {{ ucfirst($trade->status) }}
                                        </span>
                                    </td>
                                    <td>${{ number_format($trade->profit_loss, 2) }}</td>
                                    <td>{{ $trade->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ route('trades.show', $trade->id) }}" class="btn btn-sm btn-info">View</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    {{ $trades->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
