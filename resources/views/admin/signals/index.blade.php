@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2>Signals</h2>
                <a href="{{ route('admin.signals.create') }}" class="btn btn-primary">+ New Signal</a>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Asset</th>
                            <th>Direction</th>
                            <th>Amount</th>
                            <th>Duration</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($signals as $signal)
                        <tr>
                            <td>{{ $signal->asset }}</td>
                            <td>{{ ucfirst($signal->direction) }}</td>
                            <td>{{ $signal->amount }}</td>
                            <td>{{ $signal->duration }} sec</td>
                            <td>{{ $signal->created_at->diffForHumans() }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">No signals found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection