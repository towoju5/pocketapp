@extends('layouts.admin.app')

@section('title', 'Edit Cashback Rule')

@section('content')
    <x-page-header title="Edit Cashback Rule" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.cashbacks.update', $cashbackRule) }}">
            @csrf @method('PUT')
            @include('admin.cashbacks._form')
        </form>
    </x-glass-card>
@endsection
