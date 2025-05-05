@extends('layouts.admin')

@section('content')
<h2>Create Signal</h2>
<form method="POST" action="{{ route('admin.signals.store') }}">
    @csrf

    <label>Asset:</label>
    <input type="text" name="asset" required><br>

    <label>Amount:</label>
    <input type="number" step="0.01" name="amount" required><br>

    <label>Direction:</label>
    <select name="direction" required>
        <option value="up">Up</option>
        <option value="down">Down</option>
    </select><br>

    <label>Duration (in seconds):</label>
    <input type="number" name="duration" required><br>

    <label>Expected Profit (optional):</label>
    <input type="number" step="0.01" name="expected_profit"><br>

    <label>Start Price (optional):</label>
    <input type="number" step="0.000001" name="start_price"><br>

    <label>Notes:</label>
    <textarea name="notes"></textarea><br>

    <button type="submit">Create Signal</button>
</form>
@endsection