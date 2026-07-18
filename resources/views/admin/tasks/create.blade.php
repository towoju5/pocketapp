@extends('layouts.admin.app')

@section('title', 'New Task')

@section('content')
    <x-page-header title="New Task" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.tasks.store') }}">
            @csrf
            @include('admin.tasks._form')
        </form>
    </x-glass-card>
@endsection
