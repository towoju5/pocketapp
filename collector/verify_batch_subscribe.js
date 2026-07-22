const { chromium } = require('playwright');

// Reproduces exactly what collector/index.js does: fetch the real symbol
// list, subscribe to ALL of them in one tight synchronous loop the instant
// the WS opens, then count how many distinct symbols actually report a tick
// back within the window. Tests the hypothesis that firing ~158
// SUBSCRIBE.TICK sends back-to-back causes iqcent to silently drop/ignore
// most of them.

const APP_URL = 'http://127.0.0.1:8001';
const SECRET = process.env.PRICE_COLLECTOR_SECRET;

(async () => {
  const res = await fetch(`${APP_URL}/internal/assets/symbols`, { headers: { 'X-Collector-Secret': SECRET } });
  const symbols = await res.json();
  console.log('Testing with', symbols.length, 'symbols');

  const browser = await chromium.launch({ headless: true, args: ['--no-sandbox', '--disable-dev-shm-usage'] });
  const page = await browser.newPage();
  await page.goto('https://iqcent.com', { waitUntil: 'domcontentloaded', timeout: 30000 });

  const seen = new Set();
  await page.exposeFunction('__report', (s) => seen.add(s));

  await page.evaluate((syms) => {
    const ws = new WebSocket('wss://iqcent.com/trade-api-ws/api/ws/price');
    ws.onopen = () => {
      console.log(`[page] WS open, sending ${syms.length} SUBSCRIBE.TICK in a tight loop`);
      syms.forEach((id) => ws.send(JSON.stringify({ id, param: 'Option', operation: 'SUBSCRIBE.TICK' })));
      console.log('[page] all sends issued');
    };
    ws.onmessage = (e) => {
      let m;
      try { m = JSON.parse(e.data); } catch (err) { return; }
      if (m && m.s) window.__report(m.s);
    };
  }, symbols);

  page.on('console', (msg) => console.log(msg.text()));

  await new Promise((r) => setTimeout(r, 20000));
  console.log(`RESULT: ${seen.size} / ${symbols.length} distinct symbols reported a tick within 20s (tight-loop subscribe)`);
  await browser.close();
  process.exit(0);
})();
