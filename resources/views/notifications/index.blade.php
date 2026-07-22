@extends('layouts.desktop.trading')

@section('title', 'Notifications')

@section('content')
<style>
    .ntf-item { display: flex; justify-content: space-between; gap: 16px; padding: 16px; border-radius: 10px; margin-bottom: 10px; background: #1c243c; border: 1px solid #2a3350; }
    .ntf-item.unread { border-color: #4f8ef7; background: rgba(79,142,247,0.06); }
</style>

<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        <div class="flex justify-between items-end mb-6">
            <h1 class="text-xl font-bold text-white m-0">Notifications</h1>
            <form method="POST" action="{{ route('notifications.read-all') }}">
                @csrf
                <button type="submit" class="bg-transparent border border-[#2a3350] text-[#7c86a3] text-xs py-2 px-3.5 rounded-lg cursor-pointer">Mark all read</button>
            </form>
        </div>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            @forelse ($notifications as $n)
                <div class="ntf-item {{ $n->read_at ? '' : 'unread' }}">
                    <div>
                        <p class="text-white font-semibold mb-1">{{ $n->data['title'] ?? 'Notification' }}</p>
                        <p class="text-[#7c86a3] text-xs">{{ $n->data['body'] ?? '' }}</p>
                        <p class="text-[#7c86a3] text-[11px] mt-1.5 opacity-70">{{ $n->created_at->diffForHumans() }}</p>
                    </div>
                    @if (!$n->read_at)
                        <form method="POST" action="{{ route('notifications.read', $n->id) }}">
                            @csrf
                            <button type="submit" class="bg-transparent border-none text-[#4f8ef7] text-xs cursor-pointer">Mark read</button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-center text-[#7c86a3] py-10 font-semibold">No notifications yet.</p>
            @endforelse

            <div class="mt-4">{{ $notifications->links() }}</div>
        </div>
    </div>
</div>
@endsection
