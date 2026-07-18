@extends('layouts.admin.app')

@section('title', 'Edit Referral Rate')

@section('content')
    <x-page-header title="Edit Referral Rate" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.referral-rates.update', $rate) }}">
            @csrf
            @method('PUT')
            @include('admin.referral-rates._form')
        </form>
    </x-glass-card>
@endsection
