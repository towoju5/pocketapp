# Running the Relay — Step by Step

This does NOT modify your website or Cloudflare settings. It runs as a
completely separate process on your own server/VPS.

## 1. Local machine first (get it working)

```bash
# Create an isolated environment
python3 -m venv venv
source venv/bin/activate        # Windows: venv\Scripts\activate

# Install dependencies
pip install -r requirements_browser.txt
playwright install chromium     # downloads the browser Playwright drives
playwright install-deps         # installs OS-level libs Chromium needs (Linux)
```

Edit the top of `relay_browser.py`:
- `SITE_URL` — your homepage (or whatever page establishes the session)
- `WS_URL` — your actual WebSocket endpoint
- `CHANNELS` — your list of ~300 channel names
- `SUBSCRIBE_JS_TEMPLATE` — open your site in a real browser, open DevTools
  → Network → WS tab, subscribe to a channel manually, and copy the exact
  JSON shape it sends. Match that shape in the template.
- `DOWNSTREAM_API_KEY` and `REDIS_URL`

Redis needs to be running somewhere reachable (`brew install redis` /
`apt install redis-server`, or a hosted Redis).

Run it:
```bash
python relay_browser.py
```

First run: leave `HEADLESS = False` so you can visually confirm the page
loads past Cloudflare and the WS connects (watch the terminal logs). Once
you see "Subscribed to N channels. Streaming...", you're good.

## 2. Deploying to a server for 24/7 uptime

A real browser needs a display, even "headless" Chromium benefits from one
to look more like a genuine user session. On a headless Linux server, use
a virtual display:

```bash
sudo apt install xvfb
xvfb-run -a python relay_browser.py
```

Once that's stable, you can try `HEADLESS = True` in the config — test it
carefully, since some Cloudflare configurations are stricter about
detecting headless Chromium than others. If it starts failing the
challenge, go back to `HEADLESS = False` with Xvfb.

## 3. Keep it running permanently (systemd)

Edit `ws-relay-browser.service`, adjusting `WorkingDirectory`, `User`, and
paths, then:

```bash
sudo cp ws-relay-browser.service /etc/systemd/system/
sudo systemctl daemon-reload
sudo systemctl enable ws-relay-browser
sudo systemctl start ws-relay-browser
sudo systemctl status ws-relay-browser
journalctl -u ws-relay-browser -f     # live logs
```

`Restart=always` means if the browser crashes or the process dies, systemd
brings it back automatically.

## 4. How your friends/other platform consume the data

**Queue (durable):** read from the Redis Stream `ws:messages` using
`XREAD`/`XREADGROUP` — good if consumers might be offline sometimes and
need to catch up.

**Live WS (push):** connect to:
```
wss://your-relay-host:8765/?apiKey=YOUR_DOWNSTREAM_API_KEY
```
You'll want a reverse proxy (nginx/Caddy) in front of port 8765 to add
TLS, since this raw server doesn't do HTTPS/WSS itself.

## Notes on stability

- The persistent browser profile (`browser-profile/` folder) caches
  Cloudflare's `cf_clearance` cookie, so most restarts won't need to
  re-pass the challenge — don't delete this folder.
- Memory: one real Chromium instance is heavier than a bare socket client
  (expect ~150–400MB), but it's one browser holding one WS connection
  subscribed to all 300 channels — not 300 browsers.
- If your site ever adds a login requirement to view the WS data, you'll
  need to also automate a one-time login and Playwright will persist that
  session in the same profile folder.
