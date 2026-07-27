import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

// --- Legacy: Laravel Reverb (SUPERSEDED by Ably below — see config/broadcasting.php
// and .env's BROADCAST_CONNECTION. Kept, not deleted, in case broadcasting
// ever moves back to a self-hosted server.) ---
//
// // forceTLS/transports must follow VITE_REVERB_SCHEME rather than being
// // hardcoded to plain ws:// — a page served over https (any real VPS deploy)
// // refuses to open a plain ws:// socket as mixed content, so the chart's
// // price stream silently never connects in production even though the exact
// // same build works over a local http dev tunnel.
// const reverbIsSecure = (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https';
//
// const reverbHost = import.meta.env.VITE_REVERB_HOST;
//
// if (!reverbHost || reverbHost === 'localhost' || reverbHost === '127.0.0.1') {
//     console.error(
//         `[echo] VITE_REVERB_HOST is "${reverbHost}" — that is only reachable from the machine ` +
//         'that built the assets, never from a visitor\'s browser. Set REVERB_HOST in .env to the ' +
//         'site\'s real public domain and run `npm run build` again (VITE_* vars are baked in at ' +
//         'build time, so editing .env alone does not fix an already-built bundle).'
//     );
// }
// if (!import.meta.env.VITE_REVERB_APP_KEY) {
//     console.error('[echo] VITE_REVERB_APP_KEY is empty in the built bundle — price streaming cannot authenticate. Check .env and rebuild.');
// }
//
// window.Echo = new Echo({
//     broadcaster: 'reverb',
//     key: import.meta.env.VITE_REVERB_APP_KEY,
//     wsHost: reverbHost,
//     wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
//     wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
//     forceTLS: reverbIsSecure,
//     enabledTransports: reverbIsSecure ? ['ws', 'wss'] : ['ws'],
// });

// Ably doesn't ship its own connector in this version of laravel-echo, but it
// doesn't need one: Ably speaks the Pusher protocol at realtime-pusher.ably.io,
// so the same PusherConnector (pusher-js, imported above) that used to talk
// to Reverb now talks to Ably instead — same broadcaster: 'pusher', just a
// different host/key. `key` here is ONLY the public "appId.keyId" half of
// ABLY_KEY (see .env) — never the secret half, since this value ships in the
// built JS bundle for anyone to read. `cluster` is required by pusher-js's
// constructor even though Ably ignores it (wsHost below takes precedence).
if (!import.meta.env.VITE_ABLY_PUBLIC_KEY) {
    console.error(
        '[echo] VITE_ABLY_PUBLIC_KEY is empty in the built bundle — price streaming cannot ' +
        'authenticate. Set ABLY_KEY and VITE_ABLY_PUBLIC_KEY in .env (see .env.example) and ' +
        'rebuild (VITE_* vars are baked in at build time, so editing .env alone does not fix ' +
        'an already-built bundle).'
    );
}

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_ABLY_PUBLIC_KEY,
    cluster: '',
    wsHost: 'realtime-pusher.ably.io',
    wsPort: 443,
    wssPort: 443,
    forceTLS: true,
    enabledTransports: ['ws', 'wss'],
    disableStats: true,
});

window.Echo.connector.pusher.connection.bind('state_change', (states) => {
    console.log(`[echo] connection: ${states.previous} -> ${states.current}`);
});
window.Echo.connector.pusher.connection.bind('error', (err) => {
    console.error('[echo] connection error — check ABLY_KEY/VITE_ABLY_PUBLIC_KEY in .env and that your Ably app/key is active:', err);
});
