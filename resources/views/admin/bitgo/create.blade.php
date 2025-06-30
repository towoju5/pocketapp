@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <h2>Create Bitgo Wallet</h2>

    <form method="POST" action="{{ route('admin.bitgo.store') }}">
        @csrf

        @include('admin.bitgo.form')

        <button type="submit" class="btn btn-primary mt-3">Create Wallet</button>
    </form>
</div>
@endsection
