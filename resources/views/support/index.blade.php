@extends('layouts.desktop.trading')

@section('title', 'Help Desk')

@section('content')
<style>
    .btn-go { background: #4f8ef7; color: #fff; border: none; padding: 12px 20px; border-radius: 8px; font-weight: 700; cursor: pointer; text-decoration:none; display:inline-block; font-size:13px; }
    .btn-go:hover { background: #3d7de0; }
    .status-badge { padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .status-open { background: rgba(79,142,247,0.1); color: #4f8ef7; }
    .status-pending { background: rgba(245,158,11,0.1); color: #fbbf24; }
    .status-closed { background: rgba(124,134,163,0.1); color: #7c86a3; }
</style>

<div class="flex-1 overflow-y-auto p-6">
    <div class="mx-auto">
        <div class="flex justify-between items-end flex-wrap gap-4 mb-6">
            <h1 class="text-xl font-bold text-white m-0">Help Desk</h1>
            <a href="{{ route('support-tickets.create') }}" class="btn-go">New Ticket</a>
        </div>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <div class="overflow-x-auto">
                <div class="responsive-table">
                <table class="w-full text-left text-sm text-[#d7dcea]">
                    <thead>
                        <tr class="text-[#7c86a3] text-xs uppercase">
                            <th class="py-2 px-4">Subject</th><th class="py-2 px-4">Priority</th><th class="py-2 px-4">Status</th><th class="py-2 px-4"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tickets as $ticket)
                            <tr class="border-t border-[#2a3350]">
                                <td class="py-3.5 px-4 text-white" data-label="Subject">{{ $ticket->subject }}</td>
                                <td class="py-3.5 px-4 capitalize text-xs text-[#7c86a3]" data-label="Priority">{{ $ticket->priority }}</td>
                                <td class="py-3.5 px-4" data-label="Status"><span class="status-badge status-{{ $ticket->status }}">{{ $ticket->status }}</span></td>
                                <td class="py-3.5 px-4 text-right" data-label="Action">
                                    <a href="{{ route('support-tickets.show', $ticket) }}" class="text-[#4f8ef7] font-semibold text-xs no-underline">View &rarr;</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-12 text-[#7c86a3] font-semibold">No tickets yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
                </div>
            </div>
            <div class="mt-5">{{ $tickets->links() }}</div>
        </div>
    </div>
</div>
@endsection
