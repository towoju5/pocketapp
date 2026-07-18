<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Notifications\GenericNotification;
use Illuminate\Http\Request;

class SupportTicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = SupportTicket::with('user')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->priority, fn ($q) => $q->where('priority', $request->priority))
            ->latest()
            ->paginate(15);

        return view('admin.support-tickets.index', compact('tickets'));
    }

    public function show(SupportTicket $supportTicket)
    {
        return view('admin.support-tickets.show', ['ticket' => $supportTicket->load('replies.user')]);
    }

    public function reply(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $supportTicket->replies()->create([
            'user_id' => auth()->id(),
            'is_admin_reply' => true,
            'message' => $validated['message'],
        ]);

        $supportTicket->update(['status' => 'pending']);

        $supportTicket->user->notify(new GenericNotification(
            'Support reply received',
            "An admin replied to your ticket: {$supportTicket->subject}",
            route('support-tickets.show', $supportTicket)
        ));

        return back()->with('success', 'Reply sent.');
    }

    public function updateStatus(Request $request, SupportTicket $supportTicket)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:open,pending,closed'],
        ]);

        $supportTicket->update([
            'status' => $validated['status'],
            'closed_at' => $validated['status'] === 'closed' ? now() : null,
        ]);

        return back()->with('success', 'Ticket status updated.');
    }
}
