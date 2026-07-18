@extends('layouts.admin.app')

@section('title', 'Add Cashback Rule')

@section('content')
    <x-page-header title="Add Cashback Rule" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.cashbacks.store') }}">
            @csrf
            @include('admin.cashbacks._form')
        </form>
    </x-glass-card>
@endsection
