import Echo from '@ably/laravel-echo';
import * as Ably from 'ably';
import Pusher from 'pusher-js';

window.Ably = Ably;
// @ably/laravel-echo's bundled PusherConnector (used for 'reverb'/'pusher')
// expects window.Pusher to already be set, same as the classic laravel-echo
// package — it isn't bundled internally.
window.Pusher = Pusher;

/**
 * Which realtime backend the browser connects to is decided at PAGE LOAD
 * (via the <meta name="broadcaster"> tag layouts/desktop/{trading,app}.blade.php
 * render from config('broadcasting.default'), i.e. .env's BROADCAST_CONNECTION)
 * rather than baked into the JS bundle at build time — so switching that one
 * .env value and reloading the page is enough to move every Echo listener in
 * the app (chart ticks, trade updates, balance sync, chat, signals) over to
 * the other backend. No `npm run build` required, and both SDKs (@ably/
 * laravel-echo covers both 'ably' and 'reverb'/'pusher' internally) are
 * already in the bundle so there's nothing to swap at the dependency level
 * either — the same @ably/laravel-echo import handles all three simply by
 * changing the `broadcaster` option passed to it.
 */
const broadcaster = document.querySelector('meta[name="broadcaster"]')?.content || 'ably';

function buildEcho(broadcaster) {
    if (broadcaster === 'reverb' || broadcaster === 'pusher') {
        // forceTLS/transports must follow VITE_REVERB_SCHEME rather than being
        // hardcoded to plain ws:// — a page served over https (any real VPS
        // deploy) refuses to open a plain ws:// socket as mixed content.
        const isSecure = (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https';
        const host = import.meta.env.VITE_REVERB_HOST;

        if (!host || host === 'localhost' || host === '127.0.0.1') {
            console.error(
                `[echo] VITE_REVERB_HOST is "${host}" — that is only reachable from the machine ` +
                'that built the assets, never from a visitor\'s browser. Set REVERB_HOST in .env to the ' +
                'site\'s real public domain and run `npm run build` again (VITE_* vars are baked in at ' +
                'build time, so editing .env alone does not fix an already-built bundle).'
            );
        }
        if (!import.meta.env.VITE_REVERB_APP_KEY) {
            console.error('[echo] VITE_REVERB_APP_KEY is empty in the built bundle — price streaming cannot authenticate. Check .env and rebuild.');
        }

        return new Echo({
            broadcaster: 'reverb',
            key: import.meta.env.VITE_REVERB_APP_KEY,
            wsHost: host,
            wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
            wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
            forceTLS: isSecure,
            enabledTransports: isSecure ? ['ws', 'wss'] : ['ws'],
        });
    }

    // Default: native Ably connector. No key of any kind ships to the
    // browser — it authenticates by POSTing to /broadcasting/auth (session
    // cookie + the csrf-token meta tag), which config/broadcasting.php's
    // 'ably' connection signs server-side using the full ABLY_KEY. Public
    // channels (like 'asset-prices'/'brokeret-feed') skip the per-channel
    // token upgrade and only need the connection-level token.
    return new Echo({ broadcaster: 'ably' });
}

window.Echo = buildEcho(broadcaster);
console.log(`[echo] using broadcaster: ${broadcaster}`);

if (broadcaster === 'reverb' || broadcaster === 'pusher') {
    window.Echo.connector.pusher.connection.bind('state_change', (states) => {
        console.log(`[echo] connection: ${states.previous} -> ${states.current}`);
    });
    window.Echo.connector.pusher.connection.bind('error', (err) => {
        console.error('[echo] connection error — check that Reverb is running (`php artisan reverb:start` / supervisorctl status) and that it\'s reachable at REVERB_HOST/REVERB_PORT (nginx must proxy wss:// through to it in production — see deploy/nginx/reverb-proxy.conf.example):', err);
    });
} else {
    window.Echo.connector.ably.connection.on((stateChange) => {
        console.log(`[echo] connection: ${stateChange.previous} -> ${stateChange.current}`);
        if (stateChange.current === 'failed' || stateChange.current === 'suspended') {
            console.error('[echo] Ably connection failed/suspended — check ABLY_KEY in .env and that /broadcasting/auth is reachable:', stateChange.reason);
        }
    });
}
