from playwright.sync_api import sync_playwright
import json
import time
import redis

WS_URL = "wss://iqcent.com/trade-api/public-ws/price"
STAGGER_DELAY = 0.1  # seconds between each connection attempt

# Optional: limit how many symbols to test at once while finding the ceiling.
# Set to None to attempt ALL active symbols.
MAX_SYMBOLS = 50

# The server appears to close idle connections after ~60s without activity.
# The site's own JS sends a periodic PING to keep sockets alive - we replicate
# that here for each of our own connections.
PING_INTERVAL_MS = 25000  # send a PING every 25s, comfortably under the ~60s timeout

# ---------------------------------------------------------------------------
# Redis config - match whatever your Laravel app's REDIS_HOST/PORT/PASSWORD/DB
# are set to (check your .env), so both sides talk to the same instance.
# ---------------------------------------------------------------------------
REDIS_HOST = "127.0.0.1"
REDIS_PORT = 6379
REDIS_DB = 0
REDIS_PASSWORD = None  # set this if your Redis requires auth

# Key layout:
#   ticks:{symbol}        -> Redis Stream, full tick history (XADD per tick)
#   latest_tick:{symbol}  -> plain string key, JSON of the most recent tick
# Both use the raw symbol (e.g. "BTC/USDT.X") as-is; Redis keys allow slashes
# and dots natively, so no sanitization needed. In Laravel/PHP:
#   Redis::xRange('ticks:BTC/USDT.X', '-', '+')   // full history
#   Redis::get('latest_tick:BTC/USDT.X')          // latest price only
#
# Retention: keep at most 7 days of data.
#  - Stream entries older than RETENTION_SECONDS are trimmed on every write
#    via MINID (Stream IDs are ms-timestamp based, so this trims by age,
#    not by count).
#  - Both the stream key and the latest_tick key get an EXPIRE of
#    RETENTION_SECONDS on every write, so if a symbol stops ticking
#    entirely, its data disappears 7 days after the last tick rather than
#    lingering forever.
RETENTION_SECONDS = 7 * 24 * 60 * 60  # 7 days

redis_client = redis.Redis(
    host=REDIS_HOST,
    port=REDIS_PORT,
    db=REDIS_DB,
    password=REDIS_PASSWORD,
    decode_responses=True,
)

