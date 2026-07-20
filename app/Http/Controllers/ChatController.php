<?php

namespace App\Http\Controllers;

use App\Events\ChatMessageSent;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index(?User $contact = null)
    {
        $userId = auth()->id();

        // Distinct set of everyone this user has exchanged a message with,
        // most-recently-active first.
        $contactIds = ChatMessage::where('sender_id', $userId)->pluck('receiver_id')
            ->merge(ChatMessage::where('receiver_id', $userId)->pluck('sender_id'))
            ->unique();

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
        $messages = collect();

        if ($activeContact) {
            $messages = ChatMessage::conversationBetween($userId, $activeContact->id)
                ->with(['sender'])
                ->get();

            ChatMessage::where('sender_id', $activeContact->id)
                ->where('receiver_id', $userId)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        return view('chat.index', compact('contacts', 'activeContact', 'messages'));
    }

    public function search(Request $request)
    {
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
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string|max:2000',
        ]);

        if ((int) $validated['receiver_id'] === auth()->id()) {
            return response()->json(['status' => false, 'message' => "You can't message yourself."], 422);
        }

        $message = ChatMessage::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $validated['receiver_id'],
            'body' => $validated['body'],
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
