import WebSocket from 'ws';

/** Message types that carry an array of {s,b,a,t,c} price entries — matches StreamBrokeretFeed::TICK_MESSAGE_TYPES. */
const TICK_MESSAGE_TYPES = new Set(['snapshot', 'ticks']);

function parseEntry(entry) {
  if (
    !entry ||
    typeof entry !== 'object' ||
    entry.s === undefined ||
    entry.b === undefined ||
    entry.a === undefined ||
    entry.t === undefined ||
    Number.isNaN(Number(entry.b)) ||
    Number.isNaN(Number(entry.a))
  ) {
    return null;
  }

  const bid = Number(entry.b);
  const ask = Number(entry.a);

  return {
    symbol: String(entry.s),
    bid,
    ask,
    mid: (bid + ask) / 2,
    category: String(entry.c || 'other'),
    t: Number(entry.t),
  };
}

/**
 * Node port of StreamBrokeretFeed's connect+reconnect-forever loop. Calls
 * onTick(tick) for every parsed entry — the caller (index.js) owns
 * persistence and broadcast batching, this module only owns the wire
 * protocol.
 */
export function connectBrokeretFeed({ wsUrl, apiKey, reconnectDelayMs, logger, onTick }) {
  const url = wsUrl + (wsUrl.includes('?') ? '&' : '?') + 'apikey=' + encodeURIComponent(apiKey);

  function connectOnce() {
    const ws = new WebSocket(url, { handshakeTimeout: 45000 });

    ws.on('open', () => logger.info('Connected to Brokeret feed'));

    ws.on('message', (raw) => {
      let payload;
      try {
        payload = JSON.parse(raw.toString());
      } catch {
        return;
      }

      if (!payload || !TICK_MESSAGE_TYPES.has(payload.type)) return;

      for (const entry of payload.data || []) {
        const tick = parseEntry(entry);
        if (tick) onTick(tick);
      }
    });

    ws.on('error', (err) => logger.warn('Brokeret feed connection error', { error: err.message }));

    ws.on('close', () => {
      logger.warn('Brokeret feed connection dropped, reconnecting', { delayMs: reconnectDelayMs });
      setTimeout(connectOnce, reconnectDelayMs);
    });
  }

  logger.info('Brokeret UI feed starting...');
  connectOnce();
}
