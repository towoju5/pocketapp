@extends('layouts.admin.app')

@section('title', 'Review KYC Submission')

@section('content')
    <x-page-header :title="'Review: ' . ($kyc->user->first_name ?? 'User') . ' ' . ($kyc->user->last_name ?? '')" subtitle="Submitted {{ $kyc->submitted_at?->format('d M, Y H:i') }}">
        <x-slot:actions>
            <x-badge :status="$kyc->status" />
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-glass-card title="Documents">
            <dl class="space-y-4 text-sm">
                <div class="flex justify-between"><dt class="text-slate-400">Provider</dt><dd class="capitalize font-semibold text-white">{{ $kyc->provider }}</dd></div>
                @if ($kyc->provider === 'manual')
                    <div class="flex justify-between"><dt class="text-slate-400">Document Type</dt><dd class="capitalize font-semibold text-white">{{ str_replace('_', ' ', $kyc->document_type) }}</dd></div>
                @endif
            </dl>

            @if ($kyc->provider !== 'manual')
                <p class="mt-4 text-sm text-slate-400">
                    Verified through {{ ucfirst($kyc->provider) }}'s hosted flow — no documents are held on this server;
                    {{ ucfirst($kyc->provider) }} reviewed them directly and reported a decision via webhook.
                </p>
            @else
                <div class="mt-6 space-y-4">
                    @if ($kyc->document_front_path)
                        <div>
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Front</p>
                            <a href="{{ Storage::url($kyc->document_front_path) }}" target="_blank" class="block overflow-hidden rounded-xl border border-glass-border">
                                <img src="{{ Storage::url($kyc->document_front_path) }}" class="w-full object-cover" alt="Document front">
                            </a>
                        </div>
                    @endif
                    @if ($kyc->document_back_path)
                        <div>
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Back</p>
                            <a href="{{ Storage::url($kyc->document_back_path) }}" target="_blank" class="block overflow-hidden rounded-xl border border-glass-border">
                                <img src="{{ Storage::url($kyc->document_back_path) }}" class="w-full object-cover" alt="Document back">
                            </a>
                        </div>
                    @endif
                    @if ($kyc->selfie_path)
                        <div>
                            <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-slate-400">Selfie</p>
                            <a href="{{ Storage::url($kyc->selfie_path) }}" target="_blank" class="block overflow-hidden rounded-xl border border-glass-border">
                                <img src="{{ Storage::url($kyc->selfie_path) }}" class="w-full object-cover" alt="Selfie">
                            </a>
                        </div>
                    @endif

                    @if ($kyc->custom_field_values)
                        <div class="pt-2 border-t border-glass-border">
                            <p class="mb-3 text-xs font-semibold uppercase tracking-wide text-slate-400">Additional Info</p>
                            <dl class="space-y-3 text-sm">
                                @foreach ($kyc->custom_field_values as $key => $value)
                                    @php $fieldDef = \App\Models\KycCustomField::where('field_key', $key)->first(); @endphp
                                    <div class="flex justify-between gap-4">
                                        <dt class="text-slate-400">{{ $fieldDef->label ?? $key }}</dt>
                                        <dd class="text-right font-semibold text-white">
                                            @if ($fieldDef?->field_type === 'file' && $value)
                                                <a href="{{ Storage::url($value) }}" target="_blank" class="text-brand-blue hover:underline">View file</a>
                                            @elseif (is_bool($value))
                                                {{ $value ? 'Yes' : 'No' }}
                                            @else
                                                {{ $value }}
                                            @endif
                                        </dd>
                                    </div>
                                @endforeach
                            </dl>
                        </div>
                    @endif
                </div>
            @endif
        </x-glass-card>

        <x-glass-card title="Decision">
            @if ($kyc->status === 'pending')
                <form method="POST" action="{{ route('admin.kyc.approve', $kyc) }}" class="mb-4">
                    @csrf
                    <button type="submit" class="brand-btn-primary w-full justify-center">Approve</button>
                </form>

                <form method="POST" action="{{ route('admin.kyc.reject', $kyc) }}" class="space-y-3">
                    @csrf
                    <textarea name="reason" rows="3" required placeholder="Rejection reason" class="brand-input-dark"></textarea>
                    <button type="submit" class="brand-btn w-full justify-center bg-brand-danger text-white hover:bg-red-600">Reject</button>
                </form>
            @else
                <p class="text-sm text-slate-400">
                    Reviewed by {{ $kyc->reviewer?->first_name ?? 'system' }} on {{ $kyc->reviewed_at?->format('d M, Y H:i') }}.
                </p>
                @if ($kyc->rejection_reason)
                    <p class="mt-3 rounded-xl border border-brand-danger/20 bg-brand-danger/10 p-4 text-sm text-brand-danger">{{ $kyc->rejection_reason }}</p>
                @endif
            @endif
        </x-glass-card>
    </div>
@endsection
