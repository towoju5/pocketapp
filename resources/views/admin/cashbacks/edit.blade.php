@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h2>Edit Cashback Rule</h2>
            <form method="POST" action="{{ route('admin.cashbacks.update', $cashbackRule) }}">
                @csrf @method('PUT')
                @include('admin.cashbacks._form')
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>
@endsection