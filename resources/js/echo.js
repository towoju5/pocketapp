import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// forceTLS/transports must follow VITE_REVERB_SCHEME rather than being
// hardcoded to plain ws:// — a page served over https (any real VPS deploy)
// refuses to open a plain ws:// socket as mixed content, so the chart's
// price stream silently never connects in production even though the exact
// same build works over a local http dev tunnel.
const reverbIsSecure = (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https';

const reverbHost = import.meta.env.VITE_REVERB_HOST;

// Every one of these three previously failed completely silently — no
// console output, no user-facing error, nothing — which is exactly why
// "the chart isn't streaming" turned into a guessing game with no way to
// tell WHICH layer was broken (build-time env vars never baked in? wrong
// host in the built bundle? Reverb/nginx unreachable? auth rejected?).
// Now every failure mode logs distinctly, and it's loud on purpose (not
// console.debug) — this should be the first thing checked in devtools.
if (!reverbHost || reverbHost === 'localhost' || reverbHost === '127.0.0.1') {
    console.error(
        `[echo] VITE_REVERB_HOST is "${reverbHost}" — that is only reachable from the machine ` +
        'that built the assets, never from a visitor\'s browser. Set REVERB_HOST in .env to the ' +
        'site\'s real public domain and run `npm run build` again (VITE_* vars are baked in at ' +
        'build time, so editing .env alone does not fix an already-built bundle).'
    );
}
if (!import.meta.env.VITE_REVERB_APP_KEY) {
    console.error('[echo] VITE_REVERB_APP_KEY is empty in the built bundle — price streaming cannot authenticate. Check .env and rebuild.');
}

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: reverbHost,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: reverbIsSecure,
    enabledTransports: reverbIsSecure ? ['ws', 'wss'] : ['ws'],
});

window.Echo.connector.pusher.connection.bind('state_change', (states) => {
    console.log(`[echo] connection: ${states.previous} -> ${states.current}`);
});
window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('[echo] connection error — check that Reverb is running (supervisorctl status) and that nginx is proxying wss:// through to it (see deploy/nginx/reverb-proxy.conf.example):', err);
});
