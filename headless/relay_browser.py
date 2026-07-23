"""
Browser-Based WebSocket Relay
==============================
Does NOT touch your website's code or Cloudflare settings.

How it works:
  1. Playwright drives a real Chromium browser to your site. Cloudflare
     sees a genuine browser (real TLS/JS fingerprint) and passes it,
     exactly like when you open the site manually.
  2. We inject JS into that page that opens a native `WebSocket` object
     to your WS endpoint and sends your normal subscribe messages —
     this is literally what your frontend already does, just automated.
  3. Every message received is passed back into Python via
     `page.expose_function`, then fanned out to:
       - a Redis Stream (durable queue)
       - any connected downstream client on the local authenticated
         WS server (live push)

Install:
    pip install playwright websockets redis
    playwright install chromium

Run (see bottom of file / README for headless-server notes):
    python relay_browser.py
"""

import asyncio
import json
import logging
import time
from dataclasses import dataclass, field
from pathlib import Path

from playwright.async_api import async_playwright
import websockets
from websockets.exceptions import ConnectionClosed
import redis.asyncio as redis

# ---------------------------------------------------------------------------
# CONFIG — edit these for your setup
# ---------------------------------------------------------------------------

SITE_URL = "https://yourdomain.com"          # page to load, to pass Cloudflare
WS_URL = "wss://yourdomain.com/ws"           # the WebSocket endpoint itself

# Persistent browser profile dir — reusing this means Cloudflare's
# cf_clearance cookie survives restarts, so you don't re-pass the
# challenge every single time the process starts.
USER_DATA_DIR = str(Path("./browser-profile").resolve())

# Run with a visible window your first time to make sure the challenge
# passes cleanly; switch to True once it's stable. Headless is more
# likely to get flagged by Cloudflare than a headed browser.
HEADLESS = False

CHANNELS = [
    "channel-1",
    "channel-2",
    # ... your 300
]

# Redis (durable queue side)
REDIS_URL = "redis://localhost:6379/0"
REDIS_STREAM_KEY = "ws:messages"

# Local downstream WS server (what your friends'/other platform connects to)
LOCAL_WS_HOST = "0.0.0.0"
LOCAL_WS_PORT = 8765
DOWNSTREAM_API_KEY = "REPLACE_WITH_A_LONG_RANDOM_SECRET"

RECONNECT_MIN_DELAY = 2
RECONNECT_MAX_DELAY = 30

logging.basicConfig(
    level=logging.INFO,
    format="%(asctime)s [%(levelname)s] %(name)s: %(message)s",
)
log = logging.getLogger("ws-relay")


# ---------------------------------------------------------------------------
# Shared hub (same as before): fan out to Redis + live downstream clients
# ---------------------------------------------------------------------------

@dataclass
class Hub:
    clients: set = field(default_factory=set)
    redis_client: "redis.Redis | None" = None

    async def broadcast(self, message: str):
        if not self.clients:
            return
        dead = set()
        for ws in self.clients:
            try:
                await ws.send(message)
            except ConnectionClosed:
                dead.add(ws)
        self.clients -= dead

    async def push_to_queue(self, message: str):
        if self.redis_client is None:
            return
        try:
            await self.redis_client.xadd(
                REDIS_STREAM_KEY,
                {"payload": message, "ts": str(time.time())},
                maxlen=100_000,
                approximate=True,
            )
        except Exception:
            log.exception("Failed to push message to Redis stream")

    async def handle_incoming(self, message: str):
        await asyncio.gather(self.push_to_queue(message), self.broadcast(message))


hub = Hub()


# ---------------------------------------------------------------------------
# Browser-side WebSocket bridge
# ---------------------------------------------------------------------------

# NOTE: customize this to match what YOUR frontend actually sends when it
# subscribes. Open your site's devtools -> Network -> WS tab, watch what
# gets sent when you subscribe to a channel, and mirror that shape here.
# This default assumes: {"method": "SUBSCRIBE", "params": [...]}
SUBSCRIBE_JS_TEMPLATE = """
(channels) => {
    if (!window.__relaySocket || window.__relaySocket.readyState !== 1) return false;
    const batchSize = 50;
    for (let i = 0; i < channels.length; i += batchSize) {
        const batch = channels.slice(i, i + batchSize);
        window.__relaySocket.send(JSON.stringify({ method: "SUBSCRIBE", params: batch, id: i }));
    }
    return true;
}
"""

