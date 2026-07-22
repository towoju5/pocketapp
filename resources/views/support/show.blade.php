@extends('layouts.desktop.trading')

@section('title', $ticket->subject)

@section('content')
<style>
    .bubble { max-width: 85%; padding: 14px; border-radius: 12px; margin-bottom: 12px; font-size: 14px; line-height: 1.6; }
    .b-user { background: #4f8ef7; color: #fff; margin-left: auto; border-bottom-right-radius: 2px; }
    .b-admin { background: #1c243c; color: #d7dcea; border: 1px solid #2a3350; border-bottom-left-radius: 2px; }
    .btn-go { background: #4f8ef7; color: #fff; border: none; padding: 14px; border-radius: 8px; font-weight: 700; cursor: pointer; width:100%; }
    .btn-go:hover { background: #3d7de0; }
</style>

<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        @if (session('success'))
            <div class="bg-[#16c087]/10 border-l-4 border-[#16c087] text-[#16c087] p-4 rounded-lg mb-5">{{ session('success') }}</div>
        @endif

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6 mb-5">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-white font-bold m-0">{{ $ticket->subject }}</h2>
                <span class="px-3.5 py-1.5 rounded-full text-xs font-bold uppercase bg-[#4f8ef7]/10 text-[#4f8ef7]">{{ $ticket->status }}</span>
            </div>

            <div class="flex flex-col">
                @foreach ($ticket->replies as $reply)
                    <div class="bubble {{ $reply->is_admin_reply ? 'b-admin' : 'b-user' }}">
                        <small class="font-bold uppercase text-[9px] block mb-1 opacity-70">
                            {{ $reply->is_admin_reply ? 'Support Team' : 'You' }}
                        </small>
                        {{ $reply->message }}
                        <span class="text-[10px] opacity-60 block mt-1">{{ $reply->created_at->format('d M, H:i') }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        @if ($ticket->status !== 'closed')
            <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
                <form method="POST" action="{{ route('support-tickets.reply', $ticket) }}" class="mb-3.5">
                    @csrf
                    <textarea name="message" rows="3" placeholder="Type your follow-up..." class="brand-input-dark mb-3" required></textarea>
                    <button type="submit" class="btn-go">Send Reply</button>
                </form>
                <form method="POST" action="{{ route('support-tickets.close', $ticket) }}">
                    @csrf
                    <button type="submit" class="bg-transparent border border-[#2a3350] text-[#7c86a3] text-xs py-2.5 px-4 rounded-lg cursor-pointer w-full">Close Ticket</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
