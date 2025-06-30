@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <h2>Edit Bitgo Wallet</h2>

    <form method="POST" action="{{ route('admin.bitgo.update', $bitgo) }}">
        @csrf @method('PUT')

        @include('admin.bitgo.form', ['wallet' => $bitgo])

        <button type="submit" class="btn btn-success mt-3">Update Wallet</button>
    </form>
</div>
@endsection
