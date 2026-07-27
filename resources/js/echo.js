import Echo from '@ably/laravel-echo';
import * as Ably from 'ably';
window.Ably = Ably;

// --- Legacy: Laravel Reverb (SUPERSEDED by Ably below — see config/broadcasting.php
// and .env's BROADCAST_CONNECTION. Kept, not deleted, in case broadcasting
// ever moves back to a self-hosted server.) ---
//
// import Echo from 'laravel-echo';
// import Pusher from 'pusher-js';
// window.Pusher = Pusher;
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
//
// window.Echo.connector.pusher.connection.bind('state_change', (states) => {
//     console.log(`[echo] connection: ${states.previous} -> ${states.current}`);
// });
// window.Echo.connector.pusher.connection.bind('error', (err) => {
//     console.error('[echo] connection error — check that Reverb is running (supervisorctl status) and that nginx is proxying wss:// through to it (see deploy/nginx/reverb-proxy.conf.example):', err);
// });

// --- Legacy: Ably via Laravel's built-in Pusher-protocol-compatible driver
// (SUPERSEDED by the native ably/laravel-broadcaster + @ably/laravel-echo
// pairing below. That built-in driver required a public Ably key baked into
// the JS bundle AND enabling "Pusher compatibility mode" in the Ably
// dashboard's Protocol Adapter Settings. Neither is needed below: the native
// connector authenticates purely through this app's own /broadcasting/auth
// route — already registered automatically by bootstrap/app.php's
// withRouting(channels: ...) — the exact same route Reverb's private
// channels always used, so the browser never needs any Ably key at all.) ---
//
// if (!import.meta.env.VITE_ABLY_PUBLIC_KEY) {
//     console.error(
//         '[echo] VITE_ABLY_PUBLIC_KEY is empty in the built bundle — price streaming cannot ' +
//         'authenticate. Set ABLY_KEY and VITE_ABLY_PUBLIC_KEY in .env (see .env.example) and ' +
//         'rebuild (VITE_* vars are baked in at build time, so editing .env alone does not fix ' +
//         'an already-built bundle).'
//     );
// }
//
// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_ABLY_PUBLIC_KEY,
//     cluster: '',
//     wsHost: 'realtime-pusher.ably.io',
//     wsPort: 443,
//     wssPort: 443,
//     forceTLS: true,
//     enabledTransports: ['ws', 'wss'],
//     disableStats: true,
// });
//
// window.Echo.connector.pusher.connection.bind('state_change', (states) => {
//     console.log(`[echo] connection: ${states.previous} -> ${states.current}`);
// });
// window.Echo.connector.pusher.connection.bind('error', (err) => {
//     console.error('[echo] connection error — check ABLY_KEY/VITE_ABLY_PUBLIC_KEY in .env and that your Ably app/key is active:', err);
// });

// Native Ably connector (@ably/laravel-echo + ably-js). No key of any kind
// ships to the browser — it authenticates by POSTing to /broadcasting/auth
// (session cookie + the csrf-token meta tag already present in
// layouts/desktop/trading.blade.php), which config/broadcasting.php's 'ably'
// connection signs server-side using the full ABLY_KEY. Public channels
// (like 'asset-prices', see chart.js) skip the per-channel token upgrade
// entirely and only need the connection-level token.
window.Echo = new Echo({
    broadcaster: 'ably',
});

window.Echo.connector.ably.connection.on((stateChange) => {
    console.log(`[echo] connection: ${stateChange.previous} -> ${stateChange.current}`);
    if (stateChange.current === 'failed' || stateChange.current === 'suspended') {
        console.error('[echo] Ably connection failed/suspended — check ABLY_KEY in .env and that /broadcasting/auth is reachable:', stateChange.reason);
    }
});
