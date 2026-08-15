@extends('layouts.admin.app')

@section('title', 'Add KYC Field')

@section('content')
    <x-page-header title="Add KYC Field" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.kyc-custom-fields.store') }}">
            @csrf
            @include('admin.kyc-custom-fields._form')
        </form>
    </x-glass-card>
@endsection
