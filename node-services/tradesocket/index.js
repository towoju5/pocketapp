import 'dotenv/config';
import http from 'node:http';
import { Server } from 'socket.io';
import { createLogger } from '../shared/logger.js';
import { socketAuthMiddleware } from './auth.js';
import { verifySession } from './laravelClient.js';
import { registerPlaceTradeHandler } from './handlers/placeTrade.js';

const logger = createLogger('tradesocket');

const port = Number(process.env.TRADESOCKET_PORT) || 8091;
const heartbeatIntervalMs = Number(process.env.SESSION_HEARTBEAT_INTERVAL_MS) || 5 * 60 * 1000;

// Plain http.Server so this can also serve the one internal push endpoint
// (settlement results from TradeSettlementService::settle()) — guarded by
// the same shared secret as everything else in routes/internal.php, socket.io
// attaches to the same server without interfering with this route.
const httpServer = http.createServer((req, res) => {
  if (req.method === 'POST' && req.url === '/internal/push/trade-updated') {
    return handleSettlementPush(req, res);
  }
  res.writeHead(404);
  res.end();
});

function handleSettlementPush(req, res) {
  if (req.headers['x-internal-secret'] !== process.env.INTERNAL_API_SECRET) {
    res.writeHead(401);
    return res.end();
  }

  let body = '';
  req.on('data', (chunk) => (body += chunk));
  req.on('end', () => {
    try {
      const trade = JSON.parse(body);
      const userId = trade.user_id;
      if (userId) {
        const room = io.sockets.adapter.rooms.get(`user:${userId}`);
        io.to(`user:${userId}`).emit('trade:updated', trade);
        logger.info('Settlement push relayed', { tradeId: trade.id, userId, listeningSockets: room?.size || 0 });
      }
      res.writeHead(204);
      res.end();
    } catch (err) {
      logger.warn('Malformed settlement push payload', { error: err.message });
      res.writeHead(400);
      res.end();
    }
  });
}

const io = new Server(httpServer, { path: '/socket.io' });

io.use(socketAuthMiddleware(logger));

io.on('connection', (socket) => {
  const { userId, sessionId } = socket.data;
  socket.join(`user:${userId}`);
  logger.info('Socket connected', { userId });

  registerPlaceTradeHandler(socket, logger);

  // Liveness/revocation is handled here, not by a short token expiry — see
  // RealtimeAuthController's docblock. A revoked/logged-out session can keep
  // a socket alive for at most one heartbeat interval.
  const heartbeat = setInterval(async () => {
    const valid = await verifySession(userId, sessionId).catch(() => false);
    if (!valid) {
      logger.info('Session no longer valid, closing socket', { userId });
      socket.disconnect(true);
    }
  }, heartbeatIntervalMs);

  socket.on('disconnect', () => {
    clearInterval(heartbeat);
    logger.info('Socket disconnected', { userId });
  });
});

httpServer.listen(port, () => logger.info(`Trade-execution socket.io server listening on :${port}`));
