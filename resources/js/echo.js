import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// forceTLS/transports must follow VITE_REVERB_SCHEME rather than being
// hardcoded to plain ws:// — a page served over https (any real VPS deploy)
// refuses to open a plain ws:// socket as mixed content, so the chart's
// price stream silently never connects in production even though the exact
// same build works over a local http dev tunnel.
const reverbIsSecure = (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https';

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: reverbIsSecure,
    enabledTransports: reverbIsSecure ? ['ws', 'wss'] : ['ws'],
});
