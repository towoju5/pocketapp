@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4">Create Signal</h2>
            <form method="POST" action="{{ route('admin.signals.store') }}">
                @csrf

                <div class="mb-3">
                    <label for="asset" class="form-label">Asset</label>
                    <select name="asset" id="asset" class="form-control" required>
                        @foreach($assets as $asset)
                        <option value="{{ $asset->symbol }}">{{ $asset->name }}</option>
                        @endforeach
                    </select>
                    <!-- <input type="text" class="form-control" id="asset" name="asset" required> -->
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                </div>

                <div class="mb-3">
                    <label for="direction" class="form-label">Direction</label>
                    <select class="form-select" id="direction" name="direction" required>
                        <option value="up">Up</option>
                        <option value="down">Down</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Duration (in seconds)</label>
                    <input type="number" class="form-control" id="duration" name="duration" required>
                </div>

                <div class="mb-3">
                    <label for="expected_profit" class="form-label">Expected Profit (optional)</label>
                    <input type="number" step="0.01" class="form-control" id="expected_profit" name="expected_profit">
                </div>

                <div class="mb-3">
                    <label for="start_price" class="form-label">Start Price (optional)</label>
                    <input type="number" step="0.000001" class="form-control" id="start_price" name="start_price">
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notes</label>
                    <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Create Signal</button>
            </form>
        </div>
    </div>
</div>
@endsection