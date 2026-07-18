<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::latest()->paginate(15);

        return view('admin.tasks.index', compact('tasks'));
    }

    public function create()
    {
        return view('admin.tasks.create');
    }

    public function store(Request $request)
    {
        Task::create($this->validated($request));

        return redirect()->route('admin.tasks.index')->with('success', 'Task created successfully.');
    }

    public function edit(Task $task)
    {
        return view('admin.tasks.edit', compact('task'));
    }

    public function update(Request $request, Task $task)
    {
        $task->update($this->validated($request));

        return redirect()->route('admin.tasks.index')->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('admin.tasks.index')->with('success', 'Task deleted successfully.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'external_url' => 'required|url|max:2048',
            'reward_amount' => 'required|numeric|min:0',
            'wallet_slug' => 'required|string|max:50',
            'daily_limit' => 'required|integer|min:1',
            'is_active' => 'nullable|boolean',
        ]);
    }
}
