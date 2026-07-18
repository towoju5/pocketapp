<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaskSubmission;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TaskSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $submissions = TaskSubmission::with(['task', 'user'])
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15);

        return view('admin.task-submissions.index', compact('submissions'));
    }

    public function show(TaskSubmission $taskSubmission)
    {
        return view('admin.task-submissions.show', ['submission' => $taskSubmission]);
    }

    public function approve(TaskSubmission $taskSubmission)
    {
        DB::transaction(function () use ($taskSubmission) {
            $locked = TaskSubmission::where('id', $taskSubmission->id)->lockForUpdate()->first();

            if ($locked->status !== 'pending') {
                return;
            }

            // credit_user() operates on auth()->user() (the admin here) —
            // credit the submitter directly instead.
            $locked->user->getWallet($locked->task->wallet_slug)
                ->deposit($locked->reward_amount, ['description' => "Task reward: {$locked->task->title}"]);

            $locked->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
                'credited_at' => now(),
            ]);

            $locked->user->notify(new GenericNotification(
                'Task approved',
                "Your submission for \"{$locked->task->title}\" was approved. Reward credited.",
                route('tasks.submissions')
            ));
        });

        return back()->with('success', 'Submission approved and reward credited.');
    }

    public function reject(Request $request, TaskSubmission $taskSubmission)
    {
        if ($taskSubmission->status !== 'pending') {
            return back()->with('error', 'Only pending submissions can be rejected.');
        }

        $validated = $request->validate([
            'admin_notes' => ['required', 'string', 'max:1000'],
        ]);

        $taskSubmission->update([
            'status' => 'rejected',
            'admin_notes' => $validated['admin_notes'],
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $taskSubmission->user->notify(new GenericNotification(
            'Task submission rejected',
            $validated['admin_notes'],
            route('tasks.submissions')
        ));

        return back()->with('success', 'Submission rejected.');
    }
}