# ---------------------------------------------------------------------------
# Embedded asset list (from IQCent's instrument list). Kept as a raw JSON
# string and parsed below, so the whole thing stays in one file.
# ---------------------------------------------------------------------------
ASSETS_JSON = r"""
{
	"data": [
        {
            "symbol": "USATECH.IDX/USD.X",
            "optionType": "INDEX_OTC"
        },
        {
            "symbol": "EUR/DKK.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "GS.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "EUR/SGD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "UNI/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "DBK.DE/EUR",
            "optionType": "STOCKS_EUR"
        },
        {
            "symbol": "DAI.DE/EUR",
            "optionType": "STOCKS_EUR"
        },
        {
            "symbol": "CSCO.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "GBP/SEK.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "FB.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "CHF/JPY.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "GBP/NZD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "HBAR/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "MATIC/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "XMR/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "TRX/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "EUR/JPY.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "META/USD.X",
            "optionType": "STOCKS_OTC"
        },
        {
            "symbol": "DOGE/USDT",
            "optionType": "CRYPTO"
        },
        {
            "symbol": "EUR/GBP.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "NZD/USD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "USD/DKK.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "WLD/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "MATIC/USDT",
            "optionType": "CRYPTO"
        },
        {
            "symbol": "BMW.DE/EUR",
            "optionType": "STOCKS_EUR"
        },
        {
            "symbol": "BIDU.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "AMZN.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "AAPL.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "EUR/HUF.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "DOGE/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "USD/CHF.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "XRP/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "USD/THB.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "YHOO.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "USD/SEK.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "WBETH/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "ESP.IDX/EUR.X",
            "optionType": "INDEX_OTC"
        },
        {
            "symbol": "BTC/USDT",
            "optionType": "CRYPTO"
        },
        {
            "symbol": "BTC/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "TSLA.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "USD/CAD",
            "optionType": "FOREX"
        },
        {
            "symbol": "AUD/NZD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "AUD/NZD",
            "optionType": "FOREX"
        },
        {
            "symbol": "USD/RUB",
            "optionType": "FOREX"
        },
        {
            "symbol": "GBP/CAD",
            "optionType": "FOREX"
        },
        {
            "symbol": "CHE.IDX/CHF.X",
            "optionType": "INDEX_OTC"
        },
        {
            "symbol": "USD/TRY.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "USD/SGD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "OM/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "EUR/AUD",
            "optionType": "FOREX"
        },
        {
            "symbol": "AUD/CHF",
            "optionType": "FOREX"
        },
        {
            "symbol": "NKE.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "MSFT.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "EUR/CAD",
            "optionType": "FOREX"
        },
        {
            "symbol": "USD/HUF.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "MSTR/USD.X",
            "optionType": "STOCKS_OTC"
        },
        {
            "symbol": "USD/ILS.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "LHA.DE/EUR",
            "optionType": "STOCKS_EUR"
        },
        {
            "symbol": "KO.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "JPM.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "INTC.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "GOOG.US/USD",
            "optionType": "STOCKS_USD"
        },
        {
            "symbol": "COPPER.CMD/USD.X",
            "optionType": "METAL_OTC"
        },
        {
            "symbol": "EUR/CHF.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "PEPE/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "USD/CZK.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "TRUMP/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "AUD/CHF.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "ETH/USDT",
            "optionType": "CRYPTO"
        },
        {
            "symbol": "AUD/CAD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "AMD/USD.X",
            "optionType": "STOCKS_OTC"
        },
        {
            "symbol": "USD/CAD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "XAG/USD",
            "optionType": "METAL"
        },
        {
            "symbol": "AUD/USD",
            "optionType": "FOREX"
        },
        {
            "symbol": "AUD/JPY",
            "optionType": "FOREX"
        },
        {
            "symbol": "CAD/CHF",
            "optionType": "FOREX"
        },
        {
            "symbol": "CHF/JPY",
            "optionType": "FOREX"
        },
        {
            "symbol": "USD/SEK",
            "optionType": "FOREX"
        },
        {
            "symbol": "EUR/CHF",
            "optionType": "FOREX"
        },
        {
            "symbol": "EUR/GBP",
            "optionType": "FOREX"
        },
        {
            "symbol": "EUR/NZD",
            "optionType": "FOREX"
        },
        {
            "symbol": "USD/CHF",
            "optionType": "FOREX"
        },
        {
            "symbol": "GBP/AUD",
            "optionType": "FOREX"
        },
        {
            "symbol": "NZD/USD",
            "optionType": "FOREX"
        },
        {
            "symbol": "GBP/USD",
            "optionType": "FOREX"
        },
        {
            "symbol": "AUD/CAD",
            "optionType": "FOREX"
        },
        {
            "symbol": "USD/CNH",
            "optionType": "FOREX"
        },
        {
            "symbol": "GBP/JPY",
            "optionType": "FOREX"
        },
        {
            "symbol": "USD/JPY",
            "optionType": "FOREX"
        },
        {
            "symbol": "XAU/USD",
            "optionType": "METAL"
        },
        {
            "symbol": "GBP/CHF",
            "optionType": "FOREX"
        },
        {
            "symbol": "EUR/JPY",
            "optionType": "FOREX"
        },
        {
            "symbol": "ADA/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "XRP/USDT",
            "optionType": "CRYPTO"
        },
        {
            "symbol": "NOK/SEK.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "NZD/CAD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "GOOGL/USD.X",
            "optionType": "STOCKS_OTC"
        },
        {
            "symbol": "GBP/HKD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "TON/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "ENA/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "GBP/USD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "EUR/USD",
            "optionType": "FOREX"
        },
        {
            "symbol": "PLTR/USD.X",
            "optionType": "STOCKS_OTC"
        },
        {
            "symbol": "SOL/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "AUD/USD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "XLM/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "EUR/TRY.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "EUR/CZK.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "USD/MXN.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "CAD/CHF.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "EUR/CAD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "EUR/AUD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "SUI/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "GBR.IDX/GBP.X",
            "optionType": "INDEX_OTC"
        },
        {
            "symbol": "EUR/SEK.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "BNX/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "TSLA/USD.X",
            "optionType": "STOCKS_OTC"
        },
        {
            "symbol": "USD/PLN.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "EUR/USD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "ARB/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "USD/JPY.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "GBP/JPY.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "INTC/USD.X",
            "optionType": "STOCKS_OTC"
        },
        {
            "symbol": "GBP/AUD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "EUR/NOK.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "AUD/JPY.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "USD/HKD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "EUR/HKD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "ETC/USDT",
            "optionType": "CRYPTO"
        },
        {
            "symbol": "USD/CNH.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "EUR/ZAR.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "ETH/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "USA500.IDX/USD.X",
            "optionType": "INDEX_OTC"
        },
        {
            "symbol": "RARE/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "BRENT.CMD/USD.X",
            "optionType": "METAL_OTC"
        },
        {
            "symbol": "AMZN/USD.X",
            "optionType": "STOCKS_OTC"
        },
        {
            "symbol": "NZD/JPY.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "USA30.IDX/USD.X",
            "optionType": "INDEX_OTC"
        },
        {
            "symbol": "DOT/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "AAPL/USD.X",
            "optionType": "STOCKS_OTC"
        },
        {
            "symbol": "XAU/USD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "USD/NOK.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "EUR/PLN.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "HKG.IDX/HKD.X",
            "optionType": "INDEX_OTC"
        },
        {
            "symbol": "SOL/USDT",
            "optionType": "CRYPTO"
        },
        {
            "symbol": "CAD/JPY.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "NZD/CHF.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "LINK/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "GBP/CAD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "GBP/CHF.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "SHIB/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "BNB/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "ETC/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "QNT/USDT.X",
            "optionType": "CRYPTO_OTC"
        },
        {
            "symbol": "NVDA/USD.X",
            "optionType": "STOCKS_OTC"
        },
        {
            "symbol": "USD/ZAR.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "EUR/NZD.X",
            "optionType": "FOREX_OTC"
        },
        {
            "symbol": "GBP/PLN.X",
            "optionType": "FOREX_OTC"
        }
    ]
}
"""

