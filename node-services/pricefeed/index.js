import 'dotenv/config';
import { createLogger } from '../shared/logger.js';
import { connectBrokeretFeed } from './brokeretClient.js';
import { updateLatest, appendHistoryTick } from './brokeretFeedStore.js';
import { createPriceFeedWsServer } from './wsServer.js';

const logger = createLogger('pricefeed');

const wsUrl = process.env.BROKERET_WS_URL;
const apiKey = process.env.BROKERET_API_KEY;
const port = Number(process.env.PRICEFEED_WS_PORT) || 8090;
const reconnectDelayMs = 3000;
const broadcastIntervalMs = 200; // Matches StreamBrokeretFeed's default — see wsServer.js docblock for why batching matters here too.

if (!wsUrl) {
  logger.error('BROKERET_WS_URL is not configured.');
  process.exit(1);
}

const server = createPriceFeedWsServer({ port, path: '/ws/brokeret-feed', logger });

// Only the latest tick per symbol matters for a live display, so a symbol
// repeated within the flush window just overwrites its buffered entry —
// same reasoning as StreamBrokeretFeed's $pending buffer.
let pending = new Map();

setInterval(() => {
  if (pending.size === 0) return;
  const ticks = Array.from(pending.values());
  pending = new Map();
  server.broadcast(ticks);
}, broadcastIntervalMs);

connectBrokeretFeed({
  wsUrl,
  apiKey,
  reconnectDelayMs,
  logger,
  onTick: (tick) => {
    updateLatest(tick, logger);
    appendHistoryTick(tick, logger);
    pending.set(tick.symbol, tick);
  },
});
