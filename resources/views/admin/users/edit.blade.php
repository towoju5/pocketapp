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

        <div class="mb-5 flex items-center justify-between rounded-lg bg-white/5 px-4 py-3">
            <div>
                <div class="text-sm font-semibold text-white">Admin Access</div>
                <p class="text-xs text-slate-400 mt-1">Grants full access to the admin console.</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="is_admin" value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }} {{ ($user->id === auth()->id() && $user->is_admin) ? 'disabled' : '' }} class="sr-only peer">
                <div class="w-11 h-6 bg-slate-700 rounded-full peer peer-checked:bg-brand-blue transition-colors"></div>
                <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
            </label>
        </div>
        @if ($user->id === auth()->id())
            <p class="mb-4 -mt-3 text-xs text-slate-500">You can't change your own admin access.</p>
        @endif

        <button type="submit" class="brand-btn-primary">Save Changes</button>
    </form>
</x-glass-card>
@endsection
