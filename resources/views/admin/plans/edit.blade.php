@extends('layouts.admin.app')

@section('title', 'Edit Plan')

@section('content')
    <x-page-header :title="'Edit: ' . $plan->name" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.plans.update', $plan) }}">
            @csrf
            @method('PUT')
            @include('admin.plans._form')
        </form>
    </x-glass-card>
@endsection
