@extends('layouts.admin.app')

@section('title', 'New Plan')

@section('content')
    <x-page-header title="New Plan" subtitle="Configure a new investment tier." />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.plans.store') }}">
            @csrf
            @include('admin.plans._form')
        </form>
    </x-glass-card>
@endsection
