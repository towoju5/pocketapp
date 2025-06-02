@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h2>Cashback Rules</h2>
            <a href="{{ route('admin.cashbacks.create') }}" class="btn btn-primary mb-3">Add Cashback Rule</a>

            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Percentage</th>
                        <th>Volume Threshold</th>
                        <th>Promo Code</th>
                        <th>Active</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($rules as $rule)
                    <tr>
                        <td>{{ ucfirst($rule->type) }}</td>
                        <td>{{ $rule->percentage }}%</td>
                        <td>{{ $rule->volume_threshold ?? '—' }}</td>
                        <td>{{ $rule->promo_code ?? '—' }}</td>
                        <td>{{ $rule->active ? 'Yes' : 'No' }}</td>
                        <td>
                            <a href="{{ route('admin.cashbacks.edit', $rule) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form method="POST" action="{{ route('admin.cashbacks.destroy', $rule) }}" style="display:inline-block;">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger" onclick="return confirm('Delete this cashback rule?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{ $rules->links() }}
        </div>
    </div>
</div>
@endsection