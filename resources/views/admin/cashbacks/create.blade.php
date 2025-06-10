@extends('layouts.admin.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h2>Add Cashback Rule</h2>
            <form method="POST" action="{{ route('admin.cashbacks.store') }}">
                @csrf
                @include('admin.cashbacks._form')
                <button type="submit" class="btn btn-primary">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection