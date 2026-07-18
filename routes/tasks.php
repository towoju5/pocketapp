<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::post('tasks/{task}/submit', [TaskController::class, 'submit'])->name('tasks.submit');
    Route::get('tasks/submissions', [TaskController::class, 'submissions'])->name('tasks.submissions');
});
