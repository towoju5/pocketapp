@extends('layouts.admin.app')

@section('title', 'Payout Requests')

@section('content')
<div class="container">
    <h2 class="mb-4">Payout Requests</h2>

    <!-- Filter by status -->
    <form method="GET" class="mb-4">
        <div class="form-group row">
            <label for="status" class="col-sm-2 col-form-label">Filter by Status:</label>
            <div class="col-sm-4">
                <select name="status" id="status" class="form-control" onchange="this.form.submit()">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                </select>
            </div>
        </div>
    </form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($payouts->count())
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Amount</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Requested At</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payouts as $payout)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $payout->user->last_name.' '.$payout->user->first_name ?? 'N/A' }}</td>
                        <td>{{ formatPrice($payout->payout_amount, 2) }}</td>
                        <td>{{ ucfirst($payout->method->wallet_name ?? 'N/A') }}</td>
                        <td>
                            <span class="badge badge-{{ $payout->payout_status === 'pending' ? 'warning' : ($payout->payout_status === 'approved' ? 'primary' : ($payout->payout_status === 'paid' ? 'success' : 'danger')) }}">
                                {{ ucfirst($payout->payout_status) }}
                            </span>
                        </td>
                        <td>{{ $payout->created_at->format('d M Y') }}</td>
                        <td>
                            <form action="{{ route('admin.payouts.update', $payout->id) }}" method="POST" class="form-inline flex gap-1">
                                @csrf
                                @method("PUT")
                                <select name="status" class="form-control form-control-sm mr-2">
                                    <option value="pending" {{ $payout->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ $payout->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ $payout->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    <option value="paid" {{ $payout->status == 'paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-success">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $payouts->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    @else
        <p>No payout requests found.</p>
    @endif
</div>
@endsection