OPEN_SOCKET_JS = """
(wsUrl) => {
    return new Promise((resolve, reject) => {
        try {
            const sock = new WebSocket(wsUrl);
            window.__relaySocket = sock;
            sock.onopen = () => resolve(true);
            sock.onmessage = (evt) => {
                window.__onRelayMessage(typeof evt.data === "string" ? evt.data : "");
            };
            sock.onclose = () => { window.__onRelayClose(); };
            sock.onerror = (e) => { window.__onRelayError(String(e)); };
        } catch (e) {
            reject(e);
        }
    });
}
"""


async def run_browser_bridge(hub: Hub):
    delay = RECONNECT_MIN_DELAY

    async with async_playwright() as p:
        context = await p.chromium.launch_persistent_context(
            USER_DATA_DIR,
            headless=HEADLESS,
            viewport={"width": 1280, "height": 800},
        )
        page = await context.new_page()

        socket_closed_event = asyncio.Event()

        async def on_message(data: str):
            if data:
                await hub.handle_incoming(data)

        async def on_close():
            log.warning("Browser-side WebSocket closed")
            socket_closed_event.set()

        async def on_error(err: str):
            log.warning("Browser-side WebSocket error: %s", err)

        await page.expose_function("__onRelayMessage", on_message)
        await page.expose_function("__onRelayClose", on_close)
        await page.expose_function("__onRelayError", on_error)

        while True:
            try:
                socket_closed_event.clear()

                log.info("Loading %s to establish Cloudflare session...", SITE_URL)
                await page.goto(SITE_URL, wait_until="networkidle", timeout=60_000)

                log.info("Opening WebSocket from inside the page: %s", WS_URL)
                opened = await page.evaluate(OPEN_SOCKET_JS, WS_URL)
                if not opened:
                    raise RuntimeError("Failed to open browser-side WebSocket")

                await asyncio.sleep(1)  # let onopen settle
                subscribed = await page.evaluate(SUBSCRIBE_JS_TEMPLATE, CHANNELS)
                if not subscribed:
                    raise RuntimeError("Socket not ready for subscribe")

                log.info("Subscribed to %d channels. Streaming...", len(CHANNELS))
                delay = RECONNECT_MIN_DELAY

                # Wait until the socket closes/errors, then loop to reconnect
                await socket_closed_event.wait()

            except Exception:
                log.exception("Browser bridge error, will retry")
            finally:
                log.info("Reconnecting in %.1fs", delay)
                await asyncio.sleep(delay)
                delay = min(delay * 2, RECONNECT_MAX_DELAY)


# ---------------------------------------------------------------------------
# Downstream server: authenticated WS endpoint for your friends/platform
# ---------------------------------------------------------------------------

async def downstream_handler(websocket):
    request_path = websocket.request.path if hasattr(websocket, "request") else websocket.path
    query = {}
    if "?" in request_path:
        from urllib.parse import parse_qs, urlparse

        query = parse_qs(urlparse(request_path).query)

    provided_key = query.get("apiKey", [None])[0]
    if provided_key != DOWNSTREAM_API_KEY:
        await websocket.close(code=4401, reason="Invalid or missing apiKey")
        log.warning("Rejected downstream client: bad apiKey")
        return

    hub.clients.add(websocket)
    log.info("Downstream client connected (%d total)", len(hub.clients))
    try:
        async for _ in websocket:
            pass  # push-only channel; ignore inbound
    except ConnectionClosed:
        pass
    finally:
        hub.clients.discard(websocket)
        log.info("Downstream client disconnected (%d total)", len(hub.clients))


async def run_downstream_server():
    log.info("Starting downstream WS server on %s:%d", LOCAL_WS_HOST, LOCAL_WS_PORT)
    async with websockets.serve(downstream_handler, LOCAL_WS_HOST, LOCAL_WS_PORT):
        await asyncio.Future()


# ---------------------------------------------------------------------------
# Entrypoint
# ---------------------------------------------------------------------------

async def main():
    hub.redis_client = redis.from_url(REDIS_URL, decode_responses=True)
    try:
        await hub.redis_client.ping()
        log.info("Connected to Redis at %s", REDIS_URL)
    except Exception:
        log.exception("Could not connect to Redis — queue push will be skipped")
        hub.redis_client = None

    await asyncio.gather(
        run_browser_bridge(hub),
        run_downstream_server(),
    )


if __name__ == "__main__":
    try:
        asyncio.run(main())
    except KeyboardInterrupt:
        log.info("Shutting down.")
