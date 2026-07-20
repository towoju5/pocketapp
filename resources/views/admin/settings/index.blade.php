@extends('layouts.admin.app')

@section('title', 'Platform Settings')

@section('content')
    <x-page-header title="Platform Settings" subtitle="Toggle platform-wide features on or off." />

    <x-glass-card>
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf

            <div class="flex items-center justify-between py-3 border-b border-white/10">
                <div>
                    <div class="text-sm font-semibold text-white">My Safe</div>
                    <p class="text-xs text-slate-400 mt-1">Lets customers move funds into a locked vault, separate from their trading balance.</p>
                </div>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="safebox_enabled" value="1" {{ $safeboxEnabled ? 'checked' : '' }} class="sr-only peer">
                    <div class="w-11 h-6 bg-slate-700 rounded-full peer peer-checked:bg-brand-blue transition-colors"></div>
                    <div class="absolute left-1 top-1 w-4 h-4 bg-white rounded-full transition-transform peer-checked:translate-x-5"></div>
                </label>
            </div>

            <div class="mt-6">
                <button type="submit" class="brand-btn-primary">Save Settings</button>
            </div>
        </form>
    </x-glass-card>
@endsection
