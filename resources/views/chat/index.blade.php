@extends('layouts.desktop.trading')

@section('title', 'Chat')

@section('content')
<div class="flex-1 flex min-h-0">
    {{-- Contact list --}}
    <div class="w-[280px] border-r border-[#2a3350] flex flex-col flex-shrink-0">
        <div class="p-3 border-b border-[#2a3350]">
            <div class="relative">
                <i class="fa fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#7c86a3] text-xs"></i>
                <input type="text" id="chatSearchInput" placeholder="Search people..."
                    class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg pl-9 pr-3 py-2 text-sm text-white outline-none focus:border-[#4f8ef7]">
            </div>
            <div id="chatSearchResults" class="hidden absolute z-20 w-[252px] mt-1 bg-[#171e33] border border-[#2a3350] rounded-lg overflow-hidden" style="box-shadow:0 20px 60px rgba(0,0,0,0.4);"></div>
        </div>
        <div class="flex-1 overflow-y-auto">
            @forelse($contacts as $contact)
                <a href="{{ route('chat.show', $contact->id) }}" class="flex items-center gap-3 px-3.5 py-3 border-b border-[#1c243c] {{ $activeContact && $activeContact->id === $contact->id ? 'bg-[#1c243c]' : '' }} hover:bg-[#1c243c]">
                    <div class="w-9 h-9 rounded-full bg-[#33406b] flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                        {{ strtoupper(substr($contact->username ?? $contact->first_name ?? 'U', 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-semibold text-white truncate">{{ $contact->username ?? trim($contact->first_name . ' ' . $contact->last_name) }}</span>
                            @if($contact->unread_count > 0)
                                <span class="bg-[#4f8ef7] text-white text-[10px] font-bold rounded-full w-4 h-4 flex items-center justify-center flex-shrink-0">{{ $contact->unread_count }}</span>
                            @endif
                        </div>
                        <p class="text-xs text-[#7c86a3] truncate">{{ $contact->last_message->body ?? '' }}</p>
                    </div>
                </a>
            @empty
                <div class="p-4 text-center text-xs text-[#7c86a3]">Search for someone above to start a conversation.</div>
            @endforelse
        </div>
    </div>

    {{-- Conversation --}}
    <div class="flex-1 flex flex-col min-w-0">
        @if($activeContact)
            <div class="h-16 border-b border-[#2a3350] flex items-center px-5 gap-3 flex-shrink-0">
                <div class="w-9 h-9 rounded-full bg-[#33406b] flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($activeContact->username ?? $activeContact->first_name ?? 'U', 0, 2)) }}
                </div>
                <span class="text-sm font-semibold text-white">{{ $activeContact->username ?? trim($activeContact->first_name . ' ' . $activeContact->last_name) }}</span>
            </div>

            <div id="chatMessages" class="flex-1 overflow-y-auto p-5 space-y-3" data-contact-id="{{ $activeContact->id }}" data-last-id="{{ $messages->last()->id ?? 0 }}">
                @foreach($messages as $message)
                    @php($isMine = $message->sender_id === auth()->id())
                    <div class="flex {{ $isMine ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-[60%] {{ $isMine ? 'bg-[#4f8ef7] text-white' : 'bg-[#1c243c] text-[#d7dcea] border border-[#2a3350]' }} rounded-xl px-4 py-2.5 text-sm">
                            <p>{{ $message->body }}</p>
                            <span class="block text-[10px] mt-1 opacity-70">{{ $message->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @endforeach
            </div>

            <form id="chatSendForm" class="p-3 border-t border-[#2a3350] flex items-center gap-2 flex-shrink-0">
                <input type="text" id="chatMessageInput" placeholder="Type a message..." autocomplete="off"
                    class="flex-1 bg-[#1c243c] border border-[#2a3350] rounded-lg px-4 py-2.5 text-sm text-white outline-none focus:border-[#4f8ef7]">
                <button type="submit" class="bg-[#4f8ef7] hover:bg-[#3f7de6] text-white w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fa fa-paper-plane"></i>
                </button>
            </form>
        @else
            <div class="flex-1 flex items-center justify-center text-center px-6">
                <div>
                    <div class="w-14 h-14 rounded-full bg-[#1c243c] border border-[#2a3350] flex items-center justify-center mx-auto mb-4">
                        <i class="fa fa-comments text-[#4f8ef7] text-xl"></i>
                    </div>
                    <p class="text-[#7c86a3] text-sm">Search for someone or select a conversation to start chatting.</p>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('js')
<script>
    (function () {
        const searchInput = document.getElementById('chatSearchInput');
        const searchResults = document.getElementById('chatSearchResults');
        let debounceTimer = null;

        searchInput?.addEventListener('input', () => {
            clearTimeout(debounceTimer);
            const q = searchInput.value.trim();
            if (!q) {
                searchResults.classList.add('hidden');
                return;
            }
            debounceTimer = setTimeout(() => {
                fetch(`{{ route('chat.search') }}?q=${encodeURIComponent(q)}`, {
                    headers: { 'Accept': 'application/json' },
                })
                    .then((r) => r.json())
                    .then((users) => {
                        searchResults.innerHTML = users.map((u) => `
                            <a href="/chat/${u.id}" class="block px-3.5 py-2.5 text-sm text-[#d7dcea] hover:bg-[#1c243c] border-b border-[#1c243c] last:border-0">
                                ${u.username || (u.first_name + ' ' + u.last_name)}
                            </a>
                        `).join('') || '<div class="px-3.5 py-3 text-xs text-[#7c86a3]">No users found.</div>';
                        searchResults.classList.remove('hidden');
                    });
            }, 250);
        });

        document.addEventListener('click', (e) => {
            if (!searchResults?.contains(e.target) && e.target !== searchInput) {
                searchResults?.classList.add('hidden');
            }
        });

        const messagesEl = document.getElementById('chatMessages');
        const sendForm = document.getElementById('chatSendForm');
        const messageInput = document.getElementById('chatMessageInput');

        function csrfToken() {
            return document.querySelector('meta[name="csrf-token"]')?.content;
        }

        function scrollToBottom() {
            if (messagesEl) messagesEl.scrollTop = messagesEl.scrollHeight;
        }
        scrollToBottom();

        function appendMessage(msg, isMine) {
            const wrapper = document.createElement('div');
            wrapper.className = `flex ${isMine ? 'justify-end' : 'justify-start'}`;
            wrapper.innerHTML = `
                <div class="max-w-[60%] ${isMine ? 'bg-[#4f8ef7] text-white' : 'bg-[#1c243c] text-[#d7dcea] border border-[#2a3350]'} rounded-xl px-4 py-2.5 text-sm">
                    <p></p>
                    <span class="block text-[10px] mt-1 opacity-70">${msg.created_at}</span>
                </div>
            `;
            wrapper.querySelector('p').textContent = msg.body;
            messagesEl.appendChild(wrapper);
            messagesEl.dataset.lastId = msg.id;
            scrollToBottom();
        }

        sendForm?.addEventListener('submit', (e) => {
            e.preventDefault();
            const body = messageInput.value.trim();
            if (!body || !messagesEl) return;
            const receiverId = messagesEl.dataset.contactId;

            fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ receiver_id: receiverId, body }),
            })
                .then((r) => r.json())
                .then((res) => {
                    if (res.status) {
                        appendMessage(res.message, true);
                        messageInput.value = '';
                    } else {
                        window.toastr?.error(res.message || 'Unable to send message.');
                    }
                })
                .catch(() => window.toastr?.error('Unable to send message.'));
        });

        // Real-time push when a real broadcast driver is configured...
        if (window.Echo && messagesEl) {
            window.Echo.private(`chat.user.{{ auth()->id() }}`)
                .listen('.chat.message', (e) => {
                    if (String(e.sender_id) === messagesEl.dataset.contactId) {
                        appendMessage({ id: e.id, body: e.body, created_at: new Date(e.created_at).toTimeString().slice(0, 5) }, false);
                    }
                });
        }

        // ...and a polling fallback so it works regardless.
        if (messagesEl) {
            setInterval(() => {
                const contactId = messagesEl.dataset.contactId;
                const afterId = messagesEl.dataset.lastId || 0;
                fetch(`/chat/${contactId}/poll?after_id=${afterId}`, { headers: { Accept: 'application/json' } })
                    .then((r) => r.json())
                    .then((msgs) => {
                        msgs.forEach((m) => appendMessage(m, m.sender_id == {{ auth()->id() }}));
                    })
                    .catch(() => {});
            }, 3000);
        }
    })();
</script>
@endpush
