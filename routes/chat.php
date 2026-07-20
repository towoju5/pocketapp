<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('chat')->as('chat.')->group(function () {
    Route::get('/', [ChatController::class, 'index'])->name('index');
    Route::get('search', [ChatController::class, 'search'])->name('search');
    Route::post('send', [ChatController::class, 'send'])->name('send');
    Route::get('{contact}/poll', [ChatController::class, 'poll'])->name('poll');
    Route::get('{contact}', [ChatController::class, 'index'])->name('show');
});
