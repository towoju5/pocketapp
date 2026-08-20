import { getRedis } from '../shared/redis.js';
import { ensureAssetRegistered } from '../shared/db.js';

/**
 * Node port of app/Services/BrokeretFeedService.php — reuses the EXACT same
 * Redis key namespace (brokeret:latest:{symbol}, brokeret:ticks:{symbol})
 * as the PHP version. This is deliberate: the two are alternative writers of
 * the same conceptual feed, meant to run one-at-a-time (see node-services/README.md),
 * not concurrently as two independent feeds — so BrokeretFeedService::getLatest()
 * on the Laravel side keeps working with zero changes regardless of which
 * process is currently writing.
 */

const STREAM_RETENTION_SECONDS = 7 * 24 * 60 * 60;

// Tracks symbols this process has already insertOrIgnore'd into `assets`,
// same efficiency reasoning as BrokeretFeedService's $registeredSymbols.
const registeredSymbols = new Set();

function latestKey(symbol) {
  return `brokeret:latest:${symbol}`;
}

function streamKey(symbol) {
  return `brokeret:ticks:${symbol}`;
}

export async function updateLatest(tick, logger) {
  if (!registeredSymbols.has(tick.symbol)) {
    registeredSymbols.add(tick.symbol);
    await ensureAssetRegistered(tick.symbol, tick.category, logger);
  }

  try {
    await getRedis().set(
      latestKey(tick.symbol),
      JSON.stringify({ s: tick.symbol, t: tick.t, b: tick.bid, a: tick.ask, c: tick.category }),
    );
  } catch (err) {
    logger.warn('updateLatest failed', { symbol: tick.symbol, error: err.message });
  }
}

export async function appendHistoryTick(tick, logger) {
  try {
    const cutoffMs = Date.now() - STREAM_RETENTION_SECONDS * 1000;
    const mid = (tick.bid + tick.ask) / 2;
    const key = streamKey(tick.symbol);
    await getRedis().call('XADD', key, 'MINID', '~', String(cutoffMs), '*', 's', tick.symbol, 't', String(tick.t), 'p', String(mid));
    await getRedis().expire(key, STREAM_RETENTION_SECONDS);
  } catch (err) {
    logger.warn('appendHistoryTick failed', { symbol: tick.symbol, error: err.message });
  }
}
