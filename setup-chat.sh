#!/bin/bash

# Colors for output
GREEN='\033[0;32m'
NC='\033[0m' # No Color

echo -e "${GREEN}🚀 Starting Laravel Chat Setup...${NC}"

# Step 1: Install required packages
echo -e "${GREEN}📦 Installing npm dependencies...${NC}"
npm install --save laravel-echo pusher-js toastr

# Step 2: Create Message model and migration
echo -e "${GREEN}🧱 Generating Message model and migration...${NC}"
php artisan make:model Message -mf

# Step 3: Update migration file
echo -e "${GREEN}📝 Updating messages table migration...${NC}"
cat > database/migrations/*_create_messages_table.php <<EOL
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new Migration()
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint \$table) {
            \$table->id();
            \$table->foreignId('user_id')->constrained()->onDelete('cascade');
            \$table->text('message')->nullable();
            \$table->string('image')->nullable();
            \$table->unsignedBigInteger('to_user_id')->nullable();
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
EOL

# Step 4: Run migration
echo -e "${GREEN}💾 Running migrations...${NC}"
php artisan migrate

# Step 5: Generate ChatController
echo -e "${GREEN}⚙️ Creating ChatController...${NC}"
php artisan make:controller ChatController

cat > app/Http/Controllers/ChatController.php <<EOL
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

    public function sendMessage(Request \$request)
    {
        \$request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        \$message = new Message();
        \$message->user_id = Auth::id();
        \$message->message = \$request->input('message');

        if (\$request->hasFile('image')) {
            \$path = \$request->file('image')->store('chat_images', 'public');
            \$message->image = \$path;
        }

        \$message->save();

        broadcast(new \\App\\Events\\MessageSent(\$message))->withExceptionHandling();

        return response()->json(['status' => 'Message sent']);
    }
}
EOL

# Step 6: Generate MessageSent Event
echo -e "${GREEN}🔔 Creating MessageSent event...${NC}"
php artisan make:event MessageSent

cat > app/Events/MessageSent.php <<EOL
<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public \$message;

    public function __construct(Message \$message)
    {
        \$this->message = \$message->load('user');
    }

    public function broadcastOn()
    {
        return new Channel('chat');
    }

    public function broadcastAs()
    {
        return 'message.sent';
    }
}
EOL

# Step 7: Setup Broadcast Channels
echo -e "${GREEN}📡 Setting up broadcasting channels...${NC}"
cat > routes/channels.php <<EOL
<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('chat', function (\$user) {
    return true;
});
EOL

# Step 8: Add Routes
echo -e "${GREEN}🛣️ Adding chat routes...${NC}"
cat >> routes/web.php <<EOL

// Chat Routes
Route::middleware('auth')->group(function () {
    Route::get('/chat', [App\Http\Controllers\ChatController::class, 'index'])->name('chat');
    Route::post('/chat/send', [App\Http\Controllers\ChatController::class, 'sendMessage']);
    Route::get('/chat/messages', [App\Http\Controllers\ChatController::class, 'fetchMessages']);
});
EOL

# Step 9: Create Chat Blade View
echo -e "${GREEN}📄 Creating chat.blade.php...${NC}"
mkdir -p resources/views
cat > resources/views/chat.blade.php <<EOL
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Chat</title>
    @vite('resources/js/app.js')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css ">
</head>
<body class="bg-gray-900 text-white">
<div class="bg-[#222636] min-h-screen w-[25rem] mr-auto" id="content-area">
    <div class="bg-[#222636] min-h-screen w-full">
        <!-- Chat Area -->
        <div id="chat-container" class="p-4 overflow-y-auto h-[calc(100vh-200px)] space-y-4">
            <!-- Messages will be injected here -->
        </div>

        <!-- Chat Input -->
        <form id="chat-form" class="p-4 border-t border-slate-700 flex items-center gap-2">
            @csrf
            <input type="text" id="message-input" placeholder="Type a message..." class="flex-1 bg-[#1d2130] text-white rounded-lg px-4 py-2 focus:outline-none">
            <input type="file" id="image-input" accept="image/*" class="hidden">
            <button type="button" onclick="document.getElementById('image-input').click()" class="p-2 bg-[#293145] rounded-md">
                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14"></path>
                </svg>
            </button>
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Send</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/toastr @2.1.4/toastr.min.js"></script>
@vite('resources/js/chat.js')

</body>
</html>
EOL

# Step 10: Setup Echo in bootstrap.js
echo -e "${GREEN}🔌 Configuring Laravel Echo...${NC}"
cat > resources/js/bootstrap.js <<EOL
import Echo from "laravel-echo"
import Pusher from "pusher-js"

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 8080,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
EOL

# Step 11: Chat JS logic
echo -e "${GREEN}🧠 Adding chat.js logic...${NC}"
mkdir -p resources/js
cat > resources/js/chat.js <<EOL
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('chat-container');
    const form = document.getElementById('chat-form');
    const input = document.getElementById('message-input');
    const imageInput = document.getElementById('image-input');

    // Load initial messages
    fetch('/chat/messages')
        .then(res => res.json())
        .then(messages => {
            messages.forEach(msg => appendMessage(msg));
        });

    window.Echo.channel('chat')
        .listen('.message.sent', (e) => {
            appendMessage(e.message);
            toastr.success("New message!");
        });

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData();
        formData.append('message', input.value);

        if (imageInput.files.length > 0) {
            formData.append('image', imageInput.files[0]);
        }

        fetch('/chat/send', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData
        }).then(() => {
            input.value = '';
            imageInput.value = '';
        });
    });

    function appendMessage(message) {
        const isMe = message.user_id === {{ auth()->id() }};
        const div = document.createElement('div');
        div.className = 'flex ' + (isMe ? 'justify-end' : 'justify-start');

        let content = \`
            <div class="\${isMe ? 'bg-blue-600 text-white' : 'bg-gray-700 text-white'} p-3 rounded-lg max-w-xs relative group">
                \${message.message ? \`<p>\${message.message}</p>\` : ''}
                \${message.image ? \`<img src="/storage/\${message.image}" class="mt-2 max-h-40 rounded">\` : ''}
                <span class="absolute bottom-1 right-2 text-xs opacity-70">\${new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
            </div>
        \`;

        div.innerHTML = content;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }
});
EOL

# Final Instructions
echo -e "${GREEN}✅ Setup Complete! Now run:${NC}"
echo -e "1. php artisan serve"
echo -e "2. php artisan reverb:start"
echo -e "3. npm run dev"
