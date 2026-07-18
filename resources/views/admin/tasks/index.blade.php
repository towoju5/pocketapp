@extends('layouts.admin.app')

@section('title', 'Tasks')

@section('content')
    <x-page-header title="Tasks" subtitle="Daily duties users can complete for wallet rewards.">
        <x-slot:actions>
            <a href="{{ route('admin.tasks.create') }}" class="brand-btn-primary">New Task</a>
        </x-slot:actions>
    </x-page-header>

    <x-glass-card>
        <x-data-table>
            <thead>
                <tr><th>Title</th><th>Reward</th><th>Daily Limit</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td class="font-semibold text-white">{{ $task->title }}</td>
                        <td>{{ formatPrice($task->reward_amount) }}</td>
                        <td>{{ $task->daily_limit }}</td>
                        <td><x-badge :status="$task->is_active ? 'active' : 'cancelled'">{{ $task->is_active ? 'Active' : 'Inactive' }}</x-badge></td>
                        <td class="text-right space-x-3">
                            <a href="{{ route('admin.tasks.edit', $task) }}" class="text-brand-blue hover:underline">Edit</a>
                            <form method="POST" action="{{ route('admin.tasks.destroy', $task) }}" class="inline" onsubmit="return confirm('Delete this task?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-brand-danger hover:underline">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center py-10 text-slate-400">No tasks yet.</td></tr>
                @endforelse
            </tbody>
        </x-data-table>

        <div class="mt-6">{{ $tasks->links() }}</div>
    </x-glass-card>
@endsection
