@extends('layouts.admin.app')

@section('content')
<div class="container py-4">
    <div class="card">
        <div class="card-body">
            <h2>User Details</h2>
            <div class="card">
                <div class="card-body">
                    <p><strong>Name:</strong> {{ $user->first_name }} {{ $user->last_name }}</p>
                    <p><strong>Username:</strong> {{ $user->username }}</p>
                    <p><strong>Email:</strong> {{ $user->email }}</p>
                    <p><strong>Phone:</strong> {{ $user->phone ?? 'N/A' }}</p>
                    <p><strong>KYC Level:</strong> {{ $user->kyc_level }}</p>
                    <p><strong>Trade Level:</strong> {{ $user->trade_level }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection