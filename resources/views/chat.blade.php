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
