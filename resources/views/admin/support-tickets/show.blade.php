@extends('layouts.admin.app')

@section('title', $ticket->subject)

@section('content')
    <x-page-header :title="$ticket->subject" subtitle="{{ $ticket->user->first_name }} {{ $ticket->user->last_name }}">
        <x-slot:actions>
            <x-badge :status="$ticket->status" />
        </x-slot:actions>
    </x-page-header>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2">
            <x-glass-card title="Thread">
                <div class="space-y-4">
                    @foreach ($ticket->replies as $reply)
                        <div class="rounded-xl border p-4 {{ $reply->is_admin_reply ? 'border-brand-blue/30 bg-brand-blue/5' : 'border-glass-border bg-white/5' }}">
                            <p class="mb-1 text-xs font-bold uppercase tracking-wide text-slate-400">{{ $reply->is_admin_reply ? 'Admin' : $reply->user->first_name ?? 'User' }}</p>
                            <p class="text-sm text-white">{{ $reply->message }}</p>
                            <p class="mt-1 text-xs text-slate-500">{{ $reply->created_at->format('d M, Y H:i') }}</p>
                        </div>
                    @endforeach
                </div>

                <form method="POST" action="{{ route('admin.support-tickets.reply', $ticket) }}" class="mt-6 space-y-3">
                    @csrf
                    <textarea name="message" rows="3" placeholder="Reply to user..." class="brand-input-dark" required></textarea>
                    <button type="submit" class="brand-btn-primary w-full justify-center">Send Reply</button>
                </form>
            </x-glass-card>
        </div>

        <x-glass-card title="Status">
            <form method="POST" action="{{ route('admin.support-tickets.status', $ticket) }}" class="space-y-3">
                @csrf
                @method('PATCH')
                <select name="status" class="brand-input-dark">
                    <option value="open" {{ $ticket->status === 'open' ? 'selected' : '' }}>Open</option>
                    <option value="pending" {{ $ticket->status === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="closed" {{ $ticket->status === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
                <button type="submit" class="brand-btn-outline w-full justify-center">Update Status</button>
            </form>
        </x-glass-card>
    </div>
@endsection
