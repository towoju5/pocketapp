<?php

use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SupportTicketController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::resource('support-tickets', SupportTicketController::class)->only(['index', 'create', 'store', 'show']);
    Route::post('support-tickets/{supportTicket}/reply', [SupportTicketController::class, 'reply'])->name('support-tickets.reply');
    Route::post('support-tickets/{supportTicket}/close', [SupportTicketController::class, 'close'])->name('support-tickets.close');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});
