<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Models\ChatMessage;
use App\Models\SupportTicket;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(?User $contact = null)
    {
        $userId = auth()->id();
        $isAdmin = (bool) auth()->user()->is_admin;
        $supportAvailable = true;

        if ($isAdmin) {
            // Distinct set of everyone this admin has exchanged a message
            // with, most-recently-active first.
            $contactIds = ChatMessage::where('sender_id', $userId)->pluck('receiver_id')
                ->merge(ChatMessage::where('receiver_id', $userId)->pluck('sender_id'))
                ->unique();
        } else {
            // Regular users only ever talk to support — no open user search.
            $contactIds = User::where('is_admin', true)->pluck('id');
            $supportAvailable = $contactIds->isNotEmpty();
        }

        $contacts = User::whereIn('id', $contactIds)
            ->get()
            ->map(function ($user) use ($userId) {
                $last = ChatMessage::conversationBetween($userId, $user->id)->latest()->first();
                $user->last_message = $last;
                $user->unread_count = ChatMessage::where('sender_id', $user->id)
                    ->where('receiver_id', $userId)
                    ->whereNull('read_at')
                    ->count();
                return $user;
            })
            ->sortByDesc(fn ($user) => $user->last_message?->created_at)
            ->values();

        $activeContact = $contact;

        // The sidebar/search already restrict non-admins to admin contacts,
        // but that's client-facing only — enforce it here too so a non-admin
        // can't reach another regular user by guessing/reusing a /chat/{id} URL.
        if ($activeContact && !$isAdmin && !$activeContact->is_admin) {
            abort(404);
        }

        $messages = collect();
        $offlineTicket = null;

        if ($activeContact) {
            $messages = ChatMessage::conversationBetween($userId, $activeContact->id)
                ->with(['sender'])
                ->get();

            ChatMessage::where('sender_id', $activeContact->id)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        } elseif (!$isAdmin && !$supportAvailable) {
            // No admin/support account exists yet — still let the user drop
            // a message via the Support Ticket system (no live receiver
            // required), so it's waiting whenever support becomes available.
            $offlineTicket = SupportTicket::where('user_id', $userId)
                ->where('status', 'open')
                ->latest()
                ->with('replies')
                ->first();
        }

        return view('chat.index', compact('contacts', 'activeContact', 'messages', 'supportAvailable', 'offlineTicket'));
    }

    public function search(Request $request)
    {
        abort_unless(auth()->user()->is_admin, 403);

        $q = trim((string) $request->input('q'));
        if ($q === '') {
            return response()->json([]);
        }

        $users = User::where('id', '!=', auth()->id())
            ->where(function ($query) use ($q) {
                $query->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('username', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%");
            })
            ->limit(10)
            ->get(['id', 'first_name', 'last_name', 'username', 'email']);

        return response()->json($users);
    }

    public function send(Request $request)
    {
        $body = $request->validate(['body' => 'required|string|max:2000'])['body'];

        if (!auth()->user()->is_admin && !User::where('is_admin', true)->exists()) {
            $ticket = SupportTicket::firstOrCreate(
                ['user_id' => auth()->id(), 'status' => 'open'],
                ['subject' => 'Chat message', 'priority' => 'normal']
            );

            $reply = $ticket->replies()->create([
                'user_id' => auth()->id(),
                'is_admin_reply' => false,
                'message' => $body,
            ]);

            return response()->json([
                'status' => true,
                'offline' => true,
                'ticket_url' => route('support-tickets.show', $ticket),
                'message' => [
                    'id' => 'ticket-' . $reply->id,
                    'sender_id' => auth()->id(),
                    'body' => $reply->message,
                    'created_at' => $reply->created_at->format('H:i'),
                ],
            ]);
        }

        $validated = $request->validate(['receiver_id' => 'required|exists:users,id']);

        if ((int) $validated['receiver_id'] === auth()->id()) {
            return response()->json(['status' => false, 'message' => "You can't message yourself."], 422);
        }

        // Non-admins can only ever message support (an admin) — never another regular user.
        if (!auth()->user()->is_admin) {
            $receiverIsAdmin = User::where('id', $validated['receiver_id'])->where('is_admin', true)->exists();
            if (!$receiverIsAdmin) {
                return response()->json(['status' => false, 'message' => 'You can only message support.'], 403);
            }
        }

        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'body' => $body,
        ]);

        broadcast(new ChatMessageSent($message))->toOthers();

        return response()->json([
            'status' => true,
            'message' => [
                'id' => $message->id,
                'sender_id' => $message->sender_id,
                'body' => $message->body,
                'created_at' => $message->created_at->format('H:i'),
            ],
        ]);
    }

    /** Polling fallback for when real-time broadcasting isn't configured — fetches anything newer than $afterId. */
    public function poll(Request $request, User $contact)
    {
        if (!auth()->user()->is_admin && !$contact->is_admin) {
            abort(404);
        }

        $afterId = (int) $request->input('after_id', 0);
        $userId = auth()->id();

        $messages = ChatMessage::conversationBetween($userId, $contact->id)
            ->where('id', '>', $afterId)
            ->get();

        if ($messages->isNotEmpty()) {
            ChatMessage::where('sender_id', $contact->id)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return response()->json($messages->map(fn ($m) => [
            'id' => $m->id,
            'sender_id' => $m->sender_id,
            'body' => $m->body,
            'created_at' => $m->created_at->format('H:i'),
        ]));
    }
}
