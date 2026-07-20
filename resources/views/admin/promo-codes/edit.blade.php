@extends('layouts.admin.app')

@section('title', 'Edit Promo Code')

@section('content')
    <x-page-header title="Edit Promo Code" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.promo-codes.update', $promoCode) }}">
            @csrf @method('PUT')
            @include('admin.promo-codes._form')
        </form>
    </x-glass-card>
@endsection
