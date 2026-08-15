@extends('layouts.admin.app')

@section('title', 'Edit KYC Field')

@section('content')
    <x-page-header title="Edit KYC Field" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.kyc-custom-fields.update', $field) }}">
            @csrf
            @method('PUT')
            @include('admin.kyc-custom-fields._form')
        </form>
    </x-glass-card>
@endsection
