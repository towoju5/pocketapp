@extends('layouts.admin.app')

@section('title', 'Edit User')

@section('content')
<style>
    .edit-container { width: 100%; max-width: 1300px; margin: 0 auto; }
    .glass-card { background: rgba(15, 23, 42, 0.9); backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 24px; padding: clamp(20px, 4vw, 32px); }
    .input-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 15px; }
</style>

<div class="edit-container">
    <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:20px; margin-bottom:30px;">
        <div>
            <h1 style="margin:0; font-weight:800; color:#fff; font-size: clamp(22px, 3vw, 30px);">Manage: {{ $user->first_name }} {{ $user->last_name }}</h1>
            <span style="color:#64748b; font-size:14px;">User ID: #{{ $user->id }}</span>
        </div>
        <a href="{{ route('admin.users.index') }}" style="padding:12px 20px; border-radius:10px; background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); color:#94a3b8; font-size:14px; text-decoration:none;">&larr; Back to Registry</a>
    </div>

    <div class="glass-card">
        <h3 style="margin-top:0; margin-bottom:20px; color:#fff;">Primary Data</h3>
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="input-grid">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">First Name</label>
                    <input type="text" name="first_name" class="brand-input-dark" value="{{ old('first_name', $user->first_name) }}" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Last Name</label>
                    <input type="text" name="last_name" class="brand-input-dark" value="{{ old('last_name', $user->last_name) }}" required>
                </div>
            </div>

            <div class="input-grid">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Username</label>
                    <input type="text" name="username" class="brand-input-dark" value="{{ old('username', $user->username) }}" required>
                </div>
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Email Address</label>
                    <input type="email" name="email" class="brand-input-dark" value="{{ old('email', $user->email) }}" required>
                </div>
            </div>

            <div class="input-grid">
                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Phone</label>
                    <input type="text" name="phone" class="brand-input-dark" value="{{ old('phone', $user->phone) }}">
                </div>
            </div>

            <button type="submit" class="brand-btn-primary" style="margin-top:10px;">Save Identity Settings</button>
        </form>
    </div>
</div>
@endsection
