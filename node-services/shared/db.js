import mysql from 'mysql2/promise';

let pool;

function getPool() {
  if (!pool) {
    pool = mysql.createPool({
      host: process.env.DB_HOST || '127.0.0.1',
      port: Number(process.env.DB_PORT) || 3306,
      database: process.env.DB_DATABASE,
      user: process.env.DB_USERNAME,
      password: process.env.DB_PASSWORD || '',
      waitForConnections: true,
      connectionLimit: 5,
    });
  }
  return pool;
}

const DEFAULT_PROFIT_MARGIN = 0.85;

/**
 * Node port of BrokeretFeedService::ensureAssetRegistered() — the one place
 * this service writes to the shared DB, deliberately scoped to asset-catalog
 * metadata only (never wallet/trade tables). Relies on the same unique
 * `symbol` index the PHP insertOrIgnore() does, so this is safe to call
 * concurrently with the PHP daemon during a cutover window.
 */
export async function ensureAssetRegistered(symbol, category, logger) {
  const now = new Date().toISOString().slice(0, 19).replace('T', ' ');

  try {
    await getPool().execute(
      `INSERT IGNORE INTO assets
        (symbol, name, asset_group, exchange_float, asset_profit_margin, is_otc, price_source, created_at, updated_at)
       VALUES (?, ?, ?, 0, ?, 0, 'brokeret', ?, ?)`,
      [symbol, symbol, (category || 'OTHER').toUpperCase(), DEFAULT_PROFIT_MARGIN, now, now],
    );
  } catch (err) {
    logger.warn('asset auto-registration failed', { symbol, error: err.message });
  }
}
