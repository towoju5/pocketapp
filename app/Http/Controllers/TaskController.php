<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskSubmission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::where('is_active', true)->get();

        $todaysCounts = TaskSubmission::where('user_id', auth()->id())
            ->whereDate('submitted_date', today())
            ->whereIn('status', ['pending', 'approved'])
            ->selectRaw('task_id, count(*) as c')
            ->groupBy('task_id')
            ->pluck('c', 'task_id');

        return view('tasks.index', compact('tasks', 'todaysCounts'));
    }

    public function submit(Request $request, Task $task): RedirectResponse
    {
        abort_unless($task->is_active, 404);

        $validated = $request->validate([
            'proof_url' => ['required', 'url', 'max:2048'],
        ]);

        // The real gate: how many pending/approved submissions this user
        // already has for this task today. Rejected attempts don't count,
        // so a rejection doesn't permanently burn the day's slot.
        $todaysCount = TaskSubmission::where('user_id', auth()->id())
            ->where('task_id', $task->id)
            ->whereDate('submitted_date', today())
            ->whereIn('status', ['pending', 'approved'])
            ->count();

        if ($todaysCount >= $task->daily_limit) {
            return back()->with('error', "You've reached today's submission limit for this task.");
        }

        TaskSubmission::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'proof_url' => $validated['proof_url'],
            'status' => 'pending',
            'submitted_date' => today(),
            'reward_amount' => $task->reward_amount,
        ]);

        return back()->with('success', 'Task submitted for review.');
    }

    public function submissions(): View
    {
        $submissions = TaskSubmission::with('task')
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('tasks.submissions', compact('submissions'));
    }
}
