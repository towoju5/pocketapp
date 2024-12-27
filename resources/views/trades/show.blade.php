@extends('layouts.app')

@section('title', 'Trade Details')
@section('content')
    <div class="row gx-3">
        <div class="col-xxl-12">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title">Trade Details #{{ $trade->id }}</h5>
                    <a href="{{ route('trades.index') }}" class="btn btn-secondary">Back to Trades</a>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Asset</dt>
                                <dd class="col-sm-8">{{ $trade->asset }}</dd>

                                <dt class="col-sm-4">Amount</dt>
                                <dd class="col-sm-8">${{ number_format($trade->amount, 2) }}</dd>

                                <dt class="col-sm-4">Type</dt>
                                <dd class="col-sm-8">{{ ucfirst($trade->type) }}</dd>

                                <dt class="col-sm-4">Entry Price</dt>
                                <dd class="col-sm-8">${{ number_format($trade->entry_price, 2) }}</dd>
                            </dl>
                        </div>
                        <div class="col-md-6">
                            <dl class="row">
                                <dt class="col-sm-4">Exit Price</dt>
                                <dd class="col-sm-8">${{ number_format($trade->exit_price, 2) }}</dd>

                                <dt class="col-sm-4">Status</dt>
                                <dd class="col-sm-8">
                                    <span class="badge bg-{{ $trade->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ ucfirst($trade->status) }}
                                    </span>
                                </dd>

                                <dt class="col-sm-4">Profit/Loss</dt>
                                <dd class="col-sm-8">${{ number_format($trade->profit_loss, 2) }}</dd>

                                <dt class="col-sm-4">Date</dt>
                                <dd class="col-sm-8">{{ $trade->created_at->format('Y-m-d H:i') }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
