<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SupportTicketController extends Controller
{
    public function index(): View
    {
        $tickets = SupportTicket::where('user_id', auth()->id())->latest()->paginate(15);

        return view('support.index', compact('tickets'));
    }

    public function create(): View
    {
        return view('support.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'subject' => ['required', 'string', 'max:255'],
            'priority' => ['required', 'in:low,normal,high,urgent'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $validated['subject'],
            'priority' => $validated['priority'],
            'status' => 'open',
        ]);

        $ticket->replies()->create([
            'user_id' => auth()->id(),
            'is_admin_reply' => false,
            'message' => $validated['message'],
        ]);

        return redirect()->route('support-tickets.show', $ticket)->with('success', 'Ticket opened.');
    }

    public function show(SupportTicket $supportTicket): View
    {
        abort_unless($supportTicket->user_id === auth()->id(), 403);

        return view('support.show', ['ticket' => $supportTicket->load('replies.user')]);
    }

    public function reply(Request $request, SupportTicket $supportTicket): RedirectResponse
    {
        abort_unless($supportTicket->user_id === auth()->id(), 403);

        $validated = $request->validate([
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $supportTicket->replies()->create([
            'user_id' => auth()->id(),
            'is_admin_reply' => false,
            'message' => $validated['message'],
        ]);

        // Replying to a closed ticket reopens it rather than requiring a
        // distinct "reopen" action.
        if ($supportTicket->status !== 'open') {
            $supportTicket->update(['status' => 'open']);
        }

        return back()->with('success', 'Reply sent.');
    }

    public function close(SupportTicket $supportTicket): RedirectResponse
    {
        abort_unless($supportTicket->user_id === auth()->id(), 403);

        $supportTicket->update(['status' => 'closed', 'closed_at' => now()]);

        return back()->with('success', 'Ticket closed.');
    }
}
