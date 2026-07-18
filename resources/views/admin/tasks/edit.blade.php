@extends('layouts.admin.app')

@section('title', 'Edit Task')

@section('content')
    <x-page-header :title="'Edit: ' . $task->title" />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.tasks.update', $task) }}">
            @csrf
            @method('PUT')
            @include('admin.tasks._form')
        </form>
    </x-glass-card>
@endsection
