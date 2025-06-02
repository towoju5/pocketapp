<?php
// app/Http/Controllers/ChatController.php
namespace App\Http\Controllers;

use App\Events\ChatEvent;
use App\Models\Message;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function send(Request $request)
    {
        $message = Message::create([
            'user_id' => auth()->id(),
            'content' => $request->content
        ]);

        broadcast(new ChatEvent($message))->toOthers();

        return response()->json(['status' => 'success']);
    }

    public function getMessages()
    {
        return Message::where('user_id', auth()->id())->get();
    }
}