@extends('layouts.desktop.trading')

@section('title', 'Task Registry')

@section('content')
<style>
    .status-badge { padding: 4px 10px; border-radius: 6px; font-size: 10px; font-weight: 700; text-transform: uppercase; }
    .status-pending { background: rgba(245,158,11,0.1); color: #fbbf24; }
    .status-approved { background: rgba(22,192,135,0.1); color: #16c087; }
    .status-rejected { background: rgba(244,83,74,0.1); color: #f4534a; }
</style>

<div class="flex-1 overflow-y-auto p-6">
    <div class="max-w-4xl mx-auto">
        <div class="flex justify-between items-end flex-wrap gap-4 mb-6">
            <h1 class="text-xl font-bold text-white m-0">Task Registry</h1>
            <a href="{{ route('tasks.index') }}" class="text-[#4f8ef7] font-semibold text-sm no-underline">&larr; Duty Registry</a>
        </div>

        <div class="bg-[#171e33] border border-[#2a3350] rounded-xl p-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-[#d7dcea]">
                    <thead>
                        <tr class="text-[#7c86a3] text-xs uppercase">
                            <th class="py-2 px-4">Task</th>
                            <th class="py-2 px-4">Date</th>
                            <th class="py-2 px-4">Reward</th>
                            <th class="py-2 px-4">Status</th>
                            <th class="py-2 px-4">Notes</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($submissions as $sub)
                            <tr class="border-t border-[#2a3350]">
                                <td class="py-3.5 px-4 text-white">{{ $sub->task->title ?? 'Deleted task' }}</td>
                                <td class="py-3.5 px-4 text-xs text-[#7c86a3]">{{ $sub->submitted_date->format('d M, Y') }}</td>
                                <td class="py-3.5 px-4 text-[#16c087] font-semibold">{{ formatPrice($sub->reward_amount) }}</td>
                                <td class="py-3.5 px-4"><span class="status-badge status-{{ $sub->status }}">{{ $sub->status }}</span></td>
                                <td class="py-3.5 px-4 text-xs text-[#7c86a3]">{{ $sub->admin_notes }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-12 text-[#7c86a3] font-semibold">No submissions yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-5">{{ $submissions->links() }}</div>
        </div>
    </div>
</div>
@endsection
