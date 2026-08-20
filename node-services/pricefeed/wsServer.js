import { WebSocketServer } from 'ws';

/**
 * Plain, anonymous WebSocket server for Brokeret tick broadcast — the Node
 * replacement for the Ably/Reverb 'brokeret-feed' channel. No auth: this
 * data isn't user-specific or sensitive today either (it's already a public
 * Echo channel), so no auth machinery is added here — see the migration
 * plan's "Auth for socket connections" section for why that's a deliberate
 * scope boundary, not an oversight.
 *
 * Broadcast payload shape matches BrokeretTicksUpdated::broadcastWith()
 * exactly ({ ticks: [{symbol,bid,ask,mid,category,t}, ...] }) so the
 * frontend swap (brokeretWsFeed.js) is a transport change only, never a
 * payload-shape change.
 */
export function createPriceFeedWsServer({ port, path, logger }) {
  const wss = new WebSocketServer({ port, path });

  wss.on('connection', () => logger.info('Browser client connected', { clients: wss.clients.size }));

  wss.on('listening', () => logger.info(`Price-feed WS server listening on :${port}${path}`));

  return {
    broadcast(ticks) {
      if (ticks.length === 0) return;
      const payload = JSON.stringify({ ticks });
      for (const client of wss.clients) {
        if (client.readyState === client.OPEN) client.send(payload);
      }
    },
  };
}
