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

            <div class="mt-8 pt-6 border-t border-white/10">
                <div class="text-sm font-semibold text-white mb-1">Price Feed</div>
                <p class="text-xs text-slate-400 mb-4">Controls which live price feed new assets default to, and which feed AI signal generation draws candidates from.</p>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Default Chart Provider</label>
                        <select name="default_chart_provider" class="brand-input-dark" required>
                            <option value="iqcent" {{ $defaultChartProvider === 'iqcent' ? 'selected' : '' }}>iqcent (headless-Chrome collector)</option>
                            <option value="brokeret" {{ $defaultChartProvider === 'brokeret' ? 'selected' : '' }}>Brokeret (base_url/ui feed)</option>
                        </select>
                        <p class="mt-1 text-xs text-slate-500">Pre-selected when creating a new asset.</p>
                    </div>
                    <div>
                        <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">Active Chart Provider</label>
                        <select name="active_chart_provider" class="brand-input-dark" required>
                            <option value="all" {{ $activeChartProvider === 'all' ? 'selected' : '' }}>All Providers</option>
                            <option value="iqcent" {{ $activeChartProvider === 'iqcent' ? 'selected' : '' }}>iqcent</option>
                            <option value="brokeret" {{ $activeChartProvider === 'brokeret' ? 'selected' : '' }}>Brokeret</option>
                        </select>
                        <p class="mt-1 text-xs text-slate-500">Restricts "Generate with AI" signal candidates to this provider's assets.</p>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-white/10">
                <div class="text-sm font-semibold text-white mb-1">AI Signal Generation</div>
                <p class="text-xs text-slate-400 mb-4">DeepSeek API key used by "Generate with AI" on the Signals page. Leave blank to keep the current key.</p>

                <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-slate-400">DeepSeek API Key</label>
                <input type="password" name="deepseek_api_key" class="brand-input-dark" placeholder="{{ $deepseekKeySet ? '•••••••••••••••• (configured)' : 'Not configured' }}" autocomplete="off">

                @if ($deepseekKeySet)
                    <label class="mt-2 flex items-center gap-2 text-xs text-slate-400">
                        <input type="checkbox" name="clear_deepseek_api_key" value="1" class="rounded border-slate-600 bg-transparent text-brand-danger">
                        Clear stored key
                    </label>
                @endif
            </div>

            <div class="mt-6">
                <button type="submit" class="brand-btn-primary">Save Settings</button>
            </div>
        </form>
    </x-glass-card>
@endsection
