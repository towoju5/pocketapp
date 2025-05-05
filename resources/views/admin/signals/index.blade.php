@extends('layouts.admin')

@section('content')
<h2>Signals</h2>
<a href="{{ route('admin.signals.create') }}">+ New Signal</a>

<table>
    <thead>
        <tr>
            <th>Asset</th>
            <th>Direction</th>
            <th>Amount</th>
            <th>Duration</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
        @foreach($signals as $signal)
        <tr>
            <td>{{ $signal->asset }}</td>
            <td>{{ ucfirst($signal->direction) }}</td>
            <td>{{ $signal->amount }}</td>
            <td>{{ $signal->duration }} sec</td>
            <td>{{ $signal->created_at->diffForHumans() }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
