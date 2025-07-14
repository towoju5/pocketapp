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

        let content = `
            <div class="${isMe ? 'bg-blue-600 text-white' : 'bg-gray-700 text-white'} p-3 rounded-lg max-w-xs relative group">
                ${message.message ? `<p>${message.message}</p>` : ''}
                ${message.image ? `<img src="/storage/${message.image}" class="mt-2 max-h-40 rounded">` : ''}
                <span class="absolute bottom-1 right-2 text-xs opacity-70">${new Date(message.created_at).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</span>
            </div>
        `;

        div.innerHTML = content;
        container.appendChild(div);
        container.scrollTop = container.scrollHeight;
    }
});
