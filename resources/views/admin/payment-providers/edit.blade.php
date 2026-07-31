@extends('layouts.admin.app')

@section('title', 'Configure '.$paymentProvider->display_name)

@section('content')
    <x-page-header :title="$paymentProvider->display_name" subtitle="{{ ucfirst($paymentProvider->type) }} gateway configuration" />

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-brand-danger/20 bg-brand-danger/10 px-4 py-3 text-sm text-brand-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.payment-providers.update', $paymentProvider) }}" class="space-y-6">
        @csrf
        @method('PUT')

        @php $currentMode = old('mode', $paymentProvider->config['mode'] ?? 'test'); @endphp
        <div x-data="{ mode: '{{ $currentMode }}' }">
            <x-glass-card title="Mode">
                <div class="inline-flex rounded-lg border border-glass-border bg-white/5 p-1">
                    <label class="cursor-pointer rounded-md px-4 py-2 text-sm font-semibold transition"
                           :class="mode === 'test' ? 'bg-brand-amber/15 text-brand-amber' : 'text-slate-400 hover:text-white'">
                        <input type="radio" name="mode" value="test" x-model="mode" class="sr-only">
                        Test
                    </label>
                    <label class="cursor-pointer rounded-md px-4 py-2 text-sm font-semibold transition"
                           :class="mode === 'live' ? 'bg-brand-emerald/15 text-brand-emerald' : 'text-slate-400 hover:text-white'">
                        <input type="radio" name="mode" value="live" x-model="mode" class="sr-only">
                        Live
                    </label>
                </div>

                <p class="mt-3 text-xs text-slate-400">
                    @if ($modeChangesEndpoint)
                        Switches which API environment this gateway talks to (sandbox vs. production) — make sure the credentials below match the mode you pick.
                    @else
                        {{ $paymentProvider->display_name }} uses the same API either way — this just records which set of keys you're using. Paste your <strong>test</strong> keys while in Test mode and your <strong>live</strong> keys before switching to Live.
                    @endif
                </p>

                <div x-show="mode === 'live'" x-cloak class="mt-3 rounded-xl border border-brand-danger/20 bg-brand-danger/10 px-4 py-3 text-sm text-brand-danger">
                    Live mode — real money moves once this is Active. Double-check the credentials below are your live keys before saving.
                </div>
            </x-glass-card>
        </div>

        <x-glass-card title="Webhook endpoint" subtitle="Add this URL in your {{ $paymentProvider->display_name }} dashboard's webhook/notification settings — that's how deposits actually get confirmed and credited.">
            <div x-data="{ copied: false }" class="flex items-center gap-2">
                <input type="text" readonly value="{{ $webhookUrl }}" x-ref="webhookUrl" onclick="this.select()"
                       class="flex-1 rounded-lg border border-glass-border bg-glass-surface-light px-4 py-2.5 font-mono text-xs text-white focus:outline-none">
                <button type="button"
                        @click="navigator.clipboard.writeText($refs.webhookUrl.value); copied = true; setTimeout(() => copied = false, 2000)"
                        class="brand-btn-outline whitespace-nowrap !py-2.5">
                    <span x-show="!copied">Copy</span>
                    <span x-show="copied" x-cloak>Copied!</span>
                </button>
            </div>
        </x-glass-card>

        <x-glass-card title="Status">
            <div class="flex flex-wrap gap-8">
                <label class="flex items-center gap-2 text-sm text-white">
                    <input type="checkbox" name="is_active" value="1" @checked($paymentProvider->is_active) class="rounded border-white/20 bg-white/5">
                    Active (accepts real traffic)
                </label>
                <label class="flex items-center gap-2 text-sm text-white">
                    <input type="checkbox" name="can_deposit" value="1" @checked($paymentProvider->can_deposit) class="rounded border-white/20 bg-white/5">
                    Allow deposits
                </label>
                @if ($paymentProvider->slug !== '2checkout')
                    <label class="flex items-center gap-2 text-sm text-white">
                        <input type="checkbox" name="can_payout" value="1" @checked($paymentProvider->can_payout) class="rounded border-white/20 bg-white/5">
                        Allow payouts
                    </label>
                @endif
            </div>

            @if ($payoutWarning)
                <div class="mt-4 rounded-xl border border-brand-amber/20 bg-brand-amber/10 px-4 py-3 text-sm text-brand-amber">
                    <strong>Before enabling payouts:</strong> {{ $payoutWarning }}
                </div>
            @endif

            @if ($paymentProvider->slug === '2checkout')
                <div class="mt-4 rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-400">
                    2Checkout has no API to pay an arbitrary customer — deposits only.
                </div>
            @endif
        </x-glass-card>

        <x-glass-card title="Limits" subtitle="Leave blank for no limit.">
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div>
                    <x-input-label value="Min deposit" />
                    <x-text-input type="number" step="0.01" name="min_deposit" value="{{ old('min_deposit', $paymentProvider->min_deposit) }}" class="w-full" />
                </div>
                <div>
                    <x-input-label value="Max deposit" />
                    <x-text-input type="number" step="0.01" name="max_deposit" value="{{ old('max_deposit', $paymentProvider->max_deposit) }}" class="w-full" />
                </div>
                <div>
                    <x-input-label value="Min payout" />
                    <x-text-input type="number" step="0.01" name="min_payout" value="{{ old('min_payout', $paymentProvider->min_payout) }}" class="w-full" />
                </div>
                <div>
                    <x-input-label value="Max payout" />
                    <x-text-input type="number" step="0.01" name="max_payout" value="{{ old('max_payout', $paymentProvider->max_payout) }}" class="w-full" />
                </div>
            </div>
        </x-glass-card>

        @if (count($credentialFields))
            <x-glass-card title="Credentials" subtitle="Encrypted at rest. Leave a field blank to clear it.">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($credentialFields as $key => $label)
                        <div>
                            <x-input-label :value="$label" />
                            <x-text-input
                                type="password"
                                autocomplete="off"
                                name="credentials[{{ $key }}]"
                                value="{{ old('credentials.'.$key, $paymentProvider->credential($key)) }}"
                                class="w-full"
                            />
                        </div>
                    @endforeach
                </div>
            </x-glass-card>
        @endif

        @if (count($configFields))
            <x-glass-card title="Settings">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach ($configFields as $key => $label)
                        <div>
                            <x-input-label :value="$label" />
                            <x-text-input
                                type="text"
                                name="config[{{ $key }}]"
                                value="{{ old('config.'.$key, $paymentProvider->config[$key] ?? '') }}"
                                class="w-full"
                            />
                        </div>
                    @endforeach
                </div>
            </x-glass-card>
        @endif

        <div class="flex items-center gap-3">
            <x-primary-button>Save</x-primary-button>
            <a href="{{ route('admin.payment-providers.index') }}" class="text-sm text-slate-400 hover:text-white">Cancel</a>
        </div>
    </form>
@endsection
