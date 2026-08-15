@extends('layouts.admin.app')

@section('title', 'Configure '.$kycProvider->display_name)

@section('content')
    <x-page-header :title="$kycProvider->display_name" subtitle="Identity verification provider configuration" />

    @if ($errors->any())
        <div class="mb-6 rounded-xl border border-brand-danger/20 bg-brand-danger/10 px-4 py-3 text-sm text-brand-danger">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.kyc-providers.update', $kycProvider) }}" class="space-y-6">
        @csrf
        @method('PUT')

        @if ($kycProvider->slug === 'manual')
            <x-glass-card title="Manual Review">
                <p class="text-sm text-slate-400">
                    Users upload a document + selfie and an admin approves/rejects from <a href="{{ route('admin.kyc.index') }}" class="text-brand-blue hover:underline">KYC Requests</a>.
                    No credentials needed. Add extra fields to the submission form under
                    <a href="{{ route('admin.kyc-custom-fields.index') }}" class="text-brand-blue hover:underline">KYC Custom Fields</a>.
                </p>
            </x-glass-card>
        @else
            <x-glass-card title="Webhook endpoint" subtitle="Add this URL in your {{ $kycProvider->display_name }} dashboard's webhook settings — that's how a decision actually reaches this app.">
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
        @endif

        <x-glass-card title="Status">
            <label class="flex items-center gap-2 text-sm text-white">
                <input type="checkbox" name="is_active" value="1" @checked($kycProvider->is_active) class="rounded border-white/20 bg-white/5">
                Active — the single method every user sees on /kyc/create
            </label>
            @unless ($kycProvider->is_active)
                <p class="mt-3 text-xs text-slate-400">Activating this will automatically deactivate whichever provider is currently active.</p>
            @endunless
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
                                value="{{ old('credentials.'.$key, $kycProvider->credential($key)) }}"
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
                                value="{{ old('config.'.$key, $kycProvider->config[$key] ?? '') }}"
                                class="w-full"
                            />
                        </div>
                    @endforeach
                </div>
            </x-glass-card>
        @endif

        <div class="flex items-center gap-3">
            <x-primary-button>Save</x-primary-button>
            <a href="{{ route('admin.kyc-providers.index') }}" class="text-sm text-slate-400 hover:text-white">Cancel</a>
        </div>
    </form>
@endsection
