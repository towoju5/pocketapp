@extends('layouts.admin.app')

@section('title', 'Task Submissions')

@section('content')
    <x-page-header title="Task Submissions" subtitle="Review proof-of-completion submissions." />

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>User</th><th>Task</th><th>Reward</th><th>Proof</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($submissions as $sub)
                    <tr>
                        <td>{{ $sub->user->first_name }} {{ $sub->user->last_name }}</td>
                        <td>{{ $sub->task->title ?? '—' }}</td>
                        <td>{{ formatPrice($sub->reward_amount) }}</td>
                        <td><a href="{{ $sub->proof_url }}" target="_blank" class="text-brand-blue hover:underline">Link</a></td>
                        <td><x-badge :status="$sub->status" /></td>
                        <td class="text-right">
                            <a href="{{ route('admin.task-submissions.show', $sub) }}" class="text-brand-blue hover:underline">Review</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-10 text-slate-400">No submissions yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $submissions->links() }}</div>
    </x-glass-card>
@endsection
