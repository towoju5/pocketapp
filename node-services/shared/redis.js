import Redis from 'ioredis';

let client;

// Single shared connection per process — both pricefeed and tradesocket call
// this, but each runs as its own process, so there's one client per daemon.
export function getRedis() {
  if (!client) {
    client = new Redis({
      host: process.env.REDIS_HOST || '127.0.0.1',
      port: Number(process.env.REDIS_PORT) || 6379,
      password: process.env.REDIS_PASSWORD || undefined,
    });
  }
  return client;
}
