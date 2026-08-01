@extends('layouts.desktop.trading')

@section('title', 'Chat')

@section('content')
<div class="flex-1 flex min-h-0">
    {{-- Contact list --}}
    <div class="w-[280px] border-r border-[#2a3350] flex flex-col flex-shrink-0">
        @if(auth()->user()->is_admin)
            <div class="p-3 border-b border-[#2a3350]">
                <div class="relative">
                    <i class="fa fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-[#7c86a3] text-xs"></i>
                    <input type="text" id="chatSearchInput" placeholder="Search people..."
                        class="w-full bg-[#1c243c] border border-[#2a3350] rounded-lg pl-9 pr-3 py-2 text-sm text-white outline-none focus:border-[#4f8ef7]">
                </div>
                <div id="chatSearchResults" class="hidden absolute z-20 w-[252px] mt-1 bg-[#171e33] border border-[#2a3350] rounded-lg overflow-hidden" style="box-shadow:0 20px 60px rgba(0,0,0,0.4);"></div>
            </div>
        @else
            <div class="p-3 border-b border-[#2a3350]">
                <span class="text-xs font-semibold uppercase tracking-wide text-[#7c86a3]">Support</span>
            </div>
        @endif
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
                <div class="p-4 text-center text-xs text-[#7c86a3]">
                    @if(auth()->user()->is_admin)
                        Search for someone above to start a conversation.
                    @else
                        No support agent is available right now — leave a message and we'll respond as soon as we're back.
                    @endif
                </div>
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
        @elseif(!auth()->user()->is_admin && !$supportAvailable)
            <div class="h-16 border-b border-[#2a3350] flex items-center px-5 gap-3 flex-shrink-0">
                <div class="w-9 h-9 rounded-full bg-[#33406b] flex items-center justify-center text-white text-xs font-bold">S</div>
                <div>
                    <span class="text-sm font-semibold text-white">Support</span>
                    <span class="block text-[10px] text-[#7c86a3]">No agent available right now</span>
                </div>
            </div>

            <div id="chatMessages" class="flex-1 overflow-y-auto p-5 space-y-3" data-last-id="0">
                @forelse (($offlineTicket->replies ?? collect()) as $reply)
                    <div class="flex {{ $reply->is_admin_reply ? 'justify-start' : 'justify-end' }}">
                        <div class="max-w-[60%] {{ !$reply->is_admin_reply ? 'bg-[#4f8ef7] text-white' : 'bg-[#1c243c] text-[#d7dcea] border border-[#2a3350]' }} rounded-xl px-4 py-2.5 text-sm">
                            <p>{{ $reply->message }}</p>
                            <span class="block text-[10px] mt-1 opacity-70">{{ $reply->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                @empty
                    <p class="text-center text-xs text-[#7c86a3]">Leave a message below — it'll be waiting for our support team.</p>
                @endforelse
            </div>

            @if($offlineTicket)
                <div class="px-3 pb-1 text-center">
                    <a href="{{ route('support-tickets.show', $offlineTicket) }}" class="text-[11px] text-[#4f8ef7] hover:underline">View as support ticket</a>
                </div>
            @endif

            <form id="chatSendForm" class="p-3 border-t border-[#2a3350] flex items-center gap-2 flex-shrink-0">
                <input type="text" id="chatMessageInput" placeholder="Leave a message for support..." autocomplete="off"
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
                    <p class="text-[#7c86a3] text-sm">
                        @if(auth()->user()->is_admin)
                            Search for someone or select a conversation to start chatting.
                        @else
                            Select a conversation to talk to our support team.
                        @endif
                    </p>
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

        // Short two-tone beep, synthesized on the fly — no audio asset needed.
        let audioCtx = null;
        function playChime() {
            try {
                audioCtx = audioCtx || new (window.AudioContext || window.webkitAudioContext)();
                const now = audioCtx.currentTime;
                [880, 1320].forEach((freq, i) => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    osc.type = 'sine';
                    osc.frequency.value = freq;
                    gain.gain.setValueAtTime(0.001, now + i * 0.09);
                    gain.gain.linearRampToValueAtTime(0.15, now + i * 0.09 + 0.01);
                    gain.gain.exponentialRampToValueAtTime(0.001, now + i * 0.09 + 0.15);
                    osc.connect(gain).connect(audioCtx.destination);
                    osc.start(now + i * 0.09);
                    osc.stop(now + i * 0.09 + 0.16);
                });
            } catch (e) { /* Web Audio unsupported/blocked — silently skip the chime. */ }
        }

        /** status: 'sent' | 'pending' | 'failed' */
        function appendMessage(msg, isMine, status = 'sent') {
            const wrapper = document.createElement('div');
            wrapper.className = `flex ${isMine ? 'justify-end' : 'justify-start'}`;
            if (msg.localId) wrapper.dataset.localId = msg.localId;

            wrapper.innerHTML = `
                <div class="max-w-[60%]">
                    <div class="${isMine ? 'bg-[#4f8ef7] text-white' : 'bg-[#1c243c] text-[#d7dcea] border border-[#2a3350]'} rounded-xl px-4 py-2.5 text-sm" style="${status === 'pending' ? 'opacity:0.6;' : ''}">
                        <p></p>
                        <span class="block text-[10px] mt-1 opacity-70">${msg.created_at}</span>
                    </div>
                    <div class="chat-status mt-1 text-right"></div>
                </div>
            `;
            wrapper.querySelector('p').textContent = msg.body;
            renderStatus(wrapper, status);
            messagesEl.appendChild(wrapper);
            if (status === 'sent') messagesEl.dataset.lastId = msg.id;
            scrollToBottom();

            if (!isMine && status === 'sent') playChime();

            return wrapper;
        }

        function renderStatus(wrapper, status) {
            const statusEl = wrapper.querySelector('.chat-status');
            if (!statusEl) return;
            if (status === 'failed') {
                statusEl.innerHTML = `
                    <span class="text-[10px] text-brand-danger inline-flex items-center gap-1">
                        <i class="fa fa-circle-exclamation"></i> Failed
                        <button type="button" class="chat-resend-btn underline hover:no-underline" style="color:inherit;">Resend</button>
                    </span>
                `;
            } else {
                statusEl.innerHTML = '';
            }
        }

        function sendBody(body, localId) {
            const receiverId = messagesEl.dataset.contactId;

            return fetch("{{ route('chat.send') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken(),
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ receiver_id: receiverId, body }),
            }).then((r) => r.json());
        }

        function handleSendResult(wrapper, res) {
            if (res.status) {
                delete wrapper.dataset.localId;
                messagesEl.dataset.lastId = res.message.id;
                renderStatus(wrapper, 'sent');
                if (res.offline && res.ticket_url && !document.getElementById('offlineTicketLink')) {
                    const link = document.createElement('a');
                    link.id = 'offlineTicketLink';
                    link.href = res.ticket_url;
                    link.className = 'block text-center text-[11px] text-[#4f8ef7] hover:underline py-2 border-t border-[#2a3350]';
                    link.textContent = 'View as support ticket';
                    sendForm.parentElement.insertBefore(link, sendForm);
                }
            } else {
                renderStatus(wrapper, 'failed');
                window.toastr?.error(res.message || 'Unable to send message.');
            }
        }

        sendForm?.addEventListener('submit', (e) => {
            e.preventDefault();
            const body = messageInput.value.trim();
            if (!body || !messagesEl) return;
            messageInput.value = '';

            const localId = 'local-' + Date.now() + '-' + Math.random().toString(36).slice(2);
            const wrapper = appendMessage(
                { localId, body, created_at: new Date().toTimeString().slice(0, 5) },
                true,
                'pending'
            );

            sendBody(body, localId)
                .then((res) => handleSendResult(wrapper, res))
                .catch(() => renderStatus(wrapper, 'failed'));
        });

        // Click-to-resend for any failed bubble (event delegation — bubbles are added dynamically).
        messagesEl?.addEventListener('click', (e) => {
            if (!e.target.classList.contains('chat-resend-btn')) return;
            const wrapper = e.target.closest('[data-local-id]');
            if (!wrapper) return;
            const body = wrapper.querySelector('p')?.textContent ?? '';
            if (!body) return;

            renderStatus(wrapper, 'pending');
            wrapper.querySelector('.rounded-xl').style.opacity = '0.6';

            sendBody(body, wrapper.dataset.localId)
                .then((res) => {
                    wrapper.querySelector('.rounded-xl').style.opacity = '';
                    handleSendResult(wrapper, res);
                })
                .catch(() => {
                    wrapper.querySelector('.rounded-xl').style.opacity = '';
                    renderStatus(wrapper, 'failed');
                });
        });

        // Real-time push when a real broadcast driver is configured — only
        // relevant once there's an actual contact (not the offline/no-support state).
        if (window.Echo && messagesEl && messagesEl.dataset.contactId) {
            window.Echo.private(`chat.user.{{ auth()->id() }}`)
                .listen('.chat.message', (e) => {
                    if (String(e.sender_id) === messagesEl.dataset.contactId) {
                        appendMessage({ id: e.id, body: e.body, created_at: new Date(e.created_at).toTimeString().slice(0, 5) }, false);
                    }
                });
        }

        // ...and a polling fallback so it works regardless.
        if (messagesEl && messagesEl.dataset.contactId) {
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
