@extends('layouts.admin.app')

@section('title', 'Edit User')

@section('content')
<x-page-header title="Manage: {{ $user->first_name }} {{ $user->last_name }}" subtitle="User ID: #{{ $user->id }}">
    <x-slot:actions>
        <a href="{{ route('admin.users.index') }}" class="brand-btn-outline">&larr; Back to Users</a>
    </x-slot:actions>
</x-page-header>

<x-glass-card title="Primary Data">
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">First Name</label>
                <input type="text" name="first_name" class="brand-input-dark" value="{{ old('first_name', $user->first_name) }}" required>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Last Name</label>
                <input type="text" name="last_name" class="brand-input-dark" value="{{ old('last_name', $user->last_name) }}" required>
            </div>
        </div>

        <div class="mb-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Username</label>
                <input type="text" name="username" class="brand-input-dark" value="{{ old('username', $user->username) }}" required>
            </div>
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Email Address</label>
                <input type="email" name="email" class="brand-input-dark" value="{{ old('email', $user->email) }}" required>
            </div>
        </div>

        <div class="mb-5 grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Phone</label>
                <input type="text" name="phone" class="brand-input-dark" value="{{ old('phone', $user->phone) }}">
            </div>
        </div>

        <button type="submit" class="brand-btn-primary">Save Changes</button>
    </form>
</x-glass-card>
@endsection