def load_symbols(assets_json_str):
    data = json.loads(assets_json_str)
    # Only take symbols marked active, and skip POOL/HYPE types which aren't
    # standard tick feeds (they're pool-betting instruments).
    skip_types = {"FOREX_POOL", "CRYPTO_POOL", "HYPE_POOL"}
    symbols = [
        item["symbol"]
        for item in data["data"]
        if item.get("active") and item.get("optionType") not in skip_types
    ]
    return symbols

SYMBOLS_TO_WATCH = load_symbols(ASSETS_JSON)
if MAX_SYMBOLS:
    SYMBOLS_TO_WATCH = SYMBOLS_TO_WATCH[:MAX_SYMBOLS]

print(f"Loaded {len(SYMBOLS_TO_WATCH)} symbols to subscribe to.")

# Fail fast if Redis isn't reachable, rather than silently dropping ticks later
try:
    redis_client.ping()
    print(f"Connected to Redis at {REDIS_HOST}:{REDIS_PORT}")
except redis.exceptions.ConnectionError as e:
    print(f"ERROR: Could not connect to Redis at {REDIS_HOST}:{REDIS_PORT} - {e}")
    raise SystemExit(1)

connection_stats = {"opened": 0, "errored": 0, "closed": 0, "ticks_received": 0}
symbol_status = {s: "pending" for s in SYMBOLS_TO_WATCH}

