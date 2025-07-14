<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function fetchMessages()
    {
        return Message::with('user')->latest()->take(50)->get()->reverse();
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $message = new Message();
        $message->user_id = Auth::id();
        $message->message = $request->input('message');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('chat_images', 'public');
            $message->image = $path;
        }

        $message->save();

        broadcast(new \App\Events\MessageSent($message))->withExceptionHandling();

        return response()->json(['status' => 'Message sent']);
    }
}
