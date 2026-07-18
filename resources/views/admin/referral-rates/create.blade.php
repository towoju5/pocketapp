@extends('layouts.admin.app')

@section('title', 'New Referral Rate')

@section('content')
    <x-page-header title="New Referral Rate" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.referral-rates.store') }}">
            @csrf
            @include('admin.referral-rates._form')
        </form>
    </x-glass-card>
@endsection
