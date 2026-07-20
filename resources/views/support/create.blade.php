@extends('layouts.desktop.trading')

@section('title', 'New Ticket')

@section('content')
<style>
    .btn-go { background: #4f8ef7; color: #fff; border: none; padding: 14px; border-radius: 8px; font-weight: 700; cursor: pointer; width: 100%; }
    .btn-go:hover { background: #3d7de0; }
</style>

<div class="flex-1 overflow-y-auto p-6">
    <div class="w-4/5 mx-auto">
        @if ($errors->any())
            <div class="bg-[#f4534a]/10 border-l-4 border-[#f4534a] text-[#f4534a] p-4 rounded-lg mb-5">{{ $errors->first() }}</div>
        @endif

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <h2 class="text-white font-bold mb-6">Open a Ticket</h2>

            <form method="POST" action="{{ route('support-tickets.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" class="brand-input-dark" required>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Priority</label>
                    <select name="priority" class="brand-input-dark" required>
                        <option value="normal">Standard</option>
                        <option value="high">Critical</option>
                        <option value="urgent">Security</option>
                        <option value="low">Low</option>
                    </select>
                </div>

                <div>
                    <label class="mb-1.5 block text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Message</label>
                    <textarea name="message" rows="5" class="brand-input-dark" required>{{ old('message') }}</textarea>
                </div>

                <button type="submit" class="btn-go">Submit Ticket</button>
            </form>
        </div>
    </div>
</div>
@endsection
