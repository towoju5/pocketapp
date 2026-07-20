@extends('layouts.admin.app')

@section('title', 'Add Promo Code')

@section('content')
    <x-page-header title="Add Promo Code" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.promo-codes.store') }}">
            @csrf
            @include('admin.promo-codes._form')
        </form>
    </x-glass-card>
@endsection