with sync_playwright() as p:
    browser = p.chromium.launch(headless=True)
    context = browser.new_context()
    page = context.new_page()
    page.on("pageerror", lambda exc: print(f"[pageerror] {exc}"))

    def handle_console(msg):
        text = msg.text
        if text.startswith("WS_OPEN:"):
            symbol = text.split(":", 1)[1]
            symbol_status[symbol] = "open"
            connection_stats["opened"] += 1
        elif text.startswith("WS_ERROR:"):
            symbol = text.split(":", 1)[1]
            symbol_status[symbol] = "error"
            connection_stats["errored"] += 1
            print(f"[!] Error: {symbol}")
        elif text.startswith("WS_CLOSE:"):
            parts = text.split(":", 2)
            symbol = parts[1]
            code = parts[2] if len(parts) > 2 else "?"
            symbol_status[symbol] = f"closed({code})"
            connection_stats["closed"] += 1
            print(f"[x] Closed: {symbol} code={code}")

    page.on("console", handle_console)

    def on_websocket(ws):
        def on_frame_received(payload):
            try:
                data = json.loads(payload)
                sym = data.get("s")
                if sym in symbol_status:
                    connection_stats["ticks_received"] += 1
                    print(f"[tick] {data}")

                    # Store in Redis with a rolling 7-day retention window:
                    #  1) Append to a per-symbol Stream for full history,
                    #     trimming any entries older than RETENTION_SECONDS
                    #     -> Laravel: Redis::xRange("ticks:{$symbol}", '-', '+')
                    #  2) Overwrite a "latest" key for O(1) current-price lookup
                    #     -> Laravel: Redis::get("latest_tick:{$symbol}")
                    try:
                        tick_stream_key = f"ticks:{sym}"
                        latest_key = f"latest_tick:{sym}"

                        # Stream IDs are "<ms-timestamp>-<seq>", so a MINID
                        # cutoff of (now - 7 days) in ms trims anything older
                        # on every write, keeping exactly a 7-day window.
                        cutoff_ms = int((time.time() - RETENTION_SECONDS) * 1000)

                        redis_client.xadd(
                            tick_stream_key,
                            {"s": data.get("s", ""), "t": data.get("t", ""), "p": data.get("p", "")},
                            minid=cutoff_ms,
                            approximate=True,
                        )
                        redis_client.set(latest_key, json.dumps(data))

                        # Reset TTL on both keys so idle symbols (no new
                        # ticks) get cleaned up 7 days after their last write.
                        redis_client.expire(tick_stream_key, RETENTION_SECONDS)
                        redis_client.expire(latest_key, RETENTION_SECONDS)
                    except redis.exceptions.RedisError as e:
                        print(f"[redis error] {sym}: {e}")
            except (json.JSONDecodeError, AttributeError):
                pass
        ws.on("framereceived", on_frame_received)

    page.on("websocket", on_websocket)

    page.goto("https://iqcent.com/", wait_until="domcontentloaded", timeout=30000)
    print("Waiting for Cloudflare to clear...")
    page.wait_for_timeout(8000)
    print("Page title:", page.title())
    print(f"\nOpening {len(SYMBOLS_TO_WATCH)} connections, staggered by {STAGGER_DELAY}s...\n")

    for symbol in SYMBOLS_TO_WATCH:
        msg = {"id": symbol, "param": "Option", "operation": "SUBSCRIBE.TICK"}
        page.evaluate(f"""
            () => {{
                const ws = new WebSocket("{WS_URL}");
                ws.onopen = () => {{
                    console.log("WS_OPEN:{symbol}");
                    ws.send(JSON.stringify({json.dumps(msg)}));
                    // Keepalive: mirror the native site's periodic PING so the
                    // server doesn't close this connection as idle.
                    ws.__pingInterval = setInterval(() => {{
                        if (ws.readyState === WebSocket.OPEN) {{
                            ws.send(JSON.stringify({{"id": "", "param": "", "operation": "PING"}}));
                        }}
                    }}, {PING_INTERVAL_MS});
                }};
                ws.onerror = () => console.log("WS_ERROR:{symbol}");
                ws.onclose = (e) => {{
                    if (ws.__pingInterval) clearInterval(ws.__pingInterval);
                    console.log("WS_CLOSE:{symbol}:" + e.code);
                }};
            }}
        """)
        page.wait_for_timeout(int(STAGGER_DELAY * 1000))

    print(f"\nAll {len(SYMBOLS_TO_WATCH)} connection attempts issued. Monitoring...\n")

    try:
        while True:
            page.wait_for_timeout(200)
    except KeyboardInterrupt:
        print("\nStopped by user")
        failed = {s: v for s, v in symbol_status.items() if v != "open"}
        print(f"\n{len(failed)} symbols did not stay open:")
        for sym, status in failed.items():
            print(f"  {sym}: {status}")
    finally:
        browser.close()