@extends('layouts.admin.app')

@section('title', 'Review Submission')

@section('content')
    <x-page-header :title="'Submission #' . $submission->id" subtitle="{{ $submission->user->first_name }} {{ $submission->user->last_name }} — {{ $submission->task->title ?? '—' }}">
        <x-slot:actions><x-badge :status="$submission->status" /></x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <x-glass-card title="Submission">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between"><dt class="text-slate-400">Reward</dt><dd class="font-semibold text-white">{{ formatPrice($submission->reward_amount) }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Submitted</dt><dd class="font-semibold text-white">{{ $submission->submitted_date->format('d M, Y') }}</dd></div>
                <div class="flex justify-between"><dt class="text-slate-400">Proof</dt><dd><a href="{{ $submission->proof_url }}" target="_blank" class="text-brand-blue hover:underline">{{ $submission->proof_url }}</a></dd></div>
            </dl>
        </x-glass-card>

        <x-glass-card title="Decision">
            @if ($submission->status === 'pending')
                <form method="POST" action="{{ route('admin.task-submissions.approve', $submission) }}" class="mb-4">
                    @csrf
                    <button type="submit" class="brand-btn-primary w-full justify-center">Approve &amp; Credit</button>
                </form>

                <form method="POST" action="{{ route('admin.task-submissions.reject', $submission) }}" class="space-y-3">
                    @csrf
                    <textarea name="admin_notes" rows="3" required placeholder="Rejection reason" class="brand-input-dark"></textarea>
                    <button type="submit" class="brand-btn w-full justify-center bg-brand-danger text-white hover:bg-red-600">Reject</button>
                </form>
            @else
                <p class="text-sm text-slate-400">
                    Reviewed by {{ $submission->reviewer?->first_name ?? 'system' }} on {{ $submission->reviewed_at?->format('d M, Y H:i') }}.
                </p>
                @if ($submission->admin_notes)
                    <p class="mt-3 rounded-xl border border-brand-danger/20 bg-brand-danger/10 p-4 text-sm text-brand-danger">{{ $submission->admin_notes }}</p>
                @endif
            @endif
        </x-glass-card>
    </div>
@endsection
